<?php
/**
 * Controller CRUD generik untuk data master.
 * Subclass menentukan model, nama tampilan, aturan validasi, dan (opsional) data tambahan untuk form.
 *
 * Alur: index (daftar+cari) -> create/store (tambah) -> edit/update (ubah) -> delete (hapus).
 */
abstract class CrudController extends Controller
{
    /** Hanya admin yang boleh mengelola data master. */
    protected ?array $roles = ['admin'];

    /** Nama kelas model, mis. Kategori::class */
    protected string $model;
    /** Segmen route & folder view, mis. 'kategori' */
    protected string $route;
    /** Judul tunggal tampilan, mis. 'Kategori' */
    protected string $label;
    /** Nama kolom primary key, mis. 'id_kategori' */
    protected string $pk;

    /**
     * Validasi input form.
     * @return array{0:array,1:array} [data bersih untuk disimpan, galat per field]
     */
    abstract protected function validate(?array $existing): array;

    /** Nilai awal form tambah. */
    abstract protected function defaults(): array;

    /** Data tambahan untuk view form (mis. daftar dropdown). */
    protected function formExtras(): array
    {
        return [];
    }

    /** Pesan galat jika data tidak boleh dihapus; null jika boleh. */
    protected function deleteGuard(array $row): ?string
    {
        return null;
    }

    /** Ringkasan satu baris untuk pesan sukses, mis. nama kategori. */
    abstract protected function title(array $row): string;

    public function index(): void
    {
        $model = $this->model;
        $q = $this->query('q');
        $page = max(1, (int) $this->query('page', '1'));
        $result = $model::paginate($q, $page);

        $this->view("{$this->route}/index", [
            'title' => $this->label, 'result' => $result, 'q' => $q,
            'route' => $this->route, 'label' => $this->label,
        ]);
    }

    public function create(): void
    {
        $this->form($this->defaults(), [], 'create');
    }

    public function store(): void
    {
        [$data, $errors] = $this->validate(null);
        if (!$errors) {
            $model = $this->model;
            $res = $model::insert($data);
            if ($res['ok']) {
                flash('success', "{$this->label} \"{$this->title($data)}\" berhasil ditambahkan.");
                redirect("{$this->route}/index");
            }
            $errors = $this->dbError($res);
        }
        $this->form($this->oldInput(), $errors, 'create', 422);
    }

    public function edit(): void
    {
        $row = $this->findOrFail();
        $this->form($row, [], 'edit');
    }

    public function update(): void
    {
        $row = $this->findOrFail();
        [$data, $errors] = $this->validate($row);
        if (!$errors) {
            $model = $this->model;
            $res = $model::update((int) $this->post('id'), $data);
            if ($res['ok']) {
                flash('success', "{$this->label} \"{$this->title($data + $row)}\" berhasil diperbarui.");
                redirect("{$this->route}/index");
            }
            $errors = $this->dbError($res);
        }
        $this->form($this->oldInput() + $row, $errors, 'edit', 422);
    }

    public function delete(): void
    {
        $row = $this->findOrFail();
        $block = $this->deleteGuard($row);
        if ($block !== null) {
            flash('danger', $block);
            redirect("{$this->route}/index");
        }

        $model = $this->model;
        $res = $model::delete((int) $row[$this->pk]);
        if ($res['ok']) {
            flash('success', "{$this->label} \"{$this->title($row)}\" berhasil dihapus.");
        } elseif ($res['errno'] === 1451) {
            flash('danger', "{$this->label} \"{$this->title($row)}\" tidak dapat dihapus karena masih dipakai oleh data lain.");
        } else {
            flash('danger', 'Data gagal dihapus. Silakan coba lagi.');
        }
        redirect("{$this->route}/index");
    }

    /** Mengambil baris berdasarkan id (GET untuk edit, POST untuk update/delete). */
    protected function findOrFail(): array
    {
        $id = (int) ($_SERVER['REQUEST_METHOD'] === 'POST' ? $this->post('id') : $this->query('id'));
        $model = $this->model;
        $row = $id > 0 ? $model::find($id) : null;
        if ($row === null) {
            (new ErrorController())->show(404);
            exit;
        }
        return $row;
    }

    private function form(array $row, array $errors, string $mode, int $status = 200): void
    {
        $this->view("{$this->route}/form", [
            'title' => ($mode === 'create' ? 'Tambah ' : 'Ubah ') . $this->label,
            'row' => $row, 'errors' => $errors, 'mode' => $mode,
            'route' => $this->route, 'label' => $this->label,
        ] + $this->formExtras(), 'main', $status);
    }

    /** Input POST mentah untuk mengisi ulang form setelah gagal validasi (tanpa password). */
    private function oldInput(): array
    {
        $old = [];
        foreach ($_POST as $k => $v) {
            if (is_string($v) && !str_starts_with($k, 'password') && $k !== '_token') {
                $old[$k] = trim($v);
            }
        }
        return $old;
    }

    /** Mengubah galat database menjadi pesan untuk pengguna. */
    protected function dbError(array $res): array
    {
        if ($res['errno'] === 1062) {
            return ['_form' => 'Data tersebut sudah ada. Gunakan nilai yang berbeda.'];
        }
        if ($res['errno'] === 1452) {
            return ['_form' => 'Data referensi yang dipilih tidak valid.'];
        }
        return ['_form' => 'Data gagal disimpan. Silakan coba lagi.'];
    }
}
