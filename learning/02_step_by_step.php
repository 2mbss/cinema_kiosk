<?php
/**
 * LESSON 2: Step-by-Step Connection Breakdown
 * Understanding each part of the connection process
 */

echo "<h2>🔍 Breaking Down Your config.php File</h2>";

// STEP 1: Connection Variables (Constants)
echo "<h3>📋 Step 1: Setting Up Constants</h3>";
echo "<pre><code>define('DB_HOST', 'localhost');
define('DB_NAME', 'cinema_kiosk');
define('DB_USER', 'root');
define('DB_PASS', '');</code></pre>";

echo "<p><strong>Why use define()?</strong></p>";
echo "<ul>";
echo "<li>Constants can't be changed accidentally</li>";
echo "<li>Available everywhere in your code</li>";
echo "<li>Easy to update in one place</li>";
echo "</ul>";

// STEP 2: The DSN String
echo "<h3>🔗 Step 2: Building the DSN (Data Source Name)</h3>";
echo "<pre><code>\$dsn = \"mysql:host=\" . DB_HOST . \";dbname=\" . DB_NAME . \";charset=utf8mb4\";</code></pre>";

echo "<p><strong>This creates a string like:</strong></p>";
echo "<code>mysql:host=localhost;dbname=cinema_kiosk;charset=utf8mb4</code>";

echo "<p><strong>Think of DSN as a complete address:</strong></p>";
echo "<ul>";
echo "<li><strong>mysql:</strong> = Type of database</li>";
echo "<li><strong>host=localhost</strong> = Server location</li>";
echo "<li><strong>dbname=cinema_kiosk</strong> = Which database</li>";
echo "<li><strong>charset=utf8mb4</strong> = Character encoding</li>";
echo "</ul>";

// STEP 3: PDO Options
echo "<h3>⚙️ Step 3: PDO Configuration Options</h3>";
echo "<pre><code>\$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];</code></pre>";

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Option</th><th>What it does</th><th>Why it's important</th></tr>";
echo "<tr><td>ERRMODE_EXCEPTION</td><td>Show detailed errors</td><td>Helps you debug problems</td></tr>";
echo "<tr><td>FETCH_ASSOC</td><td>Return arrays with column names</td><td>Easier to use data</td></tr>";
echo "<tr><td>EMULATE_PREPARES = false</td><td>Use real prepared statements</td><td>Better security</td></tr>";
echo "</table>";

// STEP 4: Creating the Connection
echo "<h3>🔌 Step 4: Creating the PDO Connection</h3>";
echo "<pre><code>return new PDO(\$dsn, DB_USER, DB_PASS, \$options);</code></pre>";

echo "<p><strong>This line:</strong></p>";
echo "<ol>";
echo "<li>Creates a new PDO object</li>";
echo "<li>Passes the DSN, username, password, and options</li>";
echo "<li>Returns the connection object</li>";
echo "<li>If successful, you can use this to run SQL queries</li>";
echo "</ol>";

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2 { color: #2c3e50; }
h3 { color: #3498db; }
pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
code { background: #f4f4f4; padding: 2px 5px; border-radius: 3px; }
table { margin: 10px 0; }
th, td { padding: 8px; text-align: left; }
th { background: #3498db; color: white; }
</style>