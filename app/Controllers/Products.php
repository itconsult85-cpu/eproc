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
        $data = $this->request->getPost(['sku', 'name', 'brand', 'description', 'datasheet', 'image_url', 'video_url', 'cost_price', 'selling_price', 'store_name', 'store_url', 'store_phone', 'store_pic', 'is_active']);
        if (! $this->validateData($data, ['name' => 'required|max_length[180]', 'selling_price' => 'permit_empty|decimal'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $existing = $id ? $this->model->find($id) : [];
        foreach (['image' => ['image_path', ['jpg', 'jpeg', 'png', 'webp'], 5242880], 'video' => ['video_path', ['mp4', 'webm', 'mov'], 52428800]] as $field => [$column, $extensions, $maxSize]) {
            $file = $this->request->getFile($field);
            if ($file && $file->isValid() && ! $file->hasMoved()) {
                if ($file->getSize() > $maxSize || ! in_array(strtolower($file->getExtension()), $extensions, true)) {
                    return redirect()->back()->withInput()->with('errors', [$field => 'Format atau ukuran file ' . $field . ' tidak valid.']);
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
