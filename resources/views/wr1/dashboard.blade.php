@extends('layouts.app')

@section('title', 'Dashboard WR1')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Tinjauan Wakil Rektor 1</h1>
        <p class="mt-1 text-sm text-slate-500">Persetujuan konversi mata kuliah di tingkat Wakil Rektor 1 (Bidang Akademik).</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-8 rounded-2xl shadow-sm ring-1 ring-slate-200 hover:shadow-md transition-shadow group">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Persetujuan Konversi</h3>
            <p class="text-sm text-slate-500 mb-6">Tinjau dan setujui usulan konversi mata kuliah yang disetujui Dekan sebelum diteruskan ke Rektor.</p>
            <a href="{{ route('wr1.conversions.index') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                Kelola persetujuan <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
    </div>
</div>
@endsection
