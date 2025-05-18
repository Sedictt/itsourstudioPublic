<?php
require_once '../includes/db_connect.php';
require_once '../config.php';

session_start();

// Verify we have all required data
if (!isset($_SESSION['booking'])) {
    $_SESSION['error'] = 'No booking information found. Please start over.';
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation - <?php echo BUSINESS_NAME; ?></title>
   <link rel="stylesheet" href="../css/style.css"> 
    <link rel="stylesheet" href="../css/animations.css">

    <link rel="apple-touch-icon" sizes="180x180" href="../assets/images/logo/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/images/logo/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/logo/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=League+Spartan:wght@100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

    </head>
<body>
    <div class="book-form-container">
        <div class="payment-confirmation fade-in">
            <h1>Payment Details</h1>
            
            <?php if (isset($_SESSION['email_sent']) && $_SESSION['email_sent']): ?>
            <div class="success-message">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <span>A confirmation email has been sent to your email address.</span>
            </div>
            <?php unset($_SESSION['email_sent']); endif; ?>
            
            <div class="booking-summary">
                <h2><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Booking Summary</h2>
                <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
                <?php endif; ?>
                <p>Name: <?php echo htmlspecialchars($_SESSION['booking']['name']); ?></p>
                <p>Package: <?php echo htmlspecialchars($_SESSION['booking']['package']); ?></p>
                <p>Date: <?php echo htmlspecialchars($_SESSION['booking']['date']); ?></p>
                <p>Time: <?php echo htmlspecialchars($_SESSION['booking']['time']); ?></p>
                <p>Total Amount: ₱<?php echo htmlspecialchars($_SESSION['booking']['total_amount']); ?></p>
                <p>Required Downpayment: ₱<?php echo htmlspecialchars($_SESSION['booking']['downpayment']); ?></p>
            </div>

            <div class="payment-methods">
                <h2><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg> Payment Methods</h2>
                <div class="gcash-details">
                    <h3><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 9V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path><circle cx="16" cy="16" r="6"></circle></svg> GCash Payment</h3>
                    <p>GCash Number: <?php echo GCASH_NUMBER; ?></p>
                    <div class="qr-code">
                        <!-- Add QR code image here -->
                        <img src="../assets/images/gcash-qr.png" alt="GCash QR Code">
                    </div>
                </div>

                <form action="process_payment.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="payment_proof">Upload Payment Proof *</label>
                        <input type="file" id="payment_proof" name="payment_proof" 
                            accept="image/*" required class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary payment-btn">Submit Payment</button>
                </form>
            </div>
        </div>
    </div>
    <script src="../js/ui-enhancements.js"></script>
</body>
</html>
