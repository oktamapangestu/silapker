<?php

namespace App\Http\Controllers;

use App\Models\LaporanKerja;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LaporanKerjaController extends Controller
{
    public function index(Request $request): View
    {
        $karyawan = $request->session()->get('pengguna.karyawan');

        $laporan = LaporanKerja::milikPegawai($karyawan['id'])
            ->latest('waktu_lapor')
            ->paginate(15);

        return view('laporan.index', compact('laporan'));
    }

    public function create(): View
    {
        return view('laporan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'keterangan' => 'required|string',
            'foto' => 'required|image|max:2048',
        ]);

        $karyawan = $request->session()->get('pengguna.karyawan');

        $path = $request->file('foto')->store('laporan', 'public');

        (new LaporanKerja([
            'keterangan' => $validated['keterangan'],
            'foto' => $path,
        ]))->forceFill([
            'employee_id_eksternal' => $karyawan['id'],
            'nip' => $karyawan['nip'] ?? null,
            'nama_pegawai' => $karyawan['nama_lengkap'],
            'departemen' => $karyawan['departemen'] ?? null,
            'jabatan' => $karyawan['jabatan'] ?? null,
            'waktu_lapor' => now(),
            'status' => 'menunggu',
        ])->save();

        return redirect()->route('laporan.index')->with('status', 'Laporan berhasil dikirim.');
    }

    public function edit(Request $request, LaporanKerja $laporan): View|RedirectResponse
    {
        $this->authorizeAkses($request, $laporan);

        if (! $laporan->bisaDiubah()) {
            return redirect()->route('laporan.index')
                ->with('error', 'Laporan tidak bisa diubah lagi (sudah lewat hari ini atau sudah direview).');
        }

        return view('laporan.edit', compact('laporan'));
    }

    public function update(Request $request, LaporanKerja $laporan): RedirectResponse
    {
        $this->authorizeAkses($request, $laporan);

        if (! $laporan->bisaDiubah()) {
            return redirect()->route('laporan.index')
                ->with('error', 'Laporan tidak bisa diubah lagi (sudah lewat hari ini atau sudah direview).');
        }

        $validated = $request->validate([
            'keterangan' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $laporan->keterangan = $validated['keterangan'];

        if ($request->hasFile('foto')) {
            Storage::disk('public')->delete($laporan->foto);
            $laporan->foto = $request->file('foto')->store('laporan', 'public');
        }

        $laporan->save();

        return redirect()->route('laporan.index')->with('status', 'Laporan berhasil diperbarui.');
    }

    public function destroy(Request $request, LaporanKerja $laporan): RedirectResponse
    {
        $this->authorizeAkses($request, $laporan);

        if (! $laporan->bisaDiubah()) {
            return redirect()->route('laporan.index')
                ->with('error', 'Laporan tidak bisa dihapus lagi (sudah lewat hari ini atau sudah direview).');
        }

        Storage::disk('public')->delete($laporan->foto);
        $laporan->delete();

        return redirect()->route('laporan.index')->with('status', 'Laporan berhasil dihapus.');
    }

    private function authorizeAkses(Request $request, LaporanKerja $laporan): void
    {
        $karyawan = $request->session()->get('pengguna.karyawan');

        abort_unless($laporan->employee_id_eksternal === $karyawan['id'], 403);
    }
}
