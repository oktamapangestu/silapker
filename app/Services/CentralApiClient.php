<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CentralApiClient
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly string $systemName,
    ) {
    }

    /**
     * Verifikasi username/password ke SI Manajemen Karyawan.
     *
     * @return array{ok: bool, status: int, message: string, akses: array|null, karyawan: array|null}
     */
    public function login(string $username, string $password): array
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-System' => $this->systemName,
        ])->post("{$this->baseUrl}/api/login", [
            'username' => $username,
            'password' => $password,
        ]);

        $body = $response->json() ?? [];

        return [
            'ok' => $response->ok(),
            'status' => $response->status(),
            'message' => $body['message'] ?? 'Terjadi kesalahan saat menghubungi server.',
            'akses' => $body['akses'] ?? null,
            'karyawan' => $body['karyawan'] ?? null,
        ];
    }
}
