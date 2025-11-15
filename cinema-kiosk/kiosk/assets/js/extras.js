/*
   EXTRAS PAGE JAVASCRIPT
   Handles add-on selection and order summary updates
*/

// Global variables
let selectedExtras = {};
let extrasData = [];
let showtimeInfo = {};
let seatData = {};

// Wait for page to load
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize extras system
    initializeExtras();
    
    // Load seat selection data
    loadSeatData();
    
    // Update order summary
    updateOrderSummary();
    
    // Add keyboard navigation
    initializeKeyboardNavigation();
    
});

/**
 * INITIALIZE EXTRAS SYSTEM
 * Sets up the extras selection functionality
 */
function initializeExtras() {
    // Get data from PHP
    if (window.extrasData) {
        extrasData = window.extrasData;
    }
    
    if (window.showtimeData) {
        showtimeInfo = window.showtimeData;
    }
    
    // Initialize selected extras object
    extrasData.forEach(extra => {
        selectedExtras[extra.id] = 0;
    });
}

/**
 * LOAD SEAT DATA
 * Loads selected seats from localStorage
 */
function loadSeatData() {
    try {
        const savedData = localStorage.getItem('selectedSeats');
        
        if (savedData) {
            seatData = JSON.parse(savedData);
            
            // Verify it's for current showtime
            if (seatData.showtimeId === showtimeInfo.id) {
                console.log('Loaded seat data:', seatData);
            } else {
                // Wrong showtime, redirect back
                alert('Seat selection expired. Please select seats again.');
                window.location.href = `seat_selection.php?showtime_id=${showtimeInfo.id}`;
                return;
            }
        } else {
            // No seat data, redirect back
            alert('No seats selected. Please select seats first.');
            window.location.href = `seat_selection.php?showtime_id=${showtimeInfo.id}`;
            return;
        }
    } catch (error) {
        console.error('Failed to load seat data:', error);
        alert('Error loading seat selection. Please try again.');
        window.location.href = `seat_selection.php?showtime_id=${showtimeInfo.id}`;
    }
}

/**
 * CHANGE QUANTITY
 * Handles +/- buttons for add-ons
 */
function changeQuantity(extraId, change) {
    const currentQty = selectedExtras[extraId] || 0;
    const newQty = Math.max(0, Math.min(10, currentQty + change)); // Limit 0-10
    
    selectedExtras[extraId] = newQty;
    
    // Update display
    const qtyElement = document.getElementById(`qty-${extraId}`);
    if (qtyElement) {
        qtyElement.textContent = newQty;
    }
    
    // Update minus button state
    const minusBtn = qtyElement.parentNode.querySelector('.minus');
    if (minusBtn) {
        minusBtn.disabled = newQty === 0;
    }
    
    // Update order summary
    updateOrderSummary();
    
    // Save to localStorage
    saveExtrasData();
    
    // Show feedback
    const extra = extrasData.find(e => e.id == extraId);
    if (extra) {
        if (change > 0) {
            showFeedback(`Added ${extra.name}`);
        } else if (change < 0 && newQty >= 0) {
            showFeedback(`Removed ${extra.name}`);
        }
    }
}

/**
 * UPDATE ORDER SUMMARY
 * Updates the right-side order summary panel
 */
function updateOrderSummary() {
    updateSeatsDisplay();
    updateExtrasDisplay();
    updateTotalCost();
}

/**
 * UPDATE SEATS DISPLAY
 * Shows selected seats in order summary
 */
function updateSeatsDisplay() {
    const seatsDisplay = document.getElementById('selectedSeatsDisplay');
    const seatsCost = document.getElementById('seatsCost');
    
    if (seatData.selectedSeats && seatData.selectedSeats.length > 0) {
        seatsDisplay.textContent = `${seatData.selectedSeats.join(', ')} (${seatData.selectedSeats.length} seats)`;
        seatsCost.textContent = `$${seatData.totalCost.toFixed(2)}`;
    } else {
        seatsDisplay.textContent = 'No seats selected';
        seatsCost.textContent = '$0.00';
    }
}

/**
 * UPDATE EXTRAS DISPLAY
 * Shows selected add-ons in order summary
 */
function updateExtrasDisplay() {
    const extrasDisplay = document.getElementById('extrasDisplay');
    const extrasCost = document.getElementById('extrasCost');
    
    const selectedItems = [];
    let totalExtrasCost = 0;
    
    Object.keys(selectedExtras).forEach(extraId => {
        const quantity = selectedExtras[extraId];
        if (quantity > 0) {
            const extra = extrasData.find(e => e.id == extraId);
            if (extra) {
                selectedItems.push(`${extra.name} x${quantity}`);
                totalExtrasCost += extra.price * quantity;
            }
        }
    });
    
    if (selectedItems.length > 0) {
        extrasDisplay.innerHTML = selectedItems.map(item => `<div>${item}</div>`).join('');
    } else {
        extrasDisplay.textContent = 'No add-ons selected';
    }
    
    extrasCost.textContent = `$${totalExtrasCost.toFixed(2)}`;
}

/**
 * UPDATE TOTAL COST
 * Calculates and displays grand total
 */
function updateTotalCost() {
    const seatsCost = seatData.totalCost || 0;
    
    let extrasCost = 0;
    Object.keys(selectedExtras).forEach(extraId => {
        const quantity = selectedExtras[extraId];
        if (quantity > 0) {
            const extra = extrasData.find(e => e.id == extraId);
            if (extra) {
                extrasCost += extra.price * quantity;
            }
        }
    });
    
    const grandTotal = seatsCost + extrasCost;
    
    const grandTotalElement = document.getElementById('grandTotal');
    if (grandTotalElement) {
        grandTotalElement.textContent = `$${grandTotal.toFixed(2)}`;
    }
}

