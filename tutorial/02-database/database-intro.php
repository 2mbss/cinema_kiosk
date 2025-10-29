<?php
/**
 * CHAPTER 2: DATABASE BASICS
 * Understanding how data is stored and retrieved
 */

// Include the database connection to show real examples
require_once '../../db/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapter 2: Database Basics</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🗄️ Chapter 2: Database Basics</h1>
            <p>Understanding how your cinema data is stored and organized</p>
        </div>

        <div class="section">
            <h2>🤔 What is a Database?</h2>
            <div class="concept-box">
                <h3>Think of a database like a digital filing cabinet:</h3>
                <ul>
                    <li><strong>Database</strong> = The entire filing cabinet</li>
                    <li><strong>Tables</strong> = Individual drawers (Movies, Users, Sales)</li>
                    <li><strong>Rows</strong> = Individual files (One movie, one user)</li>
                    <li><strong>Columns</strong> = Information categories (Title, Price, Date)</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2>📊 Your Cinema Database Tables</h2>
            
            <?php
            try {
                $pdo = getDBConnection();
                echo "<div class='success-box'>✅ Connected to database successfully!</div>";
                
                // Get table information
                $stmt = $pdo->query("SHOW TABLES");
                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                echo "<h3>📋 Tables in your database:</h3>";
                echo "<div class='table-grid'>";
                
                foreach ($tables as $table) {
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
                    $count = $stmt->fetch()['count'];
                    
                    $icons = [
                        'admins' => '👤',
                        'movies' => '🎬',
                        'showtimes' => '🕐',
                        'seats' => '🪑',
                        'extras' => '🍿',
                        'sales' => '💰',
                        'sales_extras' => '🛒'
                    ];
                    
                    $descriptions = [
                        'admins' => 'Admin user accounts',
                        'movies' => 'Movie information',
                        'showtimes' => 'Movie schedules',
                        'seats' => 'Individual seats',
                        'extras' => 'Snacks and drinks',
                        'sales' => 'Ticket sales',
                        'sales_extras' => 'Extra items sold'
                    ];
                    
                    echo "<div class='table-card'>";
                    echo "<h4>" . ($icons[$table] ?? '📄') . " $table</h4>";
                    echo "<p>" . ($descriptions[$table] ?? 'Data table') . "</p>";
                    echo "<span class='count'>$count records</span>";
                    echo "</div>";
                }
                echo "</div>";
                
            } catch (PDOException $e) {
                echo "<div class='error-box'>❌ Database connection failed: " . $e->getMessage() . "</div>";
            }
            ?>
        </div>

        <div class="section">
            <h2>🎬 Example: Movies Table Structure</h2>
            
            <?php
            try {
                // Show movies table structure
                $stmt = $pdo->query("DESCRIBE movies");
                $columns = $stmt->fetchAll();
                
                echo "<div class='table-structure'>";
                echo "<h3>Movies Table Columns:</h3>";
                echo "<table class='structure-table'>";
                echo "<tr><th>Column Name</th><th>Data Type</th><th>What it stores</th></tr>";
                
                $explanations = [
                    'id' => 'Unique number for each movie',
                    'title' => 'Movie name (e.g., "Spider-Man")',
                    'description' => 'Movie plot summary',
                    'trailer_url' => 'YouTube trailer link',
                    'poster_image' => 'Movie poster filename',
                    'duration' => 'Movie length in minutes',
                    'rating' => 'Age rating (PG, PG-13, R)',
                    'status' => 'Active or inactive',
                    'created_at' => 'When movie was added'
                ];
                
                foreach ($columns as $column) {
                    echo "<tr>";
                    echo "<td><strong>" . $column['Field'] . "</strong></td>";
                    echo "<td><code>" . $column['Type'] . "</code></td>";
                    echo "<td>" . ($explanations[$column['Field']] ?? 'Data field') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "</div>";
                
                // Show sample data
                $stmt = $pdo->query("SELECT id, title, duration, rating, status FROM movies LIMIT 3");
                $movies = $stmt->fetchAll();
                
                if ($movies) {
                    echo "<h3>📋 Sample Movie Data:</h3>";
                    echo "<table class='data-table'>";
                    echo "<tr><th>ID</th><th>Title</th><th>Duration</th><th>Rating</th><th>Status</th></tr>";
                    
                    foreach ($movies as $movie) {
                        echo "<tr>";
                        echo "<td>" . $movie['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($movie['title']) . "</td>";
                        echo "<td>" . $movie['duration'] . " min</td>";
                        echo "<td>" . $movie['rating'] . "</td>";
                        echo "<td>" . $movie['status'] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
                
            } catch (PDOException $e) {
                echo "<div class='error-box'>Error loading table structure</div>";
            }
            ?>
        </div>

        <div class="section">
            <h2>🔗 How Tables Connect (Relationships)</h2>
            
            <div class="relationship-diagram">
                <div class="table-box">
                    <h4>🎬 movies</h4>
                    <p>id, title, description</p>
                </div>
                <div class="arrow-right">→</div>
                <div class="table-box">
                    <h4>🕐 showtimes</h4>
                    <p>id, movie_id, date, time</p>
                </div>
                <div class="arrow-right">→</div>
                <div class="table-box">
                    <h4>🪑 seats</h4>
                    <p>id, showtime_id, seat_number</p>
                </div>
            </div>
            
            <div class="concept-box">
                <h3>🔗 This relationship means:</h3>
                <ul>
                    <li><strong>One movie</strong> can have many showtimes</li>
                    <li><strong>One showtime</strong> can have many seats</li>
                    <li><strong>Foreign keys</strong> (movie_id, showtime_id) connect the tables</li>
                    <li><strong>JOIN queries</strong> let us get data from multiple tables</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2>💻 Basic SQL Commands</h2>
            
            <div class="sql-examples">
                <div class="sql-example">
                    <h4>📖 SELECT (Read data)</h4>
                    <div class="code-block">SELECT title, rating FROM movies WHERE status = 'active'</div>
                    <p><strong>Translation:</strong> "Show me the title and rating of all active movies"</p>
                </div>
                
                <div class="sql-example">
                    <h4>➕ INSERT (Add new data)</h4>
                    <div class="code-block">INSERT INTO movies (title, rating) VALUES ('New Movie', 'PG-13')</div>
                    <p><strong>Translation:</strong> "Add a new movie with title 'New Movie' and rating 'PG-13'"</p>
                </div>
                
                <div class="sql-example">
                    <h4>✏️ UPDATE (Change existing data)</h4>
                    <div class="code-block">UPDATE movies SET rating = 'R' WHERE id = 1</div>
                    <p><strong>Translation:</strong> "Change the rating to 'R' for the movie with id 1"</p>
                </div>
                
                <div class="sql-example">
                    <h4>🗑️ DELETE (Remove data)</h4>
                    <div class="code-block">DELETE FROM movies WHERE status = 'inactive'</div>
                    <p><strong>Translation:</strong> "Remove all movies that are inactive"</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>🔐 Database Security</h2>
            
            <div class="security-grid">
                <div class="security-item">
                    <h4>🛡️ Prepared Statements</h4>
                    <p>Prevent SQL injection attacks by separating code from data</p>
                </div>
                <div class="security-item">
                    <h4>🔑 User Permissions</h4>
                    <p>Different users have different access levels (read, write, admin)</p>
                </div>
                <div class="security-item">
                    <h4>🔒 Password Hashing</h4>
                    <p>Store encrypted passwords, never plain text</p>
                </div>
                <div class="security-item">
                    <h4>🚫 Input Validation</h4>
                    <p>Check all data before putting it in the database</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>🎯 What You Learned</h2>
            <div class="summary-box">
                <h3>✅ Database Concepts</h3>
                <ul>
                    <li>Databases organize data in tables with rows and columns</li>
                    <li>Tables can be connected through relationships</li>
                    <li>SQL is the language used to interact with databases</li>
                    <li>Security is crucial when handling data</li>
                </ul>
                
                <h3>✅ Your Cinema Database</h3>
                <ul>
                    <li>7 tables store all cinema information</li>
                    <li>Movies connect to showtimes, showtimes connect to seats</li>
                    <li>Sales track revenue and popular items</li>
                    <li>Admin table manages user access</li>
                </ul>
            </div>
        </div>

        <div class="navigation">
            <a href="../01-basics/project-structure.php" class="btn btn-secondary">← Previous: Project Structure</a>
            <a href="../03-files/file-explanation.php" class="btn btn-primary">Next: File Breakdown →</a>
        </div>
    </div>

    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
        .container { max-width: 1000px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 10px; text-align: center; margin-bottom: 30px; }
        .section { background: white; padding: 25px; margin: 20px 0; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .concept-box { background: #e8f5e8; padding: 20px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #28a745; }
        .success-box { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error-box { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .table-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0; }
        .table-card { background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center; border-left: 4px solid #3498db; }
        .count { background: #3498db; color: white; padding: 4px 8px; border-radius: 12px; font-size: 12px; }
        .structure-table, .data-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .structure-table th, .structure-table td, .data-table th, .data-table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        .structure-table th, .data-table th { background: #f8f9fa; font-weight: bold; }
        .relationship-diagram { display: flex; align-items: center; justify-content: center; flex-wrap: wrap; margin: 20px 0; }
        .table-box { background: #e3f2fd; padding: 15px; border-radius: 8px; margin: 10px; text-align: center; }
        .arrow-right { font-size: 24px; color: #3498db; margin: 0 10px; }
        .sql-examples { display: grid; gap: 20px; }
        .sql-example { background: #f8f9fa; padding: 20px; border-radius: 8px; }
        .code-block { background: #2c3e50; color: #ecf0f1; padding: 15px; border-radius: 5px; font-family: 'Courier New', monospace; margin: 10px 0; }
        .security-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
        .security-item { background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107; }
        .summary-box { background: #d1ecf1; padding: 20px; border-radius: 8px; border-left: 4px solid #17a2b8; }
        .navigation { text-align: center; margin: 30px 0; }
        .btn { display: inline-block; padding: 12px 25px; margin: 10px; text-decoration: none; border-radius: 5px; transition: all 0.3s; }
        .btn-primary { background: #3498db; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
    </style>
</body>
</html>