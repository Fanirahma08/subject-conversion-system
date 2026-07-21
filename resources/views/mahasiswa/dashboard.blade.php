@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="max-w-7xl mx-auto py-2 px-2">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Konversi PMB</h1>
            <p class="mt-1 text-sm text-slate-500">Lacak dan kelola status konversi mata kuliah Anda.</p>
        </div>
    </div>

    @if (session('success'))
    <div class="mb-6 bg-emerald-50 text-emerald-700 p-4 rounded-lg text-sm border border-emerald-100 flex items-center">
        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Status Summary -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white shadow-xl ring-1 ring-slate-200 rounded-2xl overflow-hidden">
                <div class="p-6 bg-slate-50 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900">Profil Konversi</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <span class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Nama Mahasiswa</span>
                        <span class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</span>
                    </div>
                    @if(auth()->user()->studentDetail && auth()->user()->studentDetail->nim)
                    <div>
                        <span class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">NIM</span>
                        <span class="text-sm font-semibold text-slate-900">{{ auth()->user()->studentDetail->nim }}</span>
                    </div>
                    @endif
                    <div>
                        <span class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Universitas Asal</span>
                        <span class="text-sm font-semibold text-slate-900">{{ $university->name ?? 'Belum Diatur' }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Prodi Asal</span>
                        <span class="text-sm font-semibold text-slate-900">{{ auth()->user()->studentDetail->prodi_origin ?? 'Belum Diatur' }}</span>
                    </div>
                    @if (auth()->user()->studentDetail && auth()->user()->studentDetail->graduation_date)
                    <div>
                        <span class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Tanggal Lulus</span>
                        <span class="text-sm font-semibold text-slate-900">{{ auth()->user()->studentDetail->graduation_date->locale('id')->translatedFormat('d F Y') ?? 'Belum Diatur' }}</span>
                    </div>
                    @endif

                    <div class="pt-4 mt-4 border-t border-slate-100">
                        <a href="{{ route('mahasiswa.profile.edit') }}" class="flex items-center justify-center w-full px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-bold rounded-xl transition-all border border-slate-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ auth()->user()->studentDetail ? 'Perbarui Profil' : 'Lengkapi Identitas' }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-blue-600 rounded-2xl p-6 text-white shadow-xl shadow-blue-500/20">
                <h4 class="font-bold mb-2">Butuh Bantuan?</h4>
                <p class="text-xs text-blue-100 leading-relaxed mb-4">Jika konversi Anda ditolak atau Anda memiliki pertanyaan tentang pemetaan, silakan hubungi kantor PMB.</p>
                <div class="flex items-center space-x-2 text-[10px] font-bold uppercase tracking-wider bg-blue-700/50 py-2 px-3 rounded-lg w-fit">
                    <div class="w-2 h-2 rounded-full bg-blue-300 animate-pulse"></div>
                    <span>Dukungan Online</span>
                </div>
            </div>
        </div>

        <!-- Current Request -->
        <div class="lg:col-span-2">
            @php
            $conv = $conversions->first();
            @endphp

            @if($conv)
            <div class="bg-white shadow-xl ring-1 ring-slate-200 rounded-3xl overflow-hidden border border-slate-100">
                <!-- Card Header -->
                <div class="p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-sm border border-blue-100/50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 leading-tight">Detail Transkrip</h3>
                            <p class="text-xs text-slate-500 font-medium">Terakhir diperbarui {{ $conv->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <div class="flex items-center">
                        @if($conv->status == 'waiting')
                        <div class="w-fit px-4 py-1.5 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold uppercase tracking-wider border border-amber-200/50 shadow-sm flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2 animate-pulse"></span>
                            Menunggu Tinjauan Kaprodi
                        </div>
                        @elseif ($conv->status == 'waiting_baak')
                        <div class="w-fit px-4 py-1.5 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold uppercase tracking-wider border border-amber-200/50 shadow-sm flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2 animate-pulse"></span>
                            Menunggu Tinjauan BAAK
                        </div>
                        @elseif ($conv->status == 'waiting_dekan')
                        <div class="w-fit px-4 py-1.5 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold uppercase tracking-wider border border-amber-200/50 shadow-sm flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2 animate-pulse"></span>
                            Menunggu Tinjauan Dekan
                        </div>
                        @elseif ($conv->status == 'waiting_wr1')
                        <div class="w-fit px-4 py-1.5 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold uppercase tracking-wider border border-amber-200/50 shadow-sm flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2 animate-pulse"></span>
                            Menunggu Tinjauan WR1
                        </div>
                        @elseif ($conv->status == 'waiting_rektor')
                        <div class="w-fit px-4 py-1.5 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold uppercase tracking-wider border border-amber-200/50 shadow-sm flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2 animate-pulse"></span>
                            Menunggu Tinjauan Rektor
                        </div>
                        @elseif($conv->status == 'approved')
                        <div class="w-fit px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider border border-emerald-200/50 shadow-sm flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span>
                            Disetujui
                        </div>
                        @else
                        <div class="w-fit px-4 py-1.5 rounded-full bg-red-50 text-red-600 text-[10px] font-bold uppercase tracking-wider border border-red-200/50 shadow-sm flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2"></span>
                            Ditolak
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-8 space-y-8">
                    <!-- Documents Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Transcript -->
                        <a href="{{ Storage::url($conv->transcript_path) }}" target="_blank" class="flex items-center p-4 bg-blue-50/50 rounded-2xl border border-blue-100 hover:bg-blue-100/50 transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-blue-600 shadow-sm mr-3 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">Transkrip Nilai</p>
                                <p class="text-[10px] text-slate-500 truncate">{{ basename($conv->transcript_path) }}</p>
                            </div>
                        </a>

                        <!-- Registration Letter -->
                        @if($conv->registration_letter_path)
                        <a href="{{ Storage::url($conv->registration_letter_path) }}" target="_blank" class="flex items-center p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100 hover:bg-indigo-100/50 transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-indigo-600 shadow-sm mr-3 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider">Surat Pendaftaran</p>
                                <p class="text-[10px] text-slate-500 truncate">{{ basename($conv->registration_letter_path) }}</p>
                            </div>
                        </a>
                        @else
                        <div class="flex items-center p-4 bg-slate-50 rounded-2xl border border-slate-100 opacity-60">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-slate-400 mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Surat Pendaftaran</p>
                                <p class="text-[10px] text-slate-400 italic">Belum Ada</p>
                            </div>
                        </div>
                        @endif

                        <!-- KTP -->
                        @if($conv->ktp_path)
                        <a href="{{ Storage::url($conv->ktp_path) }}" target="_blank" class="flex items-center p-4 bg-emerald-50/50 rounded-2xl border border-emerald-100 hover:bg-emerald-100/50 transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-emerald-600 shadow-sm mr-3 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-4 0h4"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">KTP</p>
                                <p class="text-[10px] text-slate-500 truncate">{{ basename($conv->ktp_path) }}</p>
                            </div>
                        </a>
                        @else
                        <div class="flex items-center p-4 bg-slate-50 rounded-2xl border border-slate-100 opacity-60">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-slate-400 mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-4 0h4"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">KTP</p>
                                <p class="text-[10px] text-slate-400 italic">Belum Ada</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <hr class="border-slate-100">
                    @if($conv->status == 'approved' && $conv->results->count() > 0)
                    <div class="animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-widest flex items-center">
                                <svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                Pemetaan Konversi yang Disetujui
                            </h4>
                            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-100">{{ $conv->results->count() }} Mata Kuliah Dikonversi</span>
                        </div>
                        <div class="bg-slate-50 rounded-3xl border border-slate-100 overflow-hidden shadow-inner">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead>
                                    <tr class="bg-slate-100/50">
                                        <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-tight">Mata Kuliah Asal</th>
                                        <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-tight">Ekuivalen Internal</th>
                                        <th class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase tracking-tight">Nilai Asal</th>
                                        <th class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase tracking-tight">Nilai Internal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($conv->results as $result)
                                    <tr class="hover:bg-white transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="text-xs font-bold text-slate-900">{{ $result->source_subject->code }}</div>
                                            <div class="text-[10px] text-slate-500">{{ $result->source_subject->name }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs font-bold text-blue-700">{{ $result->target_subject->code }}</div>
                                            <div class="text-[10px] text-slate-500">{{ $result->target_subject->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2 py-0.5 bg-slate-50 text-slate-700 rounded text-[10px] font-bold border border-slate-100">{{ $result->origin_grade ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded text-[10px] font-bold border border-emerald-100">{{ $result->grade ?? '-' }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="mt-0.5">
                                    <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <span class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider">Peninjau yang Ditugaskan</span>
                                    @if($conv->reviewer)
                                    <p class="text-sm font-bold text-slate-900">{{ $conv->reviewer->name }}</p>
                                    <p class="text-[10px] text-slate-500 font-medium">{{ ucfirst($conv->reviewer->role->value) }}</p>
                                    @else
                                    <p class="text-sm font-medium text-slate-400 italic">Menugaskan peninjau...</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                            <span class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-3">Umpan Balik Tinjauan</span>
                            @if($conv->notes)
                            <div class="relative">
                                <div class="absolute -left-2 top-0 h-full w-1 bg-blue-500 rounded-full"></div>
                                <p class="pl-4 text-sm text-slate-700 leading-relaxed font-semibold italic">"{{ $conv->notes }}"</p>
                            </div>
                            @else
                            <div class="flex flex-col items-center justify-center py-4 text-slate-400 text-center">
                                <svg class="w-6 h-6 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                                <p class="text-[10px] font-bold uppercase tracking-widest">Belum ada umpan balik</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Bar -->
                    @if(in_array($conv->status, ['waiting', 'rejected']))
                    <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                        <div class="hidden sm:block">
                            <p class="text-xs text-slate-500 font-medium">Anda dapat memperbarui transkrip Anda saat dalam peninjauan.</p>
                        </div>
                        <a href="{{ route('mahasiswa.conversions.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold rounded-xl shadow-lg shadow-slate-200 transition-all hover:-translate-y-0.5 active:translate-y-0">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Pengajuan
                        </a>
                    </div>
                    @else
                    <div class="pt-6 border-t border-slate-100 space-y-4">
                        @if($conv->status === 'approved')
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-emerald-50/50 rounded-2xl border border-emerald-100/50">
                            <div class="flex items-center text-emerald-600">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm font-bold">Konversi Diselesaikan</p>
                            </div>
                            @if($conv->status === 'approved')
                            <a href="{{ route('conversions.pdf', $conv) }}?type=sk" target="_blank" class="inline-flex items-center justify-center px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-emerald-600/20 transition-all active:scale-95">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                LIHAT SK (PDF)
                            </a>
                            @endif
                        </div>
                        @elseif ($conv->status === 'rejected')
                        <div class="flex items-center text-slate-500 bg-slate-50 p-4 rounded-xl border border-slate-200/50">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm font-bold">Permintaan ini telah ditolak. Silakan tinjau umpan balik.</p>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="bg-white shadow-xl ring-1 ring-slate-200 rounded-3xl p-12 text-center border border-slate-100 flex flex-col items-center">
                <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 mb-6 shadow-inner">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-2 tracking-tight">Siap untuk memulai?</h3>
                <p class="text-slate-500 mb-8 max-w-sm mx-auto leading-relaxed">Unggah transkrip PMB resmi dari universitas Anda sebelumnya untuk memulai proses konversi.</p>
                <a href="{{ route('mahasiswa.conversions.create') }}" class="inline-flex items-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-xl shadow-blue-500/25 transition-all hover:scale-105 active:scale-95">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Unggah Dokumen
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection