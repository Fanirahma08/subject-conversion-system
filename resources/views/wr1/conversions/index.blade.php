@extends('layouts.app')

@section('title', 'Persetujuan WR1')

@section('content')
<div class="max-w-7xl mx-auto py-2 px-2">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Persetujuan Konversi (WR1)</h1>
        <p class="mt-1 text-sm text-slate-500">Tinjau permintaan konversi yang telah disetujui Dekan.</p>
    </div>

    @if (session('success'))
    <div class="mb-6 bg-emerald-50 text-emerald-700 p-4 rounded-lg text-sm border border-emerald-100 flex items-center">
        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-lg text-sm border border-red-100 flex items-center">
        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm2.707-10.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293z"
                clip-rule="evenodd" />
        </svg>
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white shadow-xl ring-1 ring-slate-200 rounded-3xl overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Mahasiswa</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Info Asal</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Transkrip</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">SK</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($conversions as $conv)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-900">{{ $conv->user->name }}</span>
                            <span class="text-[10px] text-slate-500 font-medium">{{ $conv->user->email }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col">
                            <span class="text-[10px] text-slate-900 font-bold">{{ $conv->user->studentDetail->university->name ?? 'N/A' }}</span>
                            <span class="text-[10px] text-slate-500 italic">{{ $conv->user->studentDetail->prodi_origin }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ asset('storage/' . $conv->transcript_path) }}" target="_blank" class="inline-flex items-center text-xs font-bold text-blue-600 hover:underline">
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
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($conv->status === 'approved')
                        <a href="{{ route('conversions.pdf', $conv) }}?type=sk" target="_blank" class="inline-flex items-center text-xs font-bold text-emerald-600 hover:underline">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Lihat SK
                        </a>
                        @else
                        <span class="text-xs text-slate-400 font-medium">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        @if ($conv->status === 'waiting_wr1')
                        <a href="{{ route('wr1.conversions.show', $conv) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-500/20 transition-all active:scale-95 leading-none">
                            Tinjau
                        </a>
                        @else
                        <a href="{{ route('wr1.conversions.show', $conv) }}"
                            class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-slate-500/20 transition-all active:scale-95 leading-none">
                            Lihat Detail
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-20 text-center">
                        <h3 class="text-sm font-bold text-slate-900">Tidak ada permintaan yang tertunda</h3>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
