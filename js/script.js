const navbar = document.getElementById('navbar');

document.addEventListener('DOMContentLoaded', function() {
    const openSidebarBtn = document.getElementById('open-sidebar-btn');
    const closeSidebarBtn = document.getElementById('close-sidebar-btn');
    const navbar = document.getElementById('navbar');

    if (openSidebarBtn && navbar) {
        openSidebarBtn.addEventListener('click', function() {
            navbar.classList.add('show');
        });
    }
    if (closeSidebarBtn && navbar) {
        closeSidebarBtn.addEventListener('click', function() {
            navbar.classList.remove('show');
        });
    }
    
    // Back to top button functionality
    const backToTopBtn = document.getElementById('back-to-top');
    
    if (backToTopBtn) {
        // Show/hide button based on scroll position
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        });
        
        // Scroll to top when clicked
        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});

const PACKAGES = {
    solo: { price: 299, duration: 15, name: 'Solo Package' },
    basic: { price: 399, duration: 25, name: 'Basic Package' },
    transfer: { price: 549, duration: 30, name: 'Just Transfer' },
    standard: { price: 699, duration: 40, name: 'Standard Package' },
    family: { price: 1249, duration: 60, name: 'Family Package' },
    barkada: { price: 1949, duration: 90, name: 'Barkada Package' },
    birthday: { price: 599, duration: 45, name: 'Birthday Package' }
};

let bookedSlots = [];
let pendingSlots = [];
let dateSelected = false;
let selectedTimeSlot = null;

async function fetchBookedSlots(date) {
    try {
        const response = await fetch(`../includes/get_booked_slots.php?date=${date}`);
        const data = await response.json();
        
        if (data.error) {
            console.error('Error fetching booked slots:', data.error);
            return { booked: [], pending: [] };
        }
        
        // Ensure we have valid arrays for booked and pending slots
        bookedSlots = Array.isArray(data.booked) ? data.booked : [];
        pendingSlots = Array.isArray(data.pending) ? data.pending : [];
        
        // Debug info - log the booked and pending slots
        console.log('Booked slots:', bookedSlots);
        console.log('Pending slots:', pendingSlots);
        
        // Ensure extension_minutes is set for all slots
        bookedSlots.forEach(slot => {
            if (slot.extension_minutes === null || slot.extension_minutes === undefined) {
                slot.extension_minutes = 0;
            }
        });
        
        pendingSlots.forEach(slot => {
            if (slot.extension_minutes === null || slot.extension_minutes === undefined) {
                slot.extension_minutes = 0;
            }
        });
        
        return data;
    } catch (error) {
        console.error('Error fetching booked slots:', error);
        return { booked: [], pending: [] };
    }
}

function calculateAffectedTimeSlots(startTime, duration) {
    const affected = [];
    
    // Start with the exact time slot provided
    affected.push(startTime);
    
    // Calculate the end time (in minutes) to determine all affected slots
    const startParts = startTime.split(':');
    const startHour = parseInt(startParts[0]);
    const startMinute = parseInt(startParts[1]);
    
    // Convert the start time to minutes since midnight
    let currentMinutes = startHour * 60 + startMinute;
    const endMinutes = currentMinutes + duration;
    
    // For bookings that end exactly on a 30-minute boundary, we don't need to block the next slot
    // For example, a 10:00 booking with 30 minutes ends at 10:30, so 10:30 should remain available
    
    // Add slots every 30 minutes until we reach the end time
    // Start from the next 30-minute slot
    currentMinutes = Math.ceil(currentMinutes / 30) * 30;
    
    while (currentMinutes < endMinutes) {
        const hour = Math.floor(currentMinutes / 60);
        const minute = currentMinutes % 60;
        const formattedTime = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
        
        // Only add the slot if it's not already in the affected array
        if (!affected.includes(formattedTime)) {
            affected.push(formattedTime);
        }
        
        currentMinutes += 30;
    }
    
    console.log(`Calculated affected time slots for ${startTime} with duration ${duration} minutes:`, affected);
    return affected;
}

