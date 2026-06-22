@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
<div class="max-w-7xl mx-auto py-2 px-2">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li>
                <a href="{{ route('kaprodi.users.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">Manajemen Pengguna</a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="shrink-0 h-5 w-5 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 11 7.293 7.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-2 text-sm font-medium text-slate-900">Tambah Pengguna Baru</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white shadow-xl ring-1 ring-slate-200 rounded-2xl overflow-hidden p-8 sm:p-12 border border-slate-100">
        <h1 class="text-2xl font-bold text-slate-900 mb-8 tracking-tight">Buat Akun Pengguna</h1>

        <form action="{{ route('kaprodi.users.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 gap-y-6 gap-x-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="appearance-none block w-full px-3 py-3 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="John Doe">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="appearance-none block w-full px-3 py-3 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="john@example.com">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Kata Sandi Sementara</label>
                    <input type="password" id="password" name="password" required class="appearance-none block w-full px-3 py-3 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="••••••••">
                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-slate-700 mb-1">Peran Sistem</label>
                    <select id="role" name="role" required class="block w-full px-3 py-3 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm appearance-none bg-no-repeat bg-right" style="background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 20 20%27%3E%3Cpath stroke=%27%236b7280%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%271.5%27 d=%27m6 8 4 4 4-4%27/%3E%3C/svg%3E'); background-size: 1.5rem auto; background-position: right 0.5rem center;">
                        <option value="{{ \App\Enums\UserRole::PMB->value }}" {{ old('role') == \App\Enums\UserRole::PMB->value ? 'selected' : '' }}>PMB</option>
                        <option value="{{ \App\Enums\UserRole::Kaprodi->value }}" {{ old('role') == \App\Enums\UserRole::Kaprodi->value ? 'selected' : '' }}>Kaprodi</option>
                        <option value="{{ \App\Enums\UserRole::Dekan->value }}" {{ old('role') == \App\Enums\UserRole::Dekan->value ? 'selected' : '' }}>Dekan</option>
                        <option value="{{ \App\Enums\UserRole::Rektor->value }}" {{ old('role') == \App\Enums\UserRole::Rektor->value ? 'selected' : '' }}>Rektor</option>
                    </select>
                    @error('role')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="prodi" class="block text-sm font-medium text-slate-700 mb-1">Prodi (Opsional)</label>
                    <input type="text" id="prodi" name="prodi" value="{{ old('prodi') }}" class="appearance-none block w-full px-3 py-3 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Teknik Informatika">
                    @error('prodi')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-6 border-t border-slate-100">
                <a href="{{ route('kaprodi.users.index') }}" class="inline-flex justify-center py-3 px-6 border border-slate-300 shadow-sm text-sm font-semibold rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    Batal
                </a>
                <button type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-lg shadow-blue-500/20 text-sm font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    Buat Akun
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
