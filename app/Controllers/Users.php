<?php

namespace App\Controllers;

use App\Models\PermissionModel;
use App\Models\UserModel;

class Users extends BaseController
{
    private UserModel $users;
    public function __construct()
    {
        $this->users = new UserModel();
    }
    public function index()
    {
        return view('users/index', ['title' => 'Manajemen Pengguna', 'users' => $this->users->orderBy('created_at', 'DESC')->findAll()]);
    }
    public function new()
    {
        return view('users/form', ['title' => 'Tambah Pengguna', 'user' => null, 'permissions' => (new PermissionModel())->orderBy('group_name')->findAll(), 'assigned' => []]);
    }
    public function edit(string $id)
    {
        $id = $this->resolveId($id, $this->users);
        $user = $this->users->find($id);
        if (! $user) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return view('users/form', ['title' => 'Edit Pengguna', 'user' => $user, 'permissions' => (new PermissionModel())->orderBy('group_name')->findAll(), 'assigned' => array_column($this->users->permissions($id), 'permission_key')]);
    }
    public function save(?string $id = null)
    {
        $id = $id !== null ? $this->resolveId($id, $this->users) : null;
        $data = $this->request->getPost(['username', 'email', 'full_name', 'role', 'is_active']);
        $password = (string) $this->request->getPost('password');
        $isSuperadmin = auth_user('role') === 'superadmin';
        if (! $isSuperadmin) $data['role'] = 'user';
        $rules = ['username' => 'required|alpha_numeric_punct|min_length[4]|max_length[80]', 'email' => 'required|valid_email|max_length[160]', 'full_name' => 'required|max_length[160]', 'role' => 'required|in_list[admin,user]'];
        if (! $id) $rules['password'] = 'required|min_length[12]|max_length[72]';
        elseif ($password !== '') $rules['password'] = 'min_length[12]|max_length[72]';
        if (! $this->validateData(array_merge($data, ['password' => $password]), $rules)) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        if ($id && $id === (int) auth_user('id')) return redirect()->back()->with('error', 'Akun yang sedang digunakan tidak dapat diedit dari sini.');
        if ($id && ($existing = $this->users->find($id)) && in_array($existing['role'], ['superadmin', 'admin'], true) && ! $isSuperadmin) return redirect()->back()->with('error', 'Admin hanya dapat mengelola akun user.');
        $data['is_active'] = $this->request->getPost('is_active') ? 1 : 0;
        if ($password !== '') $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        if (! $id) $data['created_at'] = date('Y-m-d H:i:s');
        $avatar = $this->request->getFile('avatar');
        if ($avatar && $avatar->isValid() && ! $avatar->hasMoved()) {
            if ($avatar->getSize() > 2097152 || ! in_array(strtolower($avatar->getExtension()), ['jpg', 'jpeg', 'png', 'webp'], true)) return redirect()->back()->withInput()->with('error', 'Avatar harus JPG, PNG, atau WEBP maksimal 2 MB.');
            $dir = FCPATH . 'uploads/avatars';
            if (! is_dir($dir)) mkdir($dir, 0755, true);
            $name = $avatar->getRandomName();
            $avatar->move($dir, $name);
            $data['avatar_path'] = '/uploads/avatars/' . $name;
        }
        $userId = $id ? $id : $this->users->insert($data, true);
        if ($id) $this->users->update($id, $data);
        $db = db_connect();
        $db->table('user_permissions')->where('user_id', $userId)->delete();
        $keys = (array) $this->request->getPost('permissions');
        if (! $isSuperadmin) $keys = array_values(array_intersect($keys, auth_user('permissions') ?? []));
        if ($keys) {
            $rows = $db->table('permissions')->select('id')->whereIn('permission_key', $keys)->get()->getResultArray();
            foreach ($rows as $row) $db->table('user_permissions')->insert(['user_id' => $userId, 'permission_id' => $row['id']]);
        }
        return redirect()->to('/users')->with('message', 'Pengguna berhasil disimpan.');
    }
    public function delete(string $id)
    {
        $id = $this->resolveId($id, $this->users);
        $user = $this->users->find($id);
        if (! $user || $user['role'] === 'superadmin' || $id === (int) auth_user('id')) return redirect()->back()->with('error', 'Pengguna tidak dapat dihapus.');
        $this->users->delete($id);
        return redirect()->to('/users')->with('message', 'Pengguna berhasil dihapus.');
    }
}
