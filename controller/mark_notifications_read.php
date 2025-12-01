<?php
// Mark all notifications as read for the current user
session_start();
require_once __DIR__ . '/../db.php';

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    exit;
}

try {
    $stmt = $pdo->prepare('UPDATE notifications SET is_read = 1 WHERE recipient_id = :uid AND is_read = 0');
    $stmt->execute(['uid' => $_SESSION['user_id']]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to mark notifications as read']);
}
