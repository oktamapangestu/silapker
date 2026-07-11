@extends('layouts.app')

@section('title', 'Kelola Laporan')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Kelola Laporan Kerja</h1>
        <p class="text-sm text-slate-500 mt-0.5">Pantau dan review laporan kerja seluruh pegawai.</p>
    </div>

    <form method="GET" class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                class="text-sm rounded-lg border border-slate-300 px-3 py-1.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Nama Pegawai</label>
            <input type="text" name="nama_pegawai" value="{{ request('nama_pegawai') }}" placeholder="Cari nama..."
                class="text-sm rounded-lg border border-slate-300 px-3 py-1.5 placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Status</label>
            <select name="status" class="text-sm rounded-lg border border-slate-300 px-3 py-1.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition">
                <option value="">Semua</option>
                @foreach (['menunggu', 'disetujui', 'ditolak'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-4 py-1.5 rounded-lg hover:bg-indigo-700 transition shadow-sm">
            Filter
        </button>
        <a href="{{ route('admin.laporan.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">Reset</a>
    </form>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm divide-y divide-slate-100 overflow-hidden">
        @forelse ($laporan as $item)
            <a href="{{ route('admin.laporan.show', $item) }}" class="block p-5 hover:bg-slate-50 transition">
                <div class="flex justify-between items-start gap-4">
                    <div class="min-w-0">
                        <p class="font-medium text-slate-900">{{ $item->nama_pegawai }}</p>
                        <p class="text-sm text-slate-500 mt-0.5">NIP {{ $item->nip }} &middot; {{ $item->departemen }} &middot; {{ $item->jabatan }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $item->waktu_lapor->translatedFormat('d M Y, H:i') }}</p>
                        <p class="mt-1.5 text-sm text-slate-600 truncate">{{ strip_tags($item->keterangan) }}</p>
                    </div>
                    <x-status-badge :status="$item->status" />
                </div>
            </a>
        @empty
            <div class="py-16 flex flex-col items-center text-center">
                <svg class="h-10 w-10 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <p class="mt-3 text-sm font-medium text-slate-600">Tidak ada laporan</p>
                <p class="mt-1 text-sm text-slate-400">Coba ubah filter pencarian.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $laporan->links() }}
    </div>
@endsection
