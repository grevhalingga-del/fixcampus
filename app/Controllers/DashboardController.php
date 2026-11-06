<?php
/** Beranda setelah login; isinya menyesuaikan peran. */
class DashboardController extends Controller
{
    protected ?array $roles = ['admin', 'pelapor', 'teknisi'];
    protected string $menu = 'dashboard';

    public function index(): void
    {
        $stats = [];
        if (Auth::role() === 'admin') {
            $stats = [
                ['label' => 'Pengguna',  'jumlah' => User::count(),      'route' => 'pengguna/index'],
                ['label' => 'Kategori',  'jumlah' => Kategori::count(),  'route' => 'kategori/index'],
                ['label' => 'Lokasi',    'jumlah' => Lokasi::count(),    'route' => 'lokasi/index'],
                ['label' => 'Fasilitas', 'jumlah' => Fasilitas::count(), 'route' => 'fasilitas/index'],
            ];
        }
        $this->view('dashboard/index', ['title' => 'Beranda', 'stats' => $stats]);
    }
}
