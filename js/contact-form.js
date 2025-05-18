   function submitForm(event) {
            event.preventDefault();
            
            // Basic validation
            const name = document.getElementById('name').value;
            const contact = document.getElementById('contact').value;
            const email = document.getElementById('email').value;
            const message = document.getElementById('message').value;
            const formStatus = document.getElementById('formStatus');
            const submitBtn = document.getElementById('submitBtn');
            
            if (name.trim() === '' || contact.trim() === '' || email.trim() === '' || message.trim() === '') {
                formStatus.textContent = 'Please fill in all fields';
                formStatus.style.display = 'block';
                formStatus.style.color = '#ffcccc';
                return false;
            }
            
            if (!isValidEmail(email)) {
                formStatus.textContent = 'Please enter a valid email address';
                formStatus.style.display = 'block';
                formStatus.style.color = '#ffcccc';
                return false;
            }
            
            // Disable button and show loading state
            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';
            formStatus.textContent = 'Processing your request...';
            formStatus.style.display = 'block';
            formStatus.style.color = 'white';
            
            // Send form data via AJAX
            const formData = new FormData();
            formData.append('name', name);
            formData.append('contact', contact);
            formData.append('email', email);
            formData.append('message', message);
            
            fetch('includes/process_form.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Success
                    formStatus.textContent = data.message;
                    formStatus.style.color = '#ccffcc';
                    document.getElementById('contactForm').reset();
                } else {
                    // Error
                    formStatus.textContent = data.message;
                    formStatus.style.color = '#ffcccc';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                formStatus.textContent = 'An error occurred. Please try again later.';
                formStatus.style.color = '#ffcccc';
            })
            .finally(() => {
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.textContent = 'Send';
            });
        }
        
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }