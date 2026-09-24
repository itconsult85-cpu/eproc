<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Products extends BaseController
{
    private ProductModel $model;

    public function __construct()
    {
        $this->model = new ProductModel();
    }

    public function index()
    {
        return view('products/index', ['title' => 'Katalog Produk']);
    }

    public function datatable()
    {
        $request = $this->request->getGet();
        $draw = (int) ($request['draw'] ?? 0);
        $start = max(0, (int) ($request['start'] ?? 0));
        $length = min(100, max(1, (int) ($request['length'] ?? 10)));
        $search = trim((string) ($request['search']['value'] ?? ''));
        $total = $this->model->countAll();
        $builder = $this->model->builder();

        if ($search !== '') {
            $builder->groupStart()->like('name', $search)->orLike('sku', $search)->orLike('brand', $search)->orLike('store_name', $search)->groupEnd();
        }
        $filtered = $builder->countAllResults(false);
        // Indeks mengikuti kolom tabel: kontrol, nomor, media, produk, harga modal, harga jual, toko, aksi.
        $columns = ['name', 'name', 'name', 'name', 'cost_price', 'selling_price', 'store_name', 'created_at'];
        $orderColumn = (int) ($request['order'][0]['column'] ?? 3);
        $orderDirection = strtolower((string) ($request['order'][0]['dir'] ?? 'asc')) === 'desc' ? 'desc' : 'asc';
        $builder->orderBy($columns[$orderColumn] ?? 'name', $orderDirection);
        $rows = $builder->get($length, $start)->getResultArray();

        $data = array_map(static function (array $row): array {
            $media = $row['image_path']
                ? '<img src="' . esc($row['image_path']) . '" class="rounded border object-fit-cover" style="width:52px;height:52px" alt="' . esc($row['name']) . '">'
                : '<span class="d-inline-flex bg-body-secondary rounded align-items-center justify-content-center" style="width:52px;height:52px"><i class="bi bi-image text-secondary"></i></span>';
            if ($row['video_path']) $media .= '<div class="small text-primary mt-1"><i class="bi bi-camera-video"></i> Video</div>';
            $details = '<strong>' . esc($row['name']) . '</strong><div class="small text-body-secondary">' . esc(trim(($row['sku'] ?? '') . ' ' . ($row['brand'] ?? '')) ?: 'SKU belum diisi') . '</div>';
            if ($row['datasheet_file_path']) $details .= '<a href="' . esc($row['datasheet_file_path']) . '" target="_blank" rel="noopener" class="small text-danger"><i class="bi bi-file-pdf"></i> Datasheet PDF</a>';
            $publicId = public_id((int) $row['id']);
            $actions = '';
            if (can('products.edit')) {
                $actions .= '<li><a class="dropdown-item" href="/products/' . $publicId . '/edit"><i class="bi bi-pencil me-2 text-warning"></i>Edit produk</a></li>';
            }
            if (can('products.delete')) {
                $actions .= '<li><form method="post" action="/products/' . $publicId . '/delete" data-confirm data-confirm-title="Hapus produk?" data-confirm-message="Produk ini akan dihapus dan tidak dapat dipulihkan." data-confirm-label="Ya, hapus" data-confirm-variant="danger">' . csrf_field() . '<button class="dropdown-item text-danger" type="submit"><i class="bi bi-trash3 me-2"></i>Hapus produk</button></form></li>';
            }
            $actions = $actions !== ''
                ? '<div class="dropdown datatable-actions"><button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-expanded="false"><i class="bi bi-three-dots me-1"></i>Aksi</button><ul class="dropdown-menu dropdown-menu-end shadow-sm">' . $actions . '</ul></div>'
                : '<span class="text-body-secondary small">Tidak ada aksi</span>';
            return ['media' => $media, 'product' => $details, 'cost_price' => 'Rp ' . number_format((float) $row['cost_price'], 0, ',', '.'), 'selling_price' => 'Rp ' . number_format((float) $row['selling_price'], 0, ',', '.'), 'store' => esc($row['store_name'] ?: '-') . '<div class="small text-body-secondary">' . esc($row['store_phone'] ?: '') . '</div>', 'actions' => $actions];
        }, $rows);

        return $this->response->setJSON(['draw' => $draw, 'recordsTotal' => $total, 'recordsFiltered' => $filtered, 'data' => $data]);
    }

    public function new()
    {
        return view('products/form', ['title' => 'Tambah Produk', 'product' => [], 'action' => '/products']);
    }

    public function create()
    {
        return $this->save();
    }

    public function edit(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $product = $this->model->find($id);
        if (! $product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        return view('products/form', ['title' => 'Edit Produk', 'product' => $product, 'action' => '/products/' . public_id($id)]);
    }

    public function update(string $id)
    {
        return $this->save($this->resolveId($id, $this->model));
    }

    private function save(?int $id = null)
    {
        $data = $this->request->getPost(['sku', 'name', 'brand', 'description', 'datasheet', 'applications', 'standards', 'cost_price', 'selling_price', 'store_name', 'store_url', 'store_phone', 'store_pic', 'is_active']);
        if (! $this->validateData($data, ['name' => 'required|max_length[180]', 'selling_price' => 'permit_empty|decimal'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $labels = (array) $this->request->getPost('spec_label');
        $values = (array) $this->request->getPost('spec_value');
        $specifications = [];
        foreach ($labels as $index => $label) {
            $label = trim((string) $label);
            $value = trim((string) ($values[$index] ?? ''));
            if ($label !== '' && $value !== '') {
                $specifications[] = ['label' => $label, 'value' => $value];
            }
        }
        $data['technical_specs'] = $specifications ? json_encode($specifications, JSON_UNESCAPED_UNICODE) : null;

        $existing = $id ? $this->model->find($id) : [];
        $uploads = [
            'image' => ['image_path', ['jpg', 'jpeg', 'png', 'webp'], 5242880, 'Format atau ukuran file gambar tidak valid.'],
            'video' => ['video_path', ['mp4', 'webm', 'mov'], 52428800, 'Format atau ukuran file video tidak valid.'],
            'datasheet_file' => ['datasheet_file_path', ['pdf'], 20971520, 'Datasheet harus berupa PDF maksimal 20 MB.'],
        ];
        foreach ($uploads as $field => [$column, $extensions, $maxSize, $message]) {
            $file = $this->request->getFile($field);
            if ($file && $file->isValid() && ! $file->hasMoved()) {
                if ($file->getSize() > $maxSize || ! in_array(strtolower($file->getExtension()), $extensions, true)) {
                    return redirect()->back()->withInput()->with('errors', [$field => $message]);
                }
                $directory = FCPATH . 'uploads/products';
                if (! is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }
                $newName = $file->getRandomName();
                $file->move($directory, $newName);
                $data[$column] = '/uploads/products/' . $newName;
            } elseif ($existing && ! empty($existing[$column])) {
                $data[$column] = $existing[$column];
            }
        }
        $id ? $this->model->update($id, $data) : $this->model->insert($data);
        return redirect()->to('/products')->with('message', $id ? 'Produk berhasil diperbarui.' : 'Produk berhasil ditambahkan.');
    }

    public function delete(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $this->model->delete($id);
        return redirect()->to('/products')->with('message', 'Produk berhasil dihapus.');
    }
}
