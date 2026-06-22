@extends('layouts.app')

@section('title', 'Daftarkan Mahasiswa Baru')

@section('content')
<div class="max-w-7xl mx-auto py-2 px-2">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-slate-400 font-medium">
            <li><a href="{{ route('pmb.students.index') }}" class="hover:text-blue-600 transition-colors">Mahasiswa</a></li>
            <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
            <li class="text-slate-900">Daftarkan Mahasiswa Baru</li>
        </ol>
    </nav>

    <div class="bg-white shadow-xl ring-1 ring-slate-200 rounded-2xl overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-xl font-bold text-slate-900 tracking-tight">Formulir Pendaftaran Mahasiswa</h3>
            <p class="mt-1 text-sm text-slate-500">Daftarkan mahasiswa baru untuk konversi mata kuliah dari universitas sebelumnya.</p>
        </div>

        <div class="px-8 py-10">
            <form method="POST" action="{{ route('pmb.students.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-full">
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="mis. John Doe">
                        @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-full">
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Alamat Email</label>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="john@example.com">
                        @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label for="university_id" class="block text-sm font-medium text-slate-700">Universitas Asal</label>
                            <a href="{{ route('pmb.universities.create') }}" class="text-[10px] font-bold text-blue-600 hover:text-blue-800 uppercase tracking-tighter decoration-slate-300 decoration-dotted underline-offset-2 hover:underline">Daftarkan Uni Baru</a>
                        </div>
                        <select id="university_id" name="university_id" required class="searchable-select rounded-lg relative block w-full px-4 py-3 border border-slate-300 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="Pilih Universitas">
                            <option value="">Pilih Universitas</option>
                            @foreach($universities as $uni)
                                <option value="{{ $uni->id }}" {{ old('university_id') == $uni->id ? 'selected' : '' }}>{{ $uni->name }}</option>
                            @endforeach
                        </select>
                        @error('university_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="prodi_origin" class="block text-sm font-medium text-slate-700 mb-1">Program Studi Asal</label>
                        <input id="prodi_origin" name="prodi_origin" type="text" required value="{{ old('prodi_origin') }}" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="mis. Teknik Informatika">
                        @error('prodi_origin') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="graduation_date" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Kelulusan</label>
                        <input id="graduation_date" name="graduation_date" type="date" value="{{ old('graduation_date') }}" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                        @error('graduation_date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-full">
                        <label for="prodi" class="block text-sm font-medium text-slate-700 mb-1">Program Studi Tujuan</label>
                        <select id="prodi" name="prodi" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                            <option value="Sistem Informasi">Sistem Informasi</option>
                        </select>
                        @error('prodi') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="bg-blue-50 p-4 rounded-xl flex items-start space-x-3 mb-8 border border-blue-100 italic">
                    <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-xs text-blue-700">Mahasiswa baru akan memiliki kata sandi default <b>123456</b>. Harap beri tahu mereka untuk mengubahnya pada saat login pertama.</span>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-4">
                    <a href="{{ route('pmb.students.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                    <a href="{{ route('pmb.students.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Daftarkan Mahasiswa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
