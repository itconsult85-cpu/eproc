<?php
namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\TenderDocumentModel;

class Tenders extends BaseController
{
    private TenderDocumentModel $model;

    public function __construct()
    {
        $this->model = new TenderDocumentModel();
    }

    public function index()
    {
        return view('tenders/index', ['title' => 'Dokumen Tender']);
    }

    public function datatable()
    {
        $request = $this->request->getGet();
        $draw = (int) ($request['draw'] ?? 0);
        $start = max(0, (int) ($request['start'] ?? 0));
        $length = min(100, max(1, (int) ($request['length'] ?? 10)));
        $search = trim((string) ($request['search']['value'] ?? ''));
        $total = $this->model->countAll();
        $builder = $this->model->builder()->select('tender_documents.*, companies.name AS company_name')->join('companies', 'companies.id = tender_documents.company_id', 'left');
        if ($search !== '') $builder->groupStart()->like('tender_documents.tender_no', $search)->orLike('tender_documents.title', $search)->orLike('companies.name', $search)->orLike('tender_documents.status', $search)->groupEnd();
        $filtered = $builder->countAllResults(false);
        $columns = ['created_at', 'created_at', 'tender_no', 'title', 'company_name', 'issue_date', 'valid_until', 'status', 'created_at'];
        $orderColumn = (int) ($request['order'][0]['column'] ?? 7);
        $direction = strtolower((string) ($request['order'][0]['dir'] ?? 'desc')) === 'asc' ? 'asc' : 'desc';
        $builder->orderBy($columns[$orderColumn] ?? 'created_at', $direction);
        $rows = $builder->get($length, $start)->getResultArray();
        $data = array_map(static function (array $row): array {
            $class = ['draft' => 'secondary', 'open' => 'success', 'closed' => 'warning', 'awarded' => 'primary', 'cancelled' => 'danger'][$row['status']] ?? 'secondary';
            $pastDue = ! empty($row['valid_until']) && $row['valid_until'] < date('Y-m-d') && ! in_array($row['status'], ['awarded', 'cancelled'], true);
            $status = '<span class="badge text-bg-' . $class . '">' . esc(ucfirst($row['status'])) . '</span>' . ($pastDue ? '<br><small class="text-danger">Masa berlaku lewat</small>' : '');
            $actions = '<div class="d-flex flex-wrap gap-1"><a class="btn btn-sm btn-outline-primary" href="/tenders/' . (int) $row['id'] . '" title="Detail">Detail</a><a class="btn btn-sm btn-outline-warning" href="/tenders/' . (int) $row['id'] . '/edit" title="Edit">Edit</a>';
            if (! empty($row['file_path'])) $actions .= '<a class="btn btn-sm btn-outline-success" href="' . esc(base_url(ltrim($row['file_path'], '/'))) . '" target="_blank" rel="noopener">File</a>';
            $actions .= '<form method="post" action="/tenders/' . (int) $row['id'] . '/delete" onsubmit="return confirm(\'Hapus dokumen tender ini?\')"><button class="btn btn-sm btn-outline-danger">Hapus</button></form></div>';
            return ['tender_no' => esc($row['tender_no'] ?: '-'), 'title' => esc($row['title']), 'company_name' => esc($row['company_name'] ?: ($row['issuer_name'] ?: '-')), 'issue_date' => esc($row['issue_date'] ?: '-'), 'valid_until' => esc($row['valid_until'] ?: '-'), 'status' => $status, 'actions' => $actions];
        }, $rows);
        return $this->response->setJSON(['draw' => $draw, 'recordsTotal' => $total, 'recordsFiltered' => $filtered, 'data' => $data]);
    }

    public function new()
    {
        return view('tenders/form', ['title' => 'Tambah Dokumen Tender', 'tender' => [], 'companies' => (new CompanyModel())->orderBy('name')->findAll(), 'action' => '/tenders']);
    }

