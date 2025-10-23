<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class Notifications extends BaseController
{
    /**
     * Get notifications for the current user (AJAX endpoint)
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function get()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Not authenticated'
            ])->setStatusCode(401);
        }

        $userId = session()->get('user_id');
        $notificationModel = new NotificationModel();

        $unreadCount = $notificationModel->getUnreadCount($userId);
        $notifications = $notificationModel->getNotificationsForUser($userId, 10);

        return $this->response->setJSON([
            'success' => true,
            'unread_count' => $unreadCount,
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark a notification as read (AJAX endpoint)
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function mark_as_read($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Not authenticated'
            ])->setStatusCode(401);
        }

        $notificationModel = new NotificationModel();
        
        // Verify the notification belongs to the current user
        $notification = $notificationModel->find($id);
        
        if (!$notification) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Notification not found'
            ])->setStatusCode(404);
        }

        if ($notification && $notification['user_id'] != session()->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ])->setStatusCode(403);
        }

        $result = $notificationModel->markAsRead($id);

        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to mark notification as read'
            ])->setStatusCode(500);
        }
    }

    /**
     * Delete a notification (AJAX endpoint)
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function delete($id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Not authenticated'
            ])->setStatusCode(401);
        }

        $notificationModel = new NotificationModel();
        
        // Verify the notification belongs to the current user
        $notification = $notificationModel->find($id);
        
        if (!$notification) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Notification not found'
            ])->setStatusCode(404);
        }

        if ($notification && $notification['user_id'] != session()->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized'
            ])->setStatusCode(403);
        }

        $result = $notificationModel->deleteNotification($id);

        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Notification deleted'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete notification'
            ])->setStatusCode(500);
        }
    }
}
