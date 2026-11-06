<?php
/** CRUD data master Fasilitas (berelasi ke Kategori dan Lokasi). */
class FasilitasController extends CrudController
{
    protected string $model = Fasilitas::class;
    protected string $route = 'fasilitas';
    protected string $label = 'Fasilitas';
    protected string $pk = 'id_fasilitas';
    protected string $menu = 'fasilitas';

    protected function defaults(): array
    {
        return ['nama_fasilitas' => '', 'id_kategori' => '', 'id_lokasi' => ''];
    }

    protected function title(array $row): string
    {
        return $row['nama_fasilitas'];
    }

    /** Daftar dropdown untuk form. */
    protected function formExtras(): array
    {
        return ['kategoriList' => Kategori::all(), 'lokasiList' => Lokasi::all()];
    }

    protected function validate(?array $existing): array
    {
        $nama = $this->post('nama_fasilitas');
        $idKategori = (int) $this->post('id_kategori');
        $idLokasi = (int) $this->post('id_lokasi');
        $errors = [];

        if ($nama === '') {
            $errors['nama_fasilitas'] = 'Nama fasilitas wajib diisi.';
        } elseif (mb_strlen($nama) > 100) {
            $errors['nama_fasilitas'] = 'Nama fasilitas maksimal 100 karakter.';
        }
        if ($idKategori <= 0 || Kategori::find($idKategori) === null) {
            $errors['id_kategori'] = 'Pilih kategori yang valid.';
        }
        if ($idLokasi <= 0 || Lokasi::find($idLokasi) === null) {
            $errors['id_lokasi'] = 'Pilih lokasi yang valid.';
        }

        return [['nama_fasilitas' => $nama, 'id_kategori' => $idKategori, 'id_lokasi' => $idLokasi], $errors];
    }
}
