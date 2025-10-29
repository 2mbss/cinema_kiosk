<?php
/**
 * LESSON 3: Testing Database Connection
 * Learn how to test if your connection works
 */

echo "<h2>🧪 Testing Your Database Connection</h2>";

// Include the config file
require_once '../db/config.php';

echo "<h3>🔍 Step 1: Testing the Connection</h3>";

try {
    // Try to connect
    $pdo = getDBConnection();
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "✅ <strong>SUCCESS!</strong> Database connection is working!";
    echo "</div>";
    
    // Test a simple query
    echo "<h3>📊 Step 2: Testing a Simple Query</h3>";
    $stmt = $pdo->query("SELECT COUNT(*) as movie_count FROM movies");
    $result = $stmt->fetch();
    
    echo "<p><strong>Movies in database:</strong> " . $result['movie_count'] . "</p>";
    
    // Show connection details
    echo "<h3>ℹ️ Step 3: Connection Information</h3>";
    echo "<ul>";
    echo "<li><strong>Server Info:</strong> " . $pdo->getAttribute(PDO::ATTR_SERVER_INFO) . "</li>";
    echo "<li><strong>Connection Status:</strong> " . $pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS) . "</li>";
    echo "<li><strong>Driver Name:</strong> " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "</li>";
    echo "</ul>";
    
} catch (PDOException $e) {
    // If connection fails
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "❌ <strong>CONNECTION FAILED!</strong><br>";
    echo "<strong>Error:</strong> " . $e->getMessage();
    echo "</div>";
    
    echo "<h3>🔧 Common Solutions:</h3>";
    echo "<ul>";
    echo "<li>Make sure XAMPP is running</li>";
    echo "<li>Check if MySQL service is started</li>";
    echo "<li>Verify database name exists</li>";
    echo "<li>Check username and password</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<h3>📚 What We Learned:</h3>";
echo "<ol>";
echo "<li><strong>try/catch blocks</strong> - Handle errors gracefully</li>";
echo "<li><strong>PDOException</strong> - Specific error type for database issues</li>";
echo "<li><strong>getAttribute()</strong> - Get information about the connection</li>";
echo "<li><strong>Simple queries</strong> - Test if database is accessible</li>";
echo "</ol>";

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2 { color: #2c3e50; }
h3 { color: #3498db; }
ul, ol { margin-left: 20px; }
code { background: #f4f4f4; padding: 2px 5px; border-radius: 3px; }
</style>