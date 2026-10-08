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

if (! function_exists('allowed_company_ids')) {
    function allowed_company_ids(): ?array
    {
        return AccessControl::companyIds();
    }
}

if (! function_exists('can_access_company')) {
    function can_access_company(int $companyId): bool
    {
        return AccessControl::canAccessCompany($companyId);
    }
}
