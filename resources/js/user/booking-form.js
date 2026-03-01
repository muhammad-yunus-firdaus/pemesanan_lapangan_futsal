/**
 * Booking Form Script
 * Handles multi-step booking form functionality
 * - Field selection and preview
 * - Duration calculation
 * - Price summary updates
 * - Progress indicator
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const fieldSelect = document.getElementById('field_id');
    const durationInput = document.getElementById('duration');
    const bookingTimeInput = document.getElementById('booking_time');
    const fieldPreview = document.getElementById('fieldPreview');
    const totalPriceEl = document.getElementById('totalPrice');
    const pricePerHourEl = document.getElementById('pricePerHour');
    const durationDisplayEl = document.getElementById('durationDisplay');
    const progressLine = document.getElementById('progressLine');

    // Exit if required elements not found
    if (!fieldSelect || !durationInput) {
        console.warn('Booking form elements not found');
        return;
    }

    /**
     * Parse field data from select options
     * Format: {id: {name, price, description, image}}
     */
    const fieldsData = {};
    Array.from(fieldSelect.options).forEach(option => {
        if (option.value) {
            fieldsData[option.value] = {
                name: option.text.split('-')[0].trim(),
                price: parseFloat(option.dataset.price) || 0,
                description: option.dataset.description || '',
                image: option.dataset.image || ''
            };
        }
    });

    /**
     * Update progress bar and step indicators
     * Progress calculation:
     * - Field selected: 33%
     * - Booking time set: 66%
     * - Duration selected: 100%
     */
    function updateProgress() {
        let progress = 0;
        
        // Calculate progress based on filled fields
        if (fieldSelect.value) progress = 33;
        if (bookingTimeInput && bookingTimeInput.value) progress = 66;
        if (durationInput.value > 0) progress = 100;
        
        // Update progress bar width
        if (progressLine) {
            progressLine.style.width = progress + '%';
        }
        
        // Mark steps as completed
        const steps = document.querySelectorAll('.step');
        steps.forEach((step, index) => {
            const isCompleted = (index === 0 && fieldSelect.value) || 
                              (index === 1 && bookingTimeInput && bookingTimeInput.value) || 
                              (index === 2 && durationInput.value > 0);
            
            if (isCompleted) {
                step.classList.add('completed');
            } else {
                step.classList.remove('completed');
            }
        });
    }

    /**
     * Update field preview card and price summary
     * Shows field details when a field is selected
     * Calculates total price based on duration
     */
    function updatePreviewAndPrice() {
        const selectedId = fieldSelect.value;
        const duration = parseInt(durationInput.value) || 0;
        
        // If field is selected and exists in data
        if (selectedId && fieldsData[selectedId]) {
            const field = fieldsData[selectedId];
            
            // Show preview card
            if (fieldPreview) {
                fieldPreview.classList.add('show');
                
                // Update preview image
                const previewImage = document.getElementById('previewImage');
                if (previewImage) {
                    previewImage.src = field.image;
                    previewImage.alt = field.name;
                }
                
                // Update preview name
                const previewName = document.getElementById('previewName');
                if (previewName) {
                    previewName.textContent = field.name;
                }
                
                // Update preview description
                const previewDescription = document.getElementById('previewDescription');
                if (previewDescription) {
                    previewDescription.textContent = field.description || 
                        'Lapangan futsal berkualitas dengan fasilitas lengkap';
                }
                
                // Update preview price
                const previewPrice = document.getElementById('previewPrice');
                if (previewPrice) {
                    previewPrice.textContent = `Rp ${field.price.toLocaleString('id-ID')}/jam`;
                }
            }
            
            // Calculate and update price summary
            const pricePerHour = field.price;
            const total = pricePerHour * duration;
            
            if (pricePerHourEl) {
                pricePerHourEl.textContent = `Rp ${pricePerHour.toLocaleString('id-ID')}`;
            }
            
            if (durationDisplayEl) {
                durationDisplayEl.textContent = `${duration} Jam`;
            }
            
            if (totalPriceEl) {
                totalPriceEl.textContent = duration > 0 ? 
                    `Rp ${total.toLocaleString('id-ID')}` : 'Rp 0';
            }
        } else {
            // Hide preview if no field selected
            if (fieldPreview) {
                fieldPreview.classList.remove('show');
            }
            
            // Reset price display
            if (totalPriceEl) totalPriceEl.textContent = 'Rp 0';
            if (pricePerHourEl) pricePerHourEl.textContent = 'Rp 0';
            if (durationDisplayEl) durationDisplayEl.textContent = '0 Jam';
        }
        
        // Update progress
        updateProgress();
    }

    /**
     * Handle duration pill click
     * Quick select duration (1-6 hours)
     */
    const durationPills = document.querySelectorAll('.duration-pill');
    durationPills.forEach(pill => {
        pill.addEventListener('click', function() {
            // Remove active class from all pills
            durationPills.forEach(p => p.classList.remove('active'));
            
            // Add active class to clicked pill
            this.classList.add('active');
            
            // Update duration input value
            durationInput.value = this.dataset.duration;
            
            // Update preview and price
            updatePreviewAndPrice();
        });
    });

    /**
     * Event Listeners
     */
    
    // Field selection change
    fieldSelect.addEventListener('change', updatePreviewAndPrice);
    
    // Manual duration input
    durationInput.addEventListener('input', function() {
        // Deactivate all pills when manually typing
        durationPills.forEach(p => p.classList.remove('active'));
        updatePreviewAndPrice();
    });
    
    // Booking time change
    if (bookingTimeInput) {
        bookingTimeInput.addEventListener('change', updateProgress);
    }
    
    /**
     * Initialize on page load
     */
    updatePreviewAndPrice();
});
