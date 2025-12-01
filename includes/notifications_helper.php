<?php
// Helper function to create notifications
function createNotification($pdo, $recipientId, $message, $type = 'system') {
    try {
        $stmt = $pdo->prepare('INSERT INTO notifications (recipient_id, message, type, is_read, created_at) VALUES (:rid, :msg, :type, 0, NOW())');
        $stmt->execute([
            'rid' => $recipientId,
            'msg' => $message,
            'type' => $type
        ]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Helper to notify all admins
function notifyAdmins($pdo, $message, $type = 'system') {
    try {
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE role = 'admin'");
        $stmt->execute();
        $admins = $stmt->fetchAll();
        foreach ($admins as $admin) {
            createNotification($pdo, $admin['user_id'], $message, $type);
        }
        return true;
    } catch (Exception $e) {
        return false;
    }
}
