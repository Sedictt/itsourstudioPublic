<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once '../libs/PHPMailer/src/PHPMailer.php';
require_once '../libs/PHPMailer/src/SMTP.php';
require_once '../libs/PHPMailer/src/Exception.php';

function sendRejectionEmail($booking, $reason) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->SMTPDebug = 0; // 0 = off, 1 = client messages, 2 = client and server messages
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;

        // Recipients
        $mail->setFrom(BUSINESS_EMAIL, BUSINESS_NAME);
        $mail->addAddress($booking['email'], $booking['name']);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Booking Rejection - ' . BUSINESS_NAME;
        
        // Email template
        $body = "
        <div style='font-family: \"Quicksand\", Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #fff4e6; padding: 20px; border-radius: 10px;'>
            <h2 style='font-family: \"League Spartan\", Arial, sans-serif; color: #3b2c28; text-align: center; margin-bottom: 20px;'>Booking Update</h2>
            
            <p style='color: #3b2c28; line-height: 1.6;'>Dear {$booking['name']},</p>
            
            <p style='color: #3b2c28; line-height: 1.6;'>We regret to inform you that your booking has been rejected.</p>
            
            <div style='background-color: #fff; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #bf6a39;'>
                <p style='color: #3b2c28; margin: 5px 0;'><strong style='color: #bf6a39;'>Reason:</strong> {$reason}</p>
                
                <h3 style='font-family: \"League Spartan\", Arial, sans-serif; color: #bf6a39; margin: 15px 0 5px 0;'>Booking Details</h3>
                <ul style='color: #3b2c28; padding-left: 20px;'>
                    <li style='margin: 5px 0;'><strong>Package:</strong> {$booking['package']}</li>
                    <li style='margin: 5px 0;'><strong>Date:</strong> {$booking['date']}</li>
                    <li style='margin: 5px 0;'><strong>Time:</strong> {$booking['time_start']}</li>
                </ul>
            </div>
            
            <p style='color: #3b2c28; line-height: 1.6;'>If you have any questions, please contact us at " . BUSINESS_EMAIL . "</p>
            
            <p style='color: #3b2c28; text-align: center; margin-top: 20px;'>We hope to serve you in the future.</p>
            <p style='color: #8b5e3b; text-align: center; font-size: 12px;'>Thank you for considering it's ouR Studio.</p>
        </div>
        ";

        $mail->Body = $body;
        $mail->send();
        
        return true;
    } catch (\Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
        return false;
    }
}
