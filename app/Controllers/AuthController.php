<?php
/** Login dan logout. */
class AuthController extends Controller
{
    public function login(): void
    {
        if (Auth::check()) {
            redirect('dashboard/index');
        }
        $this->view('auth/login', ['title' => 'Login', 'email' => '', 'error' => ''], 'auth');
    }

    public function attempt(): void
    {
        $email = strtolower($this->post('email'));
        $password = $_POST['password'] ?? '';
        $password = is_string($password) ? $password : '';

        if ($email === '' || $password === '') {
            $this->failed($email, 'Email dan password wajib diisi.');
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->failed($email, 'Format email tidak valid.');
            return;
        }

        $result = Auth::attempt($email, $password);
        if (!$result['ok']) {
            $this->failed($email, $result['message']);
            return;
        }

        flash('success', 'Selamat datang, ' . Auth::user()['nama'] . '.');
        redirect('dashboard/index');
    }

    public function logout(): void
    {
        Auth::logout();
        flash('info', 'Anda telah keluar.');
        redirect('auth/login');
    }

    private function failed(string $email, string $message): void
    {
        $this->view('auth/login', ['title' => 'Login', 'email' => $email, 'error' => $message], 'auth', 401);
    }
}
