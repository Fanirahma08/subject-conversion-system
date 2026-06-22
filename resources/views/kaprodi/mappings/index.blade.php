@extends('layouts.app')

@section('title', 'Pemetaan Mata Kuliah')

@section('content')
<div class="max-w-7xl mx-auto py-2 px-2">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Pemetaan Mata Kuliah</h1>
            <p class="mt-1 text-sm text-slate-500">Tentukan hubungan antara mata kuliah eksternal dan internal.</p>
        </div>
        <a href="{{ route('kaprodi.mappings.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-500/20 transition-all">
            Buat Pemetaan Baru
        </a>
    </div>

    <!-- Filters Section -->
    <div class="bg-white p-6 rounded-3xl shadow-sm ring-1 ring-slate-200 mb-8 border border-slate-100">
        <form action="{{ route('kaprodi.mappings.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all" placeholder="Cari Kode atau Nama Mata Kuliah...">
                </div>
            </div>
            <div class="w-full md:w-64">
                <select name="university_id" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all appearance-none bg-no-repeat bg-right" style="background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 20 20%27%3E%3Cpath stroke=%27%236b7280%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%271.5%27 d=%27m6 8 4 4 4-4%27/%3E%3C/svg%3E'); background-size: 1.5rem auto; background-position: right 0.5rem center;">
                    <option value="">Semua Universitas</option>
                    @foreach($universities as $uni)
                        <option value="{{ $uni->id }}" {{ $universityId == $uni->id ? 'selected' : '' }}>{{ $uni->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition-all">
                Filter
            </button>
            @if($search || $universityId)
                <a href="{{ route('kaprodi.mappings.index') }}" class="px-6 py-2 bg-slate-100 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all text-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 text-emerald-700 p-4 rounded-lg text-sm border border-emerald-100 flex items-center">
            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @foreach($mappings->groupBy(fn($m) => $m->source_subject->university->name ?? ($m->university->name ?? 'Institusi Eksternal')) as $universityName => $universityMappings)
        <div class="mb-12">
            <div class="flex items-center mb-4 px-2">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 mr-4 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight">{{ $universityName }}</h2>
                <span class="ml-4 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-widest">{{ $universityMappings->count() }} Standar</span>
            </div>

            <div class="bg-white shadow-sm ring-1 ring-slate-200 rounded-3xl overflow-hidden">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Sumber (Asal)</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Relasi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Target (Internal)</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Dipetakan Pada</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @foreach ($universityMappings->groupBy('source_subject_id') as $sourceSubjectId => $subjectMappings)
                            @php
                                $firstMapping = $subjectMappings->first();
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-900">{{ $firstMapping->source_subject->code }}</span>
                                        <span class="text-[10px] text-slate-500 truncate max-w-50">{{ $firstMapping->source_subject->name }}</span>
                                        <span class="text-[9px] text-amber-600 font-bold uppercase mt-1">{{ $universityName }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center align-top pt-5">
                                    <svg class="mx-auto w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                    <div class="flex flex-col space-y-2">
                                        @foreach ($subjectMappings as $mapping)
                                            <div class="flex flex-col p-2 bg-slate-50/80 rounded-lg border border-slate-100 relative group min-w-50">
                                                <div class="flex justify-between items-start">
                                                    <div class="flex flex-col">
                                                        <span class="text-xs font-bold text-indigo-600">{{ $mapping->target_subject->code }}</span>
                                                        <span class="text-[10px] text-slate-500 truncate max-w-50">{{ $mapping->target_subject->name }}</span>
                                                    </div>
                                                    <form action="{{ route('kaprodi.mappings.destroy', $mapping) }}" method="POST" onsubmit="return confirm('Hapus pemetaan spesifik ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-slate-300 hover:text-red-500 p-0.5 opacity-0 group-hover:opacity-100 transition-opacity ml-2 rounded" title="Hapus target pemetaan">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="flex items-center mt-1">
                                                    <span class="text-[9px] text-blue-600 italic">{{ $mapping->target_subject->sks }} SKS</span>
                                                    @if($mapping->prodi)
                                                        <span class="mx-1 text-[9px] text-slate-300">•</span>
                                                        <span class="text-[9px] font-bold text-emerald-600 uppercase tracking-tighter bg-emerald-50 px-1 rounded">{{ $mapping->prodi }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-[10px] text-slate-500 italic align-top pt-5">
                                    {{ $firstMapping->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium align-top pt-4">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('kaprodi.mappings.edit', $firstMapping->source_subject_id) }}" class="text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-all text-xs font-bold inline-flex items-center border border-indigo-100 hover:border-indigo-200">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('kaprodi.mappings.destroy-by-source', $firstMapping->source_subject_id) }}" method="POST" onsubmit="return confirm('Hapus semua pemetaan untuk mata kuliah ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-all text-xs font-bold inline-flex items-center border border-red-100 hover:border-red-200">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    @if($mappings->isEmpty())
        <div class="bg-white shadow-sm ring-1 ring-slate-200 rounded-3xl p-12 text-center">
            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <p class="text-sm text-slate-500">Belum ada pemetaan mata kuliah yang ditentukan. Klik "Buat Pemetaan Baru" untuk mulai menautkan kurikulum.</p>
        </div>
    @endif
</div>
@endsection
