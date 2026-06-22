@extends('layouts.app')

@section('title', 'Buat Pemetaan Mata Kuliah')

@section('content')
<div class="max-w-7xl mx-auto py-2 px-2">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-slate-400 font-medium">
            <li><a href="{{ route('kaprodi.mappings.index') }}" class="hover:text-blue-600 transition-colors">Pemetaan</a></li>
            <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
            <li class="text-slate-900">Pemetaan Baru</li>
        </ol>
    </nav>

    <div class="bg-white shadow-xl ring-1 ring-slate-200 rounded-2xl overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-xl font-bold text-slate-900 tracking-tight">Alat Pemetaan Mata Kuliah</h3>
            <p class="mt-1 text-sm text-slate-500">Hubungkan satu mata kuliah asal ke satu atau lebih mata kuliah kurikulum internal.</p>
        </div>

        <div class="px-8 py-10">
            <form method="POST" action="{{ route('kaprodi.mappings.store') }}" class="space-y-8">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="source_subject_id" class="block text-sm font-bold text-slate-700 mb-3">1. Pilih Mata Kuliah Asal (Eksternal)</label>
                        <select id="source_subject_id" name="source_subject_id" required class="searchable-select rounded-lg relative block w-full px-4 py-3 border border-amber-300 bg-amber-50/10 text-slate-900 shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm" placeholder="-- Pilih Mata Kuliah Asal --">
                            <option value="">-- Pilih Mata Kuliah Asal --</option>
                            @foreach($sources as $source)
                                <option value="{{ $source->id }}">[{{ $source->code }}] {{ $source->name }} - {{ $source->sks }} Kredit ({{ $source->university->name }})</option>
                            @endforeach
                        </select>
                        @error('source_subject_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div x-data="{ targetSearch: '' }">
                        <label class="block text-sm font-bold text-slate-700 mb-3">2. Petakan ke Mata Kuliah Internal (Tujuan)</label>
                        <div class="relative mb-3">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input type="text" x-model="targetSearch" placeholder="Cari mata kuliah tujuan berdasarkan kode atau nama..."
                                   class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                        </div>
                        <div class="bg-blue-50/30 p-6 rounded-2xl ring-1 ring-blue-100 max-h-100 overflow-y-auto">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @forelse($targets as $target)
                                    <label x-show="targetSearch === '' || '{{ strtolower($target->code) }} {{ strtolower($target->name) }}'.includes(targetSearch.toLowerCase())"
                                           x-transition:enter="transition ease-out duration-200"
                                           x-transition:enter-start="opacity-0 scale-95"
                                           x-transition:enter-end="opacity-100 scale-100"
                                           class="relative flex items-start p-4 bg-white rounded-xl border border-slate-200 cursor-pointer shadow-sm hover:border-blue-500 transition-all group">
                                        <div class="flex items-center h-5">
                                            <input name="target_subject_ids[]" type="checkbox" value="{{ $target->id }}" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <span class="block font-bold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $target->code }}</span>
                                            <span class="block text-[10px] text-slate-500 line-clamp-1">{{ $target->name }}</span>
                                            <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-[8px] font-medium bg-slate-100 text-slate-600">{{ $target->sks }} Kredit</span>
                                        </div>
                                    </label>
                                @empty
                                    <div class="col-span-full py-8 text-center text-sm text-slate-500">
                                        Mata kuliah internal tidak ditemukan. Harap tambahkan terlebih dahulu di repositori.
                                    </div>
                                @endforelse
                            </div>
                            <div x-show="targetSearch !== '' && !document.querySelector('[x-show*=targetSearch]:not([style*=display\:\ none])')" class="py-6 text-center text-sm text-slate-400" x-cloak>
                                Tidak ada mata kuliah yang cocok dengan pencarian Anda.
                            </div>
                        </div>
                        @error('target_subject_ids') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="bg-indigo-50 p-4 rounded-xl flex items-start space-x-3 border border-indigo-100">
                    <svg class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-xs text-indigo-700">Anda dapat memetakan satu mata kuliah asal ke beberapa mata kuliah internal jika kurikulumnya cocok.</span>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-4 border-t border-slate-100">
                    <a href="{{ route('kaprodi.mappings.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-500/20 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Selesaikan Pemetaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
