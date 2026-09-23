<?php

namespace App\Filters;

use App\Libraries\AccessControl;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class PermissionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $permission = $arguments[0] ?? null;
        if (! $permission || ! AccessControl::can($permission)) return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): void {}
}