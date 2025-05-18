-- Create database
CREATE DATABASE IF NOT EXISTS studiobooking_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE studiobooking_system;

-- Create users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create admins table
CREATE TABLE admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL
);

-- Create bookings table
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    package VARCHAR(50) NOT NULL,
    date DATE NOT NULL,
    time_start TIME NOT NULL,
    duration INT NOT NULL, -- in minutes
    extension_minutes INT DEFAULT 0,
    total_amount DECIMAL(10,2) NOT NULL,
    downpayment DECIMAL(10,2) NOT NULL,
    payment_proof VARCHAR(255),
    status ENUM('pending', 'confirmed', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Modify bookings table to add 'completed' as a valid status
ALTER TABLE bookings MODIFY status ENUM('pending', 'confirmed', 'rejected', 'completed') DEFAULT 'pending';

-- Create indexes
CREATE INDEX idx_email ON users(email);
CREATE INDEX idx_booking_date ON bookings(date);
CREATE INDEX idx_booking_status ON bookings(status);
CREATE INDEX idx_booking_datetime ON bookings(date, time_start);

-- Insert default admin account (password: admin123)
INSERT INTO admins (username, password_hash) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Add some initial settings if needed
CREATE TABLE settings (
    setting_key VARCHAR(50) PRIMARY KEY,
    setting_value TEXT NOT NULL
);