function isTimeSlotBooked(timeSlot, slots) {
    // If there are no slots, nothing is booked
    if (!slots || !Array.isArray(slots) || slots.length === 0) {
        return false;
    }
    
    return slots.some(booking => {
        // Skip invalid bookings
        if (!booking || !booking.time_start) {
            return false;
        }
        
        // Get the booking start time and total duration
        const bookingStartTime = booking.time_start;
        
        // Parse the duration and extension_minutes, ensuring they are numbers
        let baseDuration = 0;
        let extensionMinutes = 0;
        
        if (booking.duration !== undefined && booking.duration !== null) {
            baseDuration = parseInt(booking.duration);
        }
        
        if (booking.extension_minutes !== undefined && booking.extension_minutes !== null) {
            extensionMinutes = parseInt(booking.extension_minutes);
        }
        
        // Calculate total duration - make sure both values are valid numbers
        const totalDuration = isNaN(baseDuration) ? 0 : baseDuration + 
                             (isNaN(extensionMinutes) ? 0 : extensionMinutes);
        
        if (totalDuration <= 0) {
            return false;
        }
        
        // Log the booking details for debugging
        console.log(`Checking booking at ${bookingStartTime}: Base duration=${baseDuration}, Extension=${extensionMinutes}, Total=${totalDuration}`);
        
        // Calculate all time slots affected by this booking
        const affectedSlots = calculateAffectedTimeSlots(bookingStartTime, totalDuration);
        
        // Check if the time slot we're checking is included in affected slots
        return affectedSlots.includes(timeSlot);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('date');
    const packageSelect = document.getElementById('package');
    const bookingForm = document.getElementById('bookingForm');
    const extensionSelect = document.getElementById('extension');

    // Initialize with today's date
    const today = new Date().toISOString().split('T')[0];
    dateInput.placeholder = "Please select a date";

    dateInput.min = today;
    dateInput.max = new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];

    // Initial time slot load - but disabled until date is selected
    updateTimeSlots(today);

    // Update time slots when date is selected
    dateInput.addEventListener('change', function () {
        dateSelected = true;
        updateTimeSlots(this.value, true);
        // Clear any previously selected time slot
        selectedTimeSlot = null;
        document.getElementById('selected_time').value = '';
    });

    // When package changes, update price and re-check time slot validity
    packageSelect.addEventListener('change', function() {
        updatePrice();
        if (dateSelected) {
            // Re-generate time slots to ensure validity with new package duration
            updateTimeSlots(dateInput.value, true);
        }
    });

    // When extension changes, update price and re-check time slot validity
    extensionSelect.addEventListener('change', function() {
        updatePrice();
        if (dateSelected && selectedTimeSlot) {
            const selectedPackage = packageSelect.value;
            let extension = parseInt(this.value || 0);
            
            if (selectedPackage && PACKAGES[selectedPackage]) {
                const totalDuration = PACKAGES[selectedPackage].duration + extension;
                const affectedSlots = calculateAffectedTimeSlots(selectedTimeSlot, totalDuration);
                
                // If the new duration causes an overlap, remove the selected time
                if (affectedSlots.some(slot => isTimeSlotBooked(slot, bookedSlots))) {
                    alert('The selected time slot is no longer available with the new duration. Please select another time.');
                    document.getElementById('selected_time').value = '';
                    selectedTimeSlot = null;
                    
                    // Re-generate time slots to show available options
                    updateTimeSlots(dateInput.value, true);
                }
            }
        }
    });

    // Form submission validation
    bookingForm.addEventListener('submit', function (e) {
        const selectedTime = document.getElementById('selected_time').value;

        if (!selectedTime) {
            e.preventDefault();
            alert('Please select a time slot before proceeding.');
            return false;
        }

        // Form is valid, continue submission
        return true;
    });

    // Initialize price calculation on page load
    updatePrice();
});

