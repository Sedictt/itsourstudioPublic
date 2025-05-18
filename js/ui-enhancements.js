// UI enhancements for the photography studio booking system

document.addEventListener('DOMContentLoaded', function() {
    // Add reveal animation to elements as they scroll into view
    const revealElements = document.querySelectorAll('.booking-summary, .gcash-details, .price-summary, .time-grid, form');
    
    const revealOnScroll = function() {
        for (let i = 0; i < revealElements.length; i++) {
            const windowHeight = window.innerHeight;
            const elementTop = revealElements[i].getBoundingClientRect().top;
            const elementVisible = 150;
            
            if (elementTop < windowHeight - elementVisible) {
                revealElements[i].classList.add('reveal', 'active');
            }
        }
    };
    
    // Add reveal class to all elements
    revealElements.forEach(el => el.classList.add('reveal'));
    
    // Initial check on page load
    revealOnScroll();
    
    // Check on scroll 
    window.addEventListener('scroll', revealOnScroll);
});

document.addEventListener('DOMContentLoaded', function() {
    // Thumbnail selection logic
    const thumbnails = document.querySelectorAll('#package-thumbnails .thumbnail');
    const packageInput = document.getElementById('package');

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            // Remove active class from all thumbnails
            thumbnails.forEach(t => t.classList.remove('active'));

            // Add active class to the clicked thumbnail
            this.classList.add('active');

            // Update the hidden input value
            packageInput.value = this.dataset.value;
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Modal logic for expanded thumbnail view
    const modal = document.createElement('div');
    modal.classList.add('thumbnail-modal');
    modal.innerHTML = `
        <div class="thumbnail-modal-content">
            <button class="thumbnail-modal-close">&times;</button>
            <img src="" alt="Expanded Thumbnail">
            <h3></h3>
            <p></p>
        </div>
    `;
    document.body.appendChild(modal);

    const modalContent = modal.querySelector('.thumbnail-modal-content');
    const modalImage = modalContent.querySelector('img');
    const modalTitle = modalContent.querySelector('h3');
    const modalDescription = modalContent.querySelector('p');
    const modalClose = modalContent.querySelector('.thumbnail-modal-close');

    // Variable to store scroll position
    let scrollPosition = 0;

    modalClose.addEventListener('click', () => {
        // First remove active class
        modal.classList.remove('active');
        
        // Set timeout to ensure modal animation completes before re-enabling scroll
        setTimeout(() => {
            // Re-enable scrolling
            document.body.style.overflow = '';
            
            // Restore scroll position after a brief delay to ensure proper timing
            window.scrollTo({
                top: scrollPosition,
                behavior: 'instant' // Use 'instant' instead of smooth to prevent visible scrolling
            });
        }, 10);
    });

    const thumbnails = document.querySelectorAll('#package-thumbnails .thumbnail');

    thumbnails.forEach(thumbnail => {
        const enlargeIcon = document.createElement('button');
        enlargeIcon.classList.add('enlarge-icon');
        enlargeIcon.innerHTML = `
<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M116 16v24a4 4 0 0 1-8 0V25.656L82.828 50.828C82.047 51.609 81.023 52 80 52s-2.047-.391-2.828-1.172a3.997 3.997 0 0 1 0-5.656L102.344 20H88a4 4 0 0 1 0-8h24a4 4 0 0 1 4 4zM50.828 45.172 25.656 20H40a4 4 0 0 0 0-8H16a4 4 0 0 0-4 4v24a4 4 0 0 0 8 0V25.656l25.172 25.172C45.953 51.609 46.977 52 48 52s2.047-.391 2.828-1.172a3.997 3.997 0 0 0 0-5.656zM112 84a4 4 0 0 0-4 4v14.344L82.828 77.172c-1.563-1.563-4.094-1.563-5.656 0s-1.563 4.094 0 5.656L102.344 108H88a4 4 0 0 0 0 8h24a4 4 0 0 0 4-4V88a4 4 0 0 0-4-4zm-61.172-6.828a3.997 3.997 0 0 0-5.656 0L20 102.344V88a4 4 0 0 0-8 0v24a4 4 0 0 0 4 4h24a4 4 0 0 0 0-8H25.656l25.172-25.172a3.997 3.997 0 0 0 0-5.656z" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></svg>
        `;
        thumbnail.appendChild(enlargeIcon);

        enlargeIcon.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent triggering thumbnail selection

            // Save current scroll position before showing modal
            scrollPosition = window.pageYOffset || document.documentElement.scrollTop;

            // Update modal content
            modalImage.src = thumbnail.querySelector('img').src;
            modalTitle.textContent = thumbnail.querySelector('h3').textContent;
            modalDescription.textContent = thumbnail.querySelector('p').textContent;

            // Show modal
            modal.classList.add('active');
            
            // Disable scrolling but maintain position
            document.body.style.overflow = 'hidden';
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Price summary update logic
    const basePriceElement = document.getElementById('basePrice');
    const extensionFeeElement = document.getElementById('extensionFee');
    const totalAmountElement = document.getElementById('totalAmount');
    const downpaymentElement = document.getElementById('downpayment');
    const packageInput = document.getElementById('package');
    const extensionInput = document.getElementById('extension');

    const updatePriceSummary = () => {
        const selectedPackage = packageInput.value;
        const extensionMinutes = parseInt(extensionInput.value, 10) || 0;

        // Define package prices
        const packagePrices = {
            solo: 299,
            basic: 399,
            transfer: 549,
            standard: 699,
            family: 1249,
            barkada: 1949,
            birthday: 599
        };

        const basePrice = packagePrices[selectedPackage] || 0;
        const extensionFee = (extensionMinutes / 15) * 150;
        const totalAmount = basePrice + extensionFee;
        const downpayment = totalAmount * 0.5;

        // Update the DOM elements
        basePriceElement.textContent = `₱${basePrice}`;
        extensionFeeElement.textContent = `₱${extensionFee}`;
        totalAmountElement.textContent = `₱${totalAmount}`;
        downpaymentElement.textContent = `₱${downpayment}`;

        // Update hidden inputs
        document.getElementById('total_amount_input').value = totalAmount;
        document.getElementById('downpayment_input').value = downpayment;
    };

    // Add event listeners to update price summary
    packageInput.addEventListener('change', updatePriceSummary);
    extensionInput.addEventListener('change', updatePriceSummary);

    // Trigger price summary update when a package thumbnail is clicked
    const thumbnails = document.querySelectorAll('#package-thumbnails .thumbnail');
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', updatePriceSummary);
    });

    // Initial update on page load
    updatePriceSummary();
});