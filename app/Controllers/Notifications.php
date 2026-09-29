<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class Notifications extends BaseController
{
    public function read(string $publicId)
    {
        $model = new NotificationModel();
        $id = $this->resolveId($publicId, $model);
        $userId = (int) auth_user('id');
        $notification = $model->forUserNotification($id, $userId);

        if ($notification) {
            $model->markReadForUser($id, $userId);
        }

        return redirect()->to($this->safeReturnPath((string) $this->request->getPost('return')) ?: '/');
    }

    public function readAll()
    {
        (new NotificationModel())->markAllReadForUser((int) auth_user('id'));
        return redirect()->back();
    }

    private function safeReturnPath(string $value): ?string
    {
        $value = trim($value);
        if ($value === '' || ! str_starts_with($value, '/') || str_starts_with($value, '//')) {
            return null;
        }

        return preg_match('/^[a-zA-Z0-9_\-\/?.=&%:#]+$/', $value) ? $value : null;
    }
}
