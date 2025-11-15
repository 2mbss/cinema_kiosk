/*
   TIME SELECTION PAGE JAVASCRIPT
   Handles showtime selection and navigation to seat selection
*/

// Global variables
let movieInfo = {};

// Wait for page to load
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize showtime selection
    initializeShowtimeSelection();
    
    // Add keyboard navigation
    initializeKeyboardNavigation();
    
    // Update sold out cards
    updateSoldOutCards();
    
});

/**
 * INITIALIZE SHOWTIME SELECTION
 * Sets up the showtime selection functionality
 */
function initializeShowtimeSelection() {
    // Get movie data from PHP
    if (window.movieData) {
        movieInfo = window.movieData;
    }
    
    // Add click events to showtime cards
    const showtimeCards = document.querySelectorAll('.showtime-card');
    showtimeCards.forEach(card => {
        card.addEventListener('click', function() {
            const showtimeId = this.dataset.showtimeId;
            if (showtimeId && !this.classList.contains('sold-out-card')) {
                selectShowtime(showtimeId);
            }
        });
        
        // Add hover effects for available showtimes
        if (!card.classList.contains('sold-out-card')) {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        }
    });
}

/**
 * UPDATE SOLD OUT CARDS
 * Marks cards as sold out if no seats available
 */
function updateSoldOutCards() {
    const showtimeCards = document.querySelectorAll('.showtime-card');
    
    showtimeCards.forEach(card => {
        const availabilityText = card.querySelector('.seats-available');
        if (availabilityText && availabilityText.textContent.trim().startsWith('0 seats')) {
            card.classList.add('sold-out-card');
            card.style.cursor = 'not-allowed';
        }
    });
}

/**
 * SELECT SHOWTIME FUNCTION
 * Handles showtime selection and navigation to seat selection
 */
function selectShowtime(showtimeId) {
    // Get the selected showtime card
    const selectedCard = document.querySelector(`[data-showtime-id="${showtimeId}"]`);
    
    if (!selectedCard) {
        console.error('Showtime card not found');
        return;
    }
    
    // Check if showtime is sold out
    if (selectedCard.classList.contains('sold-out-card')) {
        showFeedback('This showtime is sold out');
        return;
    }
    
    // Add selection feedback
    selectedCard.classList.add('selecting');
    
    // Save showtime selection data
    saveShowtimeSelection(showtimeId, selectedCard);
    
    // Show loading state
    showLoading();
    
    // Navigate to seat selection after brief delay
    setTimeout(() => {
        window.location.href = `seat_selection.php?showtime_id=${showtimeId}`;
    }, 800);
}

/**
 * SAVE SHOWTIME SELECTION
 * Saves selected showtime data to localStorage
 */
function saveShowtimeSelection(showtimeId, cardElement) {
    try {
        // Extract showtime information from the card
        const timeElement = cardElement.querySelector('.time');
        const priceElement = cardElement.querySelector('.price');
        const seatsElement = cardElement.querySelector('.seats-available');
        
        const showtimeData = {
            showtimeId: showtimeId,
            movieId: movieInfo.id,
            movieTitle: movieInfo.title,
            time: timeElement ? timeElement.textContent : '',
            price: priceElement ? priceElement.textContent : '',
            availableSeats: seatsElement ? seatsElement.textContent : '',
            timestamp: Date.now()
        };
        
        // Save to localStorage
        localStorage.setItem('selectedShowtime', JSON.stringify(showtimeData));
        console.log('Showtime selection saved:', showtimeData);
        
    } catch (error) {
        console.error('Failed to save showtime selection:', error);
    }
}

/**
 * SHOW FEEDBACK MESSAGE
 * Displays temporary feedback to user
 */
