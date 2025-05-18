<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once '../libs/PHPMailer/src/PHPMailer.php';
require_once '../libs/PHPMailer/src/SMTP.php';
require_once '../libs/PHPMailer/src/Exception.php';

function sendPendingEmail($booking) {
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
        $mail->Subject = 'Booking Confirmation - ' . BUSINESS_NAME;

        // Email template
        $body = "
        <div style='font-family: \"Quicksand\", Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #fff4e6; padding: 20px; border-radius: 10px;'>
            <h2 style='font-family: \"League Spartan\", Arial, sans-serif; color: #3b2c28; text-align: center; margin-bottom: 20px;'>Thank you for choosing it's ouR Studio!</h2>
            
            <p style='color: #3b2c28; line-height: 1.6;'>To confirm your booking, a 50% down payment is required. After your studio session, the remaining amount must be paid.</p>
            
            <div style='background-color: #fff; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #bf6a39;'>
                <p style='color: #3b2c28; margin: 5px 0;'><strong style='color: #bf6a39;'>{$booking['package']}</strong>: ₱{$booking['total_amount']}</p>
                <p style='color: #3b2c28; margin: 5px 0;'><strong style='color: #bf6a39;'>Down payment</strong>: ₱{$booking['downpayment']}</p>
            </div>
            
            <p style='color: #3b2c28; line-height: 1.6;'>If you haven't made your down payment yet, please send your payment to the following account:</p>
            
            <div style='background-color: #fff; padding: 15px; margin: 15px 0; border-radius: 5px; text-align: center; border-left: 4px solid #bf6a39;'>
                <p style='color: #3b2c28; margin: 5px 0;'><strong style='color: #bf6a39;'>GCASH</strong><br>
                Reggie L. - " . GCASH_NUMBER . "</p>
                
                <div style='margin: 15px auto; max-width: 200px;'>
                    <img src='cid:gcash-qr' alt='GCash QR Code' style='width: 100%; height: auto; border-radius: 5px;'>
                </div>
            </div>
            
            <p style='color: #3b2c28; line-height: 1.6;'>Once done, kindly reply to this email with your proof of payment for validation.</p>
            
            <div style='background-color: #fff; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #8b5e3b;'>
                <p style='color: #3b2c28; margin: 5px 0;'><strong style='color: #8b5e3b;'>Please Note!</strong></p>
                <ul style='color: #3b2c28; padding-left: 20px;'>
                    <li style='margin: 5px 0;'>To confirm your booking, kindly send the down payment until 11:59 pm TONIGHT.</li>
                    <li style='margin: 5px 0;'>Send the proof of payment to validate.</li>
                    <li style='margin: 5px 0;'>If you cancel or reschedule 1-2 days prior, it will be non-refundable.</li>
                </ul>
            </div>
            
            <p style='color: #3b2c28; text-align: center; margin-top: 20px;'>Thank you for choosing it's ouR Studio!</p>
            <p style='color: #8b5e3b; text-align: center; font-size: 12px;'>We can't wait to capture your special moments.</p>
        </div>
        ";
        
        $mail->Body = $body;
        
        // Add the GCash QR code as an embedded image
        $mail->addEmbeddedImage('../assets/images/gcash-qr.png', 'gcash-qr', 'gcash-qr.png');
        
        $mail->send();
        
        return true;
    } catch (\Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
        return false;
    }
}
