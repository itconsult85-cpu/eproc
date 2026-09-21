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
        return view('products/index', [
            'title' => 'Katalog Produk',
            'products' => $this->model->orderBy('name', 'ASC')->findAll(),
        ]);
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
        $columns = ['name', 'sku', 'cost_price', 'selling_price', 'store_name', 'created_at'];
        $orderColumn = (int) ($request['order'][0]['column'] ?? 1);
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
            $actions = '<div class="btn-group btn-group-sm" role="group" aria-label="Aksi produk">'
                . '<a class="btn btn-outline-secondary" href="/products/' . (int) $row['id'] . '/edit" title="Edit" aria-label="Edit"><i class="bi bi-pencil"></i></a>'
                . '<form method="post" action="/products/' . (int) $row['id'] . '/delete" onsubmit="return confirm(\'Hapus produk ini?\')"><button class="btn btn-outline-danger" title="Hapus" aria-label="Hapus"><i class="bi bi-trash3"></i></button></form></div>';
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

    public function edit(int $id)
    {
        $product = $this->model->find($id);
        if (! $product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        return view('products/form', ['title' => 'Edit Produk', 'product' => $product, 'action' => '/products/' . $id]);
    }

    public function update(int $id)
    {
        return $this->save($id);
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

    public function delete(int $id)
    {
        $this->model->delete($id);
        return redirect()->to('/products')->with('message', 'Produk berhasil dihapus.');
    }
}
