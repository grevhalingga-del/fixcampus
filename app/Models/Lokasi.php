<?php
/** Tabel lokasi (gedung dan ruangan). */
class Lokasi extends BaseModel
{
    protected static string $table = 'lokasi';
    protected static string $pk = 'id_lokasi';
    protected static array $columns = ['gedung', 'ruangan'];
    protected static array $searchable = ['t.gedung', 't.ruangan'];

    protected static function orderBy(): string
    {
        return 't.gedung ASC, t.ruangan ASC';
    }
}