function showFeedback(message) {
    // Remove existing feedback
    const existingFeedback = document.querySelector('.feedback-message');
    if (existingFeedback) {
        existingFeedback.remove();
    }
    
    // Create new feedback
    const feedback = document.createElement('div');
    feedback.className = 'feedback-message';
    feedback.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(220, 53, 69, 0.9);
        color: white;
        padding: 1rem 2rem;
        border-radius: 25px;
        z-index: 1000;
        font-weight: bold;
        animation: fadeInOut 2s ease-out;
    `;
    feedback.textContent = message;
    
    document.body.appendChild(feedback);
    
    // Remove after animation
    setTimeout(() => {
        if (feedback.parentNode) {
            feedback.remove();
        }
    }, 2000);
}

/**
 * SHOW LOADING STATE
 * Displays loading overlay during navigation
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
            <p style="margin-top: 1rem;">Loading seat selection...</p>
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
 * GO BACK FUNCTION
 * Returns to movie details page
 */
function goBack() {
    // Add fade-out effect
    document.body.style.transition = 'opacity 0.5s ease-out';
    document.body.style.opacity = '0';
    
    // Navigate after animation
    setTimeout(() => {
        window.location.href = `movie_details.php?id=${movieInfo.id}`;
    }, 500);
}

/**
 * KEYBOARD NAVIGATION
 * Adds keyboard shortcuts for accessibility
 */
function initializeKeyboardNavigation() {
    document.addEventListener('keydown', function(event) {
        switch(event.key) {
            case 'Escape':
                // ESC key - go back
                goBack();
                break;
                
            case '1':
            case '2':
            case '3':
            case '4':
            case '5':
            case '6':
            case '7':
            case '8':
            case '9':
                // Number keys - select showtime by index
                const showtimeIndex = parseInt(event.key) - 1;
                const showtimeCards = document.querySelectorAll('.showtime-card:not(.sold-out-card)');
                if (showtimeCards[showtimeIndex]) {
                    const showtimeId = showtimeCards[showtimeIndex].dataset.showtimeId;
                    if (showtimeId) {
                        selectShowtime(showtimeId);
                    }
                }
                break;
        }
    });
}

/**
 * HIGHLIGHT SHOWTIME CARDS
 * Adds visual indicators for keyboard navigation
 */
function addKeyboardIndicators() {
    const showtimeCards = document.querySelectorAll('.showtime-card:not(.sold-out-card)');
    
    showtimeCards.forEach((card, index) => {
        if (index < 9) { // Only show for first 9 showtimes
            const indicator = document.createElement('div');
            indicator.className = 'keyboard-indicator';
            indicator.textContent = index + 1;
            indicator.style.cssText = `
                position: absolute;
                top: 10px;
                left: 10px;
                background: rgba(78, 205, 196, 0.8);
                color: white;
                width: 25px;
                height: 25px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.8rem;
                font-weight: bold;
            `;
            card.style.position = 'relative';
            card.appendChild(indicator);
        }
    });
}

/**
 * REFRESH AVAILABILITY
 * Periodically updates seat availability (optional enhancement)
 */
function startAvailabilityRefresh() {
    setInterval(() => {
        // In a real application, you might want to refresh availability
        // This is just a placeholder for the concept
        console.log('Checking for availability updates...');
    }, 30000); // Check every 30 seconds
}

// Initialize keyboard indicators when page loads
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(addKeyboardIndicators, 500);
});

/*
   HOW SELECTED SHOWTIME IS CARRIED TO NEXT PAGE:

   1. URL PARAMETER METHOD:
      - When showtime is selected, redirect to: seat_selection.php?showtime_id=123
      - Next page receives showtime ID via $_GET['showtime_id']
      - Database query fetches full showtime details using the ID

   2. LOCALSTORAGE METHOD (Additional):
      - Save complete showtime data to localStorage as backup
      - Includes: showtimeId, movieId, time, price, availableSeats
      - Next page can access this data if needed

   3. DATA FLOW:
      User clicks showtime → JavaScript gets showtime_id → 
      Save to localStorage → Redirect with URL parameter →
      Next page receives ID → Database query for full details

   4. SECURITY CONSIDERATIONS:
      - URL parameter is validated on next page
      - Database query ensures showtime exists and is valid
      - localStorage is just for user experience, not security

   5. ERROR HANDLING:
      - Invalid showtime_id redirects back to movie selection
      - Sold out showtimes are disabled and show feedback
      - Database errors show appropriate error messages
*/