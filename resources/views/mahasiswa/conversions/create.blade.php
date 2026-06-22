@extends('layouts.app')

@section('title', 'Unggah Berkas Pendaftaran')

@section('content')
<div class="max-w-4xl mx-auto py-2 px-2">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-slate-400 font-medium">
            <li><a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-blue-600 transition-colors">Dashboard</a></li>
            <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
            <li class="text-slate-900">Unggah Berkas</li>
        </ol>
    </nav>

    <div class="bg-white shadow-xl ring-1 ring-slate-200 rounded-3xl overflow-hidden">
        <div class="px-8 py-8 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-2xl font-bold text-slate-900 tracking-tight">Pengajuan Berkas Pendaftaran</h3>
            <p class="mt-1 text-sm text-slate-500">Silakan lengkapi berkas pendaftaran Anda untuk ditinjau oleh tim PMB.</p>
        </div>

        <div class="px-8 py-10">
            <form method="POST" action="{{ route('mahasiswa.conversions.store') }}" enctype="multipart/form-data" class="space-y-10">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Transkrip Nilai -->
                    <div class="space-y-4" x-data="{ fileName: '{{ $conversion && $conversion->transcript_path ? basename($conversion->transcript_path) : '' }}' }">
                        <label class="block text-sm font-bold text-slate-700">
                            Transkrip Nilai <span class="text-red-500">*</span>
                            @if($conversion && $conversion->transcript_path)
                                <span class="ml-2 text-[10px] text-emerald-600 font-bold uppercase">(Sudah Diunggah)</span>
                            @endif
                        </label>
                        <div class="relative">
                            <input type="file" name="transcript" id="transcript" class="hidden" accept=".pdf,.jpg,.jpeg,.png" @change="fileName = $event.target.files[0].name" {{ $conversion && $conversion->transcript_path ? '' : 'required' }}>
                            <label for="transcript" class="flex flex-col items-center justify-center w-full min-h-40 px-6 py-8 border-2 border-dashed border-slate-300 rounded-3xl cursor-pointer bg-slate-50/50 hover:bg-blue-50/50 hover:border-blue-400 transition-all group">
                                <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div class="text-center">
                                    <span class="block text-xs font-bold text-slate-900" x-text="fileName ? fileName : 'Upload Transkrip'"></span>
                                    <span class="mt-1 block text-[10px] text-slate-400">PDF, JPG, PNG (Max. 5MB)</span>
                                </div>
                            </label>
                        </div>
                        @error('transcript') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Surat Pendaftaran -->
                    <div class="space-y-4" x-data="{ fileName: '{{ $conversion && $conversion->registration_letter_path ? basename($conversion->registration_letter_path) : '' }}' }">
                        <label class="block text-sm font-bold text-slate-700">
                            Surat Pendaftaran
                            @if($conversion && $conversion->registration_letter_path)
                                <span class="ml-2 text-[10px] text-emerald-600 font-bold uppercase">(Sudah Diunggah)</span>
                            @endif
                        </label>
                        <div class="relative">
                            <input type="file" name="registration_letter" id="registration_letter" class="hidden" accept=".pdf,.jpg,.jpeg,.png" @change="fileName = $event.target.files[0].name">
                            <label for="registration_letter" class="flex flex-col items-center justify-center w-full min-h-40 px-6 py-8 border-2 border-dashed border-slate-300 rounded-3xl cursor-pointer bg-slate-50/50 hover:bg-indigo-50/50 hover:border-indigo-400 transition-all group">
                                <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="text-center">
                                    <span class="block text-xs font-bold text-slate-900" x-text="fileName ? fileName : 'Upload Surat Pendaftaran'"></span>
                                    <span class="mt-1 block text-[10px] text-slate-400">PDF, JPG, PNG (Max. 5MB)</span>
                                </div>
                            </label>
                        </div>
                        @error('registration_letter') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- KTP -->
                    <div class="space-y-4" x-data="{ fileName: '{{ $conversion && $conversion->ktp_path ? basename($conversion->ktp_path) : '' }}' }">
                        <label class="block text-sm font-bold text-slate-700">
                            Kartu Tanda Penduduk (KTP)
                            @if($conversion && $conversion->ktp_path)
                                <span class="ml-2 text-[10px] text-emerald-600 font-bold uppercase">(Sudah Diunggah)</span>
                            @endif
                        </label>
                        <div class="relative">
                            <input type="file" name="ktp" id="ktp" class="hidden" accept=".pdf,.jpg,.jpeg,.png" @change="fileName = $event.target.files[0].name">
                            <label for="ktp" class="flex flex-col items-center justify-center w-full min-h-40 px-6 py-8 border-2 border-dashed border-slate-300 rounded-3xl cursor-pointer bg-slate-50/50 hover:bg-emerald-50/50 hover:border-emerald-400 transition-all group">
                                <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-4 0h4"></path></svg>
                                </div>
                                <div class="text-center">
                                    <span class="block text-xs font-bold text-slate-900" x-text="fileName ? fileName : 'Upload KTP'"></span>
                                    <span class="mt-1 block text-[10px] text-slate-400">PDF, JPG, PNG (Max. 5MB)</span>
                                </div>
                            </label>
                        </div>
                        @error('ktp') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="bg-amber-50 rounded-2xl p-6 border border-amber-100">
                    <div class="flex">
                        <div class="shrink-0">
                            <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-xs font-bold text-amber-800 uppercase tracking-wider">Instruksi Penting</h3>
                            <div class="mt-1 text-xs text-amber-700 space-y-1">
                                <p>• Pastikan semua dokumen dalam format PDF atau Gambar (JPG/PNG) yang terbaca jelas.</p>
                                <p>• Transkrip Nilai adalah dokumen wajib untuk proses konversi mata kuliah.</p>
                                <p>• Surat Pendaftaran dan KTP diperlukan untuk validasi data administratif.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-100">
                    <a href="{{ route('mahasiswa.dashboard') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-8 py-3 border border-transparent text-sm font-semibold rounded-2xl text-white bg-blue-600 hover:bg-blue-700 shadow-xl shadow-blue-500/20 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 active:scale-95">
                        Ajukan Berkas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
