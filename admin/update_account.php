<?php
session_start();
require_once '../config.php';
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = $_POST['newUsername'];
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];

    // Fetch the current admin details
    $stmt = $pdo->prepare("SELECT password_hash FROM admins WHERE id = ?");
    $stmt->execute([$_SESSION['admin_id']]);
    $admin = $stmt->fetch();

    if (!$admin || !password_verify($currentPassword, $admin['password_hash'])) {
        $_SESSION['error_msg'] = 'Current password is incorrect.';
        header('Location: account_settings.php');
        exit;
    }

    // Update username
    if (!empty($newUsername)) {
        $stmt = $pdo->prepare("UPDATE admins SET username = ? WHERE id = ?");
        $stmt->execute([$newUsername, $_SESSION['admin_id']]);
    }

    // Update password if provided
    if (!empty($newPassword)) {
        $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE admins SET password_hash = ? WHERE id = ?");
        $stmt->execute([$newPasswordHash, $_SESSION['admin_id']]);
    }

    $_SESSION['success_msg'] = 'Account updated successfully!';
    header('Location: account_settings.php');
    exit;
}

header('Location: index.php');
exit;

