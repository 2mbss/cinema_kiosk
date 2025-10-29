<?php
/**
 * ADD-ONS PAGE
 * Displays snacks and drinks with order summary
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
    
    // Get available extras (snacks and drinks)
    $sql = "SELECT * FROM extras WHERE status = 'active' ORDER BY category, name";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $extras = $stmt->fetchAll();
    
    // Group extras by category
    $snacks = array_filter($extras, function($extra) {
        return $extra['category'] === 'snack';
    });
    
    $drinks = array_filter($extras, function($extra) {
        return $extra['category'] === 'drink';
    });
    
} catch (PDOException $e) {
    $error_message = "Unable to load add-ons.";
    $snacks = [];
    $drinks = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Extras - <?php echo htmlspecialchars($showtime['title']); ?></title>
    <link rel="stylesheet" href="assets/css/extras.css">
</head>
<body>
    <?php if (isset($error_message)): ?>
        <!-- Error State -->
        <div class="error-container">
            <div class="error-content">
                <h1>⚠️ Error Loading Add-ons</h1>
                <p><?php echo htmlspecialchars($error_message); ?></p>
                <button onclick="goBack()" class="back-btn">← Go Back</button>
            </div>
        </div>
    <?php else: ?>
        <!-- Main Content -->
        <div class="extras-container">
            
            <!-- Header -->
            <header class="header">
                <div class="container">
                    <h1 class="page-title">🍿 Add Snacks & Drinks</h1>
                    <p class="subtitle">Enhance your movie experience</p>
                </div>
            </header>

            <!-- Main Layout -->
            <main class="main-content">
                <div class="container">
                    <div class="content-grid">
                        
                        <!-- Left Side: Add-ons Selection -->
                        <div class="addons-section">
                            
                            <!-- Snacks Section -->
                            <section class="category-section">
                                <h2 class="category-title">🍿 Snacks</h2>
                                <div class="items-grid">
                                    <?php foreach ($snacks as $snack): ?>
                                        <div class="addon-card" data-id="<?php echo $snack['id']; ?>">
                                            <div class="addon-image">
                                                <?php if ($snack['image']): ?>
                                                    <img src="../assets/images/<?php echo htmlspecialchars($snack['image']); ?>" 
                                                         alt="<?php echo htmlspecialchars($snack['name']); ?>"
                                                         onerror="this.src='assets/images/placeholder-food.jpg'">
                                                <?php else: ?>
                                                    <div class="placeholder-image">🍿</div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="addon-info">
                                                <h3 class="addon-name"><?php echo htmlspecialchars($snack['name']); ?></h3>
                                                <p class="addon-description"><?php echo htmlspecialchars($snack['description']); ?></p>
                                                <div class="addon-price">$<?php echo number_format($snack['price'], 2); ?></div>
                                            </div>
                                            <div class="addon-controls">
                                                <button class="quantity-btn minus" onclick="changeQuantity(<?php echo $snack['id']; ?>, -1)">-</button>
                                                <span class="quantity" id="qty-<?php echo $snack['id']; ?>">0</span>
                                                <button class="quantity-btn plus" onclick="changeQuantity(<?php echo $snack['id']; ?>, 1)">+</button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </section>

                            <!-- Drinks Section -->
                            <section class="category-section">
                                <h2 class="category-title">🥤 Drinks</h2>
                                <div class="items-grid">
                                    <?php foreach ($drinks as $drink): ?>
                                        <div class="addon-card" data-id="<?php echo $drink['id']; ?>">
                                            <div class="addon-image">
                                                <?php if ($drink['image']): ?>
                                                    <img src="../assets/images/<?php echo htmlspecialchars($drink['image']); ?>" 
                                                         alt="<?php echo htmlspecialchars($drink['name']); ?>"
                                                         onerror="this.src='assets/images/placeholder-drink.jpg'">
                                                <?php else: ?>
                                                    <div class="placeholder-image">🥤</div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="addon-info">
                                                <h3 class="addon-name"><?php echo htmlspecialchars($drink['name']); ?></h3>
                                                <p class="addon-description"><?php echo htmlspecialchars($drink['description']); ?></p>
                                                <div class="addon-price">$<?php echo number_format($drink['price'], 2); ?></div>
                                            </div>
                                            <div class="addon-controls">
                                                <button class="quantity-btn minus" onclick="changeQuantity(<?php echo $drink['id']; ?>, -1)">-</button>
                                                <span class="quantity" id="qty-<?php echo $drink['id']; ?>">0</span>
                                                <button class="quantity-btn plus" onclick="changeQuantity(<?php echo $drink['id']; ?>, 1)">+</button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </section>
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

                                <!-- Seats Summary -->
                                <div class="seats-summary">
                                    <h4>🎫 Selected Seats</h4>
                                    <div id="selectedSeatsDisplay">Loading...</div>
                                    <div class="seats-cost" id="seatsCost">$0.00</div>
                                </div>

                                <!-- Extras Summary -->
                                <div class="extras-summary">
                                    <h4>🍿 Add-ons</h4>
                                    <div id="extrasDisplay">No add-ons selected</div>
                                    <div class="extras-cost" id="extrasCost">$0.00</div>
                                </div>

                                <!-- Total -->
                                <div class="total-summary">
                                    <div class="total-line">
                                        <span>Total:</span>
                                        <span id="grandTotal">$0.00</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="action-buttons">
                                    <button onclick="goBack()" class="btn back-btn">← Back to Seats</button>
                                    <button onclick="proceedToCheckout()" class="btn checkout-btn" id="checkoutBtn">
                                        Proceed to Checkout →
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
        // Pass PHP data to JavaScript
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
    <script src="assets/js/extras.js"></script>
</body>
</html>