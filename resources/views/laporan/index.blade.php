@extends('layouts.app')

@section('title', 'Laporan Saya')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Laporan Kerja Saya</h1>
            <p class="text-sm text-slate-500 mt-0.5">Riwayat kegiatan kerja harian yang sudah Anda laporkan.</p>
        </div>
        <a href="{{ route('laporan.create') }}"
            class="inline-flex items-center gap-1.5 bg-indigo-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition shadow-sm shrink-0">
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
            Buat Laporan
        </a>
    </div>

    <div class="space-y-4">
        @forelse ($laporan as $item)
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                <div class="flex justify-between items-start gap-4">
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">
                            {{ $item->waktu_lapor->translatedFormat('d M Y, H:i') }}
                        </p>
                        <p class="mt-1.5 text-slate-800 leading-relaxed">{{ $item->keterangan }}</p>
                    </div>
                    <x-status-badge :status="$item->status" />
                </div>

                <img src="{{ $item->foto_url }}" alt="Foto kegiatan" class="mt-4 max-h-56 rounded-lg border border-slate-200 object-cover">

                @if ($item->catatan_admin)
                    <div class="mt-3 rounded-lg bg-slate-50 border border-slate-200 px-3 py-2 text-sm text-slate-600">
                        <span class="font-medium text-slate-700">Catatan admin:</span> {{ $item->catatan_admin }}
                    </div>
                @endif

                @if ($item->bisaDiubah())
                    <div class="mt-4 flex gap-4 text-sm border-t border-slate-100 pt-3">
                        <a href="{{ route('laporan.edit', $item) }}" class="font-medium text-indigo-600 hover:text-indigo-700">Ubah</a>
                        <form method="POST" action="{{ route('laporan.destroy', $item) }}" onsubmit="return confirm('Hapus laporan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-medium text-rose-600 hover:text-rose-700">Hapus</button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white border border-dashed border-slate-300 rounded-xl py-16 flex flex-col items-center text-center">
                <svg class="h-10 w-10 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <p class="mt-3 text-sm font-medium text-slate-600">Belum ada laporan</p>
                <p class="mt-1 text-sm text-slate-400">Mulai catat kegiatan kerja harian Anda.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $laporan->links() }}
    </div>
@endsection
