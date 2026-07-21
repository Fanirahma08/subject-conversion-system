@extends('layouts.app')

@section('title', 'Ruang Kerja Konversi')

@section('content')
<div class="max-w-8xl mx-auto py-2 px-2"
    x-data="{ 
        showAddSubject: false,
        showMappingPopup: {{ ($eligibleMappingsCount > 0 && $conversion->results->count() === 0) ? 'true' : 'false' }},
        pendingDrafts: [],
        selectedIds: [],
        gradeMap: {{ $gradeConversions->pluck('internal_grade', 'origin_grade')->toJson() }},
        existingMappings: {{ $conversion->results->map(fn($r) => ['id' => $r->source_subject_id . '-' . $r->target_subject_id])->values()->toJson() }}.map(m => m.id),
        autoConvert(originGrade) {
            if (!originGrade) return '';
            return this.gradeMap[originGrade.toUpperCase()] || '';
        },
        addDraft() {
            const sourceEl = document.getElementById('source_subject_id');
            const targetEl = document.getElementById('target_subject_id');
            const originGradeEl = document.getElementById('origin_grade');

            if (!originGradeEl.value) {
                alert('Silahkan masukkan pemetaan.');
                return;
            }
            
            if (!sourceEl.value || !targetEl.value) {
                alert('Silakan pilih mata kuliah asal dan internal.');
                return;
            }

            const pair = sourceEl.value + '-' + targetEl.value;

            if (this.pendingDrafts.some(d => d.source_subject_id + '-' + d.target_subject_id === pair)) {
                alert('Pemetaan ini sudah ada dalam daftar draf Anda.');
                return;
            }

            if (this.existingMappings.includes(pair)) {
                alert('Pemetaan ini telah disimpan untuk konversi ini.');
                return;
            }

            let sourceLabel = '';
            let targetLabel = '';

            try {
                if (sourceEl.tomselect && sourceEl.tomselect.getItem(sourceEl.value)) {
                    sourceLabel = sourceEl.tomselect.getItem(sourceEl.value).innerText;
                } else {
                    sourceLabel = sourceEl.options[sourceEl.selectedIndex].text;
                }

                if (targetEl.tomselect && targetEl.tomselect.getItem(targetEl.value)) {
                    targetLabel = targetEl.tomselect.getItem(targetEl.value).innerText;
                } else {
                    targetLabel = targetEl.options[targetEl.selectedIndex].text;
                }
            } catch (e) {
                sourceLabel = 'Mata Kuliah Tidak Diketahui (' + sourceEl.value + ')';
                targetLabel = 'Mata Kuliah Tidak Diketahui (' + targetEl.value + ')';
            }

            const upperOriginGrade = originGradeEl.value.toUpperCase();
            const converted = this.autoConvert(upperOriginGrade);

            this.pendingDrafts.push({
                source_subject_id: sourceEl.value,
                source_label: sourceLabel,
                target_subject_id: targetEl.value,
                target_label: targetLabel,
                origin_grade: upperOriginGrade,
                grade: converted
            });

            if (sourceEl.tomselect) sourceEl.tomselect.clear();
            if (targetEl.tomselect) targetEl.tomselect.clear();
            originGradeEl.value = '';
        },
        removeDraft(index) {
            this.pendingDrafts.splice(index, 1);
        },
        toggleSelectAll(e, ids) {
            this.selectedIds = e.target.checked ? [...ids] : [];
        }
     }">
    <!-- Breadcrumbs -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-slate-400 font-medium">
            <li><a href="{{ route('kaprodi.conversions.index') }}" class="hover:text-blue-600 transition-colors">Tinjauan</a></li>
            <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg></li>
            <li class="text-slate-900 font-bold">Ruang Kerja</li>
        </ol>
    </nav>

    @if (session('success'))
    <div class="mb-6 bg-emerald-50 text-emerald-700 p-4 rounded-2xl text-sm border border-emerald-100 flex items-center animate-in fade-in slide-in-from-top-4 duration-300">
        <svg class="h-5 w-5 mr-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">

        <!-- Left Column: Transcript Viewer -->
        <div class="xl:col-span-12 2xl:col-span-7 space-y-6">
            <div class="bg-white rounded-3xl shadow-xl ring-1 ring-slate-200 overflow-hidden sticky top-24">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex flex-wrap justify-between items-center gap-4">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Transkrip Mahasiswa: {{ $conversion->user->name }}
                    </h3>

                    <div class="flex items-center space-x-4">
                        @if($conversion->registration_letter_path)
                        <a href="{{ asset('storage/' . $conversion->registration_letter_path) }}" target="_blank" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 flex items-center bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 transition-all">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            SURAT PENDAFTARAN
                        </a>
                        @endif

                        @if($conversion->ktp_path)
                        <a href="{{ asset('storage/' . $conversion->ktp_path) }}" target="_blank" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-800 flex items-center bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100 transition-all">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-4 0h4"></path>
                            </svg>
                            KTP
                        </a>
                        @endif

                        <a href="{{ asset('storage/' . $conversion->transcript_path) }}" target="_blank" class="text-[10px] font-bold text-blue-600 hover:text-blue-800 flex items-center bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 transition-all">
                            TRANSKRIP PENUH
                            <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="h-200 overflow-y-auto bg-slate-900 flex items-center justify-center p-4">
                    @php $ext = pathinfo($conversion->transcript_path, PATHINFO_EXTENSION); @endphp
                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))
                    <img src="{{ asset('storage/' . $conversion->transcript_path) }}" alt="Transcript" class="max-w-full shadow-2xl rounded-lg">
                    @else
                    <iframe src="{{ asset('storage/' . $conversion->transcript_path) }}" class="w-full h-full rounded-lg" frameborder="0"></iframe>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Mapping Interface -->
        <div class="xl:col-span-12 2xl:col-span-5 space-y-6">

            <!-- Student Header Card -->
            <div class="bg-white rounded-3xl shadow-xl ring-1 ring-slate-200 p-6 flex items-start space-x-4">
                <div class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-2xl shrink-0">
                    {{ substr($conversion->user->name, 0, 1) }}
                </div>
                <div class="grow">
                    <h2 class="text-xl font-bold text-slate-900">{{ $conversion->user->name }}</h2>
                    <p class="text-sm text-slate-500 font-medium">{{ $conversion->user->studentDetail->university->name }}</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <span class="px-2 py-0.5 rounded-lg bg-slate-100 text-[10px] font-bold text-slate-600 uppercase">{{ $conversion->user->studentDetail->prodi_origin }}</span>
                        <svg class="w-4 h-4 text-slate-300 self-center" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                        </svg>
                        <span class="px-2 py-0.5 rounded-lg bg-blue-100 text-[10px] font-bold text-blue-600 uppercase">{{ $conversion->user->prodi }}</span>
                    </div>
                </div>
                @if(in_array($conversion->status, ['waiting_baak', 'waiting_dekan', 'waiting_wr1', 'waiting_rektor', 'approved']))
                <div class="shrink-0 self-center">
                    <a href="{{ route('conversions.pdf', $conversion) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold rounded-xl shadow-lg shadow-emerald-600/20 transition-all active:scale-95">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        HASIL PDF
                    </a>
                </div>
                @endif
            </div>

            <!-- Mapping Tool -->
            <div class="bg-white rounded-3xl shadow-xl ring-1 ring-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-800">Tetapkan Pemetaan Mata Kuliah</h3>
                    @if($conversion->status === 'waiting')
                    <button @click="showAddSubject = true" class="text-[10px] font-bold text-amber-600 hover:text-amber-800 flex items-center bg-amber-50 px-2 py-1 rounded-lg transition-colors">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        TAMBAH MATA KULIAH ASAL
                    </button>
                    @endif
                </div>

                @if($conversion->status === 'waiting')
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Mata Kuliah Asal (Sumber)</label>
                                <select id="source_subject_id" required class="searchable-select w-full" placeholder="Pilih mata kuliah asal...">
                                    <option value="">Pilih mata kuliah asal...</option>
                                    @foreach($sourceSubjects as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->code }} - {{ $sub->name }} ({{ $sub->sks }} SKS)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Mata Kuliah Internal (Tujuan)</label>
                                <select id="target_subject_id" required class="searchable-select w-full" placeholder="Pilih mata kuliah tujuan...">
                                    <option value="">Pilih mata kuliah tujuan...</option>
                                    @foreach($targetSubjects as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->code }} - {{ $sub->name }} ({{ $sub->sks }} SKS)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-1">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nilai Asal</label>
                                <input type="text" id="origin_grade" placeholder="mis. B-" maxlength="10" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none uppercase font-bold">
                            </div>
                            <div class="sm:col-span-1 flex items-end">
                                <button type="button" @click="addDraft()" class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-6 py-2.5 rounded-xl text-xs font-bold ring-1 ring-blue-200 active:scale-95 transition-all">
                                    TAMBAHKAN KE DRAF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif


                <div class="border-t border-slate-100">
                    @if($eligibleMappingsCount > 0 && $conversion->status === 'waiting')
                    <div class="px-6 py-4 bg-amber-50 border-b border-amber-100 flex items-center justify-between animate-in fade-in slide-in-from-top-4 duration-500">
                        <div class="flex items-center text-amber-800">
                            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-tight">Mata Kuliah Pemetaan Ditemukan</p>
                                <p class="text-[10px] font-medium opacity-80">Kami menemukan {{ $eligibleMappingsCount }} kesetaraan mata kuliah yang cocok dengan universitas & program studi ini.</p>
                            </div>
                        </div>
                        <form action="{{ route('kaprodi.conversions.results.sync', $conversion) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-xl text-[10px] font-bold shadow-lg shadow-amber-600/20 active:scale-95 transition-all">
                                Gunakan Pemetaan
                            </button>
                        </form>
                    </div>
                    @endif

                    @if($conversion->status === 'waiting')
                    <div class="px-6 py-4 bg-slate-50/50 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex flex-wrap items-center gap-4">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Hasil Pemetaan</span>

                            <!-- Bulk Delete Action -->
                            <div x-show="selectedIds.length > 0" x-cloak class="flex items-center animate-in fade-in zoom-in duration-200">
                                <form action="{{ route('kaprodi.conversions.results.bulk-destroy') }}" method="POST" onsubmit="return confirm('Hapus item yang dipilih?')">
                                    @csrf
                                    @method('DELETE')
                                    <template x-for="id in selectedIds" :key="id">
                                        <input type="hidden" name="ids[]" :value="id">
                                    </template>
                                    <button type="submit" class="bg-red-50 text-red-600 px-3 py-1 rounded-lg text-[10px] font-bold border border-red-100 hover:bg-red-600 hover:text-white transition-all">
                                        HAPUS TERPILIH (<span x-text="selectedIds.length"></span>)
                                    </button>
                                </form>
                            </div>

                            <!-- Bulk Save Drafts Action -->
                            <div x-show="pendingDrafts.length > 0" x-cloak class="flex items-center animate-in fade-in zoom-in duration-200">
                                <form action="{{ route('kaprodi.conversions.results.bulk-store', $conversion) }}" method="POST">
                                    @csrf
                                    <template x-for="(draft, index) in pendingDrafts" :key="index">
                                        <div>
                                            <input type="hidden" :name="'results['+index+'][source_subject_id]'" :value="draft.source_subject_id">
                                            <input type="hidden" :name="'results['+index+'][target_subject_id]'" :value="draft.target_subject_id">
                                            <input type="hidden" :name="'results['+index+'][origin_grade]'" :value="draft.origin_grade">
                                            <input type="hidden" :name="'results['+index+'][grade]'" :value="draft.grade">
                                        </div>
                                    </template>
                                    <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-3 py-1 rounded-lg text-[10px] font-bold shadow-md shadow-amber-600/20 active:scale-95 transition-all">
                                        Simpan Pemetaan (<span x-text="pendingDrafts.length"></span>)
                                    </button>
                                </form>
                            </div>

                            <!-- Bulk Update Action -->
                            <button type="submit" form="bulk-update-form" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-[10px] font-bold shadow-md shadow-blue-600/20 active:scale-95 transition-all">
                                Simpan Semua Perubahan
                            </button>
                        </div>
                        @else
                        <div class="px-6 py-4 bg-slate-50/50 flex flex-wrap items-center justify-between gap-4">
                            <div class="flex flex-wrap items-center gap-4">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Hasil Pemetaan</span>
                            </div>
                            @endif
                            <div class="flex items-center space-x-2">
                                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-lg border border-blue-100 uppercase tracking-tighter">{{ $conversion->results->count() }} Disimpan</span>
                                <template x-if="pendingDrafts.length > 0">
                                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-100 uppercase tracking-tighter">Draf</span>
                                </template>
                            </div>
                        </div>

                        <form id="bulk-update-form" action="{{ route('kaprodi.conversions.results.bulk-update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-100">
                                    <thead>
                                        <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-tight bg-white">
                                            <th class="px-6 py-3 text-left w-10">
                                                @if($conversion->status === 'waiting')
                                                <input type="checkbox" @change="toggleSelectAll($event, [{{ $conversion->results->pluck('id')->implode(',') }}])" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                                @endif
                                            </th>
                                            <th class="px-6 py-3 text-left">Mata Kuliah Asal</th>
                                            <th class="px-6 py-3 text-left">Tujuan Internal</th>
                                            <th class="px-6 py-3 text-center">Nilai Asal</th>
                                            <th class="px-6 py-3 text-center">Nilai Internal</th>
                                            @if($conversion->status === 'waiting')
                                            <th class="px-6 py-3 text-right"></th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50 bg-white">
                                        @foreach($conversion->results as $result)
                                        @php
                                        $isGlobal = \App\Models\SubjectMapping::where('source_subject_id', $result->source_subject_id)
                                        ->where('target_subject_id', $result->target_subject_id)
                                        ->exists();
                                        @endphp
                                        <tr class="hover:bg-slate-50/50 transition-colors" :class="selectedIds.includes({{ $result->id }}) ? 'bg-blue-50/30' : ''">
                                            <td class="px-6 py-4">
                                                @if($conversion->status === 'waiting')
                                                <input type="checkbox" value="{{ $result->id }}" x-model="selectedIds" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="grow">
                                                        <div class="flex items-center">
                                                            <span class="text-xs font-bold text-slate-900">{{ $result->source_subject->code }}</span>
                                                            <span class="ml-2 text-[8px] font-black bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded">{{ $result->source_subject->sks }} SKS</span>
                                                        </div>
                                                        <div class="text-[10px] text-slate-500 truncate max-w-45 mt-0.5">{{ $result->source_subject->name }}</div>
                                                    </div>
                                                    @if($isGlobal)
                                                    <span class="ml-2 shrink-0 px-1 py-0.5 bg-blue-50 text-blue-600 text-[8px] font-black uppercase rounded border border-blue-100/50 tracking-tighter" title="Standar Institusi">OTOMATIS</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 border-l border-slate-50">
                                                <div class="flex items-center">
                                                    <span class="text-xs font-bold text-blue-700">{{ $result->target_subject->code }}</span>
                                                    <span class="ml-2 text-[8px] font-black bg-blue-50 text-blue-400 px-1.5 py-0.5 rounded">{{ $result->target_subject->sks }} SKS</span>
                                                </div>
                                                <div class="text-[10px] text-slate-400 truncate max-w-45 mt-0.5">{{ $result->target_subject->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <input type="text"
                                                    name="results[{{ $result->id }}][origin_grade]"
                                                    value="{{ $result->origin_grade }}"
                                                    maxlength="10"
                                                    @if($conversion->status !== 'waiting') readonly @endif
                                                @input="$event.target.value = $event.target.value.toUpperCase(); let conv = autoConvert($event.target.value); if($event.target.value === '') $refs['grade_{{ $result->id }}'].value = ''; else if(conv) $refs['grade_{{ $result->id }}'].value = conv;"
                                                class="w-16 text-center px-1 py-1 bg-slate-50 border border-slate-200 rounded text-xs font-bold text-slate-700 focus:border-blue-500 focus:bg-white focus:outline-none transition-all uppercase disabled:opacity-50"
                                                placeholder="-">
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @php
                                                $displayGrade = $result->grade;
                                                if (empty($displayGrade) && !empty($result->origin_grade)) {
                                                $displayGrade = $gradeConversions->where('origin_grade', $result->origin_grade)->first()->internal_grade ?? '';
                                                }
                                                @endphp
                                                <input type="text"
                                                    x-ref="grade_{{ $result->id }}"
                                                    name="results[{{ $result->id }}][grade]"
                                                    value="{{ $displayGrade }}"
                                                    maxlength="10"
                                                    @if($conversion->status !== 'waiting') readonly @endif
                                                class="w-16 text-center px-1 py-1 bg-blue-50 border border-blue-200 rounded text-xs font-bold text-blue-700 focus:border-blue-500 focus:bg-white focus:outline-none transition-all uppercase disabled:opacity-50"
                                                placeholder="-">
                                            </td>
                                            @if($conversion->status === 'waiting')
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end space-x-2">
                                                    <!-- Link to delete individual mapping -->
                                                    <button type="button"
                                                        @click="if(confirm('Hapus item ini?')) { $refs['deleteForm'+{{ $result->id }}].submit() }"
                                                        class="text-slate-200 hover:text-red-500 transition-colors">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach

                                        <!-- Pending Drafts Integrated -->
                                        <template x-for="(draft, index) in pendingDrafts" :key="'draft-'+index">
                                            <tr class="bg-amber-50/50 border-emerald-100/50 animate-in slide-in-from-right-2 duration-200 group">
                                                <td class="px-6 py-4">
                                                    <div class="w-4 h-4 rounded border-2 border-amber-200 bg-amber-100 flex items-center justify-center">
                                                        <span class="text-[8px] font-black text-amber-700">P</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-xs font-bold text-amber-900" x-text="draft.source_label"></div>
                                                    <span class="text-[8px] font-black bg-amber-100 text-amber-600 px-1 py-0.5 rounded uppercase tracking-tighter">Menunggu Pengajuan</span>
                                                </td>
                                                <td class="px-6 py-4 border-l border-amber-100/50">
                                                    <div class="text-xs font-bold text-amber-900" x-text="draft.target_label"></div>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <input type="text"
                                                        x-model="draft.origin_grade"
                                                        @input="draft.origin_grade = draft.origin_grade.toUpperCase(); let conv = autoConvert(draft.origin_grade); if(draft.origin_grade === '') draft.grade = ''; else if(conv) draft.grade = conv;"
                                                        maxlength="10"
                                                        class="w-16 text-center px-1 py-1 bg-white border border-amber-200 rounded text-xs font-bold text-amber-900 focus:border-amber-500 focus:bg-white focus:outline-none transition-all uppercase"
                                                        placeholder="-">
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <input type="text"
                                                        x-model="draft.grade"
                                                        maxlength="10"
                                                        class="w-16 text-center px-1 py-1 bg-white border border-amber-200 rounded text-xs font-bold text-amber-900 focus:border-amber-500 focus:bg-white focus:outline-none transition-all uppercase"
                                                        placeholder="-">
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <button type="button"
                                                        @click="removeDraft(index)"
                                                        class="text-amber-300 hover:text-amber-600 transition-colors">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </form>

                        <!-- Hidden individual delete forms -->
                        @foreach($conversion->results as $result)
                        <form x-ref="deleteForm{{ $result->id }}" action="{{ route('kaprodi.conversions.results.destroy', $result) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                        @endforeach
                    </div>
                </div>

                <!-- Final Approval Card -->
                @if($conversion->status === 'waiting')
                <div class="bg-white rounded-3xl shadow-xl ring-1 ring-slate-200 overflow-hidden">
                    <div class="px-6 py-6 border-b border-slate-100">
                        <h3 class="text-sm font-bold text-slate-800 mb-4">Keputusan Akhir & Umpan Balik</h3>

                        @if ($errors->any())
                        <div class="mb-4 bg-red-50 text-red-700 p-4 rounded-xl text-sm border border-red-100">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="{{ route('kaprodi.conversions.update', $conversion) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div>
                                <div class="flex gap-4">
                                    <label class="grow flex items-center px-4 py-3 rounded-2xl border-2 border-slate-100 cursor-pointer hover:border-emerald-200 hover:bg-emerald-50 transition-all group has-checked:border-emerald-500 has-checked:bg-emerald-50" :class="pendingDrafts.length > 0 ? 'opacity-50 cursor-not-allowed pointer-events-none' : ''">
                                        <input type="radio" name="status" value="waiting_baak" class="hidden peer" required>
                                        <div class="w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center mr-3 group-hover:border-emerald-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-500">
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="flex flex-col text-left">
                                            <span class="text-sm font-bold text-slate-700 uppercase tracking-tight">Teruskan ke BAAK</span>
                                            <template x-if="pendingDrafts.length > 0">
                                                <span class="text-[9px] text-amber-600 font-bold uppercase mt-1">Ditemukan draf yang belum disimpan!</span>
                                            </template>
                                        </div>
                                    </label>
                                    <label class="grow flex items-center px-4 py-3 rounded-2xl border-2 border-slate-100 cursor-pointer hover:border-red-200 hover:bg-red-50 transition-all group has-checked:border-red-500 has-checked:bg-red-50">
                                        <input type="radio" name="status" value="rejected" class="hidden peer" required>
                                        <div class="w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center mr-3 group-hover:border-red-300 peer-checked:border-red-500 peer-checked:bg-red-500">
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-bold text-slate-700 uppercase tracking-tight">Tolak Permintaan</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label for="notes" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Catatan Peninjau</label>
                                <textarea name="notes" rows="4" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none" placeholder="Berikan umpan balik kepada mahasiswa (mis. alasan mengapa mata kuliah ditolak)...">{{ $conversion->notes }}</textarea>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl text-sm font-bold shadow-xl shadow-blue-600/20 active:scale-[0.98] transition-all">
                                SIMPAN KEPUTUSAN AKHIR
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <!-- Display Final Status -->
                <div class="bg-white rounded-3xl shadow-xl ring-1 ring-slate-200 overflow-hidden">
                    <div class="px-6 py-6 border-b border-slate-100 flex items-center">
                        <h3 class="text-sm font-bold text-slate-800 flex-1">Status Keputusan</h3>
                        <div>
                            @if($conversion->status == 'waiting_dekan')
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs font-bold uppercase">Menunggu Dekan</span>
                            @elseif($conversion->status == 'waiting_rektor')
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold uppercase">Menunggu Rektor</span>
                            @elseif($conversion->status == 'approved')
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold uppercase">Disetujui (SK Terbit)</span>
                            @elseif($conversion->status == 'rejected')
                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold uppercase">Ditolak</span>
                            @endif
                        </div>
                    </div>
                    @if($conversion->notes)
                    <div class="px-6 py-4 bg-slate-50">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Catatan:</p>
                        <p class="text-sm text-slate-700">{{ $conversion->notes }}</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Modal: Mapping Available -->
        <div x-show="showMappingPopup"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white rounded-3xl shadow-2xl ring-1 ring-slate-200 max-w-lg w-full overflow-hidden" @click.away="showMappingPopup = false">
                <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-900">Pemetaan Tersedia</h3>
                    <button @click="showMappingPopup = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-8 space-y-4">
                    <div class="flex items-start text-amber-800">
                        <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center mr-4 shrink-0">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold uppercase tracking-tight">Mata Kuliah Pemetaan Ditemukan</p>
                            <p class="text-xs font-medium opacity-80 mt-1">Kami menemukan <strong>{{ $eligibleMappingsCount }}</strong> kesetaraan mata kuliah yang cocok dengan universitas & program studi ini. Apakah Anda ingin menggunakannya?</p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-6 border-t border-slate-100">
                        <button type="button" @click="showMappingPopup = false" class="px-6 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-700">Tutup</button>
                        <form action="{{ route('kaprodi.conversions.results.sync', $conversion) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-2.5 rounded-xl text-xs font-bold shadow-lg shadow-amber-500/20 active:scale-95 transition-all">PAKAI MAPPING</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal: Add Origin Subject -->
        <div x-show="showAddSubject"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white rounded-3xl shadow-2xl ring-1 ring-slate-200 max-w-lg w-full overflow-hidden" @click.away="showAddSubject = false">
                <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-900">Tambah Mata Kuliah Asal Baru</h3>
                    <button @click="showAddSubject = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('kaprodi.subjects.store') }}" method="POST" class="p-8 space-y-4">
                    @csrf
                    <input type="hidden" name="university_id" value="{{ $conversion->user->studentDetail->university_id }}">
                    <input type="hidden" name="is_active" value="1">
                    <input type="hidden" name="redirect_to" value="{{ url()->current() }}">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-1">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Kode Mata Kuliah</label>
                            <input type="text" name="code" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 outline-none">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">SKS (Kredit)</label>
                            <input type="number" name="sks" required min="1" max="24" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Nama Mata Kuliah</label>
                        <input type="text" name="name" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Universitas Asal</label>
                        <input type="text" disabled value="{{ $conversion->user->studentDetail->university->name }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-100 bg-slate-50 text-slate-400 text-xs italic font-medium">
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" @click="showAddSubject = false" class="px-6 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-700">Batal</button>
                        <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-2.5 rounded-xl text-xs font-bold shadow-lg shadow-amber-500/20 active:scale-95 transition-all">BUAT MATA KULIAH</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection