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
        return view('products/index', ['title' => 'Katalog Produk', 'products' => $this->model->orderBy('name')->findAll()]);
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
