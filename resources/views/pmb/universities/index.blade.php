@extends('layouts.app')

@section('title', 'Manajemen Universitas')

@section('content')
<div class="max-w-7xl mx-auto py-2 px-2">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Daftar Universitas</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola daftar resmi universitas asal untuk mahasiswa dan mata kuliah.</p>
        </div>
        <a href="{{ route('pmb.universities.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all">
            Daftarkan Universitas Baru
        </a>
    </div>

    @if (session('success'))
    <div class="mb-6 bg-emerald-50 text-emerald-700 p-4 rounded-lg text-sm border border-emerald-100 flex items-center">
        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white shadow-xl ring-1 ring-slate-200 rounded-2xl overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Nama Universitas</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Kode Institusi</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Jumlah Mahasiswa</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Mata Kuliah Asal</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($universities as $uni)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-bold text-slate-900">{{ $uni->name }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">{{ $uni->code ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-xs font-medium text-slate-600">{{ $uni->users_count ?? $uni->students()->count() }} mahasiswa</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-xs font-medium text-slate-600">{{ $uni->subjects_count ?? $uni->subjects()->count() }} mata kuliah</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('pmb.universities.edit', $uni) }}" class="text-blue-600 hover:text-blue-900 font-bold text-xs uppercase tracking-tighter bg-blue-50 px-3 py-1.5 rounded-lg transition-all">Edit</a>
                            <form action="{{ route('pmb.universities.destroy', $uni) }}" method="POST" class="inline" onsubmit="return confirm('Hapus universitas ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-xs uppercase tracking-tighter bg-red-50 px-3 py-1.5 rounded-lg transition-all">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-slate-500">Belum ada universitas yang terdaftar.</p>
                            <p class="text-xs text-slate-400 mt-1">Mulai dengan menambahkan universitas asal ke registri.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection