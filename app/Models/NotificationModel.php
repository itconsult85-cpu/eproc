<?php

namespace App\Models;

class NotificationModel extends BaseModel
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'user_id',
        'type',
        'title',
        'message',
        'url',
        'due_at',
        'is_read',
        'created_at'
    ];

    public function forUser(?int $userId): array
    {
        return $this->groupStart()
            ->where('user_id', $userId)
            ->orWhere('user_id', null)
            ->groupEnd()
            ->orderBy('is_read', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->findAll(20);
    }

    public function unreadCount(?int $userId): int
    {
        return $this->groupStart()
            ->where(['user_id' => $userId, 'is_read' => 0])
            ->orWhere(['user_id' => null, 'is_read' => 0])
            ->groupEnd()
            ->countAllResults();
    }

    public function forUserNotification(int $id, int $userId): ?array
    {
        return $this->where('id', $id)
            ->groupStart()
            ->where('user_id', $userId)
            ->orWhere('user_id', null)
            ->groupEnd()
            ->first();
    }

    public function markReadForUser(int $id, int $userId): bool
    {
        return $this->where('id', $id)
            ->groupStart()
            ->where('user_id', $userId)
            ->orWhere('user_id', null)
            ->groupEnd()
            ->set(['is_read' => 1])
            ->update();
    }

    public function markAllReadForUser(int $userId): bool
    {
        return $this->groupStart()
            ->where('user_id', $userId)
            ->orWhere('user_id', null)
            ->groupEnd()
            ->where('is_read', 0)
            ->set(['is_read' => 1])
            ->update();
    }
}
