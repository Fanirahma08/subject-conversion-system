@extends('layouts.app')

@section('title', 'Tinjauan Transkrip')

@section('content')
<div class="max-w-7xl mx-auto py-2 px-2">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Tinjauan Transkrip</h1>
        <p class="mt-1 text-sm text-slate-500">Tinjau dan kelola permintaan konversi transkrip mahasiswa.</p>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 text-emerald-700 p-4 rounded-lg text-sm border border-emerald-100 flex items-center">
            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-xl ring-1 ring-slate-200 rounded-3xl overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200" x-data="{ openReview: null }">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Mahasiswa</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Program</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Transkrip</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Status</th>
                    {{-- <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-widest">Aksi</th> --}}
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($conversions as $conv)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-900">{{ $conv->user->name }}</span>
                                <span class="text-[10px] text-slate-500 italic">{{ $conv->user->studentDetail->university->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-xs font-semibold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-lg">{{ $conv->user->studentDetail->prodi_origin }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ asset('storage/' . $conv->transcript_path) }}" target="_blank" class="inline-flex items-center text-xs font-bold text-blue-600 hover:underline">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Lihat File
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
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
                        </td>
                        {{-- <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            @if ($conv->status == 'waiting')
                            <button @click="openReview = openReview === {{ $conv->id }} ? null : {{ $conv->id }}" 
                                    class="text-blue-600 hover:text-blue-900 font-bold text-xs bg-blue-50 px-3 py-1.5 rounded-xl transition-all">
                                Tinjau Permintaan
                            </button>
                            @endif
                        </td> --}}
                    </tr>
                    
                    <!-- Review Form Row -->
                    <tr x-show="openReview === {{ $conv->id }}" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="bg-blue-50/30">
                        <td colspan="5" class="px-8 py-6">
                            <div class="bg-white p-6 rounded-2xl ring-1 ring-blue-100 shadow-sm max-w-2xl ml-auto">
                                <h4 class="text-sm font-bold text-slate-900 mb-4">Perbarui Status Konversi</h4>
                                <form action="{{ route('pmb.conversions.update', $conv) }}" method="POST" class="space-y-4">
                                    @csrf
                                    @method('PUT')

                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Keputusan Pra-penyaringan</label>
                                        <div class="flex space-x-4">
                                            <label class="flex items-center px-4 py-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-blue-50 hover:border-blue-200 transition-all group">
                                                <input type="radio" name="status" value="waiting" class="text-blue-600 focus:ring-blue-500" {{ $conv->status == 'waiting' ? 'checked' : '' }}>
                                                <span class="ml-3 text-sm font-semibold text-slate-700 group-hover:text-blue-700">Terverifikasi (Tetap Menunggu)</span>
                                            </label>
                                            <label class="flex items-center px-4 py-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-red-50 hover:border-red-200 transition-all group">
                                                <input type="radio" name="status" value="rejected" class="text-red-600 focus:ring-red-500" {{ $conv->status == 'rejected' ? 'checked' : '' }}>
                                                <span class="ml-3 text-sm font-semibold text-slate-700 group-hover:text-red-700">Tolak Permintaan</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="notes" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Catatan Internal / Umpan Balik</label>
                                        <textarea name="notes" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none" placeholder="Jelaskan alasan persetujuan atau penolakan...">{{ $conv->notes }}</textarea>
                                    </div>

                                    <div class="flex justify-end space-x-3 mt-4">
                                        <button type="button" @click="openReview = null" class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700">Batal</button>
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-xs font-bold shadow-lg shadow-blue-500/20 active:scale-95 transition-all">Kirim Tinjauan</button>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-900">Tidak ada permintaan tertunda</h3>
                                <p class="text-xs text-slate-400 mt-1">Semua transkrip mahasiswa telah ditinjau.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
