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

// Get booking statistics
$stmt = $pdo->query("
    SELECT 
        COUNT(*) as total_bookings,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_bookings,
        SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_bookings,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_bookings
    FROM bookings
");
$stats = $stmt->fetch();

// Get upcoming bookings (next 7 days)
$today = date('Y-m-d');
$nextWeek = date('Y-m-d', strtotime('+7 days'));

$stmt = $pdo->prepare("
    SELECT b.*, u.name, u.email 
    FROM bookings b 
    JOIN users u ON b.user_id = u.id 
    WHERE b.date BETWEEN ? AND ? AND b.status = 'confirmed'
    ORDER BY b.date ASC, b.time_start ASC
    LIMIT 5
");
$stmt->execute([$today, $nextWeek]);
$upcomingBookings = $stmt->fetchAll();

// Get today's bookings
$stmt = $pdo->prepare("
    SELECT b.*, u.name, u.email 
    FROM bookings b 
    JOIN users u ON b.user_id = u.id 
    WHERE b.date = ? AND b.status = 'confirmed'
    ORDER BY b.time_start ASC
");
$stmt->execute([$today]);
$todayBookings = $stmt->fetchAll();

// Get recent activity (last 10 bookings with any status)
$stmt = $pdo->query("
    SELECT b.id, b.date, b.time_start, b.status, b.created_at, u.name, b.package
    FROM bookings b 
    JOIN users u ON b.user_id = u.id 
    ORDER BY b.created_at DESC
    LIMIT 10
");
$recentActivity = $stmt->fetchAll();

// Get package distribution
$stmt = $pdo->query("
    SELECT package, COUNT(*) as count
    FROM bookings
    WHERE status = 'confirmed'
    GROUP BY package
    ORDER BY count DESC
");
$packageDistribution = $stmt->fetchAll();

// Format data for charts
$packageLabels = [];
$packageData = [];
foreach ($packageDistribution as $pkg) {
    $packageLabels[] = ucfirst($pkg['package']);
    $packageData[] = $pkg['count'];
}

// Get next month's bookings count by day
$firstDayNextMonth = date('Y-m-01', strtotime('+1 month'));
$lastDayNextMonth = date('Y-m-t', strtotime('+1 month'));

$stmt = $pdo->prepare("
    SELECT DATE_FORMAT(date, '%d') as day, COUNT(*) as count
    FROM bookings
    WHERE date BETWEEN ? AND ? AND status = 'confirmed'
    GROUP BY DATE_FORMAT(date, '%d')
    ORDER BY date ASC
");
$stmt->execute([$firstDayNextMonth, $lastDayNextMonth]);
$nextMonthBookings = $stmt->fetchAll();

$daysInNextMonth = date('t', strtotime('+1 month'));
$calendarData = array_fill(1, $daysInNextMonth, 0);

foreach ($nextMonthBookings as $entry) {
    $day = (int) $entry['day'];
    $calendarData[$day] = (int) $entry['count'];
}
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    <div class="dashboard-container fade-in">
        <div class="welcome-section">
            <div class="welcome-content">
                <div class="welcome-text">
                    <h1>Welcome back, <?php echo htmlspecialchars($admin['username']); ?>!</h1>
                    <p>Here's an overview of your studio bookings and activities</p>

                    <div class="quick-actions">
                        <a href="dashboard.php" class="quick-action-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 0 0-2 2H5a2 2 0 0 0-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                            Manage Bookings
                        </a>
                        <?php if ($stats['pending_bookings'] > 0): ?>
                            <a href="dashboard.php#pending" class="quick-action-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                Review Pending (<?php echo $stats['pending_bookings']; ?>)
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="date-display">
                    <?php echo date('l, F j, Y'); ?>
                </div>
            </div>
            <div class="admin-settings">
                <a href="account_settings.php" class="" title="Account Settings">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="512" height="512" x="0" y="0" viewBox="0 0 24 24"
                        style="enable-background:new 0 0 512 512" xml:space="preserve" fill-rule="evenodd" class="">
                        <g>
                            <circle cx="11.5" cy="6.744" r="5.5" fill="#fff4e6" opacity="1" data-original="#000000"
                                class=""></circle>
                            <path
                                d="M17.5 13.938a3.564 3.564 0 0 0 0 7.125c1.966 0 3.563-1.597 3.563-3.563s-1.597-3.562-3.563-3.562zm0 1.5c1.138 0 2.063.924 2.063 2.062s-.925 2.063-2.063 2.063-2.063-.925-2.063-2.063.925-2.062 2.063-2.062z"
                                fill="#fff4e6" opacity="1" data-original="#000000" class=""></path>
                            <path
                                d="M18.25 14.687V13a.75.75 0 0 0-1.5 0v1.688a.75.75 0 0 0 1.5-.001zM20.019 16.042l1.193-1.194a.749.749 0 1 0-1.06-1.06l-1.194 1.193a.752.752 0 0 0 0 1.061.752.752 0 0 0 1.061 0zM20.312 18.25H22a.75.75 0 0 0 0-1.5h-1.688a.75.75 0 0 0 0 1.5zM18.958 20.019l1.194 1.193a.749.749 0 1 0 1.06-1.06l-1.193-1.194a.752.752 0 0 0-1.061 0 .752.752 0 0 0 0 1.061zM16.75 20.312V22a.75.75 0 0 0 1.5 0v-1.688a.75.75 0 0 0-1.5 0zM14.981 18.958l-1.193 1.194a.749.749 0 1 0 1.06 1.06l1.194-1.193a.752.752 0 0 0 0-1.061.752.752 0 0 0-1.061 0zM14.687 16.75H13a.75.75 0 0 0 0 1.5h1.687a.75.75 0 1 0 0-1.5zM16.042 14.981l-1.194-1.193a.749.749 0 1 0-1.06 1.06l1.193 1.194a.752.752 0 0 0 1.061 0 .752.752 0 0 0 0-1.061z"
                                fill="#fff4e6" opacity="1" data-original="#000000" class=""></path>
                            <path
                                d="M12.936 21.756a1.751 1.751 0 0 1 .145-2.311l.194-.195H13a1.75 1.75 0 0 1 0-3.5h.275l-.194-.195a1.75 1.75 0 0 1-.078-2.391 19.45 19.45 0 0 0-1.503-.058c-3.322 0-6.263.831-8.089 2.076-1.393.95-2.161 2.157-2.161 3.424v1.45a1.697 1.697 0 0 0 1.7 1.7z"
                                fill="#fff4e6" opacity="1" data-original="#000000" class=""></path>
                        </g>
                    </svg>
                </a>
            </div>
        </div>

        <div class="stats-container">
            <a href="dashboard.php" class="stat-card-link">
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <div class="stat-value"><?php echo $stats['total_bookings']; ?></div>
                    <div class="stat-label">Total Bookings</div>
                </div>
            </a>

            <a href="dashboard.php?status=pending" class="stat-card-link">
                <div class="stat-card">
                    <div class="stat-icon orange">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div class="stat-value"><?php echo $stats['pending_bookings']; ?></div>
                    <div class="stat-label">Pending Approval</div>
                </div>
            </a>

            <a href="dashboard.php?status=confirmed" class="stat-card-link">
                <div class="stat-card">
                    <div class="stat-icon green">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <div class="stat-value"><?php echo $stats['confirmed_bookings']; ?></div>
                    <div class="stat-label">Confirmed</div>
                </div>
            </a>

            <a href="dashboard.php?status=rejected" class="stat-card-link">
                <div class="stat-card">
                    <div class="stat-icon red">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                    </div>
                    <div class="stat-value"><?php echo $stats['rejected_bookings']; ?></div>
                    <div class="stat-label">Rejected</div>
                </div>
            </a>

            <a href="dashboard.php?status=completed" class="stat-card-link">
                <div class="stat-card">
                    <div class="stat-icon purple">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <div class="stat-value"><?php echo $stats['completed_bookings']; ?></div>
                    <div class="stat-label">Completed</div>
                </div>
            </a>
        </div>

        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Today's Schedule</h2>
                    <a href="dashboard.php" class="view-all">
                        View All
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                </div>

                <div class="card-content">
                    <?php if (count($todayBookings) > 0): ?>
                        <?php foreach ($todayBookings as $booking): ?>
                            <div class="booking-item">
                                <div class="booking-time"><?php echo date('h:i A', strtotime($booking['time_start'])); ?></div>
                                <div class="booking-details">
                                    <div class="booking-title"><?php echo htmlspecialchars($booking['name']); ?></div>
                                    <div class="booking-subtitle">
                                        <?php echo ucfirst($booking['package']); ?> Package
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="empty-list">No bookings scheduled for today</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Recent Activity</h2>
                </div>

                <div class="card-content">
                    <?php foreach ($recentActivity as $activity): ?>
                        <div class="activity-item">
                            <?php
                            $iconClass = '';
                            $iconSvg = '';

                            if ($activity['status'] === 'confirmed') {
                                $iconClass = 'green';
                                $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>';
                            } elseif ($activity['status'] === 'pending') {
                                $iconClass = 'orange';
                                $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>';
                            } else {
                                $iconClass = 'red';
                                $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>';
                            }
                            ?>
                            <div class="activity-icon <?php echo $iconClass; ?>">
                                <?php echo $iconSvg; ?>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">
                                    <?php echo htmlspecialchars($activity['name']); ?> -
                                    <span class="status-badge status-<?php echo $activity['status']; ?>">
                                        <?php echo ucfirst($activity['status']); ?>
                                    </span>
                                </div>
                                <div class="activity-time">
                                    <?php
                                    echo date('M j', strtotime($activity['date']));
                                    echo ' at ' . date('h:i A', strtotime($activity['time_start']));
                                    echo ' · ' . ucfirst($activity['package']) . ' Package';
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Package Distribution</h2>
                </div>

                <div class="chart-container">
                    <canvas id="packageChart"></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <?php echo date('F Y', strtotime('+1 month')); ?> Bookings
                    </h2>
                </div>

                <div class="calendar-header">
                    <div class="calendar-header-day">Sun</div>
                    <div class="calendar-header-day">Mon</div>
                    <div class="calendar-header-day">Tue</div>
                    <div class="calendar-header-day">Wed</div>
                    <div class="calendar-header-day">Thu</div>
                    <div class="calendar-header-day">Fri</div>
                    <div class="calendar-header-day">Sat</div>
                </div>

                <div class="calendar-view">
                    <?php
                    // Add empty days for proper alignment
                    $firstDayOfMonth = date('N', strtotime($firstDayNextMonth)) % 7;
                    for ($i = 0; $i < $firstDayOfMonth; $i++) {
                        echo '<div class="calendar-day"></div>';
                    }

                    // Add days with booking count
                    for ($day = 1; $day <= $daysInNextMonth; $day++) {
                        $count = $calendarData[$day];
                        $class = $count > 0 ? ($count > 2 ? 'has-multiple-bookings' : 'has-bookings') : '';

                        echo '<div class="calendar-day ' . $class . '">';
                        echo '<div class="calendar-day-inner">';
                        echo '<div class="calendar-day-number">' . $day . '</div>';
                        if ($count > 0) {
                            echo '<div class="calendar-day-count">' . $count . ' booking' . ($count > 1 ? 's' : '') . '</div>';
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Upcoming Bookings</h2>
                    <a href="dashboard.php" class="view-all">
                        View All
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                </div>

                <div class="card-content">
                    <?php if (count($upcomingBookings) > 0): ?>
                        <?php foreach ($upcomingBookings as $booking): ?>
                            <div class="booking-item">
                                <div class="booking-time">
                                    <?php
                                    // Format as "May 15" or "Today" if it's today
                                    if ($booking['date'] === date('Y-m-d')) {
                                        echo 'Today';
                                    } else {
                                        echo date('M j', strtotime($booking['date']));
                                    }
                                    ?>
                                </div>
                                <div class="booking-details">
                                    <div class="booking-title"><?php echo htmlspecialchars($booking['name']); ?></div>
                                    <div class="booking-subtitle">
                                        <?php
                                        echo date('h:i A', strtotime($booking['time_start']));
                                        echo ' · ' . ucfirst($booking['package']) . ' Package';
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="empty-list">No upcoming bookings for the next 7 days</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        const packageCtx = document.getElementById('packageChart').getContext('2d');
        const packageChart = new Chart(packageCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($packageLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($packageData); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(199, 199, 199, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(199, 199, 199, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.formattedValue;
                                const dataset = context.dataset;
                                const total = dataset.data.reduce((acc, data) => acc + data, 0);
                                const percentage = Math.round((context.raw / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '65%',
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });

        // Add animations to stats cards
        document.addEventListener('DOMContentLoaded', function () {
            const statCards = document.querySelectorAll('.stat-card');

            statCards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('fade-in');
                }, 100 * index);
            });

            // Add hover effect for calendar days
            const calendarDays = document.querySelectorAll('.calendar-day');
            calendarDays.forEach(day => {
                day.addEventListener('mouseenter', () => {
                    if (day.classList.contains('has-bookings') || day.classList.contains('has-multiple-bookings')) {
                        day.style.transform = 'scale(1.05)';
                        day.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
                    }
                });

                day.addEventListener('mouseleave', () => {
                    day.style.transform = '';
                    day.style.boxShadow = '';
                });
            });

            // Add pulse animation to pending bookings notification
            if (document.querySelector('.quick-action-btn:nth-child(2)')) {
                const pendingButton = document.querySelector('.quick-action-btn:nth-child(2)');
                setInterval(() => {
                    pendingButton.classList.add('pulse');
                    setTimeout(() => {
                        pendingButton.classList.remove('pulse');
                    }, 1000);
                }, 3000);
            }
        });

        // Add current time display with live update
        const dateDisplay = document.querySelector('.date-display');
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            if (!dateDisplay.textContent.includes(timeString)) {
                dateDisplay.textContent = `${now.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' })} | ${timeString}`;
            }
        }

        updateTime();
        setInterval(updateTime, 60000); // Update time every minute
    </script>
</body>

</html>