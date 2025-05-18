<?php
require_once '../includes/db_connect.php';
require_once '../config.php';

// Start a session if not already started
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>it's ouR Studio</title>

    <!-- Link to CSS for styling -->
    <link rel="stylesheet" href="../css/style.css">

    <link rel="apple-touch-icon" sizes="180x180" href="../assets/images/logo/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/images/logo/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/logo/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=League+Spartan:wght@100..900&family=Quicksand:wght@300..700&display=swap"
        rel="stylesheet">

    <script src="../js/sidebar.js" defer></script>
    <script src="../js/script.js" defer></script>

</head>

<body class="booking-page darknav">
    <button id="open-sidebar-btn" onclick="openSidebar()">
    <img src="../assets/images/icons/menus.png" alt="">
</button>

<button id="logo-btn" onclick="window.location.href='index.php'">
    <img src="../assets/images/logo/LOGO_var1.png" alt="">
</button>

<button id="booking-btn" onclick="window.location.href='booking/index.php'">
    <img src="../assets/images/icons/reservation.png" alt="">
</button>

<nav class="navbar" id="navbar">
    <ul class="nav-links">
        <li><button id="close-sidebar-btn" onclick="closeSidebar()"><svg xmlns="http://www.w3.org/2000/svg"
                    height="32px" viewBox="0 -960 960 960" width="32px" fill="#fff4e6">
                    <path
                        d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
                </svg></button></li>
        <li class="logo logo-desktop"><a href="../index.php">
                <img src="../assets/images/logo/LOGO_var1.png" alt="">
            </a></li>
            <li class="logo logo-mobile"><a href="index.php">
                <img src="../assets/images/logo/LOGO_var3.png" alt="">
            </a></li>
        <li><a href="../index.php">HOME</a></li>
        <li><a href="../about.php">ABOUT US</a></li>
        <li><a href="../services.php">SERVICES</a></li>
        <li><a href="../gallery.php">GALLERY</a></li>
        <li><a href="../contact.php">CONTACTS</a></li>
        <li class="cta"><a href="../booking/index.php">BOOK NOW</a></li>
    </ul>