/**
 * SAVE EXTRAS DATA
 * Saves selected extras to localStorage
 */
function saveExtrasData() {
    const extrasOrderData = {
        showtimeId: showtimeInfo.id,
        selectedExtras: selectedExtras,
        timestamp: Date.now()
    };
    
    try {
        localStorage.setItem('selectedExtras', JSON.stringify(extrasOrderData));
        console.log('Extras saved to localStorage:', extrasOrderData);
    } catch (error) {
        console.error('Failed to save extras:', error);
    }
}

/**
 * SHOW FEEDBACK MESSAGE
 * Displays temporary feedback to user
 */
function showFeedback(message) {
    // Remove existing feedback
    const existingFeedback = document.querySelector('.extras-feedback');
    if (existingFeedback) {
        existingFeedback.remove();
    }
    
    // Create new feedback
    const feedback = document.createElement('div');
    feedback.className = 'extras-feedback';
    feedback.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(78, 205, 196, 0.9);
        color: white;
        padding: 1rem 2rem;
        border-radius: 25px;
        z-index: 1000;
        font-weight: bold;
        animation: fadeInOut 1.5s ease-out;
    `;
    feedback.textContent = message;
    
    document.body.appendChild(feedback);
    
    // Remove after animation
    setTimeout(() => {
        if (feedback.parentNode) {
            feedback.remove();
        }
    }, 1500);
}

/**
 * GO BACK FUNCTION
 * Returns to seat selection
 */
function goBack() {
    // Save current extras selection
    saveExtrasData();
    
    // Add fade-out effect
    document.body.style.transition = 'opacity 0.5s ease-out';
    document.body.style.opacity = '0';
    
    // Navigate after animation
    setTimeout(() => {
        window.location.href = `seat_selection.php?showtime_id=${showtimeInfo.id}`;
    }, 500);
}

/**
 * PROCEED TO CHECKOUT
 * Navigates to checkout/payment page
 */
function proceedToCheckout() {
    // Save all order data
    saveExtrasData();
    
    // Prepare complete order data
    const completeOrder = {
        showtime: showtimeInfo,
        seats: seatData,
        extras: selectedExtras,
        timestamp: Date.now()
    };
    
    try {
        localStorage.setItem('completeOrder', JSON.stringify(completeOrder));
    } catch (error) {
        console.error('Failed to save complete order:', error);
    }
    
    // Show loading state
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) {
        checkoutBtn.innerHTML = 'Processing...';
        checkoutBtn.disabled = true;
    }
    
    showLoading();
    
    // Navigate to checkout
    setTimeout(() => {
        window.location.href = `checkout.php?showtime_id=${showtimeInfo.id}`;
    }, 800);
}

/**
 * SHOW LOADING STATE
 * Displays loading overlay
 */
function showLoading() {
    const loadingOverlay = document.createElement('div');
    loadingOverlay.id = 'loadingOverlay';
    loadingOverlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        color: white;
        font-size: 1.2rem;
    `;
    
    loadingOverlay.innerHTML = `
        <div style="text-align: center;">
            <div class="loading-spinner"></div>
            <p style="margin-top: 1rem;">Preparing checkout...</p>
        </div>
    `;
    
    // Add loading spinner styles
    const style = document.createElement('style');
    style.textContent = `
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin: 0 auto;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        @keyframes fadeInOut {
            0%, 100% { opacity: 0; }
            50% { opacity: 1; }
        }
    `;
    document.head.appendChild(style);
    document.body.appendChild(loadingOverlay);
}

/**
 * KEYBOARD NAVIGATION
 * Adds keyboard shortcuts
 */
function initializeKeyboardNavigation() {
    document.addEventListener('keydown', function(event) {
        switch(event.key) {
            case 'Escape':
                // ESC - go back
                goBack();
                break;
                
            case 'Enter':
                // Enter - proceed to checkout
                proceedToCheckout();
                break;
        }
    });
}

/**
 * CLEAR ALL EXTRAS
 * Resets all add-on quantities to 0
 */
function clearAllExtras() {
    Object.keys(selectedExtras).forEach(extraId => {
        selectedExtras[extraId] = 0;
        const qtyElement = document.getElementById(`qty-${extraId}`);
        if (qtyElement) {
            qtyElement.textContent = '0';
        }
        
        const minusBtn = qtyElement.parentNode.querySelector('.minus');
        if (minusBtn) {
            minusBtn.disabled = true;
        }
    });
    
    updateOrderSummary();
    saveExtrasData();
    showFeedback('All add-ons cleared');
}

/*
   HOW ORDER SUMMARY UPDATES:

   1. SEAT DATA LOADING:
      - Loads selected seats from localStorage (from previous page)
      - Validates data is for current showtime
      - Displays seat numbers and total cost

   2. REAL-TIME EXTRAS UPDATES:
      - When user clicks +/- buttons, changeQuantity() is called
      - Updates selectedExtras object with new quantities
      - Immediately updates order summary display
      - Saves to localStorage for persistence

   3. ORDER SUMMARY COMPONENTS:
      - Movie info: Title, poster, showtime (from PHP data)
      - Seats: List of selected seats + cost (from localStorage)
      - Add-ons: List of selected extras + quantities + cost (real-time)
      - Total: Sum of seats + add-ons (auto-calculated)

   4. DATA FLOW:
      Previous Page → localStorage (seats) → This Page → Display
      User Interaction → selectedExtras object → Update Display → localStorage

   5. PERSISTENCE:
      - Seat data: Loaded from previous page
      - Extras data: Saved on every change
      - Complete order: Saved before checkout
*/