function updateTimeSlots(date, enableSelection = false) {
    console.log(`Updating time slots for ${date}, enableSelection=${enableSelection}`);
    
    fetchBookedSlots(date).then(() => {
        const container = document.getElementById('timeSlotContainer');
        const selectedDate = new Date(date);
        const isWeekend = selectedDate.getDay() === 0 || selectedDate.getDay() === 6;

        // Get business hours
        const startHour = isWeekend ? 9 : 10;
        const endHour = isWeekend ? 20 : 19;

        // Generate time slots
        generateTimeSlots(container, startHour, endHour, enableSelection);
        
        console.log(`Time slots updated for ${date}`);
    }).catch(error => {
        console.error('Error updating time slots:', error);
    });
}

function generateTimeSlots(container, startHour, endHour, enableSelection) {
    container.innerHTML = `
        <h3>Available Time Slots</h3>
        <div class="time-grid"></div>
        <div class="time-slot-legend">
            <div class="legend-item">
                <div class="legend-color available"></div>
                <span>Available</span>
            </div>
            <div class="legend-item">
                <div class="legend-color selected"></div>
                <span>Selected</span>
            </div>
            <div class="legend-item">
                <div class="legend-color affected"></div>
                <span>Affected by selection</span>
            </div>
            <div class="legend-item">
                <div class="legend-color booked"></div>
                <span>Booked</span>
            </div>
            <div class="legend-item">
                <div class="legend-color pending"></div>
                <span>Pending confirmation</span>
            </div>
        </div>
    `;
    const grid = container.querySelector('.time-grid');

    for (let hour = startHour; hour < endHour; hour++) {
        for (let minute = 0; minute < 60; minute += 30) {
            const timeSlot = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;

            // Check if this slot is booked or pending
            const isBooked = isTimeSlotBooked(timeSlot, bookedSlots);
            const isPending = isTimeSlotBooked(timeSlot, pendingSlots);

            const slotElement = document.createElement('div');
            slotElement.classList.add('time-slot');
            slotElement.textContent = timeSlot;
            
            // Check if this is the previously selected time slot
            if (timeSlot === selectedTimeSlot) {
                slotElement.classList.add('selected');
                slotElement.title = "Your selected time slot";
            }

            if (isBooked) {
                slotElement.classList.add('booked');
                slotElement.title = "This time slot is booked";
            } else if (isPending) {
                slotElement.classList.add('pending');
                slotElement.title = "This time slot has a pending booking";
            } else if (!dateSelected && !enableSelection) {
                slotElement.classList.add('disabled');
                slotElement.title = "Please select a date first";
            } else {
                slotElement.title = "Available - Click to select";
                slotElement.addEventListener('click', () => selectTimeSlot(timeSlot));
            }

            grid.appendChild(slotElement);
        }
    }
    
    // If a time slot is selected, add 'affected' class to slots affected by the selection
    if (selectedTimeSlot) {
        const selectedPackage = document.getElementById('package').value;
        const extension = parseInt(document.getElementById('extension').value || 0);
        
        if (selectedPackage && PACKAGES[selectedPackage]) {
            const totalDuration = PACKAGES[selectedPackage].duration + extension;
            const affectedSlots = calculateAffectedTimeSlots(selectedTimeSlot, totalDuration);
            
            // For each affected slot, find the element and add the 'affected' class
            grid.querySelectorAll('.time-slot').forEach(slot => {
                const slotTime = slot.textContent.trim();
                if (affectedSlots.includes(slotTime) && slotTime !== selectedTimeSlot) {
                    slot.classList.add('affected');
                    slot.title = "Part of your booking session";
                }
            });
        }
    }
}

