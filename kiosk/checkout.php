<?php
/**
 * PAYMENT PAGE
 * Handles payment method selection and order processing
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
    
    // Get showtime and movie details
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
    
    // Get extras for order summary
    $sql = "SELECT * FROM extras WHERE status = 'active'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $extras = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $error_message = "Unable to load checkout information.";
}

// Handle payment processing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_payment'])) {
    try {
        $pdo->beginTransaction();
        
        // Get order data from POST
        $order_data = json_decode($_POST['order_data'], true);
        $payment_method = $_POST['payment_method'];
        
        // Insert sale record
        $sql = "INSERT INTO sales (showtime_id, seats_booked, total_amount, sale_date) VALUES (?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $showtime_id,
            count($order_data['seats']),
            $order_data['total']
        ]);
        
        $sale_id = $pdo->lastInsertId();
        
        // Book the seats
        foreach ($order_data['seats'] as $seat) {
            $sql = "INSERT INTO seats (showtime_id, seat_number, is_booked) VALUES (?, ?, 1)
                    ON DUPLICATE KEY UPDATE is_booked = 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$showtime_id, $seat]);
        }
        
        // Update available seats count
        $sql = "UPDATE showtimes SET available_seats = available_seats - ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([count($order_data['seats']), $showtime_id]);
        
        // Insert extras if any
        if (!empty($order_data['extras'])) {
            foreach ($order_data['extras'] as $extra_id => $quantity) {
                if ($quantity > 0) {
                    $sql = "INSERT INTO sales_extras (sale_id, extra_id, quantity) VALUES (?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$sale_id, $extra_id, $quantity]);
                }
            }
        }
        
        $pdo->commit();
        
        // Return success response
        echo json_encode([
            'success' => true,
            'sale_id' => $sale_id,
            'payment_method' => $payment_method
        ]);
        exit;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode([
            'success' => false,
            'error' => 'Payment processing failed. Please try again.'
        ]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - <?php echo htmlspecialchars($showtime['title']); ?></title>
    <link rel="stylesheet" href="assets/css/checkout.css">
</head>
<body>
    <?php if (isset($error_message)): ?>
        <!-- Error State -->
        <div class="error-container">
            <div class="error-content">
                <h1>⚠️ Error Loading Checkout</h1>
                <p><?php echo htmlspecialchars($error_message); ?></p>
                <button onclick="goBack()" class="back-btn">← Go Back</button>
            </div>
        </div>
    <?php else: ?>
        <!-- Main Content -->
        <div class="checkout-container">
            
            <!-- Header -->
            <header class="header">
                <div class="container">
                    <h1 class="page-title">💳 Payment & Checkout</h1>
                    <p class="subtitle">Complete your movie booking</p>
                </div>
            </header>

            <!-- Main Layout -->
            <main class="main-content">
                <div class="container">
                    <div class="content-grid">
                        
                        <!-- Left Side: Payment Methods -->
                        <div class="payment-section">
                            <h2 class="section-title">💰 Choose Payment Method</h2>
                            
                            <div class="payment-methods">
                                <div class="payment-option" data-method="cash">
                                    <div class="payment-icon">💵</div>
                                    <div class="payment-info">
                                        <h3>Cash Payment</h3>
                                        <p>Pay with cash at the counter</p>
                                    </div>
                                    <div class="payment-radio">
                                        <input type="radio" name="payment_method" value="cash" id="cash">
                                        <label for="cash"></label>
                                    </div>
                                </div>

                                <div class="payment-option" data-method="gcash">
                                    <div class="payment-icon">📱</div>
                                    <div class="payment-info">
                                        <h3>GCash</h3>
                                        <p>Digital wallet payment</p>
                                    </div>
                                    <div class="payment-radio">
                                        <input type="radio" name="payment_method" value="gcash" id="gcash">
                                        <label for="gcash"></label>
                                    </div>
                                </div>

                                <div class="payment-option" data-method="bank">
                                    <div class="payment-icon">🏦</div>
                                    <div class="payment-info">
                                        <h3>Bank Transfer</h3>
                                        <p>Direct bank account transfer</p>
                                    </div>
                                    <div class="payment-radio">
                                        <input type="radio" name="payment_method" value="bank" id="bank">
                                        <label for="bank"></label>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Details -->
                            <div class="payment-details" id="paymentDetails" style="display: none;">
                                <div class="payment-instructions" id="paymentInstructions"></div>
                            </div>
                        </div>

                        <!-- Right Side: Order Summary -->
                        <div class="order-summary">
                            <div class="summary-card">
                                <h2 class="summary-title">📋 Order Summary</h2>
                                
                                <!-- Movie Info -->
                                <div class="movie-summary">
                                    <div class="movie-poster">
                                        <?php if ($showtime['poster_image']): ?>
                                            <img src="../assets/images/<?php echo htmlspecialchars($showtime['poster_image']); ?>" 
                                                 alt="<?php echo htmlspecialchars($showtime['title']); ?>">
                                        <?php else: ?>
                                            <div class="placeholder-poster">🎬</div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="movie-details">
                                        <h3><?php echo htmlspecialchars($showtime['title']); ?></h3>
                                        <p class="showtime-info">
                                            <?php echo date('M j, Y', strtotime($showtime['show_date'])); ?><br>
                                            <?php echo date('g:i A', strtotime($showtime['show_time'])); ?>
                                        </p>
                                    </div>
                                </div>

                                <!-- Order Details -->
                                <div class="order-details">
                                    <div class="detail-section">
                                        <h4>🎫 Tickets</h4>
                                        <div id="ticketsDisplay">Loading...</div>
                                        <div class="detail-cost" id="ticketsCost">$0.00</div>
                                    </div>

                                    <div class="detail-section">
                                        <h4>🍿 Add-ons</h4>
                                        <div id="addonsDisplay">No add-ons</div>
                                        <div class="detail-cost" id="addonsCost">$0.00</div>
                                    </div>
                                </div>

                                <!-- Total -->
                                <div class="total-section">
                                    <div class="total-line">
                                        <span>Total Amount:</span>
                                        <span id="totalAmount">$0.00</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="action-buttons">
                                    <button onclick="goBack()" class="btn back-btn">← Back to Add-ons</button>
                                    <button onclick="processPayment()" class="btn pay-btn" id="payBtn" disabled>
                                        Complete Payment
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </main>

        </div>
    <?php endif; ?>

    <!-- Hidden data for JavaScript -->
    <script>
        window.extrasData = <?php echo json_encode($extras); ?>;
        window.showtimeData = {
            id: <?php echo $showtime_id; ?>,
            price: <?php echo $showtime['price']; ?>,
            title: <?php echo json_encode($showtime['title']); ?>,
            date: <?php echo json_encode(date('M j, Y', strtotime($showtime['show_date']))); ?>,
            time: <?php echo json_encode(date('g:i A', strtotime($showtime['show_time']))); ?>
        };
    </script>
    
    <!-- JavaScript -->
    <script src="assets/js/checkout.js"></script>
</body>
</html>