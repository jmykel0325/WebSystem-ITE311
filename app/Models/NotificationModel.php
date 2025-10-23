<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['user_id', 'message', 'link', 'is_read', 'created_at'];
    protected $useTimestamps = false;

    /**
     * Get count of unread notifications for a user
     *
     * @param int $userId
     * @return int
     */
    public function getUnreadCount(int $userId): int
    {
        return $this->where('user_id', $userId)
                    ->where('is_read', 0)
                    ->countAllResults();
    }

    /**
     * Get latest notifications for a user
     *
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getNotificationsForUser(int $userId, int $limit = 10): array
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Mark a notification as read
     *
     * @param int $notificationId
     * @return bool
     */
    public function markAsRead(int $notificationId): bool
    {
        return $this->update($notificationId, ['is_read' => 1]);
    }

    /**
     * Create a new notification
     *
     * @param int $userId
     * @param string $message
     * @param string|null $link
     * @return int|bool
     */
    public function createNotification(int $userId, string $message, ?string $link = null)
    {
        return $this->insert([
            'user_id' => $userId,
            'message' => $message,
            'link' => $link,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Delete a notification
     *
     * @param int $notificationId
     * @return bool
     */
    public function deleteNotification(int $notificationId): bool
    {
        return $this->delete($notificationId);
    }
}