    public function create()
    {
        return $this->save();
    }

    public function edit(int $id)
    {
        $tender = $this->model->find($id);
        if (! $tender) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('tenders/form', ['title' => 'Edit Dokumen Tender', 'tender' => $tender, 'companies' => (new CompanyModel())->orderBy('name')->findAll(), 'action' => '/tenders/' . $id]);
    }

    public function update(int $id)
    {
        return $this->save($id);
    }

    private function save(?int $id = null)
    {
        $data = $this->request->getPost(['company_id', 'tender_no', 'title', 'procurement_method', 'issuer_name', 'description', 'issue_date', 'valid_until', 'submission_deadline', 'status', 'contact_name', 'contact_email', 'contact_phone', 'notes']);
        if (! empty($data['submission_deadline'])) $data['submission_deadline'] = str_replace('T', ' ', (string) $data['submission_deadline']) . (strlen((string) $data['submission_deadline']) === 16 ? ':00' : '');
        $rules = ['title' => 'required|max_length[220]', 'issue_date' => 'permit_empty|valid_date[Y-m-d]', 'valid_until' => 'permit_empty|valid_date[Y-m-d]', 'submission_deadline' => 'permit_empty|valid_date[Y-m-d H:i]', 'contact_email' => 'permit_empty|valid_email|max_length[160]'];
        if (! $this->validateData($data, $rules)) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        if (! empty($data['issue_date']) && ! empty($data['valid_until']) && $data['valid_until'] < $data['issue_date']) return redirect()->back()->withInput()->with('errors', ['valid_until' => 'Masa berlaku tidak boleh sebelum tanggal diterbitkan.']);
        if (! in_array($data['status'] ?? 'draft', ['draft', 'open', 'closed', 'awarded', 'cancelled'], true)) $data['status'] = 'draft';
        $existing = $id ? $this->model->find($id) : [];
        $file = $this->request->getFile('tender_file');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $extensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'jpg', 'jpeg', 'png'];
            if ($file->getSize() > 52428800 || ! in_array(strtolower($file->getExtension()), $extensions, true)) return redirect()->back()->withInput()->with('errors', ['tender_file' => 'File harus PDF, Word, Excel, ZIP, JPG, atau PNG maksimal 50 MB.']);
            $directory = FCPATH . 'uploads/tenders';
            if (! is_dir($directory)) mkdir($directory, 0755, true);
            $newName = $file->getRandomName(); $file->move($directory, $newName);
            $data['file_path'] = '/uploads/tenders/' . $newName; $data['original_file_name'] = $file->getClientName(); $data['file_mime'] = $file->getClientMimeType(); $data['file_size'] = $file->getSize();
            if ($existing && ! empty($existing['file_path'])) $this->removeFile($existing['file_path']);
        } elseif ($existing) {
            foreach (['file_path', 'original_file_name', 'file_mime', 'file_size'] as $field) $data[$field] = $existing[$field] ?? null;
        }
        if (! $id) $data['created_by'] = (string) (session()->get('username') ?: 'system');
        $id ? $this->model->update($id, $data) : $this->model->insert($data);
        return redirect()->to('/tenders')->with('message', $id ? 'Dokumen tender berhasil diperbarui.' : 'Dokumen tender berhasil disimpan.');
    }

    public function show(int $id)
    {
        $tender = $this->model->detail($id);
        if (! $tender) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('tenders/show', ['title' => 'Detail Tender', 'tender' => $tender]);
    }

    public function delete(int $id)
    {
        $tender = $this->model->find($id);
        if ($tender) { $this->removeFile($tender['file_path'] ?? null); $this->model->delete($id); }
        return redirect()->to('/tenders')->with('message', 'Dokumen tender berhasil dihapus.');
    }

    private function removeFile(?string $path): void
    {
        if ($path && is_file(FCPATH . ltrim($path, '/'))) @unlink(FCPATH . ltrim($path, '/'));
    }
}
