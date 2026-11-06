<?php
/** Controller dasar: render view, redirect, akses input, dan pemeriksaan peran. */
abstract class Controller
{
    /** null = publik; array = hanya peran tertentu yang boleh (harus login). */
    protected ?array $roles = null;

    /** Nama menu yang disorot pada navbar. */
    protected string $menu = '';

    /** Dipanggil Router sebelum aksi dijalankan. */
    public function authorize(): void
    {
        if ($this->roles === null) {
            return;
        }
        if (!Auth::check()) {
            flash('warning', 'Silakan login terlebih dahulu.');
            redirect('auth/login');
        }
        if (!Auth::hasRole($this->roles)) {
            (new ErrorController())->show(403);
            exit;
        }
    }

    protected function view(string $view, array $data = [], string $layout = 'main', int $status = 200): void
    {
        http_response_code($status);
        $data += ['active' => $this->menu];
        extract($data, EXTR_SKIP);

        ob_start();
        require BASE_PATH . "/app/Views/$view.php";
        $content = ob_get_clean();

        require BASE_PATH . "/app/Views/layouts/$layout.php";
    }

    /** Mengambil input POST (string, sudah di-trim). */
    protected function post(string $key, string $default = ''): string
    {
        $v = $_POST[$key] ?? $default;
        return is_string($v) ? trim($v) : $default;
    }

    /** Mengambil parameter GET (string, sudah di-trim). */
    protected function query(string $key, string $default = ''): string
    {
        $v = $_GET[$key] ?? $default;
        return is_string($v) ? trim($v) : $default;
    }
}
