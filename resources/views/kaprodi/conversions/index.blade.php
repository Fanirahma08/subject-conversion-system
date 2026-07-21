@extends('layouts.app')

@section('title', 'Tinjauan Transkrip')

@section('content')
<div class="max-w-7xl mx-auto py-2 px-2">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Tinjauan Transkrip Program Studi</h1>
        <p class="mt-1 text-sm text-slate-500">Tinjau permintaan konversi untuk mahasiswa di **{{ auth()->user()->prodi }}**.</p>
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
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Lihat File
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($conv->status == 'waiting')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 uppercase tracking-tight">Menunggu Kaprodi</span>
                            @elseif($conv->status == 'waiting_baak')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700 uppercase tracking-tight">Menunggu BAAK</span>
                            @elseif($conv->status == 'waiting_dekan')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700 uppercase tracking-tight">Menunggu Dekan</span>
                            @elseif($conv->status == 'waiting_wr1')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-cyan-100 text-cyan-700 uppercase tracking-tight">Menunggu WR1</span>
                            @elseif($conv->status == 'waiting_rektor')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 uppercase tracking-tight">Menunggu Rektor</span>
                            @elseif($conv->status == 'approved')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 uppercase tracking-tight">Disetujui</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700 uppercase tracking-tight">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($conv->status === 'approved')
                                <a href="{{ route('conversions.pdf', $conv) }}?type=sk" target="_blank" class="inline-flex items-center text-xs font-bold text-emerald-600 hover:underline">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Lihat SK
                                </a>
                            @else
                                <span class="text-xs text-slate-400 font-medium">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <a href="{{ route('kaprodi.conversions.show', $conv) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-500/20 transition-all active:scale-95 leading-none">
                                Tinjau & Petakan Mata Kuliah
                                <svg class="w-3.5 h-3.5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-900">Tidak ada permintaan yang tertunda</h3>
                                <p class="text-xs text-slate-400 mt-1">Tidak ada permintaan konversi untuk program studi Anda saat ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
