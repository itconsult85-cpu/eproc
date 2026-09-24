<?php

namespace App\Controllers;

use App\Libraries\AccessControl;
use App\Models\AuthAuditLogModel;
use App\Models\QuotationSettingModel;
use App\Models\UserModel;

class Auth extends BaseController
{
    private UserModel $users;
    public function __construct()
    {
        $this->users = new UserModel();
    }

    public function login()
    {
        if (AccessControl::isLoggedIn()) return redirect()->to('/');
        return view('auth/login', ['title' => 'Login', 'settings' => (new QuotationSettingModel())->current()]);
    }

    public function authenticate()
    {
        $login = trim((string) $this->request->getPost('login'));
        $password = (string) $this->request->getPost('password');
        if ($login === '' || $password === '') return redirect()->back()->withInput()->with('error', 'Username/email dan password wajib diisi.');
        $user = $this->users->findByLogin($login);
        $audit = new AuthAuditLogModel();
        $now = date('Y-m-d H:i:s');
        $locked = $user && ! empty($user['locked_until']) && strtotime($user['locked_until']) > time();
        if (! $user || ! password_verify($password, $user['password_hash']) || ! (bool) $user['is_active'] || $locked) {
            if ($user && ! $locked) {
                $attempts = ((int) $user['failed_login_attempts']) + 1;
                $data = ['failed_login_attempts' => $attempts];
                if ($attempts >= 5) {
                    $data['locked_until'] = date('Y-m-d H:i:s', time() + 900);
                }
                $this->users->update($user['id'], $data);
            }
            $audit->insert(['user_id' => $user['id'] ?? null, 'username' => $login, 'event' => 'login_failed', 'ip_address' => $this->request->getIPAddress(), 'user_agent' => substr((string) $this->request->getUserAgent(), 0, 500), 'details' => 'Invalid credentials or locked account', 'created_at' => $now]);
            return redirect()->back()->withInput()->with('error', 'Kredensial tidak valid atau akun sedang terkunci.');
        }
        $this->users->update($user['id'], ['failed_login_attempts' => 0, 'locked_until' => null, 'last_login_at' => $now, 'last_login_ip' => $this->request->getIPAddress()]);
        session()->regenerate(true);
        AccessControl::refreshSession($user);
        $audit->insert(['user_id' => $user['id'], 'username' => $user['username'], 'event' => 'login_success', 'ip_address' => $this->request->getIPAddress(), 'user_agent' => substr((string) $this->request->getUserAgent(), 0, 500), 'created_at' => $now]);
        return redirect()->to((string) (session()->get('login_redirect') ?: '/'));
    }

    public function logout()
    {
        if (AccessControl::user()) (new AuthAuditLogModel())->insert(['user_id' => auth_user('id'), 'username' => auth_user('username'), 'event' => 'logout', 'ip_address' => $this->request->getIPAddress(), 'user_agent' => substr((string) $this->request->getUserAgent(), 0, 500), 'created_at' => date('Y-m-d H:i:s')]);
        AccessControl::logout();
        return redirect()->to('/login')->with('message', 'Anda telah logout.');
    }

    public function setup()
    {
        if ($this->users->countAllResults() > 0) return redirect()->to('/login');
        return view('auth/setup', ['title' => 'Setup Administrator']);
    }

    public function createFirstAdmin()
    {
        if ($this->users->countAllResults() > 0) return redirect()->to('/login');
        $data = $this->request->getPost(['username', 'email', 'full_name']);
        $password = (string) $this->request->getPost('password');
        $rules = ['username' => 'required|alpha_numeric_punct|min_length[4]|max_length[80]', 'email' => 'required|valid_email|max_length[160]', 'full_name' => 'required|max_length[160]', 'password' => 'required|min_length[12]|max_length[72]'];
        if (! $this->validateData(array_merge($data, ['password' => $password]), $rules)) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        $this->users->insert(array_merge($data, ['password_hash' => password_hash($password, PASSWORD_DEFAULT), 'role' => 'superadmin', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]));
        return redirect()->to('/login')->with('message', 'Superadmin berhasil dibuat. Silakan login.');
    }

    public function password()
    {
        return view('auth/password', ['title' => 'Ganti Password']);
    }

    public function updatePassword()
    {
        $current = (string) $this->request->getPost('current_password');
        $password = (string) $this->request->getPost('password');
        $confirmation = (string) $this->request->getPost('password_confirmation');
        $user = $this->users->find((int) auth_user('id'));
        if (! $user || ! password_verify($current, $user['password_hash'])) return redirect()->back()->with('error', 'Password saat ini salah.');
        if (strlen($password) < 12 || strlen($password) > 72 || $password !== $confirmation) return redirect()->back()->with('error', 'Password baru minimal 12 karakter dan konfirmasi harus sama.');
        $this->users->update($user['id'], ['password_hash' => password_hash($password, PASSWORD_DEFAULT)]);
        AccessControl::logout();
        return redirect()->to('/login')->with('message', 'Password berhasil diubah. Silakan login kembali.');
    }
}
