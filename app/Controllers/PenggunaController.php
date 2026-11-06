<?php
/**
 * CRUD data master Pengguna (akun pelapor, admin, teknisi).
 * Aturan keamanan: password disimpan sebagai hash bcrypt, dan admin tidak dapat
 * menghapus, menonaktifkan, atau mengubah peran akunnya sendiri (mencegah terkunci).
 */
class PenggunaController extends CrudController
{
    protected string $model = User::class;
    protected string $route = 'pengguna';
    protected string $label = 'Pengguna';
    protected string $pk = 'id_user';
    protected string $menu = 'pengguna';

    protected function defaults(): array
    {
        return ['nama' => '', 'email' => '', 'no_hp' => '', 'role' => 'pelapor', 'status_aktif' => '1'];
    }

    protected function title(array $row): string
    {
        return $row['nama'];
    }

    protected function formExtras(): array
    {
        return ['roles' => User::ROLES];
    }

    protected function deleteGuard(array $row): ?string
    {
        return (int) $row['id_user'] === Auth::id() ? 'Anda tidak dapat menghapus akun yang sedang dipakai.' : null;
    }

    protected function validate(?array $existing): array
    {
        $isCreate = $existing === null;
        $isSelf = !$isCreate && (int) $existing['id_user'] === Auth::id();

        $nama = $this->post('nama');
        $email = strtolower($this->post('email'));
        $noHp = $this->post('no_hp');
        $role = $this->post('role');
        $status = $this->post('status_aktif') === '1' ? 1 : 0;
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';
        $password = is_string($password) ? $password : '';
        $confirm = is_string($confirm) ? $confirm : '';
        $errors = [];

        if ($nama === '') {
            $errors['nama'] = 'Nama wajib diisi.';
        } elseif (mb_strlen($nama) > 100) {
            $errors['nama'] = 'Nama maksimal 100 karakter.';
        }

        if ($email === '') {
            $errors['email'] = 'Email wajib diisi.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100) {
            $errors['email'] = 'Format email tidak valid (maksimal 100 karakter).';
        }

        if ($noHp !== '' && !preg_match('/^[0-9+\- ]{8,20}$/', $noHp)) {
            $errors['no_hp'] = 'No. HP hanya berisi angka, +, spasi, atau tanda minus (8-20 karakter).';
        }

        if (!isset(User::ROLES[$role])) {
            $errors['role'] = 'Pilih peran yang valid.';
        }

        if ($isCreate || $password !== '') {
            if (strlen($password) < 8) {
                $errors['password'] = 'Password minimal 8 karakter.';
            } elseif ($password !== $confirm) {
                $errors['password_confirm'] = 'Konfirmasi password tidak sama.';
            }
        }

        // Akun yang sedang dipakai: peran dan status tidak boleh berubah.
        if ($isSelf) {
            $role = $existing['role'];
            $status = 1;
        }

        $data = [
            'nama' => $nama, 'email' => $email, 'no_hp' => $noHp === '' ? null : $noHp,
            'role' => $role, 'status_aktif' => $status,
        ];
        if ($password !== '' && !$errors) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        return [$data, $errors];
    }
}
