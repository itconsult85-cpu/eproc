<?php

use App\Libraries\AccessControl;

if (! function_exists('can')) {
    function can(string $permission): bool
    {
        return AccessControl::can($permission);
    }
}

if (! function_exists('auth_user')) {
    function auth_user(?string $key = null)
    {
        $user = AccessControl::user() ?? [];
        return $key === null ? $user : ($user[$key] ?? null);
    }
}