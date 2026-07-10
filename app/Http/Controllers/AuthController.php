<?php

namespace App\Http\Controllers;

use App\Services\CentralApiClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (session()->has('pengguna')) {
            return redirect()->route('laporan.index');
        }

        return view('auth.login');
    }

    public function login(Request $request, CentralApiClient $client): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $result = $client->login($credentials['username'], $credentials['password']);

        if (! $result['ok']) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => $result['message']]);
        }

        $request->session()->regenerate();
        $request->session()->put('pengguna', [
            'akses' => $result['akses'],
            'karyawan' => $result['karyawan'],
        ]);

        return redirect()->intended(route('laporan.index'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('pengguna');
        $request->session()->regenerate();

        return redirect()->route('login');
    }
}
