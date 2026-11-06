<?php
/** CRUD data master Kategori fasilitas. */
class KategoriController extends CrudController
{
    protected string $model = Kategori::class;
    protected string $route = 'kategori';
    protected string $label = 'Kategori';
    protected string $pk = 'id_kategori';
    protected string $menu = 'kategori';

    protected function defaults(): array
    {
        return ['nama_kategori' => ''];
    }

    protected function title(array $row): string
    {
        return $row['nama_kategori'];
    }

    protected function validate(?array $existing): array
    {
        $nama = $this->post('nama_kategori');
        $errors = [];
        if ($nama === '') {
            $errors['nama_kategori'] = 'Nama kategori wajib diisi.';
        } elseif (mb_strlen($nama) > 50) {
            $errors['nama_kategori'] = 'Nama kategori maksimal 50 karakter.';
        }
        return [['nama_kategori' => $nama], $errors];
    }
}
