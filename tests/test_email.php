<?php
require_once 'config.php';
require_once 'libs/PHPMailer/src/PHPMailer.php';
require_once 'libs/PHPMailer/src/SMTP.php';
require_once 'libs/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// This script is for testing email functionality only
// Remove or protect this file in production

// Check if the script is being run from the command line or browser
$isCLI = (php_sapi_name() === 'cli');

try {
    $mail = new PHPMailer(true);

    // Server settings
    $mail->SMTPDebug = $isCLI ? 2 : 1; // More verbose output for CLI
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = SMTP_PORT;

    // Recipients
    $mail->setFrom(SMTP_USER, BUSINESS_NAME);
    $mail->addAddress(SMTP_USER); // Send to self for testing

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Email Test from ' . BUSINESS_NAME;
    $mail->Body = '<h1>This is a test email</h1><p>If you can see this, your email configuration is working correctly!</p>';
    $mail->AltBody = 'This is a test email. If you can see this, your email configuration is working correctly!';

    $mail->send();
    
    $message = "Test email sent successfully!";
    
} catch (Exception $e) {
    $message = "Error sending test email: " . $mail->ErrorInfo;
}

// Output message based on context (CLI or browser)
if ($isCLI) {
    echo $message . PHP_EOL;
} else {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Email Test</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
            .success { color: green; }
            .error { color: red; }
        </style>
    </head>
    <body>
        <h1>Email Configuration Test</h1>
        <p class=\"" . (strpos($message, 'Error') === false ? 'success' : 'error') . "\">
            $message
        </p>
        <p><a href=\"index.php\">Return to homepage</a></p>
    </body>
    </html>";
}