function selectTimeSlot(timeSlot) {
    const selectedPackage = document.getElementById('package').value;
    let extension = parseInt(document.getElementById('extension').value || 0);
    
    // Check if a package is selected before proceeding
    if (!selectedPackage || !PACKAGES[selectedPackage]) {
        alert('Please select a package first.');
        return;
    }
    
    const totalDuration = PACKAGES[selectedPackage].duration + extension;
    const packageName = PACKAGES[selectedPackage].name;
    
    // Calculate all time slots that would be affected by this booking
    const affectedSlots = calculateAffectedTimeSlots(timeSlot, totalDuration);
    
    // Format the time display to show the exact end time
    const startParts = timeSlot.split(':');
    const startHour = parseInt(startParts[0]);
    const startMinute = parseInt(startParts[1]);
    
    let endTimeMinutes = startHour * 60 + startMinute + totalDuration;
    const endHour = Math.floor(endTimeMinutes / 60);
    const endMinute = endTimeMinutes % 60;
    const endTimeFormatted = `${endHour.toString().padStart(2, '0')}:${endMinute.toString().padStart(2, '0')}`;
    
    console.log(`Selecting ${packageName} at ${timeSlot} (${totalDuration} min). Session ends at ${endTimeFormatted}`);
    console.log('Time slots affected:', affectedSlots);
    
    // Check if any of these slots overlap with existing bookings
    const overlappingSlots = affectedSlots.filter(slot => isTimeSlotBooked(slot, bookedSlots));
    if (overlappingSlots.length > 0) {
        alert(`This booking would overlap with existing reservations at ${overlappingSlots.join(', ')}.`);
        document.getElementById('selected_time').value = '';
        return;
    }
    
    // Check if any of these slots overlap with pending bookings
    const pendingOverlaps = affectedSlots.filter(slot => isTimeSlotBooked(slot, pendingSlots));
    if (pendingOverlaps.length > 0) {
        alert(`This booking would overlap with pending reservations at ${pendingOverlaps.join(', ')}.`);
        document.getElementById('selected_time').value = '';
        return;
    }
    
    // Remove selected class from all time slots
    document.querySelectorAll('.time-slot').forEach(slot => {
        slot.classList.remove('selected', 'pop', 'affected');
    });
    
    // Store the selected time in global variable and form input
    selectedTimeSlot = timeSlot;
    document.getElementById('selected_time').value = selectedTimeSlot;
    
    // Add selected class to the clicked time slot and affected class to all affected slots
    const timeSlots = document.querySelectorAll('.time-slot');
    timeSlots.forEach(slot => {
        const slotTime = slot.textContent.trim();
        
        if (slotTime === timeSlot) {
            // This is the primary selected slot
            slot.classList.add('selected', 'pop');
            slot.title = `${packageName}: ${timeSlot} - ${endTimeFormatted} (${totalDuration} min)`;
            setTimeout(() => slot.classList.remove('pop'), 300);
        } else if (affectedSlots.includes(slotTime)) {
            // This is an affected slot
            slot.classList.add('affected');
            slot.title = `Affected by your booking at ${timeSlot}`;
        }
    });
    
    // Update other hidden form inputs
    document.getElementById('selected_date').value = document.getElementById('date').value;
    document.getElementById('duration').value = totalDuration;
}

