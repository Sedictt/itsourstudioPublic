<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');  // Change in production
define('DB_PASS', '');      // Change in production
define('DB_NAME', 'studiobooking_system');

// Database charset
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', 'utf8mb4_unicode_ci');

// Email configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'abc@gmail.com');  // Your full Gmail address
define('SMTP_PASS', 'abcd abcd abcd abcd');  // Your 16-character App Password
define('SMTP_PORT', 587);  // Gmail SMTP port

// Business configuration
define('BUSINESS_NAME', 'it\'s ouR Studio');
define('BUSINESS_EMAIL', 'abc@gmail.com');
define('GCASH_NUMBER', '09123456789');

// Time slots configuration
define('WEEKDAY_START', '10:00');
define('WEEKDAY_END', '19:00');
define('WEEKEND_START', '09:00');
define('WEEKEND_END', '20:00');

// Upload configuration
define('UPLOAD_PATH', __DIR__ . '/uploads/payments/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB

// Ensure upload directory exists
if (!file_exists(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0755, true);
}

// Package durations
define('PACKAGES', json_encode([
    'solo' => ['duration' => 15, 'price' => 299, 'name' => 'Solo Package'],
    'basic' => ['duration' => 25, 'price' => 399, 'name' => 'Basic Package'],
    'transfer' => ['duration' => 30, 'price' => 549, 'name' => 'Just Transfer'],
    'standard' => ['duration' => 45, 'price' => 699, 'name' => 'Standard Package'],
    'family' => ['duration' => 50, 'price' => 1249, 'name' => 'Family Package'],
    'barkada' => ['duration' => 50, 'price' => 1949, 'name' => 'Barkada Package'],
    'birthday' => ['duration' => 45, 'price' => 599, 'name' => 'Birthday Package']
]));
