@extends('layouts.app')

@section('title', 'Ubah Laporan')

@section('content')
    <div class="max-w-full">
        <a href="{{ route('laporan.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 mb-4">
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z" clip-rule="evenodd" /></svg>
            Kembali
        </a>

        <h1 class="text-2xl font-semibold text-slate-900 mb-6">Ubah Laporan Kerja</h1>

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 text-sm">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('laporan.update', $laporan) }}" enctype="multipart/form-data" class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 space-y-5">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-2 rounded-lg bg-slate-50 border border-slate-200 px-3 py-2.5 text-sm text-slate-500">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" /></svg>
                <span>Waktu lapor: <span class="font-medium text-slate-700">{{ $laporan->waktu_lapor->translatedFormat('d M Y, H:i') }}</span> &middot; otomatis, tidak bisa diubah</span>
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-medium text-slate-700 mb-1.5">Keterangan Kegiatan</label>
                <textarea id="keterangan" name="keterangan" rows="4" required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition">{{ old('keterangan', $laporan->keterangan) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Foto Kegiatan Saat Ini</label>
                <img src="{{ $laporan->foto_url }}" alt="Foto kegiatan" class="max-h-56 rounded-lg border border-slate-200 object-cover mb-3">
                <label for="foto" class="block text-sm font-medium text-slate-700 mb-1.5">Ganti Foto (opsional)</label>
                <input type="file" id="foto" name="foto" accept="image/*"
                    class="block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="inline-flex items-center gap-1.5 bg-indigo-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition shadow-sm">
                    Simpan Perubahan
                </button>
                <a href="{{ route('laporan.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">Batal</a>
            </div>
        </form>
    </div>
@endsection
