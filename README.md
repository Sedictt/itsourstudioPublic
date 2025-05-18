# Backend Documentation for it's ouR Studio

---

## 1. Overview

The backend is the part of your website that runs on the server. It handles things like:
- Storing and retrieving data (like bookings and users) from a database
- Processing form submissions (like booking a session)
- Sending emails (like booking confirmations)
- Authenticating admin users
- Managing business logic (like checking available time slots)

Your backend is written in **PHP** and uses a **MySQL database**. The main configuration is in `config.php`, and database connections are handled in `includes/db_connect.php`.

---

## 2. Configuration (`config.php`)

This file sets up important settings for your website:

```php
define('DB_HOST', 'localhost'); // Database server
define('DB_USER', 'root');      // Database username
define('DB_PASS', '');          // Database password
define('DB_NAME', 'studiobooking_system'); // Database name
```
- These lines tell PHP how to connect to your MySQL database.

**Other settings:**
- **Email settings**: For sending emails via Gmail SMTP.
- **Business info**: Name, email, GCash number.
- **Time slots**: Defines opening/closing hours for weekdays and weekends.
- **Upload settings**: Where payment proof images are stored.
- **Packages**: Lists all available packages, their durations, and prices.

---

## 3. Database Connection (`includes/db_connect.php`)

This file connects your PHP code to the MySQL database:

```php
$dsn = sprintf("mysql:host=%s;dbname=%s;charset=%s", DB_HOST, DB_NAME, DB_CHARSET);
$pdo = new PDO($dsn, DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
```
- **PDO** is a PHP extension for working with databases.
- If the connection fails, it logs an error and stops the script.

---

## 4. Booking Flow

### a. Booking Form (`booking/index.php`)

- Users fill out a form to book a session.
- The form collects:
  - Name, email, phone
  - Package selection
  - Date and time slot
  - Optional session extension

**Form submission:**
- The form sends data to `save_booking.php` using POST.

### b. Saving a Booking (`booking/save_booking.php`)

- Receives form data.
- Validates the data (checks if all required fields are filled).
- Checks if the selected time slot is available.
- Saves the booking to the database (usually in a `bookings` table).
- Optionally, sends a confirmation email to the user.

**Example code:**
```php
$stmt = $pdo->prepare("INSERT INTO bookings (name, email, package, date, time_start, ...) VALUES (?, ?, ?, ?, ?, ...)");
$stmt->execute([$name, $email, $package, $date, $time_start, ...]);
```
- This code inserts a new booking into the database.

---

## 5. Checking Booked Slots (`includes/get_booked_slots.php`)

- When a user selects a date, the frontend calls this PHP file via AJAX.
- It fetches all booked and pending slots for that date from the database.
- Returns the data as JSON so the frontend can show which times are available.

**Example code:**
```php
$stmt = $pdo->prepare("SELECT time_start, duration, extension_minutes FROM bookings WHERE date = ? AND status = 'confirmed'");
$stmt->execute([$date]);
$booked = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['booked' => $booked, 'pending' => $pending]);
```
- This code gets all confirmed bookings for a specific date.

---

## 6. Admin Panel (`admin`)

- **Login (`admin/login.php`)**: Admins log in with a username and password.
- **Dashboard (`admin/index.php`)**: Shows stats, upcoming bookings, and recent activity.
- **Manage Bookings**: Admins can approve, reject, edit, or delete bookings.
- **Account Settings**: Admins can change their password and username.

**Security:**
- Admin pages check if the user is logged in using PHP sessions.
- If not logged in, the user is redirected to the login page.

---

## 7. Email Sending (`emails`)

- When a booking is made or its status changes, the backend sends an email.
- Uses Gmail SMTP settings from `config.php`.
- Example: `emails/send_confirmation_email.php` sends a confirmation to the user.

---

## 8. File Uploads

- Users may upload payment proofs.
- Files are stored in the `uploads/payments` directory.
- The backend checks file size and type for security.

---

## 9. Form Processing (`includes/process_form.php`)

- Handles contact form submissions.
- Saves inquiries to the database and sends an email notification.

---

## 10. Error Handling

- Most backend scripts use `try...catch` blocks to handle errors.
- Errors are logged, and user-friendly messages are shown.

---

## 11. Session Management

- PHP sessions are used to keep track of logged-in users (admins).
- `session_start();` is called at the top of scripts that need session data.

---

## 12. Security Best Practices

- **Prepared statements**: Prevent SQL injection.
- **Session checks**: Prevent unauthorized admin access.
- **File validation**: Prevent malicious uploads.
- **Error logging**: Helps debug issues without exposing sensitive info to users.

---

## 13. How Everything Connects

1. **User visits booking page** → Fills out form → Form data sent to backend
2. **Backend validates and saves booking** → Checks for conflicts → Stores in database
3. **Frontend fetches available slots** via AJAX → Backend returns JSON data
4. **Admin logs in** → Views and manages bookings via dashboard
5. **Emails sent** for confirmations, rejections, etc.
6. **Contact form** and other forms are processed and stored

---

## 14. Key Files and Their Roles

| File/Folder                  | Purpose                                                      |
|------------------------------|--------------------------------------------------------------|
| `config.php`                 | Main configuration (DB, email, business info, packages)      |
| `includes/db_connect.php`    | Database connection logic                                    |
| `includes/get_booked_slots.php` | Returns booked slots for a date (AJAX)                   |
| `includes/process_form.php`  | Handles contact form submissions                             |
| `booking/index.php`          | Booking form (frontend)                                      |
| `booking/save_booking.php`   | Processes and saves bookings                                 |
| `admin`                     | Admin dashboard and management tools                         |
| `emails`                    | Scripts for sending emails                                   |
| `uploads/payments`          | Stores uploaded payment proofs                               |

---

## 15. Summary

- **Frontend**: What users see and interact with.
- **Backend**: Handles data, logic, security, and communication with the database and email server.
- **Database**: Stores all persistent data (bookings, users, etc.).
- **Admin Panel**: Lets staff manage bookings and settings.

---

**If you want to understand a specific file or function in more detail, just ask!**
