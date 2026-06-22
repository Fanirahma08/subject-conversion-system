@extends('layouts.app')

@section('title', 'Edit Universitas')

@section('content')
<div class="max-w-2xl mx-auto py-2 px-2">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-slate-400 font-medium">
            <li><a href="{{ route('pmb.universities.index') }}" class="hover:text-blue-600 transition-colors">Universitas</a></li>
            <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg></li>
            <li class="text-slate-900">Edit Universitas</li>
        </ol>
    </nav>

    <div class="bg-white shadow-xl ring-1 ring-slate-200 rounded-3xl overflow-hidden">
        <div class="px-8 py-8 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-2xl font-bold text-slate-900 tracking-tight text-center">Perbarui Universitas</h3>
            <p class="mt-1 text-sm text-slate-500 text-center">Ubah detail institusi untuk **{{ $university->name }}**.</p>
        </div>

        <div class="px-8 py-10">
            <form method="POST" action="{{ route('pmb.universities.update', $university) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nama Universitas</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $university->name) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none" placeholder="mis. Universitas Indonesia" required>
                        @error('name') <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="code" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Kode Institusi (Opsional)</label>
                        <input type="text" name="code" id="code" value="{{ old('code', $university->code) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none" placeholder="mis. UI">
                        @error('code') <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-4 border-t border-slate-100">
                    <a href="{{ route('pmb.universities.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-8 py-3 border border-transparent text-sm font-semibold rounded-2xl text-white bg-blue-600 hover:bg-blue-700 shadow-xl shadow-blue-500/20 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 active:scale-95">
                        Perbarui Universitas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection