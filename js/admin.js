// Booking management functions
function approveBooking(bookingId) {
    if (!confirm('Are you sure you want to approve this booking?')) return;
    
    fetch('../admin/approve_booking.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `booking_id=${bookingId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Booking approved successfully!');
            location.reload();
        } else {
            throw new Error(data.error);
        }
    })
    .catch(error => {
        alert('Error approving booking: ' + error.message);
    });
}

function rejectBooking(bookingId) {
    const reason = prompt('Please enter a detailed reason for rejection. This will be sent directly to the customer:');
    
    // Validate reason
    if (!reason) {
        alert('A rejection reason is required.');
        return;
    }
    
    fetch('../admin/reject_booking.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `booking_id=${bookingId}&reason=${encodeURIComponent(reason)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Booking rejected successfully!');
            location.reload();
        } else {
            throw new Error(data.error);
        }
    })
    .catch(error => {
        alert('Error rejecting booking: ' + error.message);
    });
}

function editBooking(bookingId) {
    // Redirect to edit page
    window.location.href = `edit_booking.php?id=${bookingId}`;
}

function deleteBooking(bookingId) {
    if (!confirm('Are you sure you want to delete this booking? This action cannot be undone.')) {
        return;
    }
    
    const row = document.querySelector(`tr[data-booking-id="${bookingId}"]`);
    if (row) {
        row.style.opacity = '0.5'; // Visual feedback
    }

    fetch(`../admin/delete_booking.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `booking_id=${bookingId}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Booking deleted successfully!');
            if (row) {
                row.remove(); // Remove row without page reload
            }
        } else {
            throw new Error(data.error);
        }
    })
    .catch(error => {
        if (row) {
            row.style.opacity = '1'; // Restore opacity on error
        }
        alert('Error deleting booking: ' + error.message);
    });
}

// Add table sorting functionality
document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('.booking-table table');
    const headers = table.querySelectorAll('th');
    
    headers.forEach((header, index) => {
        if (header.classList.contains('no-sort')) return;
        
        header.addEventListener('click', () => {
            sortTable(index);
        });
        header.style.cursor = 'pointer';
    });
});

function sortTable(column) {
    const table = document.querySelector('.booking-table table');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    const sortedRows = rows.sort((a, b) => {
        const aCol = a.cells[column].textContent.trim();
        const bCol = b.cells[column].textContent.trim();
        return aCol.localeCompare(bCol);
    });
    
    tbody.innerHTML = '';
    sortedRows.forEach(row => tbody.appendChild(row));
}