function updatePrice() {
    const selectedPackage = document.getElementById('package').value;
    let extension = parseInt(document.getElementById('extension').value || 0);

    if (!selectedPackage || !PACKAGES[selectedPackage]) return;
    if (isNaN(extension)) extension = 0;

    const basePrice = PACKAGES[selectedPackage].price;
    const extensionFee = (extension / 15) * 150;
    const total = basePrice + extensionFee;
    const downpayment = total * 0.5;
    const totalDuration = parseInt(PACKAGES[selectedPackage].duration) + parseInt(extension);
    const packageName = PACKAGES[selectedPackage].name;

    // Update hidden form inputs
    document.getElementById('total_amount_input').value = total.toFixed(2);
    document.getElementById('downpayment_input').value = downpayment.toFixed(2);
    document.getElementById('duration').value = totalDuration;
    document.getElementById('selected_date').value = document.getElementById('date').value;

    // Update display
    document.getElementById('basePrice').textContent = `₱${basePrice}`;
    document.getElementById('extensionFee').textContent = `₱${extensionFee}`;
    document.getElementById('totalAmount').textContent = `₱${total}`;
    document.getElementById('downpayment').textContent = `₱${downpayment.toFixed(2)}`;
    
    // If time slot is selected, check if it's still valid with the new duration
    const selectedTime = document.getElementById('selected_time').value;
    if (selectedTime && dateSelected) {
        console.log(`Checking if ${selectedTime} is still valid with new duration: ${totalDuration} minutes`);
        
        // Calculate the exact end time for display
        const startParts = selectedTime.split(':');
        const startHour = parseInt(startParts[0]);
        const startMinute = parseInt(startParts[1]);
        
        let endTimeMinutes = startHour * 60 + startMinute + totalDuration;
        const endHour = Math.floor(endTimeMinutes / 60);
        const endMinute = endTimeMinutes % 60;
        const endTimeFormatted = `${endHour.toString().padStart(2, '0')}:${endMinute.toString().padStart(2, '0')}`;
        
        console.log(`New session time: ${selectedTime} - ${endTimeFormatted} (${totalDuration} min)`);
        
        const affectedSlots = calculateAffectedTimeSlots(selectedTime, totalDuration);
        console.log('Affected slots after duration change:', affectedSlots);
        
        // Check for overlaps with booked slots
        const bookedOverlaps = affectedSlots.filter(slot => isTimeSlotBooked(slot, bookedSlots));
        // Check for overlaps with pending slots
        const pendingOverlaps = affectedSlots.filter(slot => isTimeSlotBooked(slot, pendingSlots));
        
        const hasOverlap = bookedOverlaps.length > 0 || pendingOverlaps.length > 0;
        
        if (hasOverlap) {
            // Format a more helpful error message
            let conflictMessage = 'The selected time slot is no longer available with the new duration.';
            if (bookedOverlaps.length > 0) {
                conflictMessage += ` Conflicts with confirmed bookings at: ${bookedOverlaps.join(', ')}.`;
            }
            if (pendingOverlaps.length > 0) {
                conflictMessage += ` Conflicts with pending bookings at: ${pendingOverlaps.join(', ')}.`;
            }
            conflictMessage += ' Please select another time.';
            
            alert(conflictMessage);
            
            // Clear the selection
            document.getElementById('selected_time').value = '';
            selectedTimeSlot = null;
            document.querySelectorAll('.time-slot').forEach(slot => {
                slot.classList.remove('selected', 'pop', 'affected');
            });
            
            // Re-generate time slots to show available options
            updateTimeSlots(document.getElementById('date').value, true);
        } else {
            // If it's still valid, update the affected slots visualization
            document.querySelectorAll('.time-slot').forEach(slot => {
                const slotTime = slot.textContent.trim();
                
                // Remove all highlighting first
                slot.classList.remove('selected', 'pop', 'affected');
                
                // Then apply appropriate highlighting
                if (slotTime === selectedTime) {
                    slot.classList.add('selected');
                    slot.title = `${packageName}: ${selectedTime} - ${endTimeFormatted} (${totalDuration} min)`;
                } else if (affectedSlots.includes(slotTime)) {
                    slot.classList.add('affected');
                    slot.title = `Affected by your booking at ${selectedTime}`;
                }
            });
        }
    }
}

