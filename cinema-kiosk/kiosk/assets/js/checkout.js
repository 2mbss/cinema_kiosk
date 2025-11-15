/*
   CHECKOUT PAGE JAVASCRIPT
   Handles payment method selection and order processing
*/

// Global variables
let orderData = {};
let selectedPaymentMethod = '';
let extrasData = [];
let showtimeInfo = {};

// Wait for page to load
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize checkout system
    initializeCheckout();
    
    // Load order data
    loadOrderData();
    
    // Setup payment method selection
    setupPaymentMethods();
    
    // Update order summary
    updateOrderSummary();
    
    // Add keyboard navigation
    initializeKeyboardNavigation();
    
});

/**
 * INITIALIZE CHECKOUT SYSTEM
 * Sets up the checkout functionality
 */
function initializeCheckout() {
    // Get data from PHP
    if (window.extrasData) {
        extrasData = window.extrasData;
    }
    
    if (window.showtimeData) {
        showtimeInfo = window.showtimeData;
    }
}

/**
 * LOAD ORDER DATA
 * Loads complete order from localStorage
 */
function loadOrderData() {
    try {
        // Load seat data
        const seatData = localStorage.getItem('selectedSeats');
        const extrasOrderData = localStorage.getItem('selectedExtras');
        
        if (!seatData) {
            alert('Order data not found. Please start over.');
            window.location.href = 'movies.php';
            return;
        }
        
        const seats = JSON.parse(seatData);
        const extras = extrasOrderData ? JSON.parse(extrasOrderData) : { selectedExtras: {} };
        
        // Verify showtime matches
        if (seats.showtimeId !== showtimeInfo.id) {
            alert('Order data mismatch. Please start over.');
            window.location.href = 'movies.php';
            return;
        }
        
        // Build complete order data
        orderData = {
            showtime: showtimeInfo,
            seats: seats.selectedSeats || [],
            seatsCost: seats.totalCost || 0,
            extras: extras.selectedExtras || {},
            timestamp: Date.now()
        };
        
        console.log('Order data loaded:', orderData);
        
    } catch (error) {
        console.error('Failed to load order data:', error);
        alert('Error loading order. Please try again.');
        window.location.href = 'movies.php';
    }
}

/**
 * SETUP PAYMENT METHODS
 * Handles payment method selection
 */
function setupPaymentMethods() {
    const paymentOptions = document.querySelectorAll('.payment-option');
    const paymentDetails = document.getElementById('paymentDetails');
    const paymentInstructions = document.getElementById('paymentInstructions');
    const payBtn = document.getElementById('payBtn');
    
    paymentOptions.forEach(option => {
        option.addEventListener('click', function() {
            const method = this.dataset.method;
            const radio = this.querySelector('input[type="radio"]');
            
            // Update selection
            paymentOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            radio.checked = true;
            
            selectedPaymentMethod = method;
            
            // Show payment instructions
            showPaymentInstructions(method);
            paymentDetails.style.display = 'block';
            
            // Enable pay button
            payBtn.disabled = false;
        });
    });
}

/**
 * SHOW PAYMENT INSTRUCTIONS
 * Displays instructions for selected payment method
 */
function showPaymentInstructions(method) {
    const instructions = document.getElementById('paymentInstructions');
    
    const instructionText = {
        cash: `
            <h4>💵 Cash Payment Instructions</h4>
            <ul>
                <li>Proceed to the counter after confirmation</li>
                <li>Present this order confirmation</li>
                <li>Pay the exact amount: <strong>$${calculateTotal().toFixed(2)}</strong></li>
                <li>Collect your tickets and receipt</li>
            </ul>
        `,
        gcash: `
            <h4>📱 GCash Payment Instructions</h4>
            <ul>
                <li>Open your GCash app</li>
                <li>Scan the QR code at the counter</li>
                <li>Enter amount: <strong>$${calculateTotal().toFixed(2)}</strong></li>
                <li>Complete the transaction</li>
                <li>Show confirmation to staff</li>
            </ul>
        `,
        bank: `
            <h4>🏦 Bank Transfer Instructions</h4>
            <ul>
                <li>Transfer to: Cinema Account #123456789</li>
                <li>Amount: <strong>$${calculateTotal().toFixed(2)}</strong></li>
                <li>Reference: Your order number</li>
                <li>Present transfer receipt at counter</li>
            </ul>
        `
    };
    
    instructions.innerHTML = instructionText[method] || '';
}

/**
 * UPDATE ORDER SUMMARY
 * Updates the order summary display
 */
function updateOrderSummary() {
    updateTicketsDisplay();
    updateAddonsDisplay();
    updateTotalDisplay();
}

/**
 * UPDATE TICKETS DISPLAY
 * Shows selected tickets in summary
 */
function updateTicketsDisplay() {
    const ticketsDisplay = document.getElementById('ticketsDisplay');
    const ticketsCost = document.getElementById('ticketsCost');
    
    if (orderData.seats && orderData.seats.length > 0) {
        ticketsDisplay.innerHTML = `
            <div>Seats: ${orderData.seats.join(', ')}</div>
            <div>${orderData.seats.length} ticket(s) × $${showtimeInfo.price}</div>
        `;
        ticketsCost.textContent = `$${orderData.seatsCost.toFixed(2)}`;
    } else {
        ticketsDisplay.textContent = 'No tickets selected';
        ticketsCost.textContent = '$0.00';
    }
}

