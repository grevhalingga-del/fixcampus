<?php
/**
 * Autentikasi dan otorisasi berbasis peran (pelapor, admin, teknisi).
 * - Password diverifikasi dengan password_verify (bcrypt).
 * - ID sesi diganti setelah login (cegah session fixation).
 * - Sesi berakhir setelah 30 menit tidak aktif.
 * - Login dibatasi: 5 kali gagal berturut-turut menahan percobaan selama 60 detik.
 */
class Auth
{
    private const TIMEOUT = 1800;      // detik
    private const MAX_GAGAL = 5;
    private const TAHAN = 60;          // detik
    // Hash bcrypt "kosong" agar waktu proses sama walau email tidak ditemukan.
    private const DUMMY_HASH = '$2y$10$GrhQrwab6kHIGG26FhWUj.VImUX5RnRvoqpZjJ2IND5wthVjhrzdC';

    /** @return array{ok:bool,message:string} */
    public static function attempt(string $email, string $password): array
    {
        $sisa = self::sisaTahan();
        if ($sisa > 0) {
            return ['ok' => false, 'message' => "Terlalu banyak percobaan gagal. Coba lagi dalam $sisa detik."];
        }

        $user = User::findByEmail($email);
        $hash = $user['password'] ?? self::DUMMY_HASH;
        $valid = password_verify($password, $hash) && $user !== null;

        if (!$valid) {
            self::catatGagal();
            return ['ok' => false, 'message' => 'Email atau password salah.'];
        }
        if ((int) $user['status_aktif'] !== 1) {
            return ['ok' => false, 'message' => 'Akun Anda tidak aktif. Hubungi admin sarana-prasarana.'];
        }

        // Upgrade hash otomatis jika algoritma bawaan PHP berubah.
        if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
            User::updatePassword((int) $user['id_user'], password_hash($password, PASSWORD_DEFAULT));
        }

        session_regenerate_id(true);
        unset($_SESSION['login_gagal'], $_SESSION['csrf']);
        $_SESSION['user'] = [
            'id'    => (int) $user['id_user'],
            'nama'  => $user['nama'],
            'email' => $user['email'],
            'role'  => $user['role'],
        ];
        $_SESSION['last_activity'] = time();
        return ['ok' => true, 'message' => ''];
    }

    public static function check(): bool
    {
        if (empty($_SESSION['user'])) {
            return false;
        }
        if (time() - ($_SESSION['last_activity'] ?? 0) > self::TIMEOUT) {
            self::logout();
            flash('warning', 'Sesi Anda berakhir karena tidak aktif. Silakan login kembali.');
            return false;
        }
        $_SESSION['last_activity'] = time();
        return true;
    }

    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function id(): ?int
    {
        return $_SESSION['user']['id'] ?? null;
    }

    public static function role(): ?string
    {
        return $_SESSION['user']['role'] ?? null;
    }

    public static function hasRole(array $roles): bool
    {
        return in_array(self::role(), $roles, true);
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_regenerate_id(true);
    }

    private static function catatGagal(): void
    {
        $g = $_SESSION['login_gagal'] ?? ['count' => 0, 'until' => 0];
        $g['count']++;
        if ($g['count'] >= self::MAX_GAGAL) {
            $g = ['count' => 0, 'until' => time() + self::TAHAN];
        }
        $_SESSION['login_gagal'] = $g;
    }

    private static function sisaTahan(): int
    {
        return max(0, ($_SESSION['login_gagal']['until'] ?? 0) - time());
    }
}
