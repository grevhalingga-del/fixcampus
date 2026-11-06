<?php
/**
 * Router front controller. URL berbentuk index.php?r=<controller>/<aksi>&id=<n>
 * Hanya controller dan aksi pada daftar putih di bawah yang dapat dipanggil.
 */
class Router
{
    private const CONTROLLERS = [
        'auth'      => 'AuthController',
        'dashboard' => 'DashboardController',
        'kategori'  => 'KategoriController',
        'lokasi'    => 'LokasiController',
        'fasilitas' => 'FasilitasController',
        'pengguna'  => 'PenggunaController',
    ];

    // Aksi yang boleh dipanggil dan apakah harus lewat POST.
    private const ACTIONS = [
        'index' => false, 'login' => false, 'create' => false, 'edit' => false,
        'attempt' => true, 'logout' => true, 'store' => true, 'update' => true, 'delete' => true,
    ];

    public static function dispatch(): void
    {
        $route = $_GET['r'] ?? '';
        if ($route === '') {
            $route = Auth::check() ? 'dashboard/index' : 'auth/login';
        }

        $parts = explode('/', $route, 2);
        $name = $parts[0];
        $action = $parts[1] ?? 'index';

        if (!isset(self::CONTROLLERS[$name]) || !array_key_exists($action, self::ACTIONS)) {
            (new ErrorController())->show(404);
            return;
        }

        $class = self::CONTROLLERS[$name];
        $controller = new $class();
        if (!method_exists($controller, $action)) {
            (new ErrorController())->show(404);
            return;
        }

        $isPost = $_SERVER['REQUEST_METHOD'] === 'POST';
        if (self::ACTIONS[$action] && !$isPost) {
            (new ErrorController())->show(405);
            return;
        }
        if ($isPost && !Csrf::verify()) {
            (new ErrorController())->show(419);
            return;
        }

        $controller->authorize();
        $controller->$action();
    }
}
