@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="max-w-7xl mx-auto py-2 px-2">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-slate-400 font-medium">
            <li><a href="{{ route('kaprodi.users.index') }}" class="hover:text-blue-600 transition-colors">Manajemen Pengguna</a></li>
            <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
            <li class="text-slate-900">Edit Pengguna</li>
        </ol>
    </nav>

    <div class="bg-white shadow-xl ring-1 ring-slate-200 rounded-2xl overflow-hidden p-8 sm:p-12 border border-slate-100">
        <h1 class="text-2xl font-bold text-slate-900 mb-8 tracking-tight">Perbarui Akun Pengguna</h1>

        <form action="{{ route('kaprodi.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-y-6 gap-x-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="appearance-none block w-full px-3 py-3 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="John Doe">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="appearance-none block w-full px-3 py-3 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="john@example.com">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Kata Sandi Baru (Opsional)</label>
                    <input type="password" id="password" name="password" class="appearance-none block w-full px-3 py-3 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Biarkan kosong jika tidak diubah">
                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-slate-700 mb-1">Peran Sistem</label>
                    <select id="role" name="role" required class="block w-full px-3 py-3 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm appearance-none bg-no-repeat bg-right" style="background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 20 20%27%3E%3Cpath stroke=%27%236b7280%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%271.5%27 d=%27m6 8 4 4 4-4%27/%3E%3C/svg%3E'); background-size: 1.5rem auto; background-position: right 0.5rem center;">
                        <option value="{{ \App\Enums\UserRole::PMB->value }}" {{ old('role', $user->role->value) == \App\Enums\UserRole::PMB->value ? 'selected' : '' }}>PMB</option>
                        <option value="{{ \App\Enums\UserRole::Kaprodi->value }}" {{ old('role', $user->role->value) == \App\Enums\UserRole::Kaprodi->value ? 'selected' : '' }}>Kaprodi</option>
                        <option value="{{ \App\Enums\UserRole::BAAK->value }}" {{ old('role', $user->role->value) == \App\Enums\UserRole::BAAK->value ? 'selected' : '' }}>BAAK</option>
                        <option value="{{ \App\Enums\UserRole::Dekan->value }}" {{ old('role', $user->role->value) == \App\Enums\UserRole::Dekan->value ? 'selected' : '' }}>Dekan</option>
                        <option value="{{ \App\Enums\UserRole::WR1->value }}" {{ old('role', $user->role->value) == \App\Enums\UserRole::WR1->value ? 'selected' : '' }}>Wakil Rektor 1 (WR1)</option>
                        <option value="{{ \App\Enums\UserRole::Rektor->value }}" {{ old('role', $user->role->value) == \App\Enums\UserRole::Rektor->value ? 'selected' : '' }}>Rektor</option>
                    </select>
                    @error('role')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="prodi" class="block text-sm font-medium text-slate-700 mb-1">Prodi (Opsional)</label>
                    <input type="text" id="prodi" name="prodi" value="{{ old('prodi', $user->prodi) }}" class="appearance-none block w-full px-3 py-3 border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Teknik Informatika">
                    @error('prodi')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-6 border-t border-slate-100">
                <a href="{{ route('kaprodi.users.index') }}" class="inline-flex justify-center py-3 px-6 border border-slate-300 shadow-sm text-sm font-semibold rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    Batal
                </a>
                <button type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-lg shadow-blue-500/20 text-sm font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
