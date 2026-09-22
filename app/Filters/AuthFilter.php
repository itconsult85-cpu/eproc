<?php
namespace App\Filters;
use App\Libraries\AccessControl;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! AccessControl::isLoggedIn()) return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        if (! AccessControl::can('dashboard.view')) return redirect()->to('/login')->with('error', 'Akun belum memiliki akses aplikasi.');
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): void {}
}
