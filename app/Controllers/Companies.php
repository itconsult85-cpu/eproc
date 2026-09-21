<?php

namespace App\Controllers;

use App\Models\CompanyModel;

class Companies extends BaseController
{
    private CompanyModel $model;
    public function __construct() { $this->model = new CompanyModel(); }
    public function index() { return view('companies/index', ['title' => 'Perusahaan', 'companies' => $this->model->orderBy('name')->findAll()]); }
    public function new() { return view('companies/form', ['title' => 'Tambah Perusahaan', 'company' => [], 'action' => '/companies']); }
    public function create() { $data = $this->request->getPost(['name','address','phone','email','pic_name','pic_phone','notes']); if (! $this->validateData($data, ['name' => 'required|max_length[160]'])) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors()); $this->model->insert($data); return redirect()->to('/companies')->with('message', 'Perusahaan berhasil ditambahkan.'); }
    public function edit(int $id) { $company = $this->model->find($id); if (! $company) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(); return view('companies/form', ['title' => 'Edit Perusahaan', 'company' => $company, 'action' => '/companies/' . $id]); }
    public function update(int $id) { $data = $this->request->getPost(['name','address','phone','email','pic_name','pic_phone','notes']); if (! $this->validateData($data, ['name' => 'required|max_length[160]'])) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors()); $this->model->update($id, $data); return redirect()->to('/companies')->with('message', 'Perusahaan berhasil diperbarui.'); }
    public function delete(int $id) { $this->model->delete($id); return redirect()->to('/companies')->with('message', 'Perusahaan berhasil dihapus.'); }
}

