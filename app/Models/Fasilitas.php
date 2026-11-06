<?php
/** Tabel fasilitas, berelasi ke kategori dan lokasi. */
class Fasilitas extends BaseModel
{
    protected static string $table = 'fasilitas';
    protected static string $pk = 'id_fasilitas';
    protected static array $columns = ['nama_fasilitas', 'id_kategori', 'id_lokasi'];
    protected static array $searchable = ['t.nama_fasilitas', 'k.nama_kategori', 'l.gedung', 'l.ruangan'];

    protected static function fromClause(): string
    {
        return 'fasilitas t JOIN kategori k ON k.id_kategori = t.id_kategori JOIN lokasi l ON l.id_lokasi = t.id_lokasi';
    }

    protected static function selectColumns(): string
    {
        return 't.*, k.nama_kategori, l.gedung, l.ruangan';
    }

    protected static function orderBy(): string
    {
        return 't.nama_fasilitas ASC';
    }
}
