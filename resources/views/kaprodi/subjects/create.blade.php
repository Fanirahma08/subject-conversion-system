@extends('layouts.app')

@section('title', 'Tambah Mata Kuliah Baru')

@section('content')
<div class="max-w-7xl mx-auto py-2 px-2">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-slate-400 font-medium">
            <li><a href="{{ route('kaprodi.subjects.index') }}" class="hover:text-blue-600 transition-colors">Mata Kuliah</a></li>
            <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
            <li class="text-slate-900">Tambah Mata Kuliah Baru</li>
        </ol>
    </nav>

    <div class="bg-white shadow-xl ring-1 ring-slate-200 rounded-2xl overflow-hidden" x-data="{ isOrigin: false }">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold text-slate-900 tracking-tight">Informasi Mata Kuliah</h3>
                <p class="mt-1 text-sm text-slate-500">Tambahkan mata kuliah ke repositori.</p>
            </div>
            <div class="flex items-center space-x-3 bg-white p-1 rounded-xl shadow-sm ring-1 ring-slate-100">
                <button @click="isOrigin = false" :class="!isOrigin ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-slate-600'" class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all">Internal</button>
                <button @click="isOrigin = true" :class="isOrigin ? 'bg-amber-500 text-white' : 'text-slate-400 hover:text-slate-600'" class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all">Eksternal</button>
            </div>
        </div>

        <div class="px-8 py-10">
            <form method="POST" action="{{ route('kaprodi.subjects.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1">
                        <label for="code" class="block text-sm font-medium text-slate-700 mb-1">Kode Mata Kuliah</label>
                        <input id="code" name="code" type="text" required value="{{ old('code') }}" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="mis. INF-101">
                        @error('code') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Mata Kuliah</label>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="mis. Sistem Basis Data">
                        @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-full">
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Deskripsi (Opsional)</label>
                        <textarea id="description" name="description" rows="3" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Tinjauan singkat tentang kurikulum mata kuliah...">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="sks" class="block text-sm font-medium text-slate-700 mb-1">SKS (Kredit) <span class="text-red-500">*</span></label>
                        <input id="sks" name="sks" type="number" required value="{{ old('sks') }}" min="1" max="24" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('sks') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="semester" class="block text-sm font-medium text-slate-700 mb-1">Semester <span class="text-slate-400 text-xs font-normal">(Opsional untuk Eksternal)</span></label>
                        <input id="semester" name="semester" type="number" value="{{ old('semester') }}" min="1" max="10" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('semester') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-full" x-show="isOrigin" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <label for="university_id" class="block text-sm font-medium text-slate-700 mb-1">Universitas Asal</label>
                        <select id="university_id" name="university_id" :required="isOrigin" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-amber-300 bg-amber-50/20 text-slate-900 shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                            <option value="">Pilih Universitas</option>
                            @foreach($universities as $uni)
                                <option value="{{ $uni->id }}" {{ old('university_id') == $uni->id ? 'selected' : '' }}>{{ $uni->name }}</option>
                            @endforeach
                        </select>
                        @error('university_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-full">
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-200">
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">Status Aktif</h4>
                                <p class="text-[10px] text-slate-500">Aktifkan ini jika mata kuliah saat ini adalah bagian dari kurikulum aktif.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-8">
                    <a href="{{ route('kaprodi.subjects.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                        Batal
                    </a>
                    <button type="submit" :class="isOrigin ? 'bg-amber-500 hover:bg-amber-600 shadow-amber-500/20' : 'bg-blue-600 hover:bg-blue-700 shadow-blue-500/20'" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-lg text-white shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-offset-2">
                        Simpan Mata Kuliah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
