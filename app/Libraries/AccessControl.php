<?php

namespace App\Libraries;

use App\Models\UserModel;

class AccessControl
{
    public static function user(): ?array
    {
        $session = session();
        $user = $session->get('auth_user');
        return is_array($user) ? $user : null;
    }

    public static function isLoggedIn(): bool
    {
        return self::user() !== null;
    }

    public static function can(string $permission): bool
    {
        $user = self::user();
        if (! $user) return false;
        if (($user['role'] ?? '') === 'superadmin') return true;
        return in_array($permission, $user['permissions'] ?? [], true);
    }

    public static function refreshSession(array $user): void
    {
        $permissions = (new UserModel())->permissions((int) $user['id']);
        session()->set('auth_user', [
            'id' => (int) $user['id'], 'username' => $user['username'], 'email' => $user['email'],
            'full_name' => $user['full_name'], 'role' => $user['role'], 'avatar_path' => $user['avatar_path'] ?? null,
            'permissions' => array_column($permissions, 'permission_key'),
        ]);
        session()->set('username', $user['username']);
    }

    public static function logout(): void
    {
        session()->remove(['auth_user', 'username']);
        session()->regenerate(true);
    }
}