</nav>
    
    <div class="book-form-container">
        <div class="page-header fade-in">
            <h1>Book Your Photography Session</h1>
            <p class="tagline">Capture your special moments with <span
                    class="highlight"><?php echo BUSINESS_NAME; ?></span></p>
        </div>

        <form class="booking-form fade-in" id="bookingForm" action="save_booking.php" method="POST">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message animate-shake">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span><?php echo $_SESSION['error'];
                    unset($_SESSION['error']); ?></span>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="name"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg> Full Name *</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg> Email Address *</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="phone"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                        </path>
                    </svg> Phone Number *</label>
                <input type="tel" id="phone" name="phone" class="form-control" required>
            </div>

            <h2 class="form-subheader">Choose Your Package</h2>
            <div class="form-group">
                <label for="package">Choose Your Package</label>
                <div id="package-thumbnails" class="thumbnail-grid">
                    <div class="thumbnail" data-value="solo">
                        <img src="../assets/images/packages/solo.jpg" alt="Solo Package">
                        <h3>Solo Package</h3>
                        <p>₱299</p>
                    </div>
                    <div class="thumbnail" data-value="basic">
                        <img src="../assets/images/packages/basic.jpg" alt="Basic Package">
                        <h3>Basic Package</h3>
                        <p>₱399</p>
                    </div>
                    <div class="thumbnail" data-value="transfer">
                        <img src="../assets/images/packages/just-transfer.jpg" alt="Just Transfer">
                        <h3>Just Transfer</h3>
                        <p>₱549</p>
                    </div>
                    <div class="thumbnail" data-value="standard">
                        <img src="../assets/images/packages/standard.jpg" alt="Standard Package">
                        <h3>Standard Package</h3>
                        <p>₱699</p>
                    </div>
                    <div class="thumbnail" data-value="family">
                        <img src="../assets/images/packages/family.jpg" alt="Family Package">
                        <h3>Family Package</h3>
                        <p>₱1249</p>
                    </div>
                    <div class="thumbnail" data-value="barkada">
                        <img src="../assets/images/packages/barkada.jpg" alt="Barkada Package">
                        <h3>Barkada Package</h3>
                        <p>₱1949</p>
                    </div>
                    <div class="thumbnail" data-value="birthday">
                        <img src="../assets/images/packages/birthday.jpg" alt="Birthday Package">
                        <h3>Birthday Package</h3>
                        <p>₱599</p>
                    </div>
                </div>
                <input type="hidden" id="package" name="package" required>
            </div>

            <div class="form-group">
                <label for="date"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg> Select Date *</label>
                <input type="date" 
                    id="date" 
                    name="date" 
                    class="form-control" 
                    required
                    min="<?php echo date('Y-m-d'); ?>"
                    max="<?php echo date('Y-m-d', strtotime('+30 days')); ?>"
                    data-today="<?php echo date('Y-m-d'); ?>">
            </div>

            <div id="timeSlotContainer">
                <!-- Time slots will be populated by JavaScript -->
            </div>

            <div class="form-group">
                <label for="extension"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg> Extend Session (₱150 per 15 minutes)</label>
                <select id="extension" name="extension" class="form-control">
                    <option value="0">No Extension</option>
                    <option value="15">+15 minutes (₱150)</option>
                    <option value="30">+30 minutes (₱300)</option>
                    <option value="45">+45 minutes (₱450)</option>
                    <option value="60">+60 minutes (₱600)</option>
                </select>
            </div>

            <div class="price-summary reveal">
                <h3><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg> Price Summary</h3>
                <div class="price-item">
                    <span class="price-label">Base Price:</span>
                    <span class="price-value" id="basePrice">₱0</span>
                </div>
                <div class="price-item">
                    <span class="price-label">Extension Fee:</span>
                    <span class="price-value" id="extensionFee">₱0</span>
                </div>
                <div class="price-item total">
                    <span class="price-label">Total Amount:</span>
                    <span class="price-value" id="totalAmount">₱0</span>
                </div>
                <div class="price-item downpayment">
                    <span class="price-label">Required Downpayment (50%):</span>
                    <span class="price-value" id="downpayment">₱0</span>
                </div>
            </div>

            <input type="hidden" name="total_amount" id="total_amount_input" value="0">
            <input type="hidden" name="downpayment" id="downpayment_input" value="0">
            <input type="hidden" name="selected_time" id="selected_time" value="">
            <input type="hidden" name="selected_date" id="selected_date" value="">
            <input type="hidden" name="duration" id="duration" value="">

            <button type="submit" class="btn btn-primary btn-action payment-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
                Proceed to Payment
            </button>

            <!-- Add a simple form validation message area -->
            <div id="formValidationMessage" style="color: red; margin-top: 10px;"></div>
        </form>
    </div>

    <script src="../js/script.js"></script>
    <script src="../js/ui-enhancements.js"></script>

    <script>
        // Add form validation before submission
        document.getElementById('bookingForm').addEventListener('submit', function (e) {
            const form = this;
            const validationMessage = document.getElementById('formValidationMessage');
            const selectedPackage = document.getElementById('package').value;
            const extension = parseInt(document.getElementById('extension').value || 0);
            
            // Log debug information
            console.log('Form submission:');
            console.log('Package:', selectedPackage);
            console.log('Extension:', extension);
            console.log('Duration input value:', document.getElementById('duration').value);
            
            // Check if all required fields have values
            if (!form.name.value || !form.email.value || !form.package.value ||
                !form.date.value || !document.getElementById('selected_time').value) {
                e.preventDefault(); // Prevent form submission
                validationMessage.textContent = 'Please fill in all required fields and select a time slot.';

                // Highlight the missing fields
                if (!form.name.value) form.name.style.borderColor = 'red';
                if (!form.email.value) form.email.style.borderColor = 'red';
                if (!form.package.value) form.package.style.borderColor = 'red';
                if (!form.date.value) form.date.style.borderColor = 'red';
                if (!document.getElementById('selected_time').value)
                    validationMessage.textContent += ' Please select a time slot.';
                return false;
            }
            
            // Make sure duration is properly set with the base package duration only
            if (PACKAGES[selectedPackage]) {
                const baseDuration = PACKAGES[selectedPackage].duration;
                document.getElementById('duration').value = baseDuration;
                console.log('Setting base duration to:', baseDuration);
            }
        });
    </script>
</body>

</html>
