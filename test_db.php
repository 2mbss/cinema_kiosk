<?php
/**
 * Database Connection Test
 * Check if database connection and admin user exist
 */

require_once 'db/config.php';

echo "<h2>🔍 Database Connection Test</h2>";

try {
    // Test database connection
    $pdo = getDBConnection();
    echo "<p>✅ Database connection: SUCCESS</p>";
    
    // Check if admin table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'admins'");
    if ($stmt->rowCount() > 0) {
        echo "<p>✅ Admin table: EXISTS</p>";
        
        // Check admin user
        $stmt = $pdo->query("SELECT id, username, password FROM admins WHERE username = 'admin'");
        $admin = $stmt->fetch();
        
        if ($admin) {
            echo "<p>✅ Admin user: FOUND</p>";
            echo "<p>Username: " . htmlspecialchars($admin['username']) . "</p>";
            echo "<p>Password hash: " . substr($admin['password'], 0, 20) . "...</p>";
            
            // Test password verification
            if (password_verify('admin123', $admin['password'])) {
                echo "<p>✅ Password verification: SUCCESS</p>";
            } else {
                echo "<p>❌ Password verification: FAILED</p>";
                echo "<p><strong>Fix:</strong> Password hash is incorrect</p>";
            }
        } else {
            echo "<p>❌ Admin user: NOT FOUND</p>";
            echo "<p><strong>Fix:</strong> Admin user doesn't exist</p>";
        }
    } else {
        echo "<p>❌ Admin table: NOT FOUND</p>";
        echo "<p><strong>Fix:</strong> Database not properly imported</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Database connection: FAILED</p>";
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Fix:</strong> Check database credentials in db/config.php</p>";
}

echo "<hr>";
echo "<p><a href='admin/login.php'>← Back to Login</a></p>";
?>