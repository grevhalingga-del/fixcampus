<?php
/** Fungsi bantu global yang dipakai di controller dan view. */

/** Escape output HTML (pencegah XSS). */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Membaca konfigurasi dari config/database.php (dan database.local.php jika ada). */
function config(string $key, $default = null)
{
    static $cfg = null;
    if ($cfg === null) {
        $cfg = require BASE_PATH . '/config/database.php';
        $local = BASE_PATH . '/config/database.local.php';
        if (is_file($local)) {
            $cfg = array_merge($cfg, require $local);
        }
    }
    return $cfg[$key] ?? $default;
}

/** Membuat URL relatif ke front controller: url('kategori/edit', ['id' => 3]). */
function url(string $route = '', array $params = []): string
{
    $query = [];
    if ($route !== '') {
        $query[] = 'r=' . $route;   // route hanya berisi huruf kecil dan "/", jadi aman tanpa encoding
    }
    if ($params) {
        $query[] = http_build_query($params);
    }
    return 'index.php' . ($query ? '?' . implode('&', $query) : '');
}

/** URL berkas statis di public/assets. */
function asset(string $path): string
{
    return 'assets/' . ltrim($path, '/');
}

function redirect(string $route, array $params = []): never
{
    header('Location: ' . url($route, $params));
    exit;
}

/** Pesan satu kali tampil (flash) antar-halaman. Tipe: success, danger, warning, info. */
function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'msg' => $message];
}

function pullFlashes(): array
{
    $items = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $items;
}

/** Kelas is-invalid untuk input yang gagal validasi. */
function invalid(array $errors, string $field): string
{
    return isset($errors[$field]) ? ' is-invalid' : '';
}

/** Pesan galat di bawah input. */
function feedback(array $errors, string $field): string
{
    return isset($errors[$field]) ? '<div class="invalid-feedback">' . e($errors[$field]) . '</div>' : '';
}
