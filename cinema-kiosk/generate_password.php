<?php
/**
 * Password Hash Generator
 * Generate correct password hash for admin123
 */

$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>🔑 Password Hash Generator</h2>";
echo "<p><strong>Password:</strong> admin123</p>";
echo "<p><strong>Generated Hash:</strong></p>";
echo "<textarea style='width: 100%; height: 100px;'>" . $hash . "</textarea>";
echo "<hr>";
echo "<h3>📋 SQL Command to Update:</h3>";
echo "<textarea style='width: 100%; height: 80px;'>UPDATE admins SET password = '" . $hash . "' WHERE username = 'admin';</textarea>";
echo "<hr>";
echo "<p><strong>Instructions:</strong></p>";
echo "<ol>";
echo "<li>Copy the SQL command above</li>";
echo "<li>Run it in MySQL Workbench</li>";
echo "<li>Test login again</li>";
echo "</ol>";
?>