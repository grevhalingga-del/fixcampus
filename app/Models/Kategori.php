<?php
/** Tabel kategori (jenis fasilitas: Listrik, AC, Proyektor, dst.). */
class Kategori extends BaseModel
{
    protected static string $table = 'kategori';
    protected static string $pk = 'id_kategori';
    protected static array $columns = ['nama_kategori'];
    protected static array $searchable = ['t.nama_kategori'];

    protected static function orderBy(): string
    {
        return 't.nama_kategori ASC';
    }
}
