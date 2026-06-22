@extends('layouts.app')

@section('title', 'Edit Detail Mahasiswa')

@section('content')
<div class="max-w-7xl mx-auto py-2 px-2">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-slate-400 font-medium">
            <li><a href="{{ route('pmb.students.index') }}" class="hover:text-blue-600 transition-colors">Mahasiswa</a></li>
            <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
            <li class="text-slate-900">Edit Detail Mahasiswa</li>
        </ol>
    </nav>

    <div class="bg-white shadow-xl ring-1 ring-slate-200 rounded-2xl overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-xl font-bold text-slate-900 tracking-tight">Edit Data Mahasiswa</h3>
            <p class="mt-1 text-sm text-slate-500">Perbarui informasi profil dan data akademik mahasiswa.</p>
        </div>

        <div class="px-8 py-10">
            <form method="POST" action="{{ route('pmb.students.update', $student) }}" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">Informasi Akun & Akademik</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-full">
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                            <input id="name" name="name" type="text" required value="{{ old('name', $student->name) }}" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                            @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="col-span-full">
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Alamat Email</label>
                            <input id="email" name="email" type="email" required value="{{ old('email', $student->email) }}" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                            @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="university_id" class="block text-sm font-medium text-slate-700 mb-1">Universitas Asal</label>
                            <select id="university_id" name="university_id" required class="searchable-select rounded-lg relative block w-full px-4 py-3 border border-slate-300 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                                <option value="">Pilih Universitas</option>
                                @foreach($universities as $uni)
                                    <option value="{{ $uni->id }}" {{ old('university_id', $student->studentDetail?->university_id) == $uni->id ? 'selected' : '' }}>{{ $uni->name }}</option>
                                @endforeach
                            </select>
                            @error('university_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="prodi_origin" class="block text-sm font-medium text-slate-700 mb-1">Program Studi Asal</label>
                            <input id="prodi_origin" name="prodi_origin" type="text" required value="{{ old('prodi_origin', $student->studentDetail?->prodi_origin) }}" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                            @error('prodi_origin') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="graduation_date" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Kelulusan</label>
                            <input id="graduation_date" name="graduation_date" type="date" value="{{ old('graduation_date', $student->studentDetail?->graduation_date?->format('Y-m-d')) }}" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                            @error('graduation_date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="col-span-full">
                            <label for="prodi" class="block text-sm font-medium text-slate-700 mb-1">Program Studi Tujuan</label>
                            <select id="prodi" name="prodi" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                                <option value="Sistem Informasi" {{ old('prodi', $student->prodi) == 'Sistem Informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                            </select>
                            @error('prodi') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-6 pt-6">
                    <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">Detail Personal</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nim" class="block text-sm font-medium text-slate-700 mb-1">NIM (Jika Ada)</label>
                            <input id="nim" name="nim" type="text" value="{{ old('nim', $student->studentDetail?->nim) }}" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                            @error('nim') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Nomor Telepon</label>
                            <input id="phone" name="phone" type="text" value="{{ old('phone', $student->studentDetail?->phone) }}" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                            @error('phone') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="place_of_birth" class="block text-sm font-medium text-slate-700 mb-1">Tempat Lahir</label>
                            <input id="place_of_birth" name="place_of_birth" type="text" value="{{ old('place_of_birth', $student->studentDetail?->place_of_birth) }}" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                            @error('place_of_birth') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Lahir</label>
                            <input id="date_of_birth" name="date_of_birth" type="date" value="{{ old('date_of_birth', $student->studentDetail?->date_of_birth) }}" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                            @error('date_of_birth') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-slate-700 mb-1">Jenis Kelamin</label>
                            <select id="gender" name="gender" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('gender', $student->studentDetail?->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('gender', $student->studentDetail?->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="col-span-full">
                            <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                            <textarea id="address" name="address" rows="3" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">{{ old('address', $student->studentDetail?->address) }}</textarea>
                            @error('address') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="father_name" class="block text-sm font-medium text-slate-700 mb-1">Nama Ayah</label>
                            <input id="father_name" name="father_name" type="text" value="{{ old('father_name', $student->studentDetail?->father_name) }}" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                            @error('father_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="mother_name" class="block text-sm font-medium text-slate-700 mb-1">Nama Ibu</label>
                            <input id="mother_name" name="mother_name" type="text" value="{{ old('mother_name', $student->studentDetail?->mother_name) }}" class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                            @error('mother_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-8 border-t border-slate-100">
                    <a href="{{ route('pmb.students.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
