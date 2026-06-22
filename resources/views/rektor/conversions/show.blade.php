@extends('layouts.app')

@section('title', 'Tinjauan Konversi (Rektor)')

@section('content')
<div class="max-w-7xl mx-auto py-2 px-2">
    <!-- Breadcrumbs -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-slate-400 font-medium">
            <li><a href="{{ route('rektor.conversions.index') }}" class="hover:text-blue-600 transition-colors">Tinjauan</a></li>
            <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
            <li class="text-slate-900 font-bold">Persetujuan Rektor</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: Transcript Viewer -->
        <div class="xl:col-span-12 2xl:col-span-4 space-y-6">
            <div class="bg-white rounded-3xl shadow-xl ring-1 ring-slate-200 overflow-hidden sticky top-24">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-800">Transkrip</h3>
                    <a href="{{ asset('storage/' . $conversion->transcript_path) }}" target="_blank" class="text-[10px] font-bold text-blue-600 hover:text-blue-800">
                        BUKA
                    </a>
                </div>
                <div class="h-150 overflow-y-auto bg-slate-900 flex items-center justify-center p-4">
                    @php $ext = pathinfo($conversion->transcript_path, PATHINFO_EXTENSION); @endphp
                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))
                        <img src="{{ asset('storage/' . $conversion->transcript_path) }}" alt="Transcript" class="max-w-full shadow-2xl rounded-lg">
                    @else
                        <iframe src="{{ asset('storage/' . $conversion->transcript_path) }}" class="w-full h-full rounded-lg" frameborder="0"></iframe>
                    @endif
                </div>
            </div>
        </div>

        <!-- Middle/Right Column: Results & Approval -->
        <div class="xl:col-span-12 2xl:col-span-8 space-y-6">
            <div class="bg-white rounded-3xl shadow-xl ring-1 ring-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-800">Detail Pemetaan Mahasiswa: {{ $conversion->user->name }}</h3>
                    <a href="{{ route('conversions.pdf', $conversion) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold rounded-xl shadow-lg shadow-emerald-600/20 transition-all active:scale-95">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        LIHAT PDF
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead>
                            <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-tight bg-white">
                                <th class="px-6 py-3 text-left">Mata Kuliah Asal</th>
                                <th class="px-6 py-3 text-left">Tujuan Internal</th>
                                <th class="px-6 py-3 text-center">Nilai Asal</th>
                                <th class="px-6 py-3 text-center">Nilai Internal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 bg-white">
                            @foreach($conversion->results as $result)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-xs font-bold text-slate-900">{{ $result->source_subject->code }}</div>
                                        <div class="text-[10px] text-slate-500 truncate max-w-45 mt-0.5">{{ $result->source_subject->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 border-l border-slate-50">
                                        <div class="text-xs font-bold text-blue-700">{{ $result->target_subject->code }}</div>
                                        <div class="text-[10px] text-slate-400 truncate max-w-45 mt-0.5">{{ $result->target_subject->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-xs font-bold">{{ $result->origin_grade ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center text-xs font-bold text-blue-700">{{ $result->grade ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($conversion->status === 'waiting_rektor')
            <div class="bg-white rounded-3xl shadow-xl ring-1 ring-slate-200 overflow-hidden">
                <div class="px-6 py-6 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-800 mb-4">Persetujuan Rektor & Terbitkan SK</h3>
                    <form action="{{ route('rektor.conversions.update', $conversion) }}" method="POST" class="space-y-6" x-data="{ status: '' }">
                        @csrf
                        @method('PUT')

                        <div>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <label class="grow flex items-center px-4 py-3 rounded-2xl border-2 border-slate-100 cursor-pointer hover:border-emerald-200 hover:bg-emerald-50 transition-all group has-checked:border-emerald-500 has-checked:bg-emerald-50" :class="status === 'approved' ? 'border-emerald-500 bg-emerald-50' : ''">
                                    <input type="radio" name="status" value="approved" class="hidden peer" required x-model="status">
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center mr-3 group-hover:border-emerald-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-500" :class="status === 'approved' ? 'border-emerald-500 bg-emerald-500' : ''">
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <div class="flex flex-col text-left">
                                        <span class="text-sm font-bold text-slate-700 uppercase tracking-tight">Setujui & Terbitkan SK</span>
                                    </div>
                                </label>
                                <label class="grow flex items-center px-4 py-3 rounded-2xl border-2 border-slate-100 cursor-pointer hover:border-red-200 hover:bg-red-50 transition-all group has-checked:border-red-500 has-checked:bg-red-50" :class="status === 'rejected' ? 'border-red-500 bg-red-50' : ''">
                                    <input type="radio" name="status" value="rejected" class="hidden peer" x-model="status">
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center mr-3 group-hover:border-red-300 peer-checked:border-red-500 peer-checked:bg-red-500" :class="status === 'rejected' ? 'border-red-500 bg-red-500' : ''">
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <span class="text-sm font-bold text-slate-700 uppercase tracking-tight">Tolak Permintaan</span>
                                </label>
                            </div>
                        </div>

                        <!-- Decree Details (Shown only when Approved) -->
                        <div x-show="status === 'approved'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6 bg-slate-50 p-6 rounded-2xl border border-slate-100 mt-6">
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Detail Surat Keputusan (SK)</h4>
                            
                            <div>
                                <label for="decree_number" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nomor SK</label>
                                <input type="text" name="decree_number" id="decree_number" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none" placeholder="mis. 019/SK/Rektor-ITEBA/III/2024" :required="status === 'approved'">
                                <p class="mt-2 text-[10px] text-slate-400">Tanggal SK dan Tahun Akademik akan diatur otomatis saat persetujuan.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-200 pt-4">
                                <div>
                                    <label for="rector_name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Rektor</label>
                                    <input type="text" name="rector_name" id="rector_name" value="Prof. Dr. Ing. Ir. H. Hairul Abral" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none" :required="status === 'approved'">
                                </div>
                                <div>
                                    <label for="rector_nidn" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">NIDN Rektor</label>
                                    <input type="text" name="rector_nidn" id="rector_nidn" value="0017086612" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none" :required="status === 'approved'">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="notes" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Catatan (Opsional)</label>
                            <textarea name="notes" rows="4" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none" placeholder="Catatan...">{{ $conversion->notes }}</textarea>
                        </div>

                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-2xl text-sm font-bold shadow-xl shadow-emerald-600/20 active:scale-[0.98] transition-all">
                            SIMPAN KEPUTUSAN
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
