<?php
// Prevent any output before headers
ob_start();

require_once '../includes/db_connect.php';
require_once '../config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

try {
    // Validate uploaded file
    if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Payment proof is required.');
    }

    $file = $_FILES['payment_proof'];
    $fileSize = $file['size'];
    $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Validate file size and type
    if ($fileSize > MAX_FILE_SIZE) {
        throw new Exception('File is too large. Maximum size is 10MB.');
    }
    
    if (!in_array($fileType, ['jpg', 'jpeg', 'png'])) {
        throw new Exception('Only JPG, JPEG & PNG files are allowed.');
    }

    // Check if booking ID exists in session
    if (!isset($_SESSION['booking']['id'])) {
        throw new Exception('Booking ID not found in session.');
    }

    $fileName = uniqid('payment_') . '.' . $fileType; 
    $uploadPath = UPLOAD_PATH . $fileName;
    
    // Save file
    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        throw new Exception('Failed to upload file.');
    }

    // Update existing booking with payment proof
    $stmt = $pdo->prepare("UPDATE bookings SET payment_proof = ? WHERE id = ?");
    $stmt->execute([
        $fileName,
        $_SESSION['booking']['id']
    ]);

    echo "<p>Process completed successfully</p>";
    
    // Clear session
    unset($_SESSION['booking']);

    // Clear any buffered output before redirecting
    ob_end_clean();
    // Redirect to success page
    header('Location: success.php');
    exit;

} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: confirm_payment.php');
    exit;
}
