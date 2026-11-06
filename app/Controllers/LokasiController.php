<?php
/** CRUD data master Lokasi (gedung dan ruangan). */
class LokasiController extends CrudController
{
    protected string $model = Lokasi::class;
    protected string $route = 'lokasi';
    protected string $label = 'Lokasi';
    protected string $pk = 'id_lokasi';
    protected string $menu = 'lokasi';

    protected function defaults(): array
    {
        return ['gedung' => '', 'ruangan' => ''];
    }

    protected function title(array $row): string
    {
        return $row['gedung'] . ' - ' . $row['ruangan'];
    }

    protected function validate(?array $existing): array
    {
        $data = ['gedung' => $this->post('gedung'), 'ruangan' => $this->post('ruangan')];
        $errors = [];
        foreach (['gedung' => 'Gedung', 'ruangan' => 'Ruangan'] as $f => $nama) {
            if ($data[$f] === '') {
                $errors[$f] = "$nama wajib diisi.";
            } elseif (mb_strlen($data[$f]) > 50) {
                $errors[$f] = "$nama maksimal 50 karakter.";
            }
        }
        return [$data, $errors];
    }
}
