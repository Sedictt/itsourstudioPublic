<?php
session_start();
require_once '../config.php';
require_once '../includes/db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get admin details
$stmt = $pdo->prepare("SELECT username FROM admins WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch();

// Check for success or error messages
$success_msg = $_SESSION['success_msg'] ?? null;
$error_msg = $_SESSION['error_msg'] ?? null;

// Clear session messages after retrieving
unset($_SESSION['success_msg'], $_SESSION['error_msg']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;500;600;700&family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .settings-container {
            max-width: 600px;
            margin: 80px auto;
            padding: 30px;
            background-color: var(--card-bg);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
            border: var(--card-border);
            animation: fadeIn 0.5s ease-out;
        }
        
        .settings-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--primary-color);
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        }
        
        .settings-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .settings-icon {
            background-color: rgba(191, 106, 57, 0.1);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            color: var(--primary-color);
        }
        
        .settings-header h1 {
            font-family: 'League Spartan', Arial, sans-serif;
            margin-bottom: 5px;
            letter-spacing: -0.5px;
        }
        
        .settings-header p {
            font-family: 'Quicksand', Arial, sans-serif;
            margin-top: 0;
            font-size: 16px;
            color: var(--text-color);
            opacity: 0.8;
        }
        
        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(191, 106, 57, 0.1);
            margin-bottom: 25px;
        }
        
        .form-header h3 {
            font-family: 'League Spartan', Arial, sans-serif;
            margin: 0;
            font-weight: 600;
            letter-spacing: -0.3px;
            color: var(--text-color);
        }

        .password-toggle-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--secondary-color);
            opacity: 0.6;
            transition: opacity 0.2s ease;
        }
        
        .password-toggle:hover {
            opacity: 1;
        }
        
        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
        }

        .username-info {
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: rgba(191, 106, 57, 0.05);
            padding: 12px 15px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            font-family: 'Quicksand', Arial, sans-serif;
        }

        .username-info i {
            color: var(--primary-color);
        }

        .username-info strong {
            font-weight: 600;
        }

        .help-text {
            display: block;
            font-size: 0.85rem;
            color: #777;
            margin-top: 5px;
            margin-bottom: 0;
            font-family: 'Quicksand', Arial, sans-serif;
            font-weight: 400;
        }

        label {
            font-family: 'Quicksand', Arial, sans-serif;
            font-weight: 600;
        }

        input.form-control {
            font-family: 'Quicksand', Arial, sans-serif;
        }

        .btn {
            font-family: 'Quicksand', Arial, sans-serif;
            font-weight: 600;
        }

        .success-message, .error-message {
            font-family: 'Quicksand', Arial, sans-serif;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="settings-container">
        <?php if($success_msg): ?>
        <div class="success-message">
            <i class="fas fa-check-circle"></i>
            <?php echo $success_msg; ?>
        </div>
        <?php endif; ?>
        
        <?php if($error_msg): ?>
        <div class="error-message">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $error_msg; ?>
        </div>
        <?php endif; ?>
        
        <div class="settings-header">
            <div class="settings-icon">
                <i class="fas fa-user-cog fa-2x"></i>
            </div>
            <div>
                <h1>Account Settings</h1>
                <p>Manage your account information and password</p>
            </div>
        </div>
        
        <div class="username-info">
            <i class="fas fa-user"></i>
            <div>
                <span>Current username:</span>
                <strong><?php echo htmlspecialchars($admin['username']); ?></strong>
            </div>
        </div>
        
        <form method="POST" action="update_account.php" class="settings-form">
            <div class="form-header">
                <h3>Update Account Information</h3>
            </div>
            
            <div class="form-group">
                <label for="newUsername">New Username</label>
                <input type="text" id="newUsername" name="newUsername" class="form-control" value="<?php echo htmlspecialchars($admin['username']); ?>">
                <span class="help-text">Choose a username that will be easy for you to remember.</span>
            </div>
            
            <div class="form-group">
                <label for="currentPassword">Current Password</label>
                <div class="password-toggle-wrapper">
                    <input type="password" id="currentPassword" name="currentPassword" class="form-control" required>
                    <i class="fas fa-eye password-toggle" id="toggleCurrentPassword"></i>
                </div>
                <span class="help-text">Enter your current password to authorize changes.</span>
            </div>
            
            <div class="form-group">
                <label for="newPassword">New Password</label>
                <div class="password-toggle-wrapper">
                    <input type="password" id="newPassword" name="newPassword" class="form-control">
                    <i class="fas fa-eye password-toggle" id="toggleNewPassword"></i>
                </div>
                <span class="help-text">Leave blank if you don't want to change your password.</span>
            </div>
            
            <div class="form-footer">
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Account
                </button>
            </div>
        </form>
    </div>
    
    <script>
        // Toggle password visibility
        document.getElementById('toggleCurrentPassword').addEventListener('click', function() {
            togglePasswordVisibility('currentPassword', this);
        });
        
        document.getElementById('toggleNewPassword').addEventListener('click', function() {
            togglePasswordVisibility('newPassword', this);
        });
        
        function togglePasswordVisibility(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>