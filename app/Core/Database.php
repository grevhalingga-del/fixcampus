<?php
/**
 * Lapisan akses data (MySQLi).
 * Semua query memakai prepared statement (bind_param) sehingga aman dari SQL injection.
 */
class Database
{
    private static ?mysqli $conn = null;

    public static function connection(): mysqli
    {
        if (self::$conn === null) {
            // Error MySQLi dilempar sebagai exception agar mudah ditangani.
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            self::$conn = new mysqli(
                config('host'), config('user'), config('pass'), config('name')
            );
            self::$conn->set_charset(config('charset', 'utf8mb4'));
        }
        return self::$conn;
    }

    /**
     * Menjalankan SELECT dan mengembalikan semua baris (array asosiatif).
     * $types: string tipe bind_param, misalnya "si" (s = string, i = integer, d = double).
     */
    public static function select(string $sql, string $types = '', array $params = []): array
    {
        $stmt = self::prepare($sql, $types, $params);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    /**
     * Menjalankan INSERT/UPDATE/DELETE.
     * Hasil: ['ok' => bool, 'affected' => int, 'insert_id' => int, 'errno' => int, 'error' => string].
     * errno 1062 = data ganda (UNIQUE), 1451 = masih dipakai tabel lain (FOREIGN KEY).
     */
    public static function execute(string $sql, string $types = '', array $params = []): array
    {
        try {
            $stmt = self::prepare($sql, $types, $params);
            $stmt->execute();
            $result = [
                'ok' => true, 'affected' => $stmt->affected_rows,
                'insert_id' => (int) $stmt->insert_id, 'errno' => 0, 'error' => '',
            ];
            $stmt->close();
            return $result;
        } catch (mysqli_sql_exception $e) {
            return ['ok' => false, 'affected' => 0, 'insert_id' => 0, 'errno' => (int) $e->getCode(), 'error' => $e->getMessage()];
        }
    }

    private static function prepare(string $sql, string $types, array $params): mysqli_stmt
    {
        $stmt = self::connection()->prepare($sql);
        if ($types !== '') {
            $stmt->bind_param($types, ...$params);
        }
        return $stmt;
    }
}
