<?php
require_once 'db_connect.php';

header('Content-Type: application/json');

$date = $_GET['date'] ?? date('Y-m-d');

try {
    // Get booked slots
    $stmt = $pdo->prepare("
        SELECT time_start, duration, extension_minutes 
        FROM bookings 
        WHERE date = ? AND status = 'confirmed'
    ");
    $stmt->execute([$date]);
    $booked = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Log bookings data for debugging
    error_log("Booked slots for $date: " . json_encode($booked));
    
    // Ensure extension_minutes is never null
    foreach ($booked as &$slot) {
        if ($slot['extension_minutes'] === null) {
            $slot['extension_minutes'] = 0;
        }
    }

    // Get pending slots
    $stmt = $pdo->prepare("
        SELECT time_start, duration, extension_minutes 
        FROM bookings 
        WHERE date = ? AND status = 'pending'
    ");
    $stmt->execute([$date]);
    $pending = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Log pending data for debugging
    error_log("Pending slots for $date: " . json_encode($pending));
    
    // Ensure extension_minutes is never null
    foreach ($pending as &$slot) {
        if ($slot['extension_minutes'] === null) {
            $slot['extension_minutes'] = 0;
        }
    }

    echo json_encode([
        'booked' => $booked,
        'pending' => $pending
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
