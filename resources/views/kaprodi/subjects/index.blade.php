@extends('layouts.app')

@section('title', 'Repositori Mata Kuliah')

@section('content')
<div class="max-w-7xl mx-auto py-2 px-2">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Repositori Mata Kuliah</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola kurikulum internal dan mata kuliah asal untuk konversi.</p>
        </div>
        <div class="flex items-center space-x-4 w-full md:w-auto">
            <form action="{{ route('kaprodi.subjects.index') }}" method="GET" class="relative grow md:grow-0 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                <select name="type" onchange="this.form.submit()" class="w-full sm:w-auto pl-3 pr-8 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm bg-white text-slate-700 appearance-none">
                    <option value="">Semua Mata Kuliah</option>
                    <option value="internal" {{ request('type') == 'internal' ? 'selected' : '' }}>Internal (Kurikulum)</option>
                    <optgroup label="Penyedia Eksternal">
                        @foreach($universities as $univ)
                            <option value="external_{{ $univ->id }}" {{ request('type') == 'external_'.$univ->id ? 'selected' : '' }}>{{ $univ->name }}</option>
                        @endforeach
                    </optgroup>
                </select>
                <div class="relative w-full md:w-64">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari kode atau nama..." class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">
                    <div class="absolute left-3 top-2.5 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </form>
            <a href="{{ route('kaprodi.subjects.create') }}" class="shrink-0 inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all">
                Tambah Mata Kuliah Baru
            </a>
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

    <div class="bg-white shadow-sm ring-1 ring-slate-200 rounded-2xl overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Info Mata Kuliah</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">PMB</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Asal</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Pemetaan</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse ($subjects as $subject)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $subject->university_id ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $subject->university_id ? 'Eksternal' : 'Internal' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-slate-900">{{ $subject->code }}</div>
                            <div class="text-xs text-slate-500">{{ $subject->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-xs text-slate-900">{{ $subject->sks }} SKS</div>
                            <div class="text-[10px] text-slate-500">Semester {{ $subject->semester ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-600">
                            {{ $subject->university?->name ?? 'Universitas Saat Ini' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $subject->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $subject->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            @if($subject->university_id)
                                <div class="flex items-center space-x-1">
                                    <div class="w-2 h-2 rounded-full {{ $subject->source_mappings->count() > 0 ? 'bg-emerald-500' : 'bg-red-500' }}"></div>
                                    <span class="text-[10px] font-medium">{{ $subject->source_mappings->count() > 0 ? 'Dipetakan (' . $subject->source_mappings->count() . ')' : 'Belum Dipetakan' }}</span>
                                </div>
                            @else
                                <span class="text-[10px] font-medium text-slate-400">Tersedia</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('kaprodi.subjects.edit', $subject) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md transition-colors">Edit</a>
                                <form action="{{ route('kaprodi.subjects.destroy', $subject) }}" method="POST" onsubmit="return confirm('Hapus mata kuliah ini? Ini juga akan menghapus pemetaan terkait.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md transition-colors">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500">
                            Belum ada mata kuliah yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 px-4">
        {{ $subjects->links() }}
    </div>
</div>
@endsection
