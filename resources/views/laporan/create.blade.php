@extends('layouts.app')

@section('title', 'Buat Laporan')

@section('content')
    <div class="max-w-full">
        <a href="{{ route('laporan.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 mb-4">
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z" clip-rule="evenodd" /></svg>
            Kembali
        </a>

        <h1 class="text-2xl font-semibold text-slate-900 mb-6">Buat Laporan Kerja</h1>

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 text-sm">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('laporan.store') }}" enctype="multipart/form-data" class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 space-y-5">
            @csrf

            <div class="flex items-center gap-2 rounded-lg bg-slate-50 border border-slate-200 px-3 py-2.5 text-sm text-slate-500">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" /></svg>
                <span>Waktu lapor: <span class="font-medium text-slate-700">{{ now()->translatedFormat('d M Y, H:i') }}</span> &middot; otomatis, tidak bisa diubah</span>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Keterangan Kegiatan</label>
                <div data-quill data-placeholder="Ceritakan kegiatan kerja Anda hari ini...">
                    <div data-quill-editor style="min-height: 140px;" class="bg-white rounded-b-lg"></div>
                    <textarea data-quill-input name="keterangan" class="hidden">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <div data-foto-picker>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Foto Kegiatan (bisa lebih dari satu)</label>
                <input type="file" name="foto[]" accept="image/*" multiple required data-foto-input class="hidden">
                <div class="flex flex-wrap gap-2" data-foto-preview>
                    <button type="button" data-foto-add
                        class="h-20 w-20 flex flex-col items-center justify-center gap-1 rounded-lg border-2 border-dashed border-slate-300 text-slate-400 hover:border-indigo-400 hover:text-indigo-500 transition">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
                        <span class="text-xs">Tambah</span>
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="inline-flex items-center gap-1.5 bg-indigo-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition shadow-sm">
                    Kirim Laporan
                </button>
                <a href="{{ route('laporan.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">Batal</a>
            </div>
        </form>
    </div>
@endsection
