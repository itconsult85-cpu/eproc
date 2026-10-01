<?php

namespace App\Libraries;

use App\Models\UserModel;

class AccessControl
{
    private static bool $validated = false;
    private static ?array $requestUser = null;

    public static function user(): ?array
    {
        if (self::$validated) {
            return self::$requestUser;
        }

        self::$validated = true;
        $sessionUser = session()->get('auth_user');
        if (! is_array($sessionUser) || (int) ($sessionUser['id'] ?? 0) < 1) {
            return null;
        }

        $user = (new UserModel())->find((int) $sessionUser['id']);
        if (! $user || ! (bool) ($user['is_active'] ?? false)) {
            session()->remove(['auth_user', 'username']);
            session()->regenerate(true);
            return null;
        }

        self::storeSession($user);
        return self::$requestUser;
    }

    public static function isLoggedIn(): bool
    {
        return self::user() !== null;
    }

    public static function can(string $permission): bool
    {
        $user = self::user();
        if (! $user) {
            return false;
        }

        if (($user['role'] ?? '') === 'superadmin') {
            return true;
        }

        return in_array($permission, $user['permissions'] ?? [], true);
    }

    public static function refreshSession(array $user): void
    {
        self::$validated = true;
        self::storeSession($user);
    }

    private static function storeSession(array $user): void
    {
        $permissions = (new UserModel())->permissions((int) $user['id']);
        self::$requestUser = [
            'id' => (int) $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'full_name' => $user['full_name'],
            'role' => $user['role'],
            'avatar_path' => $user['avatar_path'] ?? null,
            'permissions' => array_column($permissions, 'permission_key'),
        ];
        session()->set('auth_user', self::$requestUser);
        session()->set('username', $user['username']);
    }

    public static function logout(): void
    {
        self::$validated = true;
        self::$requestUser = null;
        session()->remove(['auth_user', 'username']);
        session()->regenerate(true);
    }
}
