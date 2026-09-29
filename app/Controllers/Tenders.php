<?php
namespace App\Controllers;

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
        $today = date('Y-m-d');
        $warningDate = date('Y-m-d', strtotime('+30 days'));
        $base = $this->model->whereNotIn('status', ['cancelled'])->where('valid_until IS NOT NULL', null, false);
        $expired = (clone $base)->where('valid_until <', $today)->countAllResults();
        $expiring = (clone $base)->where('valid_until >=', $today)->where('valid_until <=', $warningDate)->countAllResults();
        return view('tenders/index', ['title' => 'Dokumen Tender', 'expiredCount' => $expired, 'expiringCount' => $expiring]);
    }

    public function datatable()
    {
        $request = $this->request->getGet();
        $draw = (int) ($request['draw'] ?? 0);
        $start = max(0, (int) ($request['start'] ?? 0));
        $length = min(100, max(1, (int) ($request['length'] ?? 10)));
        $search = trim((string) ($request['search']['value'] ?? ''));
        $total = $this->model->countAll();
        $builder = $this->model->builder()->select('tender_documents.*');
        if ($search !== '') $builder->groupStart()->like('tender_documents.tender_no', $search)->orLike('tender_documents.title', $search)->orLike('tender_documents.issuer_name', $search)->orLike('tender_documents.status', $search)->groupEnd();
        $filtered = $builder->countAllResults(false);
        $columns = ['created_at', 'title', 'issue_date', 'valid_until', 'status', 'original_file_name', 'created_at'];
        $orderColumn = (int) ($request['order'][0]['column'] ?? 3);
        $direction = strtolower((string) ($request['order'][0]['dir'] ?? 'desc')) === 'asc' ? 'asc' : 'desc';
        $builder->orderBy($columns[$orderColumn] ?? 'created_at', $direction);
        $rows = $builder->get($length, $start)->getResultArray();
        $data = array_map(static function (array $row, int $index) use ($start): array {
            $publicId = public_id((int) $row['id']);
            $class = ['draft' => 'secondary', 'open' => 'success', 'closed' => 'warning', 'awarded' => 'primary', 'cancelled' => 'danger'][$row['status']] ?? 'secondary';
            $label = ucfirst($row['status']);
            $pastDue = ! empty($row['valid_until']) && $row['valid_until'] < date('Y-m-d') && $row['status'] !== 'cancelled';
            $soonDue = ! empty($row['valid_until']) && $row['valid_until'] >= date('Y-m-d') && $row['valid_until'] <= date('Y-m-d', strtotime('+30 days')) && $row['status'] !== 'cancelled';
            if ($pastDue) { $class = 'danger'; $label = 'Kedaluwarsa'; }
            $statusNote = $soonDue ? '<br><small class="text-warning">Segera berakhir</small>' : '';
            $status = '<span class="badge text-bg-' . $class . '">' . esc($label) . $statusNote . '</span>';
            $actions = '<div class="d-flex flex-wrap gap-1"><a class="btn btn-sm btn-outline-primary" href="/tenders/' . $publicId . '" title="Detail">Detail</a>';
            if (can('tenders.edit')) $actions .= '<a class="btn btn-sm btn-outline-warning" href="/tenders/' . $publicId . '/edit" title="Edit">Edit</a>';
            if (can('tenders.delete')) $actions .= '<form method="post" action="/tenders/' . $publicId . '/delete" data-confirm data-confirm-title="Hapus dokumen tender?" data-confirm-message="Dokumen dan file yang tersimpan akan dihapus." data-confirm-label="Ya, hapus" data-confirm-variant="danger">' . csrf_field() . '<button class="btn btn-sm btn-outline-danger">Hapus</button></form>';
            $actions .= '</div>';
            return ['no' => $start + $index + 1, 'title' => esc($row['title']), 'issue_date' => esc($row['issue_date'] ?: '-'), 'valid_until' => esc($row['valid_until'] ?: '-'), 'status' => $status, 'file' => esc($row['original_file_name'] ?: '-'), 'actions' => $actions];
        }, $rows, array_keys($rows));
        return $this->response->setJSON(['draw' => $draw, 'recordsTotal' => $total, 'recordsFiltered' => $filtered, 'data' => $data]);
    }

    public function new()
    {
        return view('tenders/form', ['title' => 'Tambah Dokumen Tender', 'tender' => [], 'action' => '/tenders']);
    }

    public function create()
    {
        return $this->save();
    }

    public function edit(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $tender = $this->model->find($id);
        if (! $tender) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('tenders/form', ['title' => 'Edit Dokumen Tender', 'tender' => $tender, 'action' => '/tenders/' . public_id($id)]);
    }

    public function update(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        return $this->save($id);
    }

    private function save(?int $id = null)
    {
        $data = $this->request->getPost(['tender_no', 'title', 'procurement_method', 'issuer_name', 'description', 'issue_date', 'valid_until', 'no_expiry', 'status', 'contact_name', 'contact_email', 'contact_phone', 'notes']);
        $rules = ['title' => 'required|max_length[220]', 'issue_date' => 'required|valid_date[Y-m-d]', 'valid_until' => 'permit_empty|valid_date[Y-m-d]', 'contact_email' => 'permit_empty|valid_email|max_length[160]'];
        if (! $this->validateData($data, $rules)) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        if (! empty($data['no_expiry'])) {
            $data['valid_until'] = null;
        } elseif (empty($data['valid_until'])) {
            return redirect()->back()->withInput()->with('errors', ['valid_until' => 'Isi tanggal berlaku sampai atau pilih dokumen tidak memiliki tanggal kedaluwarsa.']);
        } elseif ($data['valid_until'] < $data['issue_date']) {
            return redirect()->back()->withInput()->with('errors', ['valid_until' => 'Masa berlaku tidak boleh sebelum tanggal diterbitkan.']);
        }
        unset($data['no_expiry']);
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

    public function show(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $tender = $this->model->detail($id);
        if (! $tender) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('tenders/show', ['title' => 'Detail Tender', 'tender' => $tender]);
    }

    public function delete(string $id)
    {
        $id = $this->resolveId($id, $this->model);
        $tender = $this->model->find($id);
        if ($tender) { $this->removeFile($tender['file_path'] ?? null); $this->model->delete($id); }
        return redirect()->to('/tenders')->with('message', 'Dokumen tender berhasil dihapus.');
    }

    private function removeFile(?string $path): void
    {
        if ($path && is_file(FCPATH . ltrim($path, '/'))) @unlink(FCPATH . ltrim($path, '/'));
    }
}
