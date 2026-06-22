@extends('layouts.app')

@section('title', 'Edit Profil Mahasiswa')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="mb-8">
        <a href="{{ route('mahasiswa.dashboard') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Dashboard
        </a>
        <h1 class="text-3xl font-bold text-slate-900 mt-4 tracking-tight">Lengkapi Identitas</h1>
        <p class="text-slate-500 mt-1">Pastikan informasi yang Anda berikan sudah benar dan sesuai dengan dokumen resmi.</p>
    </div>

    <form action="{{ route('mahasiswa.profile.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="bg-white shadow-xl ring-1 ring-slate-200 rounded-3xl overflow-hidden">
            <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-900">Informasi Dasar</h3>
                <p class="text-xs text-slate-500">Informasi utama untuk identifikasi mahasiswa.</p>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="nim" class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">NIM (Nomor Induk Mahasiswa)</label>
                    <input type="text" name="nim" id="nim" value="{{ old('nim', $detail->nim) }}" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm" placeholder="Masukkan NIM">
                    @error('nim') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="gender" class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Jenis Kelamin</label>
                    <select name="gender" id="gender" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ old('gender', $detail->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('gender', $detail->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="place_of_birth" class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Tempat Lahir</label>
                    <input type="text" name="place_of_birth" id="place_of_birth" value="{{ old('place_of_birth', $detail->place_of_birth) }}" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm" placeholder="Contoh: Jakarta">
                    @error('place_of_birth') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="date_of_birth" class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Tanggal Lahir</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $detail->date_of_birth) }}" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm">
                    @error('date_of_birth') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white shadow-xl ring-1 ring-slate-200 rounded-3xl overflow-hidden">
            <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-900">Kontak & Alamat</h3>
                <p class="text-xs text-slate-500">Bagaimana kami dapat menghubungi Anda.</p>
            </div>
            <div class="p-8 space-y-6">
                <div class="space-y-2">
                    <label for="phone" class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Nomor Telepon/WhatsApp</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $detail->phone) }}" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm" placeholder="Contoh: 08123456789">
                    @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="address" class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Alamat Lengkap</label>
                    <textarea name="address" id="address" rows="3" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm" placeholder="Masukkan alamat lengkap sesuai KTP">{{ old('address', $detail->address) }}</textarea>
                    @error('address') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white shadow-xl ring-1 ring-slate-200 rounded-3xl overflow-hidden">
            <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-900">Informasi Orang Tua</h3>
                <p class="text-xs text-slate-500">Nama lengkap orang tua kandung.</p>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="father_name" class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Nama Ayah</label>
                    <input type="text" name="father_name" id="father_name" value="{{ old('father_name', $detail->father_name) }}" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm" placeholder="Nama Lengkap Ayah">
                    @error('father_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="mother_name" class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Nama Ibu</label>
                    <input type="text" name="mother_name" id="mother_name" value="{{ old('mother_name', $detail->mother_name) }}" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm" placeholder="Nama Lengkap Ibu">
                    @error('mother_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="inline-flex items-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-xl shadow-blue-500/25 transition-all hover:scale-105 active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
