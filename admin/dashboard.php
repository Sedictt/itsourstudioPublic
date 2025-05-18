<?php
session_start();
require_once '../config.php';
require_once '../includes/db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle 'Mark as Completed' action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'markAsCompleted') {
    $bookingId = intval($_POST['bookingId']);

    // Update the booking status to 'completed'
    $stmt = $pdo->prepare("UPDATE bookings SET status = 'completed' WHERE id = ?");
    $stmt->execute([$bookingId]);

    // Redirect back to the dashboard
    header('Location: dashboard.php');
    exit;
}

// Check if a filter is applied via query string
$filterStatus = isset($_GET['status']) ? $_GET['status'] : null;
$sortOrder = isset($_GET['sort']) && in_array(strtolower($_GET['sort']), ['asc', 'desc']) ? strtoupper($_GET['sort']) : 'DESC';

// Modify the query to include filtering and sorting if a status is provided
$query = "SELECT b.*, u.name, u.email FROM bookings b JOIN users u ON b.user_id = u.id";
if ($filterStatus) {
    $query .= " WHERE b.status = :status";
}
$query .= " ORDER BY b.created_at $sortOrder";

$stmt = $pdo->prepare($query);
if ($filterStatus) {
    $stmt->bindParam(':status', $filterStatus);
}
$stmt->execute();
$bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo BUSINESS_NAME; ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/animations.css">
    <link
        href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&family=Quicksand:wght@300..700&display=swap"
        rel="stylesheet">
    <style>
        .booking-filters {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .booking-filters label {
            font-size: 14px;
            font-weight: bold;
            color: #495057;
        }

        .booking-filters .form-control {
            padding: 5px 10px;
            border: 1px solid #ced4da;
            border-radius: 3px;
            font-size: 12px;
            width: auto;
            transition: border-color 0.3s ease;
        }

        .booking-filters .form-control:focus {
            border-color: #80bdff;
            outline: none;
            box-shadow: 0 0 3px rgba(0, 123, 255, 0.5);
        }

        .booking-filters select {
            background-color: #fff;
            color: #495057;
            cursor: pointer;
        }

        .booking-table table tr {
            height: 50px; /* Set a uniform height for all rows */
        }

        .actions.compact button {
            padding: 2px 5px; /* Reduce padding for compact buttons */
            font-size: 12px; /* Smaller font size for buttons */
            margin: 0 2px; /* Reduce margin between buttons */
        }

        .actions.compact svg {
            width: 10px; /* Smaller icons */
            height: 10px;
        }
    </style>
</head>

<body class="dashboard-page">
    <div class="admin-container fade-in">
        <header class="admin-header">
            <div class="admin-title">
                <h1>Booking Management</h1>
                <p class="admin-subtitle"><?php echo BUSINESS_NAME; ?> Admin Dashboard</p>
            </div>
            <div class="admin-actions">
                <a href="index.php" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                        <line x1="9" y1="12" x2="21" y2="12"></line>
                    </svg>
                    Back to Home
                </a>
                <a href="logout.php" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    Logout
                </a>
            </div>
        </header>

        <div class="booking-filters">
            <label for="statusFilter">Status:</label>
            <select id="statusFilter" class="form-control" onchange="filterTable()">
                <option value="all">All</option>
                <option value="confirmed">Confirmed</option>
                <option value="pending">Pending</option>
                <option value="rejected">Rejected</option>
                <option value="completed">Completed</option>
            </select>

            <label for="sortBookings">Sort by:</label>
            <select id="sortBookings" class="form-control" onchange="sortTable()">
                <option value="id">ID</option>
                <option value="name">Name</option>
                <option value="date">Date</option>
                <option value="status">Status</option>
            </select>

            <label for="sortOrder">Order:</label>
            <select id="sortOrder" class="form-control" onchange="updateSortOrder()">
                <option value="desc" <?php echo $sortOrder === 'DESC' ? 'selected' : ''; ?>>Descending</option>
                <option value="asc" <?php echo $sortOrder === 'ASC' ? 'selected' : ''; ?>>Ascending</option>
            </select>
        </div>

        <div class="booking-table fade-in">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Package</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Proof of Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr class="booking-row <?php echo $booking['status']; ?>"
                            data-booking-id="<?php echo $booking['id']; ?>">
                            <td><?php echo $booking['id']; ?></td>
                            <td><?php echo htmlspecialchars($booking['name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['email']); ?></td>
                            <td><?php echo htmlspecialchars($booking['package']); ?></td>
                            <td><?php echo $booking['date']; ?></td>
                            <td><?php echo $booking['time_start']; ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $booking['status']; ?>">
                                    <?php if ($booking['status'] === 'confirmed'): ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                        </svg>
                                    <?php elseif ($booking['status'] === 'pending'): ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                    <?php elseif ($booking['status'] === 'completed'): ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="#86568a" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                        </svg>

                                    <?php else: ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="15" y1="9" x2="9" y2="15"></line>
                                            <line x1="9" y1="9" x2="15" y2="15"></line>
                                        </svg>
                                    <?php endif; ?>
                                    <?php echo ucfirst($booking['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($booking['payment_proof'])): ?>
                                    <a href="../uploads/payments/<?php echo $booking['payment_proof']; ?>" target="_blank">View
                                        Proof</a>
                                <?php endif; ?>
                            </td>
                            <td class="actions compact">
                                <?php if ($booking['status'] === 'pending'): ?>
                                    <button onclick="approveBooking(<?php echo $booking['id']; ?>)" class="btn btn-success btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                        </svg>
                                        Approve
                                    </button>
                                    <button onclick="rejectBooking(<?php echo $booking['id']; ?>)" class="btn btn-danger btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="15" y1="9" x2="9" y2="15"></line>
                                            <line x1="9" y1="9" x2="15" y2="15"></line>
                                        </svg>
                                        Reject
                                    </button>
                                <?php endif; ?>
                                <?php if ($booking['status'] === 'confirmed'): ?>
                                    <button onclick="markAsCompleted(<?php echo $booking['id']; ?>)" class="btn btn-complete btn-sm">Mark as Completed</button>
                                <?php endif; ?>
                                <button onclick="editBooking(<?php echo $booking['id']; ?>)" title="Edit booking" class="btn btn-primary btn-sm">Edit</button>
                                <?php
                                $bookingDate = new DateTime($booking['date']);
                                $today = new DateTime();
                                if ($bookingDate < $today): ?>
                                    <button onclick="deleteBooking(<?php echo $booking['id']; ?>)" title="Delete booking" class="btn btn-danger btn-sm">Delete</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../js/admin.js"></script>
    <script src="../js/ui-enhancements.js"></script>
    <script>
        function filterTable() {
            const filter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('.booking-row');

            rows.forEach(row => {
                const status = row.classList.contains(filter) || filter === 'all';
                row.style.display = status ? '' : 'none';
            });
        }

        function sortTable() {
            const table = document.querySelector('.booking-table table tbody');
            const rows = Array.from(table.querySelectorAll('tr'));
            const sortBy = document.getElementById('sortBookings').value;

            rows.sort((a, b) => {
                let valA, valB;

                switch (sortBy) {
                    case 'id':
                        valA = parseInt(a.querySelector('td:nth-child(1)').textContent);
                        valB = parseInt(b.querySelector('td:nth-child(1)').textContent);
                        break;
                    case 'name':
                        valA = a.querySelector('td:nth-child(2)').textContent.toLowerCase();
                        valB = b.querySelector('td:nth-child(2)').textContent.toLowerCase();
                        break;
                    case 'date':
                        valA = new Date(a.querySelector('td:nth-child(5)').textContent);
                        valB = new Date(b.querySelector('td:nth-child(5)').textContent);
                        break;
                    case 'status':
                        valA = a.querySelector('td:nth-child(7)').textContent.toLowerCase();
                        valB = b.querySelector('td:nth-child(7)').textContent.toLowerCase();
                        break;
                }

                if (valA < valB) return -1;
                if (valA > valB) return 1;
                return 0;
            });

            rows.forEach(row => table.appendChild(row));
        }

        function searchBookings() {
            const searchInput = document.getElementById('searchBookings').value.toLowerCase();
            const rows = document.querySelectorAll('.booking-row');

            rows.forEach(row => {
                const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const packageName = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

                if (name.includes(searchInput) || email.includes(searchInput) || packageName.includes(searchInput)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Attach the search function to the input field
        const searchInputField = document.getElementById('searchBookings');
        searchInputField.addEventListener('input', searchBookings);

        function markAsCompleted(bookingId) {
            if (confirm('Are you sure you want to mark this booking as completed?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'dashboard.php';

                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'markAsCompleted';
                form.appendChild(actionInput);

                const bookingIdInput = document.createElement('input');
                bookingIdInput.type = 'hidden';
                bookingIdInput.name = 'bookingId';
                bookingIdInput.value = bookingId;
                form.appendChild(bookingIdInput);

                document.body.appendChild(form);
                form.submit();
            }
        }

        function updateSortOrder() {
            const sortOrder = document.getElementById('sortOrder').value;
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('sort', sortOrder);
            window.location.search = urlParams.toString();
        }
    </script>
</body>

</html>