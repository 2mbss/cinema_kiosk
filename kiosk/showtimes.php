<?php
/**
 * TIME SELECTION PAGE
 * Displays available showtimes for selected movie
 */

// Include database configuration
require_once '../db/config.php';

// Get movie ID from URL parameter
$movie_id = isset($_GET['movie_id']) ? (int)$_GET['movie_id'] : 0;

if ($movie_id <= 0) {
    header('Location: movies.php');
    exit;
}

try {
    $pdo = getDBConnection();
    
    // Get movie details
    $sql = "SELECT * FROM movies WHERE id = ? AND status = 'active'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$movie_id]);
    $movie = $stmt->fetch();
    
    if (!$movie) {
        header('Location: movies.php');
        exit;
    }
    
    // Get showtimes for this movie (today and future dates)
    $sql = "SELECT s.*, 
            (s.total_seats - s.available_seats) as booked_seats
            FROM showtimes s 
            WHERE s.movie_id = ? 
            AND s.show_date >= CURDATE()
            ORDER BY s.show_date, s.show_time";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$movie_id]);
    $showtimes = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $error_message = "Unable to load showtimes. Please try again.";
    $showtimes = [];
}

// Group showtimes by date
function groupShowtimesByDate($showtimes) {
    $grouped = [];
    foreach ($showtimes as $showtime) {
        $date = $showtime['show_date'];
        $grouped[$date][] = $showtime;
    }
    return $grouped;
}

$grouped_showtimes = groupShowtimesByDate($showtimes);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Showtime - <?php echo htmlspecialchars($movie['title']); ?></title>
    <link rel="stylesheet" href="assets/css/time_selection.css">
</head>
<body>
    <?php if (isset($error_message)): ?>
        <!-- Error State -->
        <div class="error-container">
            <div class="error-content">
                <h1>⚠️ Error Loading Showtimes</h1>
                <p><?php echo htmlspecialchars($error_message); ?></p>
                <button onclick="goBack()" class="back-btn">← Go Back</button>
            </div>
        </div>
    <?php else: ?>
        <!-- Main Content -->
        <div class="showtime-container">
            
            <!-- Header Section -->
            <header class="header">
                <div class="container">
                    <div class="movie-info">
                        <h1 class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></h1>
                        <div class="movie-details">
                            <span class="rating"><?php echo htmlspecialchars($movie['rating']); ?></span>
                            <span class="duration"><?php echo $movie['duration']; ?> minutes</span>
                        </div>
                        <p class="instruction">Select a showtime to continue</p>
                    </div>
                </div>
            </header>

            <!-- Showtimes Section -->
            <main class="showtimes-section">
                <div class="container">
                    <?php if (empty($grouped_showtimes)): ?>
                        <!-- No Showtimes Available -->
                        <div class="no-showtimes">
                            <h2>🎬 No Showtimes Available</h2>
                            <p>There are currently no scheduled showtimes for this movie.</p>
                            <p>Please check back later or select a different movie.</p>
                        </div>
                    <?php else: ?>
                        <!-- Showtimes by Date -->
                        <div class="showtimes-grid">
                            <?php foreach ($grouped_showtimes as $date => $date_showtimes): ?>
                                <div class="date-section">
                                    <h2 class="date-header">
                                        <?php echo date('l, F j, Y', strtotime($date)); ?>
                                        <span class="date-badge"><?php echo date('M j', strtotime($date)); ?></span>
                                    </h2>
                                    
                                    <div class="showtimes-list">
                                        <?php foreach ($date_showtimes as $showtime): ?>
                                            <div class="showtime-card" 
                                                 data-showtime-id="<?php echo $showtime['id']; ?>"
                                                 onclick="selectShowtime(<?php echo $showtime['id']; ?>)">
                                                
                                                <div class="time-display">
                                                    <span class="time"><?php echo date('g:i A', strtotime($showtime['show_time'])); ?></span>
                                                </div>
                                                
                                                <div class="showtime-info">
                                                    <div class="price">$<?php echo number_format($showtime['price'], 2); ?></div>
                                                    <div class="availability">
                                                        <?php 
                                                        $available = $showtime['available_seats'];
                                                        $total = $showtime['total_seats'];
                                                        $percentage = ($available / $total) * 100;
                                                        ?>
                                                        <span class="seats-available"><?php echo $available; ?> seats left</span>
                                                        <div class="availability-bar">
                                                            <div class="availability-fill" 
                                                                 style="width: <?php echo $percentage; ?>%"
                                                                 data-percentage="<?php echo round($percentage); ?>"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <?php if ($available <= 5 && $available > 0): ?>
                                                    <div class="low-availability">Almost Full!</div>
                                                <?php elseif ($available == 0): ?>
                                                    <div class="sold-out">SOLD OUT</div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </main>

            <!-- Navigation Section -->
            <footer class="navigation-section">
                <div class="container">
                    <button onclick="goBack()" class="nav-btn back-btn">
                        ← Back to Movie Details
                    </button>
                </div>
            </footer>

        </div>
    <?php endif; ?>

    <!-- Hidden data for JavaScript -->
    <script>
        window.movieData = {
            id: <?php echo $movie_id; ?>,
            title: <?php echo json_encode($movie['title']); ?>
        };
    </script>
    
    <!-- JavaScript -->
    <script src="assets/js/time_selection.js"></script>
</body>
</html>