<?php
/** Perlindungan CSRF: setiap form POST membawa token acak yang dicocokkan dengan sesi. */
class Csrf
{
    public static function token(): string
    {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf'];
    }

    /** Input hidden untuk disisipkan di dalam <form method="post">. */
    public static function field(): string
    {
        return '<input type="hidden" name="_token" value="' . e(self::token()) . '">';
    }

    public static function verify(): bool
    {
        $sent = $_POST['_token'] ?? '';
        return is_string($sent) && $sent !== '' && hash_equals(self::token(), $sent);
    }
}
