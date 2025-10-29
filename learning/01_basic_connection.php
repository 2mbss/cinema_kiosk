<?php
/**
 * LESSON 1: Basic Database Connection Concepts
 * This file explains the fundamental concepts step by step
 */

echo "<h2>🎓 Database Connection Learning</h2>";

// STEP 1: Understanding Connection Variables
echo "<h3>📋 Step 1: Connection Variables</h3>";
echo "<p><strong>Think of these like your address book:</strong></p>";

$host = 'localhost';        // 🏠 Server address (like street address)
$username = 'root';         // 👤 Your username (like your name)
$password = '';             // 🔑 Your password (like your key)
$database = 'cinema_kiosk'; // 🏢 Which database (like which building)

echo "<ul>";
echo "<li><strong>Host:</strong> '$host' - Where is the database server?</li>";
echo "<li><strong>Username:</strong> '$username' - Who are you?</li>";
echo "<li><strong>Password:</strong> '$password' - What's your password? (empty for local)</li>";
echo "<li><strong>Database:</strong> '$database' - Which database to use?</li>";
echo "</ul>";

// STEP 2: Two Ways to Connect
echo "<h3>🔌 Step 2: Two Connection Methods</h3>";
echo "<p>PHP offers two main ways to connect to MySQL:</p>";
echo "<ol>";
echo "<li><strong>MySQLi</strong> - MySQL Improved (only for MySQL)</li>";
echo "<li><strong>PDO</strong> - PHP Data Objects (works with many databases)</li>";
echo "</ol>";
echo "<p><strong>We use PDO because it's more flexible and secure!</strong></p>";

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2 { color: #2c3e50; }
h3 { color: #3498db; }
ul, ol { margin-left: 20px; }
code { background: #f4f4f4; padding: 2px 5px; border-radius: 3px; }
</style>