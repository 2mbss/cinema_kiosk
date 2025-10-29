<?php
/**
 * SEAT SELECTION PAGE
 * Interactive seat selection with real-time updates
 */

// Include database configuration
require_once '../db/config.php';

// Get showtime ID from URL parameter
$showtime_id = isset($_GET['showtime_id']) ? (int)$_GET['showtime_id'] : 0;

if ($showtime_id <= 0) {
    header('Location: movies.php');
    exit;
}

try {
    $pdo = getDBConnection();
    
    // Get showtime details with movie information
    $sql = "SELECT s.*, m.title, m.poster_image 
            FROM showtimes s 
            JOIN movies m ON s.movie_id = m.id 
            WHERE s.id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$showtime_id]);
    $showtime = $stmt->fetch();
    
    if (!$showtime) {
        header('Location: movies.php');
        exit;
    }
    
    // Get booked seats for this showtime
    $sql = "SELECT seat_number FROM seats WHERE showtime_id = ? AND is_booked = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$showtime_id]);
    $booked_seats = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
} catch (PDOException $e) {
    $error_message = "Unable to load seat information.";
    $booked_seats = [];
}

// Generate seat grid (8 rows A-H, 12 columns 1-12)
function generateSeatGrid($booked_seats) {
    $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
    $seats = [];
    
    foreach ($rows as $row) {
        for ($col = 1; $col <= 12; $col++) {
            $seat_number = $row . $col;
            $seats[$row][] = [
                'number' => $seat_number,
                'status' => in_array($seat_number, $booked_seats) ? 'booked' : 'available'
            ];
        }
    }
    
    return $seats;
}

$seat_grid = generateSeatGrid($booked_seats);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Seats - <?php echo htmlspecialchars($showtime['title']); ?></title>
    <link rel="stylesheet" href="assets/css/seat_selection.css">
</head>
<body>
    <?php if (isset($error_message)): ?>
        <!-- Error State -->
        <div class="error-container">
            <div class="error-content">
                <h1>⚠️ Error Loading Seats</h1>
                <p><?php echo htmlspecialchars($error_message); ?></p>
                <button onclick="goBack()" class="back-btn">← Go Back</button>
            </div>
        </div>
    <?php else: ?>
        <!-- Main Content -->
        <div class="seat-selection-container">
            
            <!-- Header Section -->
            <header class="header">
                <div class="container">
                    <div class="showtime-info">
                        <h1 class="movie-title"><?php echo htmlspecialchars($showtime['title']); ?></h1>
                        <div class="showtime-details">
                            <span class="date"><?php echo date('M j, Y', strtotime($showtime['show_date'])); ?></span>
                            <span class="time"><?php echo date('g:i A', strtotime($showtime['show_time'])); ?></span>
                            <span class="price">$<?php echo number_format($showtime['price'], 2); ?> per seat</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Seat Legend -->
            <section class="legend-section">
                <div class="container">
                    <div class="seat-legend">
                        <div class="legend-item">
                            <div class="legend-seat available"></div>
                            <span>Available</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-seat selected"></div>
                            <span>Selected</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-seat booked"></div>
                            <span>Sold</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Cinema Screen -->
            <section class="screen-section">
                <div class="container">
                    <div class="screen">
                        <div class="screen-text">SCREEN</div>
                    </div>
                </div>
            </section>

            <!-- Seat Grid -->
            <section class="seat-grid-section">
                <div class="container">
                    <div class="seat-grid" id="seatGrid">
                        <?php foreach ($seat_grid as $row_letter => $row_seats): ?>
                            <div class="seat-row">
                                <div class="row-label"><?php echo $row_letter; ?></div>
                                <div class="seats">
                                    <?php foreach ($row_seats as $seat): ?>
                                        <button 
                                            class="seat <?php echo $seat['status']; ?>"
                                            data-seat="<?php echo $seat['number']; ?>"
                                            <?php echo $seat['status'] === 'booked' ? 'disabled' : ''; ?>>
                                            <?php echo $seat['number']; ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                                <div class="row-label"><?php echo $row_letter; ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <!-- Selection Summary -->
            <section class="summary-section">
                <div class="container">
                    <div class="selection-summary" id="selectionSummary">
                        <div class="summary-content">
                            <div class="selected-seats">
                                <h3>Selected Seats: <span id="selectedSeatsDisplay">None</span></h3>
                                <p class="seat-count">Count: <span id="seatCount">0</span></p>
                            </div>
                            <div class="total-cost">
                                <h3>Total: <span id="totalCost">$0.00</span></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Navigation -->
            <section class="navigation-section">
                <div class="container">
                    <div class="nav-buttons">
                        <button onclick="goBack()" class="nav-btn back-btn">
                            ← Back to Showtimes
                        </button>
                        <button onclick="proceedToExtras()" class="nav-btn next-btn" id="nextBtn" disabled>
                            Add Extras →
                        </button>
                    </div>
                </div>
            </section>

        </div>
    <?php endif; ?>

    <!-- Hidden data for JavaScript -->
    <script>
        // Pass PHP data to JavaScript
        window.showtimeData = {
            id: <?php echo $showtime_id; ?>,
            price: <?php echo $showtime['price']; ?>,
            title: <?php echo json_encode($showtime['title']); ?>,
            date: <?php echo json_encode(date('M j, Y', strtotime($showtime['show_date']))); ?>,
            time: <?php echo json_encode(date('g:i A', strtotime($showtime['show_time']))); ?>
        };
    </script>
    
    <!-- JavaScript -->
    <script src="assets/js/seat_selection.js"></script>
</body>
</html>