<?php
// Prevent any output before headers
ob_start();

require_once '../includes/db_connect.php';
require_once '../config.php';
session_start();

// Verify we have all required data
if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['package']) || 
    !isset($_POST['date']) || !isset($_POST['selected_time'])) {
    $_SESSION['error'] = 'Missing required booking information';
    exit;
}

try {
    // Decode PACKAGES from JSON to array
    $packages = json_decode(PACKAGES, true);
    
    // Check if the package exists
    if (!isset($packages[$_POST['package']])) {
        throw new Exception("Invalid package selected: " . $_POST['package']);
    }
    
    // Get the base duration for this package
    $baseDuration = $packages[$_POST['package']]['duration'];
    
    // Store booking details in session
    $_SESSION['booking'] = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'] ?? '',
        'package' => $_POST['package'],
        'date' => $_POST['date'],
        'time' => $_POST['selected_time'],
        'extension' => isset($_POST['extension']) ? $_POST['extension'] : 0,
        'total_amount' => $_POST['total_amount'],
        'downpayment' => $_POST['downpayment'],
        'duration' => $baseDuration // Use base package duration only, not including extension
    ];
    
    // Debug information to be logged
    error_log("Booking data: Package: " . $_POST['package'] . 
              ", Base Duration: " . $baseDuration . 
              ", Extension: " . (isset($_POST['extension']) ? $_POST['extension'] : 0) . 
              ", Submitted Duration: " . ($_POST['duration'] ?? 'not set'));

    // Save user to database
    // First check if a user with this email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$_SESSION['booking']['email']]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        // User already exists, use their ID
        $userId = $existingUser['id'];
        
        // Optionally update their information
        $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
        $stmt->execute([
            $_SESSION['booking']['name'],
            $_SESSION['booking']['phone'],
            $userId
        ]);
    } else {
        // Create a new user
        $stmt = $pdo->prepare("INSERT INTO users (name, email, phone) VALUES (?, ?, ?)");
        $stmt->execute([
            $_SESSION['booking']['name'],
            $_SESSION['booking']['email'],
            $_SESSION['booking']['phone']
        ]);
        $userId = $pdo->lastInsertId();
    }

    if (!$userId) {
        throw new Exception("Failed to insert user: " . print_r($pdo->errorInfo(), true));
    }

    // Save booking to database with pending status
    $stmt = $pdo->prepare("
        INSERT INTO bookings (
            user_id, package, date, time_start, duration, extension_minutes,
            total_amount, downpayment, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    
    $stmt->execute([
        $userId,
        $_SESSION['booking']['package'],
        $_SESSION['booking']['date'],
        $_SESSION['booking']['time'],
        $_SESSION['booking']['duration'],
        $_SESSION['booking']['extension'],
        $_SESSION['booking']['total_amount'],
        $_SESSION['booking']['downpayment']
    ]);

    // Store booking ID in session
    $_SESSION['booking']['id'] = $pdo->lastInsertId();
    
    if (!$_SESSION['booking']['id']) {
        throw new Exception("Failed to insert booking: " . print_r($pdo->errorInfo(), true));
    }
    
    // Send pending email notification
    require_once '../emails/send_pending_email.php';
    $emailSent = sendPendingEmail($_SESSION['booking']);
    
    if (!$emailSent) {
        // Log the error but continue with the booking process
        error_log("Failed to send pending email to: " . $_SESSION['booking']['email']);
    } else {
        // Add a success message to the session that can be displayed on the next page
        $_SESSION['email_sent'] = true;
    }
    
    // Clear any buffered output before redirecting
    ob_end_clean();

    // Redirect to payment confirmation page
    header('Location: confirm_payment.php');
    exit;
    
} catch (Exception $e) {
    $_SESSION['error'] = 'Error saving booking: ' . $e->getMessage();
    exit;
}
