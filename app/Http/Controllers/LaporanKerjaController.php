<?php

namespace App\Http\Controllers;

use App\Models\LaporanKerja;
use App\Support\RichTextSanitizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LaporanKerjaController extends Controller
{
    public function index(Request $request): View
    {
        $karyawan = $request->session()->get('pengguna.karyawan');
        $tanggal = $request->date('tanggal')?->toDateString() ?? now()->toDateString();

        $laporan = LaporanKerja::milikPegawai($karyawan['id'])
            ->whereDate('waktu_lapor', $tanggal)
            ->latest('waktu_lapor')
            ->paginate(15)
            ->withQueryString();

        return view('laporan.index', compact('laporan', 'tanggal'));
    }

    public function tanggalTersedia(Request $request): JsonResponse
    {
        $karyawan = $request->session()->get('pengguna.karyawan');
        $bulan = Carbon::parse($request->query('bulan', now()->format('Y-m')).'-01');

        $tanggal = LaporanKerja::milikPegawai($karyawan['id'])
            ->whereBetween('waktu_lapor', [$bulan->copy()->startOfMonth(), $bulan->copy()->endOfMonth()])
            ->get()
            ->map(fn (LaporanKerja $item) => $item->waktu_lapor->toDateString())
            ->unique()
            ->values();

        return response()->json(['tanggal_ada_laporan' => $tanggal]);
    }

    public function create(): View
    {
        return view('laporan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'keterangan' => ['required', 'string', $this->keteranganTidakKosong()],
            'foto' => 'required|array|min:1',
            'foto.*' => 'image|max:2048',
        ]);

        $karyawan = $request->session()->get('pengguna.karyawan');

        $paths = collect($request->file('foto'))
            ->map(fn ($file) => $file->store('laporan', 'public'))
            ->all();

        (new LaporanKerja([
            'keterangan' => RichTextSanitizer::clean($validated['keterangan']),
            'foto' => $paths,
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
            'keterangan' => ['required', 'string', $this->keteranganTidakKosong()],
            'hapus_foto' => 'array',
            'hapus_foto.*' => 'string',
            'foto_baru' => 'array',
            'foto_baru.*' => 'image|max:2048',
        ]);

        $fotoAsli = $laporan->foto;
        $hapusFoto = $validated['hapus_foto'] ?? [];

        $sisaFoto = collect($fotoAsli)->reject(fn ($path) => in_array($path, $hapusFoto, true));

        $fotoBaru = collect($request->file('foto_baru', []))
            ->map(fn ($file) => $file->store('laporan', 'public'));

        $fotoAkhir = $sisaFoto->concat($fotoBaru)->values();

        if ($fotoAkhir->isEmpty()) {
            $fotoBaru->each(fn ($path) => Storage::disk('public')->delete($path));

            return back()->withInput()->withErrors(['foto_baru' => 'Laporan harus memiliki minimal 1 foto.']);
        }

        collect($hapusFoto)
            ->filter(fn ($path) => in_array($path, $fotoAsli, true))
            ->each(fn ($path) => Storage::disk('public')->delete($path));

        $laporan->keterangan = RichTextSanitizer::clean($validated['keterangan']);
        $laporan->foto = $fotoAkhir->all();
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

    private function keteranganTidakKosong(): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail): void {
            if (trim(strip_tags((string) $value)) === '') {
                $fail('Keterangan kegiatan wajib diisi.');
            }
        };
    }
}
