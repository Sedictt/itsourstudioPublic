<?php
require_once 'config.php';
require_once 'includes/db_connect.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Simple Database Insert Test</h1>";

try {
    // Test direct insert into users table
    $name = "Test User " . date('Y-m-d H:i:s');
    $email = "test_" . time() . "@example.com";
    $phone = "123-456-7890";
    
    echo "<p>Attempting to insert user: $name, $email</p>";
    
    $stmt = $pdo->prepare("INSERT INTO users (name, email, phone) VALUES (?, ?, ?)");
    $result = $stmt->execute([$name, $email, $phone]);
    
    if ($result) {
        $userId = $pdo->lastInsertId();
        echo "<p style='color: green;'>✓ User inserted successfully with ID: $userId</p>";
        
        // Now test inserting a booking
        echo "<p>Attempting to insert booking for user ID: $userId</p>";
        
        $package = "solo";
        $date = date('Y-m-d', strtotime('+1 day'));
        $time = "14:00";
        $duration = 15;
        $extension = 0;
        $totalAmount = 299;
        $downpayment = 149.5;
        
        $stmt = $pdo->prepare("
            INSERT INTO bookings (
                user_id, package, date, time_start, duration, extension_minutes,
                total_amount, downpayment, status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')
        ");
        
        $result = $stmt->execute([
            $userId, $package, $date, $time, $duration, $extension,
            $totalAmount, $downpayment
        ]);
        
        if ($result) {
            $bookingId = $pdo->lastInsertId();
            echo "<p style='color: green;'>✓ Booking inserted successfully with ID: $bookingId</p>";
            
            // Now test updating the booking
            echo "<p>Attempting to update booking with payment proof</p>";
            
            $paymentProof = "test_payment.jpg";
            $stmt = $pdo->prepare("UPDATE bookings SET payment_proof = ? WHERE id = ?");
            $result = $stmt->execute([$paymentProof, $bookingId]);
            
            if ($result) {
                echo "<p style='color: green;'>✓ Booking updated successfully</p>";
            } else {
                echo "<p style='color: red;'>✗ Failed to update booking: " . print_r($pdo->errorInfo(), true) . "</p>";
            }
        } else {
            echo "<p style='color: red;'>✗ Failed to insert booking: " . print_r($pdo->errorInfo(), true) . "</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Failed to insert user: " . print_r($pdo->errorInfo(), true) . "</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

<p><a href="index.php">Return to homepage</a></p>