// GALLERY
    function initCarousel(trackId, prevId, nextId, images) {
      const track = document.getElementById(trackId);
      const prev = document.getElementById(prevId);
      const next = document.getElementById(nextId);
      const extended = [...images, ...images, ...images];
      const visibleCount = 3;
      let current = images.length;
      let isMoving = false;
      let autoplay;

      extended.forEach(img => {
        const slide = document.createElement('div');
        slide.className = 'slide';
        slide.style.backgroundColor = img.bg;

        const brand = document.createElement('div');
        brand.className = 'studio-brand';
        slide.appendChild(brand);

        const image = document.createElement('img');
        image.src = img.src;
        image.alt = img.alt;
        image.loading = 'lazy';
        image.decoding = 'async';
        slide.appendChild(image);

        track.appendChild(slide);
      });

            function update(active = true) {
        // Check if we're in mobile view
        const isMobile = window.innerWidth <= 767;
        
        let offset;
        if (isMobile) {
          // For mobile, use a more precise calculation
          const slideWidth = track.children[0].offsetWidth;
          const containerWidth = track.offsetWidth;
          const margin = 5; // Match the margin in CSS
          
          // Calculate the exact center position
          offset = -(current * (slideWidth + margin * 2)) + ((containerWidth - slideWidth) / 2);
        } else {
          // Original desktop calculation
          const width = track.children[0].offsetWidth + 20;
          offset = -(current * width) + ((track.offsetWidth - width) / 2);
        }
        
        track.style.transition = active ? 'transform 0.5s ease' : 'none';
        track.style.transform = `translateX(${offset}px)`;
        updateActive();
      }

      function updateActive() {
        const slides = track.querySelectorAll('.slide');
        slides.forEach((slide, i) => {
          slide.classList.toggle('active', i === current);
        });
      }

      function nextSlide() {
        if (isMoving) return;
        isMoving = true;
        current++;
        update(true);
        setTimeout(() => {
          if (current >= images.length * 2) {
            current = images.length;
            update(false);
            void track.offsetWidth;
            update(false);
          }
          isMoving = false;
        }, 500);
      }

      function prevSlide() {
        if (isMoving) return;
        isMoving = true;
        current--;
        update(true);
        setTimeout(() => {
          if (current < images.length) {
            current = images.length * 2 - 1;
            update(false);
            void track.offsetWidth;
            update(false);
          }
          isMoving = false;
        }, 500);
      }

      function start() {
        autoplay = setInterval(nextSlide, 3000);
      }

      function stop() {
        clearInterval(autoplay);
      }

      prev.addEventListener('click', () => {
        stop(); prevSlide(); start();
      });

      next.addEventListener('click', () => {
        stop(); nextSlide(); start();
      });

      track.addEventListener('mouseenter', stop);
      track.addEventListener('mouseleave', start);

      // Update on resize to handle orientation changes and window resizing
      window.addEventListener('resize', () => update(false));
      
      // Also update on orientation change for mobile devices
      window.addEventListener('orientationchange', () => {
        setTimeout(() => update(false), 100); // Small delay to ensure dimensions are updated
      });

      setTimeout(() => {
        update(false);
        start();
      }, 100);
    }

    const soloImages = [
      { src: "assets/images/gallery/solo1.webp", bg: '#6B7280', alt: 'Professional solo portrait photography at It\'s Our Studio' },
      { src: "assets/images/gallery/solo2.webp", bg: '#F472B6', alt: 'Creative individual portrait in photography studio' },
      { src: "assets/images/gallery/solo3.webp", bg: '#6B7280', alt: 'Artistic solo portrait session at It\'s Our Studio' },
      { src: "assets/images/gallery/solo4.webp", bg: '#93C5FD', alt: 'Modern solo photoshoot in Valenzuela photo studio' },
      { src: "assets/images/gallery/solo5.webp", bg: '#FDE68A', alt: 'Contemporary individual portrait photography' }
    ];

    const duoImages = [
      { src: "assets/images/gallery/duo1.webp", bg: '#FBBF24', alt: 'Couple portrait photography at It\'s Our Studio' },
      { src: "assets/images/gallery/duo2.webp", bg: '#34D399', alt: 'Friends duo photoshoot in professional studio' },
      { src: "assets/images/gallery/duo3.webp", bg: '#60A5FA', alt: 'Creative pair portrait photography session' },
      { src: "assets/images/gallery/duo4.webp", bg: '#A78BFA', alt: 'Stylish duo photography at It\'s Our Studio Valenzuela' },
      { src: "assets/images/gallery/duo5.webp", bg: '#F87171', alt: 'Professional couple photography in studio setting' }
    ];

    const groupImages = [
      { src: "assets/images/gallery/group1.webp", bg: '#FDBA74', alt: 'Family group portrait at It\'s Our Studio' },
      { src: "assets/images/gallery/group2.webp", bg: '#6EE7B7', alt: 'Friends group photoshoot in Valenzuela studio' },
      { src: "assets/images/gallery/group3.webp", bg: '#93C5FD', alt: 'Barkada group photography session in studio' },
      { src: "assets/images/gallery/group4.webp", bg: '#F9A8D4', alt: 'Creative group portrait photography' },
      { src: "assets/images/gallery/group5.webp", bg: '#DDD6FE', alt: 'Professional family photography at It\'s Our Studio' }
    ];

    initCarousel('soloTrack', 'soloPrev', 'soloNext', soloImages);
    initCarousel('duoTrack', 'duoPrev', 'duoNext', duoImages);
    initCarousel('groupTrack', 'groupPrev', 'groupNext', groupImages);


