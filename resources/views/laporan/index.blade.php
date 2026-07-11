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

    <form method="GET" class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 mb-6 flex flex-wrap gap-4 items-end">
        <div id="date-picker" class="relative" data-api="{{ route('laporan.tanggal-tersedia') }}" data-today="{{ now()->toDateString() }}">
            <label class="block text-xs font-medium text-slate-500 mb-1">Tanggal</label>
            <button type="button" id="date-picker-toggle"
                class="flex items-center gap-2 text-sm rounded-lg border border-slate-300 px-3 py-1.5 hover:border-slate-400 transition min-w-[9rem]">
                <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5a1.25 1.25 0 00-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5A1.25 1.25 0 0015.25 7.5H4.75z" clip-rule="evenodd" /></svg>
                {{ \Illuminate\Support\Carbon::parse($tanggal)->translatedFormat('d M Y') }}
            </button>
            <input type="hidden" id="date-picker-value" name="tanggal" value="{{ $tanggal }}">

            <div id="date-picker-calendar" class="hidden absolute z-20 mt-2 bg-white border border-slate-200 rounded-xl shadow-lg p-3 w-72">
                <div class="flex items-center justify-between mb-2 px-1">
                    <button type="button" data-nav="-1" class="h-7 w-7 flex items-center justify-center rounded-md text-slate-500 hover:bg-slate-100">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 010 1.06L9.06 10l3.73 3.71a.75.75 0 11-1.06 1.06l-4.25-4.25a.75.75 0 010-1.06l4.25-4.25a.75.75 0 011.06 0z" clip-rule="evenodd" /></svg>
                    </button>
                    <span id="date-picker-label" class="text-sm font-medium text-slate-700"></span>
                    <button type="button" data-nav="1" class="h-7 w-7 flex items-center justify-center rounded-md text-slate-500 hover:bg-slate-100">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 010-1.06L10.94 10 7.21 6.29a.75.75 0 111.06-1.06l4.25 4.25a.75.75 0 010 1.06l-4.25 4.25a.75.75 0 01-1.06 0z" clip-rule="evenodd" /></svg>
                    </button>
                </div>
                <div class="grid grid-cols-7 gap-1 text-center text-xs text-slate-400 mb-1">
                    <span>Min</span><span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span>
                </div>
                <div id="date-picker-days" class="grid grid-cols-7 gap-1"></div>
                <div class="mt-3 flex items-center gap-3 text-xs text-slate-500 border-t border-slate-100 pt-2">
                    <span class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-emerald-500"></span> Ada laporan</span>
                    <span class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-rose-500"></span> Tidak ada laporan</span>
                </div>
            </div>
        </div>
        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-4 py-1.5 rounded-lg hover:bg-indigo-700 transition shadow-sm">
            Filter
        </button>
        @unless ($tanggal === now()->toDateString())
            <a href="{{ route('laporan.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">Kembali ke Hari Ini</a>
        @endunless
    </form>

    <div class="space-y-4">
        @forelse ($laporan as $item)
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                <div class="flex justify-between items-start gap-4">
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">
                            {{ $item->waktu_lapor->translatedFormat('d M Y, H:i') }}
                        </p>
                        <div class="mt-1.5 text-slate-800 leading-relaxed keterangan-content">{!! $item->keterangan !!}</div>
                    </div>
                    <x-status-badge :status="$item->status" />
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($item->foto_urls as $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener">
                            <img src="{{ $url }}" alt="Foto kegiatan" class="h-24 w-24 rounded-lg border border-slate-200 object-cover hover:opacity-90 transition">
                        </a>
                    @endforeach
                </div>

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
                <p class="mt-1 text-sm text-slate-400">
                    @if ($tanggal === now()->toDateString())
                        Mulai catat kegiatan kerja harian Anda.
                    @else
                        Tidak ada laporan pada {{ \Illuminate\Support\Carbon::parse($tanggal)->translatedFormat('d M Y') }}.
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $laporan->links() }}
    </div>
@endsection
