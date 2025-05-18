<?php
session_start();
require_once '../config.php';
require_once '../includes/db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$bookingId = $_GET['id'] ?? null;
if (!$bookingId) {
    header('Location: dashboard.php');
    exit;
}

// Fetch booking details
$stmt = $pdo->prepare("
    SELECT b.*, u.name, u.email, u.phone
    FROM bookings b 
    JOIN users u ON b.user_id = u.id 
    WHERE b.id = ?
");
$stmt->execute([$bookingId]);
$booking = $stmt->fetch();

if (!$booking) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Update booking details
        $stmt = $pdo->prepare("
            UPDATE bookings 
            SET date = ?, time_start = ?, package = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $_POST['date'],
            $_POST['time'],
            $_POST['package'],
            $bookingId
        ]);

        // Update user details
        $stmt = $pdo->prepare("
            UPDATE users 
            SET name = ?, email = ?, phone = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $_POST['name'],
            $_POST['email'],
            $_POST['phone'],
            $booking['user_id']
        ]);

        header('Location: dashboard.php');
        exit;
    } catch (Exception $e) {
        $error = "Error updating booking: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking - <?php echo BUSINESS_NAME; ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/animations.css">
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <div class="page-header">
            <h1>Edit Booking</h1>
            <p class="tagline">Update the details for this booking</p>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="error-message animate-shake">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form class="booking-form fade-in" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Name
                    </label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($booking['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        Email
                    </label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($booking['email']); ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="phone">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                        Phone
                    </label>
                    <input type="tel" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($booking['phone']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="package">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        Package
                    </label>
                    <select id="package" name="package" class="form-control" required>
                        <option value="solo" <?php echo $booking['package'] === 'solo' ? 'selected' : ''; ?>>Solo Package</option>
                        <option value="basic" <?php echo $booking['package'] === 'basic' ? 'selected' : ''; ?>>Basic Package</option>
                        <option value="transfer" <?php echo $booking['package'] === 'transfer' ? 'selected' : ''; ?>>Just Transfer</option>
                        <!-- Add other packages -->
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="date">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Date
                    </label>
                    <input type="date" id="date" name="date" class="form-control" value="<?php echo $booking['date']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="time">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        Time
                    </label>
                    <input type="time" id="time" name="time" class="form-control" value="<?php echo $booking['time_start']; ?>" required>
                </div>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn btn-primary btn-action">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Save Changes
                </button>
                <a href="dashboard.php" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Cancel
                </a>
            </div>
        </form>
    </div>
    <script src="../js/ui-enhancements.js"></script>
</body>
</html>
