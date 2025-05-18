<?php
// Prevent any output before headers
ob_start();

session_start();
require_once '../config.php';
require_once '../includes/db_connect.php';
require_once '../emails/send_confirmation_email.php';

if (!isset($_SESSION['admin_id']) || !isset($_POST['booking_id'])) {
    http_response_code(403);
    exit('Unauthorized');
}

try {
    $bookingId = $_POST['booking_id'];
    
    // Update booking status
    $stmt = $pdo->prepare("UPDATE bookings SET status = 'confirmed' WHERE id = ?");
    $stmt->execute([$bookingId]);
    
    // Get booking details for email
    $stmt = $pdo->prepare("
        SELECT b.*, u.name, u.email 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        WHERE b.id = ?
    ");
    $stmt->execute([$bookingId]);
    $booking = $stmt->fetch();
    
    // Send confirmation email
    if ($booking) {
        $emailSent = sendConfirmationEmail($booking);
    }
    
    // Clear any buffered output before sending JSON response
    ob_end_clean();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
