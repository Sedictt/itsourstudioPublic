<?php
require_once 'config.php';
require_once 'includes/db_connect.php';

// Check if the script is being run from the command line or browser
$isCLI = (php_sapi_name() === 'cli');

try {
    // Hash the password using PHP's password_hash function
    $username = 'jv';
    $password = 'jvtillo0939';
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    // Check if the username already exists
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $existingAdmin = $stmt->fetch();
    
    if ($existingAdmin) {
        $message = "Admin user '$username' already exists!";
    } else {
        // Insert the new admin record
        $stmt = $pdo->prepare("INSERT INTO admins (username, password_hash) VALUES (?, ?)");
        $stmt->execute([$username, $passwordHash]);
        
        $message = "Admin user '$username' created successfully!";
    }
    
    // Output message based on context (CLI or browser)
    if ($isCLI) {
        echo $message . PHP_EOL;
    } else {
        echo "<p>$message</p>";
        echo "<p><a href='admin/login.php'>Go to login page</a></p>";
    }
    
} catch (Exception $e) {
    $errorMessage = "Error: " . $e->getMessage();
    
    if ($isCLI) {
        echo $errorMessage . PHP_EOL;
    } else {
        echo "<p>$errorMessage</p>";
    }
}
?>
