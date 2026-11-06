<?php
/**
 * Model dasar untuk operasi CRUD satu tabel.
 * Subclass cukup mengisi $table, $pk, $columns, dan $searchable
 * (serta fromClause()/selectColumns() bila perlu JOIN).
 */
abstract class BaseModel
{
    protected static string $table;
    protected static string $pk;
    /** Kolom yang boleh diisi lewat insert()/update(). */
    protected static array $columns = [];
    /** Kolom yang dicari saat memakai kotak pencarian (boleh memakai alias tabel). */
    protected static array $searchable = [];

    /** Bagian FROM, bisa berisi JOIN. Alias tabel utama selalu "t". */
    protected static function fromClause(): string
    {
        return static::$table . ' t';
    }

    protected static function selectColumns(): string
    {
        return 't.*';
    }

    protected static function orderBy(): string
    {
        return 't.' . static::$pk . ' ASC';
    }

    /** Daftar data dengan pencarian dan halaman. */
    public static function paginate(string $keyword = '', int $page = 1, int $perPage = 10): array
    {
        $where = '';
        $types = '';
        $params = [];
        if ($keyword !== '' && static::$searchable) {
            $likes = array_map(fn($c) => "$c LIKE ?", static::$searchable);
            $where = ' WHERE (' . implode(' OR ', $likes) . ')';
            $types = str_repeat('s', count($likes));
            $params = array_fill(0, count($likes), '%' . $keyword . '%');
        }

        $total = (int) Database::select(
            'SELECT COUNT(*) AS n FROM ' . static::fromClause() . $where, $types, $params
        )[0]['n'];

        $pages = max(1, (int) ceil($total / $perPage));
        $page = min(max(1, $page), $pages);

        $rows = Database::select(
            'SELECT ' . static::selectColumns() . ' FROM ' . static::fromClause() . $where
            . ' ORDER BY ' . static::orderBy() . ' LIMIT ? OFFSET ?',
            $types . 'ii',
            array_merge($params, [$perPage, ($page - 1) * $perPage])
        );

        return ['rows' => $rows, 'total' => $total, 'page' => $page, 'pages' => $pages, 'perPage' => $perPage];
    }

    /** Seluruh data tanpa halaman (untuk dropdown). */
    public static function all(): array
    {
        return Database::select(
            'SELECT ' . static::selectColumns() . ' FROM ' . static::fromClause() . ' ORDER BY ' . static::orderBy()
        );
    }

    public static function find(int $id): ?array
    {
        $rows = Database::select(
            'SELECT ' . static::selectColumns() . ' FROM ' . static::fromClause() . ' WHERE t.' . static::$pk . ' = ? LIMIT 1',
            'i', [$id]
        );
        return $rows[0] ?? null;
    }

    public static function count(): int
    {
        return (int) Database::select('SELECT COUNT(*) AS n FROM ' . static::$table)[0]['n'];
    }

    public static function insert(array $data): array
    {
        $data = array_intersect_key($data, array_flip(static::$columns));
        $cols = array_keys($data);
        $sql = 'INSERT INTO ' . static::$table . ' (' . implode(', ', $cols) . ') VALUES ('
             . implode(', ', array_fill(0, count($cols), '?')) . ')';
        return Database::execute($sql, self::types($data), array_values($data));
    }

    public static function update(int $id, array $data): array
    {
        $data = array_intersect_key($data, array_flip(static::$columns));
        $set = implode(', ', array_map(fn($c) => "$c = ?", array_keys($data)));
        $sql = 'UPDATE ' . static::$table . " SET $set WHERE " . static::$pk . ' = ?';
        return Database::execute($sql, self::types($data) . 'i', array_merge(array_values($data), [$id]));
    }

    public static function delete(int $id): array
    {
        return Database::execute('DELETE FROM ' . static::$table . ' WHERE ' . static::$pk . ' = ?', 'i', [$id]);
    }

    /** Menentukan string tipe bind_param dari nilai data. */
    private static function types(array $data): string
    {
        $t = '';
        foreach ($data as $v) {
            $t .= is_int($v) ? 'i' : (is_float($v) ? 'd' : 's');
        }
        return $t;
    }
}