function togglePackage(titleElement) {
    // Get the parent package element
    const packageElement = titleElement.closest('.package');
    
    // Toggle the 'open' class
    packageElement.classList.toggle('open');
}

// For elements that might be added dynamically later
document.addEventListener('DOMContentLoaded', function() {
    const packageTitles = document.querySelectorAll('.package-title');
    
    packageTitles.forEach(title => {
        // Make sure they have the click event if not using inline
        if (!title.hasAttribute('onclick')) {
            title.addEventListener('click', function() {
                togglePackage(this);
            });
        }
    });
});


/* Toggle function for expanding/collapsing packages on mobile */
function togglePackage(element) {
    // Check if we're on mobile viewport
    if (window.innerWidth <= 767) {
        const packageElement = element.closest('.package, .package-even');
        const features = packageElement.querySelector('.package-features, .package-addons');
        const toggleIcon = element.querySelector('.toggle-icon svg');
        
        // Toggle visibility of the features
        if (features.style.display === 'none') {
            // Expand this package
            features.style.display = 'block';
            // Rotate the icon to point up
            toggleIcon.style.transform = 'rotate(180deg)';
            
            // Add the purple background effect
            if (!packageElement.classList.contains('active-package')) {
                packageElement.classList.add('active-package');
            }
        } else {
            // Collapse this package
            features.style.display = 'none';
            // Rotate the icon to point down
            toggleIcon.style.transform = 'rotate(0deg)';
            
            // Remove the purple background effect
            packageElement.classList.remove('active-package');
        }
    }
}

// Initialize the page - hide all package features on mobile by default
document.addEventListener('DOMContentLoaded', function() {
    if (window.innerWidth <= 767) {
        // Get all package features
        const allFeatures = document.querySelectorAll('.package-features, .package-addons');
        
        // Hide them initially on mobile
        allFeatures.forEach(feature => {
            feature.style.display = 'none';
        });
    }
});

// Handle window resize events
window.addEventListener('resize', function() {
    const allFeatures = document.querySelectorAll('.package-features, .package-addons');
    
    // Show all features if we're on desktop
    if (window.innerWidth > 767) {
        allFeatures.forEach(feature => {
            feature.style.display = 'block';
        });
    } else {
        // Hide them on mobile
        allFeatures.forEach(feature => {
            feature.style.display = 'none';
        });
    }
});