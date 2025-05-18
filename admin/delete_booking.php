<?php
session_start();
require_once '../config.php';
require_once '../includes/db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

// Check if booking ID was provided
if (!isset($_POST['booking_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Booking ID is required']);
    exit;
}

$bookingId = intval($_POST['booking_id']);

// Validate booking ID
if ($bookingId <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid booking ID']);
    exit;
}

try {
    // Start transaction
    $pdo->beginTransaction();

    // Get booking details first (for payment proof deletion)
    $stmt = $pdo->prepare("SELECT payment_proof, date FROM bookings WHERE id = ?");
    $stmt->execute([$bookingId]);
    $booking = $stmt->fetch();

    if (!$booking) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Booking not found']);
        exit;
    }

    // Check if booking is in the past
    $bookingDate = new DateTime($booking['date']);
    $today = new DateTime();
    
    if ($bookingDate > $today) {
        throw new Exception('Cannot delete future bookings');
    }
    
    // Check if booking is already deleted
    if (!$booking) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Booking not found']);
        exit;
    }

    // Delete payment proof file if it exists
    if (!empty($booking['payment_proof'])) {
        $filepath = "../uploads/payments/" . $booking['payment_proof'];
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }

    // Delete the booking
    $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->execute([$bookingId]);

    // Check if deletion was successful
    if ($stmt->rowCount() === 0) {
        throw new Exception('Failed to delete booking');
    }

    // Commit transaction
    $pdo->commit();

    header('Content-Type: application/json');
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    // Rollback transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
