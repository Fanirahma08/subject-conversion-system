@extends('layouts.app')

@section('title', 'Konversi Nilai')

@section('content')
<div class="max-w-4xl mx-auto py-2 px-2">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Konversi Nilai</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola pemetaan nilai asal ke nilai internal secara global.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 text-emerald-700 p-4 rounded-2xl text-sm border border-emerald-100 flex items-center animate-in fade-in slide-in-from-top-4 duration-300">
            <svg class="h-5 w-5 mr-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-2xl text-sm border border-red-100">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Add New Conversion Form --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-xl ring-1 ring-slate-200 overflow-hidden sticky top-24">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Konversi Baru
                    </h3>
                </div>
                <form action="{{ route('kaprodi.grade-conversions.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nilai Asal</label>
                        <input type="text" name="origin_grade" required placeholder="mis. B-" maxlength="10"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none uppercase font-bold text-center"
                               value="{{ old('origin_grade') }}">
                    </div>

                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nilai Internal</label>
                        <input type="text" name="internal_grade" required placeholder="mis. AB" maxlength="10"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none uppercase font-bold text-center"
                               value="{{ old('internal_grade') }}">
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-xs font-bold shadow-lg shadow-blue-600/20 active:scale-95 transition-all">
                        TAMBAH KONVERSI
                    </button>
                </form>
            </div>

            {{-- Info Card --}}
            <div class="mt-6 bg-blue-600 rounded-2xl p-6 text-white shadow-xl shadow-blue-500/20">
                <h4 class="font-bold mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Tentang Konversi Nilai
                </h4>
                <p class="text-xs text-blue-100 leading-relaxed">Pemetaan ini bersifat global dan berlaku untuk semua universitas. Saat Kaprodi memasukkan nilai asal mahasiswa, sistem akan otomatis mengonversi ke nilai internal berdasarkan tabel ini.</p>
            </div>
        </div>

        {{-- Existing Conversions Table --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl ring-1 ring-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-800">Daftar Konversi Nilai</h3>
                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-lg border border-blue-100 uppercase">{{ $gradeConversions->count() }} Pemetaan</span>
                </div>

                @if($gradeConversions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead>
                                <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-tight bg-white">
                                    <th class="px-6 py-3 text-center">Nilai Asal</th>
                                    <th class="px-6 py-3 text-center"></th>
                                    <th class="px-6 py-3 text-center">Nilai Internal</th>
                                    <th class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 bg-white" x-data>
                                @foreach($gradeConversions as $gc)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-6 py-4 text-center">
                                            <form id="update-form-{{ $gc->id }}" action="{{ route('kaprodi.grade-conversions.update', $gc) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="origin_grade" value="{{ $gc->origin_grade }}"
                                                       class="w-20 text-center px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-700 focus:border-blue-500 focus:bg-white focus:outline-none transition-all uppercase">
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <svg class="w-5 h-5 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                                <input type="text" name="internal_grade" value="{{ $gc->internal_grade }}"
                                                       class="w-20 text-center px-2 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-blue-700 focus:border-blue-500 focus:bg-white focus:outline-none transition-all uppercase">
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <button type="submit" class="text-slate-300 hover:text-blue-600 transition-colors" title="Simpan perubahan">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </form>
                                                <form action="{{ route('kaprodi.grade-conversions.destroy', $gc) }}" method="POST" onsubmit="return confirm('Hapus konversi ini?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-slate-300 hover:text-red-500 transition-colors" title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        </div>
                        <h4 class="text-sm font-bold text-slate-400 mb-1">Belum Ada Konversi</h4>
                        <p class="text-xs text-slate-400">Tambahkan pemetaan nilai asal ke nilai internal menggunakan form di samping.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
