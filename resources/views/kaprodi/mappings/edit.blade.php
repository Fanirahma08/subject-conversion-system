@extends('layouts.app')

@section('title', 'Edit Pemetaan Mata Kuliah')

@section('content')
<div class="max-w-4xl mx-auto py-2 px-2">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-slate-400 font-medium">
            <li><a href="{{ route('kaprodi.mappings.index') }}" class="hover:text-blue-600 transition-colors">Pemetaan</a></li>
            <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
            <li class="text-slate-900">Edit Pemetaan</li>
        </ol>
    </nav>

    <div class="bg-white shadow-xl ring-1 ring-slate-200 rounded-2xl overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-xl font-bold text-slate-900 tracking-tight">Kelola Pemetaan untuk: {{ $source->code }}</h3>
            <p class="mt-1 text-sm text-slate-500">{{ $source->name }} ({{ $source->university_origin }})</p>
        </div>

        <div class="px-8 py-10">
            <form method="POST" action="{{ route('kaprodi.mappings.update', $source) }}" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="space-y-6" x-data="{ targetSearch: '' }">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3">Petakan ke Mata Kuliah Internal (Tujuan)</label>
                        <div class="relative mb-3">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input type="text" x-model="targetSearch" placeholder="Cari mata kuliah tujuan berdasarkan kode atau nama..."
                                   class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                        </div>
                        <div class="bg-blue-50/30 p-6 rounded-2xl ring-1 ring-blue-100 max-h-100 overflow-y-auto">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($targets as $target)
                                    <label x-show="targetSearch === '' || '{{ strtolower($target->code) }} {{ strtolower($target->name) }}'.includes(targetSearch.toLowerCase())"
                                           x-transition:enter="transition ease-out duration-200"
                                           x-transition:enter-start="opacity-0 scale-95"
                                           x-transition:enter-end="opacity-100 scale-100"
                                           class="relative flex items-start p-4 bg-white rounded-xl border border-slate-200 cursor-pointer shadow-sm hover:border-blue-500 transition-all group">
                                        <div class="flex items-center h-5">
                                            <input name="target_subject_ids[]" type="checkbox" value="{{ $target->id }}" 
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded"
                                                   {{ in_array($target->id, $currentMappingIds) ? 'checked' : '' }}>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <span class="block font-bold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $target->code }}</span>
                                            <span class="block text-[10px] text-slate-500 line-clamp-1">{{ $target->name }}</span>
                                            <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-[8px] font-medium bg-slate-100 text-slate-600">{{ $target->sks }} Kredit</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @error('target_subject_ids') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-4 border-t border-slate-100">
                    <a href="{{ route('kaprodi.mappings.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Perbarui Pemetaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