/**
 * UPDATE ADDONS DISPLAY
 * Shows selected add-ons in summary
 */
function updateAddonsDisplay() {
    const addonsDisplay = document.getElementById('addonsDisplay');
    const addonsCost = document.getElementById('addonsCost');
    
    const selectedAddons = [];
    let totalAddonsCost = 0;
    
    if (orderData.extras) {
        Object.keys(orderData.extras).forEach(extraId => {
            const quantity = orderData.extras[extraId];
            if (quantity > 0) {
                const extra = extrasData.find(e => e.id == extraId);
                if (extra) {
                    selectedAddons.push(`${extra.name} × ${quantity}`);
                    totalAddonsCost += extra.price * quantity;
                }
            }
        });
    }
    
    if (selectedAddons.length > 0) {
        addonsDisplay.innerHTML = selectedAddons.map(addon => `<div>${addon}</div>`).join('');
    } else {
        addonsDisplay.textContent = 'No add-ons selected';
    }
    
    addonsCost.textContent = `$${totalAddonsCost.toFixed(2)}`;
}

/**
 * UPDATE TOTAL DISPLAY
 * Shows grand total
 */
function updateTotalDisplay() {
    const totalAmount = document.getElementById('totalAmount');
    const total = calculateTotal();
    totalAmount.textContent = `$${total.toFixed(2)}`;
}

/**
 * CALCULATE TOTAL
 * Calculates total order amount
 */
function calculateTotal() {
    let total = orderData.seatsCost || 0;
    
    if (orderData.extras) {
        Object.keys(orderData.extras).forEach(extraId => {
            const quantity = orderData.extras[extraId];
            if (quantity > 0) {
                const extra = extrasData.find(e => e.id == extraId);
                if (extra) {
                    total += extra.price * quantity;
                }
            }
        });
    }
    
    return total;
}

/**
 * PROCESS PAYMENT
 * Handles payment processing and database update
 */
function processPayment() {
    if (!selectedPaymentMethod) {
        alert('Please select a payment method');
        return;
    }
    
    // Show loading state
    const payBtn = document.getElementById('payBtn');
    payBtn.innerHTML = '<span class="loading-spinner"></span> Processing...';
    payBtn.disabled = true;
    
    document.body.classList.add('processing');
    
    // Prepare order data for submission
    const submitData = {
        seats: orderData.seats,
        extras: orderData.extras,
        total: calculateTotal()
    };
    
    // Create form data
    const formData = new FormData();
    formData.append('process_payment', '1');
    formData.append('payment_method', selectedPaymentMethod);
    formData.append('order_data', JSON.stringify(submitData));
    
    // Submit to server
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Save transaction data
            const transactionData = {
                saleId: data.sale_id,
                paymentMethod: data.payment_method,
                orderData: orderData,
                total: calculateTotal(),
                timestamp: Date.now()
            };
            
            localStorage.setItem('transactionData', JSON.stringify(transactionData));
            
            // Clear order data
            localStorage.removeItem('selectedSeats');
            localStorage.removeItem('selectedExtras');
            localStorage.removeItem('completeOrder');
            
            // Redirect to receipt
            setTimeout(() => {
                window.location.href = `receipt.php?sale_id=${data.sale_id}`;
            }, 1000);
            
        } else {
            throw new Error(data.error || 'Payment processing failed');
        }
    })
    .catch(error => {
        console.error('Payment error:', error);
        alert('Payment failed: ' + error.message);
        
        // Reset button
        payBtn.innerHTML = 'Complete Payment';
        payBtn.disabled = false;
        document.body.classList.remove('processing');
    });
}

/**
 * GO BACK FUNCTION
 * Returns to extras page
 */
function goBack() {
    // Add fade-out effect
    document.body.style.transition = 'opacity 0.5s ease-out';
    document.body.style.opacity = '0';
    
    // Navigate after animation
    setTimeout(() => {
        window.location.href = `extras.php?showtime_id=${showtimeInfo.id}`;
    }, 500);
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
                // Enter - process payment if method selected
                if (selectedPaymentMethod) {
                    processPayment();
                }
                break;
                
            case '1':
                // Number keys for payment method selection
                selectPaymentMethod('cash');
                break;
            case '2':
                selectPaymentMethod('gcash');
                break;
            case '3':
                selectPaymentMethod('bank');
                break;
        }
    });
}

/**
 * SELECT PAYMENT METHOD
 * Programmatically select payment method
 */
function selectPaymentMethod(method) {
    const option = document.querySelector(`[data-method="${method}"]`);
    if (option) {
        option.click();
    }
}

/*
   HOW ORDER AND PAYMENT ARE STORED:

   1. ORDER DATA COLLECTION:
      - Seats: Loaded from localStorage (from seat selection page)
      - Extras: Loaded from localStorage (from extras page)
      - Combined into complete order object

   2. PAYMENT PROCESSING:
      - User selects payment method (cash/gcash/bank)
      - Order data sent to PHP via POST request
      - PHP processes transaction in database

   3. DATABASE STORAGE:
      - sales table: Main transaction record
      - seats table: Individual seat bookings
      - sales_extras table: Add-on items purchased
      - showtimes table: Updated available seats count

   4. TRANSACTION FLOW:
      Frontend Order → POST to PHP → Database Transaction → Response → Receipt

   5. DATA PERSISTENCE:
      - Order data: localStorage until payment complete
      - Transaction: Permanent database storage
      - Receipt data: localStorage for receipt display
*/