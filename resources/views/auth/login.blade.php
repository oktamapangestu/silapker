<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Laporan Kerja Karyawan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased flex items-center justify-center px-4">
    <div class="w-full max-w-sm">
        <div class="flex flex-col items-center mb-6">
            <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-600 text-white text-xl font-bold shadow-sm">L</span>
            <h1 class="mt-4 text-lg font-semibold text-slate-900">Laporan Kerja Karyawan</h1>
            <p class="mt-1 text-sm text-slate-500 text-center">Masuk dengan akun Izin Akses Sistem Anda</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            @if ($errors->any())
                <div class="mb-4 flex items-start gap-2 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 px-3 py-2.5 text-sm">
                    <svg class="h-5 w-5 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.19-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="username" class="block text-sm font-medium text-slate-700 mb-1.5">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition">
                </div>
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 text-white text-sm font-semibold py-2.5 rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition shadow-sm">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</body>
</html>
