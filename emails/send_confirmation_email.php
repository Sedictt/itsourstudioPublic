<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once '../libs/PHPMailer/src/PHPMailer.php';
require_once '../libs/PHPMailer/src/SMTP.php';
require_once '../libs/PHPMailer/src/Exception.php';

function sendConfirmationEmail($booking)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->SMTPDebug = 2; // 0 = off, 1 = client messages, 2 = client and server messages
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
        $mail->Subject = 'Booking Confirmed - ' . BUSINESS_NAME;

        // Check if extension is provided
        $extensionText = $booking['extension'] > 0 ? "<p style='color: #3b2c28; margin: 5px 0;'><strong>Additional:</strong> {$booking['extension']} minutes extension</p>" : "";

        // Email template
        $body = "

<div style='font-family: \"Quicksand\", Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #fff4e6; padding: 20px; border-radius: 10px;'>
    <h2 style='font-family: \"League Spartan\", Arial, sans-serif; color: #3b2c28; text-align: center; margin-bottom: 20px;'>Good day, {$booking['name']}!</h2>
    <p style='color: #3b2c28; text-align: center; font-size: 18px;'>Your booking with it's ouR studio has been confirmed!</p>
    
    <div style='background-color: #fff; padding: 15px; margin: 20px 0; border-radius: 5px; border-left: 4px solid #bf6a39; text-align: center;'>
        <h3 style='font-family: \"League Spartan\", Arial, sans-serif; color: #bf6a39; margin: 5px 0;'>Appointment Details</h3>
        <p style='color: #3b2c28; margin: 5px 0;'><strong>Date:</strong> {$booking['date']}</p>
        <p style='color: #3b2c28; margin: 5px 0;'><strong>Time:</strong> {$booking['time_start']}</p>
    </div>
    
    <div style='background-color: #fff; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #bf6a39;'>
        <h3 style='font-family: \"League Spartan\", Arial, sans-serif; color: #bf6a39; margin: 5px 0;'>Package Details</h3>
        <p style='color: #3b2c28; margin: 5px 0;'><strong>Package:</strong> {$booking['package']}</p>
        $extensionText
        <h3 style='font-family: \"League Spartan\", Arial, sans-serif; color: #bf6a39; margin: 15px 0 5px 0;'>Location</h3>
        <p style='color: #3b2c28; margin: 5px 0;'><strong>Address:</strong> FJ Center 15 Tongco Maysan, Valenzuela City</p>
        <p style='color: #3b2c28; margin: 5px 0;'><strong>Landmark:</strong> PLV, Cebuana, Mr. DIY, and Ever</p>
    </div>
    
    <div style='background-color: #fff; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #8b5e3b;'>
        <h3 style='font-family: \"League Spartan\", Arial, sans-serif; color: #8b5e3b; margin: 5px 0;'>Important Reminders</h3>
        <ul style='color: #3b2c28; padding-left: 20px;'>
            <li style='margin: 8px 0;'>To maximize your time, please arrive at least 15 minutes before your appointment.</li>
            <li style='margin: 8px 0;'>Your time will begin on time and cannot be adjusted as there will be another client after you.</li>
            <li style='margin: 8px 0;'>If you are late, your time will be deducted based on how many minutes you are late.</li>
            <li style='margin: 8px 0;'>If you miss your appointment and do not arrive on time, it will be considered cancelled and non-refundable.</li>
            <li style='margin: 8px 0;'>Rescheduling is allowed 5 days before your appointment. ❌</li>
            <li style='margin: 8px 0;'>Cancelling and rebooking is not allowed 1-2 days prior to your appointment.</li>
            <li style='margin: 8px 0;'>If you are bringing your furbabies, make sure they are wearing a diaper and be responsible for your own pet. 🐱🐶</li>
            <li style='margin: 8px 0;'>Ages over one will be counted as one pax.</li>
            <li style='margin: 8px 0;'>Any damages that occur within the studio will be covered by the previous client. Please use the equipment with caution.</li>
            <li style='margin: 8px 0;'>You are welcome to bring your own props! Hazardous substances, explosives, and other items that might damage the studio won't be permitted.</li>
        </ul>
    </div>
    
    <p style='color: #3b2c28; text-align: center; margin-top: 20px;'>If you have any questions or concerns, please don't hesitate to inform us!</p>
    <p style='color: #3b2c28; text-align: center; font-weight: bold;'>Thank you for choosing it's ouR Studio!</p>
    <p style='color: #8b5e3b; text-align: center; font-size: 14px;'>We look forward to capturing your special moments.</p>
    <p style='color: #bf6a39; text-align: center; font-size: 16px; font-weight: bold;'>See you soon! 😊🥰</p>
</div>        ";

        $mail->Body = $body;
        $mail->send();

        return true;
    } catch (\Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
        return false;
    }
}
