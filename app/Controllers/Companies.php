<?php

namespace App\Controllers;

use App\Models\CompanyModel;

class Companies extends BaseController
{
    private CompanyModel $model;

    public function __construct()
    {
        $this->model = new CompanyModel();
    }

    public function index()
    {
        return view('companies/index', ['title' => 'Perusahaan']);
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
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('email', $search)
                ->orLike('pic_name', $search)
                ->orLike('phone', $search)
                ->groupEnd();
        }

        $filtered = $builder->countAllResults(false);
        // Indeks mengikuti kolom tabel: kontrol, nomor, nama, PIC, telepon, aksi.
        $columns = ['name', 'name', 'name', 'pic_name', 'phone', 'created_at'];
        $orderColumn = (int) ($request['order'][0]['column'] ?? 2);
        $orderDirection = strtolower((string) ($request['order'][0]['dir'] ?? 'asc')) === 'desc' ? 'desc' : 'asc';
        $builder->orderBy($columns[$orderColumn] ?? 'name', $orderDirection);
        $rows = $builder->get($length, $start)->getResultArray();

        $data = array_map(static function (array $row): array {
            $publicId = public_id((int) $row['id']);
            $actions = '';
            if (can('companies.edit')) {
                $actions .= '<li><a class="dropdown-item" href="/companies/' . $publicId . '/edit"><i class="bi bi-pencil me-2 text-warning"></i>Edit perusahaan</a></li>';
            }
            if (can('companies.delete')) {
                $actions .= '<li><form method="post" action="/companies/' . $publicId . '/delete" data-confirm data-confirm-title="Hapus perusahaan?" data-confirm-message="Data perusahaan ini akan dihapus dan tidak dapat dipulihkan." data-confirm-label="Ya, hapus" data-confirm-variant="danger">' . csrf_field() . '<button class="dropdown-item text-danger" type="submit"><i class="bi bi-trash3 me-2"></i>Hapus perusahaan</button></form></li>';
            }
            $actions = $actions !== ''
                ? '<div class="dropdown datatable-actions"><button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-expanded="false"><i class="bi bi-three-dots me-1"></i>Aksi</button><ul class="dropdown-menu dropdown-menu-end shadow-sm">' . $actions . '</ul></div>'
                : '<span class="text-body-secondary small">Tidak ada aksi</span>';
            return [
                'name' => '<strong>' . esc($row['name']) . '</strong><div class="small text-body-secondary">' . esc($row['email'] ?: 'Email belum diisi') . '</div>',
                'pic_name' => esc($row['pic_name'] ?: '-'),
                'phone' => esc($row['phone'] ?: '-'),
                'actions' => $actions,
            ];
        }, $rows);

        return $this->response->setJSON(['draw' => $draw, 'recordsTotal' => $total, 'recordsFiltered' => $filtered, 'data' => $data]);
    }

    public function new()
    {
        return view('companies/form', ['title' => 'Tambah Perusahaan', 'company' => [], 'action' => '/companies']);
    }

    public function create()
    {
        $data = $this->request->getPost(['name', 'quotation_prefix', 'quotation_code', 'address', 'phone', 'email', 'pic_name', 'pic_phone', 'notes']);
        if (! $this->validateData($data, ['name' => 'required|max_length[160]', 'quotation_prefix' => 'permit_empty|alpha_numeric|max_length[12]', 'quotation_code' => 'permit_empty|regex_match[/^[A-Za-z0-9_-]+$/]|max_length[30]'])) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        $data['quotation_prefix'] = strtoupper(trim((string) ($data['quotation_prefix'] ?? 'CCIP'))) ?: 'CCIP';
        $data['quotation_code'] = strtoupper(trim((string) ($data['quotation_code'] ?? '')));
        $data['quotation_sequence'] = 0;
        $this->model->insert($data);
        return redirect()->to('/companies')->with('message', 'Perusahaan berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $company = $this->model->find($id);
        if (! $company) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('companies/form', ['title' => 'Edit Perusahaan', 'company' => $company, 'action' => '/companies/' . public_id($id)]);
    }

    public function update(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $data = $this->request->getPost(['name', 'quotation_prefix', 'quotation_code', 'address', 'phone', 'email', 'pic_name', 'pic_phone', 'notes']);
        if (! $this->validateData($data, ['name' => 'required|max_length[160]', 'quotation_prefix' => 'permit_empty|alpha_numeric|max_length[12]', 'quotation_code' => 'permit_empty|regex_match[/^[A-Za-z0-9_-]+$/]|max_length[30]'])) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        $data['quotation_prefix'] = strtoupper(trim((string) ($data['quotation_prefix'] ?? 'CCIP'))) ?: 'CCIP';
        $data['quotation_code'] = strtoupper(trim((string) ($data['quotation_code'] ?? '')));
        $this->model->update($id, $data);
        return redirect()->to('/companies')->with('message', 'Perusahaan berhasil diperbarui.');
    }

    public function delete(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $this->model->delete($id);
        return redirect()->to('/companies')->with('message', 'Perusahaan berhasil dihapus.');
    }
}
