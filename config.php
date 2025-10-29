<?php
/**
 * Cinema Kiosk Configuration
 * Global settings and constants
 */

// Application Settings
define('APP_NAME', 'Cinema Kiosk Admin');
define('APP_VERSION', '1.0.0');
define('TIMEZONE', 'America/New_York');

// Set timezone
date_default_timezone_set(TIMEZONE);

// File Upload Settings
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('UPLOAD_PATH', 'assets/images/');
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);

// Pagination Settings
define('ITEMS_PER_PAGE', 10);

// Session Settings
ini_set('session.cookie_lifetime', 3600); // 1 hour
ini_set('session.gc_maxlifetime', 3600);

// Error Reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Security Headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

/**
 * Format currency for display
 * @param float $amount
 * @return string
 */
function formatCurrency($amount) {
    return '$' . number_format($amount, 2);
}

/**
 * Format date for display
 * @param string $date
 * @param string $format
 * @return string
 */
function formatDate($date, $format = 'M j, Y') {
    return date($format, strtotime($date));
}

/**
 * Format time for display
 * @param string $time
 * @return string
 */
function formatTime($time) {
    return date('g:i A', strtotime($time));
}

/**
 * Sanitize input data
 * @param string $data
 * @return string
 */
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate random string for tokens
 * @param int $length
 * @return string
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}
?>