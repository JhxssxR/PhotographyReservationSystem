<?php
// Controller to clear all reservations (admin only)
session_start();
require_once __DIR__ . '/../db.php';

// Check if user is logged in and is admin
if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Start transaction
        $pdo->beginTransaction();
        
        // Delete all payments first (foreign key constraint)
        $pdo->exec("DELETE FROM payments");
        
        // Delete all reservations
        $pdo->exec("DELETE FROM reservations");
        
        // Commit transaction
        $pdo->commit();
        
        $_SESSION['admin_success'] = 'All reservations have been cleared successfully.';
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['admin_error'] = 'Failed to clear reservations: ' . $e->getMessage();
    }
}

header('Location: ../adminpage/admin_dashboard.php');
exit;
