<?php
/** Tabel users (akun pelapor, admin, teknisi). */
class User extends BaseModel
{
    public const ROLES = ['pelapor' => 'Pelapor', 'admin' => 'Admin Sarana-Prasarana', 'teknisi' => 'Teknisi'];

    protected static string $table = 'users';
    protected static string $pk = 'id_user';
    protected static array $columns = ['nama', 'email', 'password', 'role', 'no_hp', 'status_aktif'];
    protected static array $searchable = ['t.nama', 't.email', 't.role'];

    protected static function selectColumns(): string
    {
        // Kolom password tidak pernah dikirim ke view.
        return 't.id_user, t.nama, t.email, t.role, t.no_hp, t.status_aktif, t.created_at';
    }

    protected static function orderBy(): string
    {
        return 't.nama ASC';
    }

    /** Dipakai proses login; sengaja mengambil kolom password. */
    public static function findByEmail(string $email): ?array
    {
        $rows = Database::select('SELECT * FROM users WHERE email = ? LIMIT 1', 's', [$email]);
        return $rows[0] ?? null;
    }

    public static function updatePassword(int $id, string $hash): array
    {
        return static::update($id, ['password' => $hash]);
    }
}
