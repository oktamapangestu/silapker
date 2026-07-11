@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('content')
    <div class="max-w-2xl">
        <a href="{{ route('admin.laporan.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 mb-4">
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z" clip-rule="evenodd" /></svg>
            Kembali
        </a>

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-start gap-4">
                <div>
                    <h1 class="text-lg font-semibold text-slate-900">{{ $laporan->nama_pegawai }}</h1>
                    <p class="text-sm text-slate-500 mt-0.5">NIP {{ $laporan->nip }} &middot; {{ $laporan->departemen }} &middot; {{ $laporan->jabatan }}</p>
                    <p class="text-sm text-slate-500">{{ $laporan->waktu_lapor->translatedFormat('d M Y, H:i') }}</p>
                </div>
                <x-status-badge :status="$laporan->status" />
            </div>

            <div class="mt-4 text-slate-800 leading-relaxed keterangan-content">{!! $laporan->keterangan !!}</div>

            <div class="mt-4 flex flex-wrap gap-3">
                @foreach ($laporan->foto_urls as $url)
                    <a href="{{ $url }}" target="_blank" rel="noopener">
                        <img src="{{ $url }}" alt="Foto kegiatan" class="h-40 w-40 rounded-lg border border-slate-200 object-cover hover:opacity-90 transition">
                    </a>
                @endforeach
            </div>

            @if ($laporan->status !== 'menunggu')
                <div class="mt-5 rounded-lg bg-slate-50 border border-slate-200 px-4 py-3 text-sm text-slate-600">
                    <p><span class="font-medium text-slate-700">Direview oleh:</span> {{ $laporan->reviewed_by }} pada {{ $laporan->reviewed_at->translatedFormat('d M Y, H:i') }}</p>
                    @if ($laporan->catatan_admin)
                        <p class="mt-1"><span class="font-medium text-slate-700">Catatan:</span> {{ $laporan->catatan_admin }}</p>
                    @endif
                </div>
            @else
                <form method="POST" action="{{ route('admin.laporan.review', $laporan) }}" class="mt-5 space-y-3 border-t border-slate-100 pt-5">
                    @csrf
                    <div>
                        <label for="catatan_admin" class="block text-sm font-medium text-slate-700 mb-1.5">Catatan (opsional)</label>
                        <textarea id="catatan_admin" name="catatan_admin" rows="2" placeholder="Tambahkan catatan untuk pegawai..."
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition"></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" name="status" value="disetujui"
                            class="inline-flex items-center gap-1.5 bg-emerald-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg hover:bg-emerald-700 active:bg-emerald-800 transition shadow-sm">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                            Setujui
                        </button>
                        <button type="submit" name="status" value="ditolak"
                            class="inline-flex items-center gap-1.5 bg-white text-rose-600 border border-rose-200 text-sm font-semibold px-4 py-2.5 rounded-lg hover:bg-rose-50 active:bg-rose-100 transition">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg>
                            Tolak
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection
