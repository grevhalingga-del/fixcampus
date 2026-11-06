<?php
/** Halaman galat (404, 403, 405, 419). Tidak dapat dipanggil lewat URL. */
class ErrorController extends Controller
{
    private const PESAN = [
        403 => ['Akses ditolak', 'Peran Anda tidak memiliki izin untuk membuka halaman ini.'],
        404 => ['Halaman tidak ditemukan', 'Alamat yang Anda buka tidak tersedia.'],
        405 => ['Metode tidak diizinkan', 'Aksi ini harus dikirim melalui formulir.'],
        419 => ['Sesi formulir kedaluwarsa', 'Token keamanan tidak cocok. Muat ulang halaman lalu coba lagi.'],
    ];

    public function show(int $code): void
    {
        [$judul, $pesan] = self::PESAN[$code] ?? ['Terjadi kesalahan', ''];
        $layout = Auth::check() ? 'main' : 'auth';
        $this->view('errors/error', compact('code', 'judul', 'pesan'), $layout, $code);
    }
}
