# Dokumentasi API — SI Manajemen Karyawan

Dokumen ini adalah panduan integrasi API untuk sistem eksternal yang ingin memverifikasi izin akses pengguna melalui **SI Manajemen Karyawan**.

---

## Informasi Umum

| Item | Keterangan |
|------|-----------|
| Base URL | `http://{domain}/api` |
| Format | JSON |
| Autentikasi | Berdasarkan izin akses sistem (`system_accesses`) |
| Versi | 1.0 |

---

## Persyaratan Wajib di Setiap Request

```
Content-Type: application/json
Accept: application/json
X-System: {nama_sistem}
```

### Header `X-System`

Isi dengan nama sistem Anda yang sudah terdaftar. Nama sistem didaftarkan melalui menu **Master Sistem** di aplikasi SI Manajemen Karyawan.

---

## Endpoint

### Login / Verifikasi Izin Akses

Memverifikasi kredensial pengguna berdasarkan izin akses yang telah didaftarkan untuk sistem pemanggil. Jika berhasil, mengembalikan data karyawan beserta informasi aksesnya.

```
POST /api/login
```

**Headers:**
```
Content-Type: application/json
Accept: application/json
X-System: {nama_sistem}
```

**Body (JSON):**
```json
{
    "username": "username_pengguna",
    "password": "password_pengguna"
}
```

> `username` dan `password` adalah kredensial yang diinputkan melalui menu **Izin Akses Sistem** di aplikasi SI Manajemen Karyawan.

---

**Response Sukses `200`:**
```json
{
    "message": "Login berhasil.",
    "akses": {
        "username": "budi.simpeg",
        "sistem": "simpeg",
        "role": {
            "nama_role": "staff",
            "prioritas": 2
        }
    },
    "karyawan": {
        "id": 2,
        "nip": "198501012010011001",
        "nama_lengkap": "Budi Santoso",
        "email": "budi@contoh.com",
        "jenis_kelamin": "L",
        "no_telepon": "081234567890",
        "status": "aktif",
        "tanggal_bergabung": "2024-01-01T00:00:00.000000Z",
        "departemen": "Keuangan",
        "jabatan": "Staf Keuangan"
    }
}
```

**Response Gagal `401` — Username atau password salah:**
```json
{
    "message": "Username atau password salah."
}
```

**Response Gagal `400` — Header X-System tidak disertakan:**
```json
{
    "message": "Header X-System wajib disertakan."
}
```

**Response Gagal `403` — Sistem tidak terdaftar:**
```json
{
    "message": "Sistem 'nama_sistem' tidak terdaftar."
}
```

**Response Gagal `422` — Field tidak lengkap:**
```json
{
    "message": "The username field is required.",
    "errors": {
        "username": ["The username field is required."]
    }
}
```

---

## Kode Status HTTP

| Kode | Keterangan |
|------|-----------|
| `200` | Login berhasil |
| `400` | Header X-System tidak disertakan |
| `401` | Username atau password salah |
| `403` | Sistem tidak terdaftar di master sistem |
| `422` | Field request tidak lengkap |
| `500` | Kesalahan server |

---

## Alur Integrasi

```
1. Daftarkan sistem Anda di menu Master Sistem
   → Catat nama_sistem (dipakai di header X-System)

2. Daftarkan izin akses karyawan di menu Izin Akses Sistem
   → Input username, password, dan role untuk setiap karyawan

3. Panggil POST /api/login dari sistem Anda
   → Kirim username + password + header X-System

4. Jika response 200 → pengguna diizinkan masuk
   → Gunakan field "role" untuk menentukan hak akses di sistem Anda

5. Jika response 401 → tolak akses
```

---

## Contoh Implementasi

### PHP (cURL)

```php
<?php

$baseUrl    = 'http://{domain}/api';
$systemName = 'simpeg'; // sesuaikan dengan nama sistem Anda

$ch = curl_init("$baseUrl/login");
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Accept: application/json',
        "X-System: $systemName",
    ],
    CURLOPT_POSTFIELDS => json_encode([
        'username' => 'budi.simpeg',
        'password' => 'password_pengguna',
    ]),
]);

$response = json_decode(curl_exec($ch), true);
curl_close($ch);

if (isset($response['akses'])) {
    $role     = $response['akses']['role'];
    $karyawan = $response['karyawan'];
    // lanjutkan proses login di sistem Anda
} else {
    // tolak akses
    echo $response['message'];
}
```

---

### JavaScript (Fetch)

```javascript
const baseUrl    = 'http://{domain}/api';
const systemName = 'simpeg'; // sesuaikan dengan nama sistem Anda

const res = await fetch(`${baseUrl}/login`, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept':        'application/json',
        'X-System':      systemName,
    },
    body: JSON.stringify({
        username: 'budi.simpeg',
        password: 'password_pengguna',
    }),
});

const data = await res.json();

if (res.ok) {
    const { role, sistem } = data.akses;
    const karyawan = data.karyawan;
    // lanjutkan proses login di sistem Anda
} else {
    console.error(data.message);
}
```

---

### Python (requests)

```python
import requests

BASE_URL    = 'http://{domain}/api'
SYSTEM_NAME = 'simpeg'  # sesuaikan dengan nama sistem Anda

res = requests.post(f'{BASE_URL}/login', json={
    'username': 'budi.simpeg',
    'password': 'password_pengguna',
}, headers={
    'Content-Type': 'application/json',
    'Accept':        'application/json',
    'X-System':      SYSTEM_NAME,
})

data = res.json()

if res.status_code == 200:
    role     = data['akses']['role']
    karyawan = data['karyawan']
    # lanjutkan proses login di sistem Anda
else:
    print(data['message'])
```

---

## Catatan Penting

- Endpoint ini **stateless** — tidak menghasilkan token. Setiap login memanggil endpoint ini kembali.
- **Username bersifat unik per sistem.** Satu username hanya bisa dipakai oleh satu karyawan dalam satu sistem.
- **Nama sistem bersifat case-sensitive.** `Simpeg` dan `simpeg` dianggap berbeda.
- Penambahan atau perubahan izin akses dilakukan melalui menu **Izin Akses Sistem** di aplikasi SI Manajemen Karyawan.
