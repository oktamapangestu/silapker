<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laporan Kerja Karyawan')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    @php
        $pengguna = session('pengguna');
        $prioritas = $pengguna['akses']['role']['prioritas'] ?? null;
        $isAdmin = is_numeric($prioritas) && (int) $prioritas <= config('services.central_api.admin_priority_threshold');
        $initial = $pengguna ? mb_strtoupper(mb_substr($pengguna['karyawan']['nama_lengkap'], 0, 1)) : '';
    @endphp

    @if ($pengguna)
        <nav class="sticky top-0 z-10 bg-white/90 backdrop-blur border-b border-slate-200">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <a href="{{ route('laporan.index') }}" class="flex items-center gap-2 font-semibold text-slate-900">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 text-white text-sm font-bold">L</span>
                        <span class="hidden sm:inline">Laporan Kerja</span>
                    </a>
                    <div class="hidden sm:flex items-center gap-1 text-sm">
                        <a href="{{ route('laporan.index') }}"
                            @class([
                                'px-3 py-1.5 rounded-md font-medium transition',
                                'bg-indigo-50 text-indigo-700' => request()->routeIs('laporan.*'),
                                'text-slate-600 hover:text-slate-900 hover:bg-slate-100' => ! request()->routeIs('laporan.*'),
                            ])>
                            Laporan Saya
                        </a>
                        @if ($isAdmin)
                            <a href="{{ route('admin.laporan.index') }}"
                                @class([
                                    'px-3 py-1.5 rounded-md font-medium transition',
                                    'bg-indigo-50 text-indigo-700' => request()->routeIs('admin.*'),
                                    'text-slate-600 hover:text-slate-900 hover:bg-slate-100' => ! request()->routeIs('admin.*'),
                                ])>
                                Kelola Laporan
                            </a>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex flex-col items-end leading-tight">
                        <span class="text-sm font-medium text-slate-800">{{ $pengguna['karyawan']['nama_lengkap'] }}</span>
                        <span class="text-xs text-slate-500">{{ $pengguna['karyawan']['jabatan'] ?? '-' }} &middot; {{ $pengguna['karyawan']['departemen'] ?? '-' }}</span>
                        <span class="text-xs text-slate-400">NIP {{ $pengguna['karyawan']['nip'] ?? '-' }}</span>
                    </div>
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-800 text-white text-sm font-semibold">
                        {{ $initial }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                        @csrf
                        <button class="text-sm font-medium text-slate-500 hover:text-rose-600 transition" title="Keluar">
                            Keluar
                        </button>
                    </form>
                    <button type="button" id="mobile-menu-toggle" class="sm:hidden inline-flex h-9 w-9 items-center justify-center rounded-md text-slate-600 hover:bg-slate-100" aria-expanded="false" aria-controls="mobile-menu">
                        <svg id="mobile-menu-icon-open" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 5.25a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10zm0 5.25a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75a.75.75 0 01-.75-.75z" clip-rule="evenodd" /></svg>
                        <svg id="mobile-menu-icon-close" class="hidden h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>
                    </button>
                </div>
            </div>

            <div id="mobile-menu" class="hidden sm:hidden border-t border-slate-200 bg-white px-4 py-3 space-y-3">
                <div class="text-sm">
                    <p class="font-medium text-slate-800">{{ $pengguna['karyawan']['nama_lengkap'] }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $pengguna['karyawan']['jabatan'] ?? '-' }} &middot; {{ $pengguna['karyawan']['departemen'] ?? '-' }}</p>
                    <p class="text-xs text-slate-400">NIP {{ $pengguna['karyawan']['nip'] ?? '-' }}</p>
                </div>

                <div class="flex flex-col gap-1 border-t border-slate-100 pt-3 text-sm">
                    <a href="{{ route('laporan.index') }}"
                        @class([
                            'px-3 py-2 rounded-md font-medium transition',
                            'bg-indigo-50 text-indigo-700' => request()->routeIs('laporan.*'),
                            'text-slate-600 hover:bg-slate-100' => ! request()->routeIs('laporan.*'),
                        ])>
                        Laporan Saya
                    </a>
                    @if ($isAdmin)
                        <a href="{{ route('admin.laporan.index') }}"
                            @class([
                                'px-3 py-2 rounded-md font-medium transition',
                                'bg-indigo-50 text-indigo-700' => request()->routeIs('admin.*'),
                                'text-slate-600 hover:bg-slate-100' => ! request()->routeIs('admin.*'),
                            ])>
                            Kelola Laporan
                        </a>
                    @endif
                </div>

                <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100 pt-3">
                    @csrf
                    <button class="text-sm font-medium text-rose-600 hover:text-rose-700 transition">
                        Keluar
                    </button>
                </form>
            </div>
        </nav>
    @endif

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        @if (session('status'))
            <div class="mb-6 flex items-start gap-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm">
                <svg class="h-5 w-5 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 flex items-start gap-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 text-sm">
                <svg class="h-5 w-5 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.19-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
