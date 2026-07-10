<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanKerja;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanKerjaController extends Controller
{
    public function index(Request $request): View
    {
        $laporan = LaporanKerja::query()
            ->when($request->tanggal, fn ($q) => $q->whereDate('waktu_lapor', $request->tanggal))
            ->when($request->nama_pegawai, fn ($q) => $q->where('nama_pegawai', 'like', "%{$request->nama_pegawai}%"))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest('waktu_lapor')
            ->paginate(20)
            ->withQueryString();

        return view('admin.laporan.index', compact('laporan'));
    }

    public function show(LaporanKerja $laporan): View
    {
        return view('admin.laporan.show', compact('laporan'));
    }

    public function review(Request $request, LaporanKerja $laporan): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_admin' => 'nullable|string',
        ]);

        $karyawan = $request->session()->get('pengguna.karyawan');

        $laporan->forceFill([
            'status' => $validated['status'],
            'catatan_admin' => $validated['catatan_admin'] ?? null,
            'reviewed_by' => $karyawan['nama_lengkap'],
            'reviewed_at' => now(),
        ])->save();

        return redirect()->route('admin.laporan.index')->with('status', 'Laporan berhasil direview.');
    }
}
