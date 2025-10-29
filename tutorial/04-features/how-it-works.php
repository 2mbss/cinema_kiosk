<?php
/**
 * CHAPTER 4: HOW FEATURES WORK
 * Understanding login, CRUD operations, and analytics behind the scenes
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapter 4: How Features Work</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚙️ Chapter 4: How Features Work</h1>
            <p>Understanding login, CRUD operations, and analytics behind the scenes</p>
        </div>

        <div class="section">
            <h2>🎯 What You'll Learn</h2>
            <ul>
                <li>How user authentication and sessions work</li>
                <li>How CRUD operations (Create, Read, Update, Delete) function</li>
                <li>How analytics and charts are generated</li>
                <li>How form submissions and data validation work</li>
                <li>Security measures and best practices</li>
            </ul>
        </div>

        <div class="section">
            <h2>🔐 Authentication System Deep Dive</h2>
            
            <div class="feature-breakdown">
                <div class="step-card">
                    <h3>Step 1: User Enters Credentials</h3>
                    <div class="code-block">
&lt;form method="POST" action=""&gt;
    &lt;input type="text" name="username" required&gt;
    &lt;input type="password" name="password" required&gt;
    &lt;button type="submit"&gt;Login&lt;/button&gt;
&lt;/form&gt;</div>
                    <p><strong>What happens:</strong> User fills form and clicks submit. Browser sends POST request to server.</p>
                </div>

                <div class="step-card">
                    <h3>Step 2: Server Receives Data</h3>
                    <div class="code-block">
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Validate input is not empty
    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields';
    }
}</div>
                    <p><strong>What happens:</strong> PHP checks if form was submitted and validates input data.</p>
                </div>

                <div class="step-card">
                    <h3>Step 3: Database Lookup</h3>
                    <div class="code-block">
$stmt = $pdo->prepare("SELECT id, username, password FROM admins WHERE username = ?");
$stmt->execute([$username]);
$admin = $stmt->fetch();</div>
                    <p><strong>What happens:</strong> Query database for user with matching username. Uses prepared statement for security.</p>
                </div>

                <div class="step-card">
                    <h3>Step 4: Password Verification</h3>
                    <div class="code-block">
if ($admin && password_verify($password, $admin['password'])) {
    // Login successful
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    header('Location: dashboard.php');
}</div>
                    <p><strong>What happens:</strong> Verify password hash, create session variables, redirect to dashboard.</p>
                </div>
            </div>

            <div class="concept-box">
                <h3>🔒 Security Measures</h3>
                <ul>
                    <li><strong>Password Hashing:</strong> Passwords stored as encrypted hashes, never plain text</li>
                    <li><strong>Prepared Statements:</strong> Prevent SQL injection attacks</li>
                    <li><strong>Sessions:</strong> Server-side storage of login state</li>
                    <li><strong>Input Validation:</strong> Check all user input before processing</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2>📝 Sessions Explained</h2>
            
            <div class="session-flow">
                <div class="session-step">
                    <h4>🍪 Session Creation</h4>
                    <div class="code-block">
session_start();                    // Start session system
$_SESSION['admin_id'] = 123;       // Store user ID
$_SESSION['admin_username'] = 'admin'; // Store username</div>
                    <p>Server creates unique session ID, stores data server-side, sends session cookie to browser.</p>
                </div>

                <div class="session-step">
                    <h4>🔍 Session Checking</h4>
                    <div class="code-block">
function requireAuth() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: login.php');  // Redirect if not logged in
        exit;
    }
}</div>
                    <p>Every protected page checks if session exists. If not, redirect to login.</p>
                </div>

                <div class="session-step">
                    <h4>🚪 Session Destruction</h4>
                    <div class="code-block">
function logout() {
    session_destroy();              // Delete all session data
    header('Location: login.php');  // Redirect to login
}</div>
                    <p>Logout destroys session data and redirects to login page.</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>🎬 CRUD Operations Explained</h2>
            
            <div class="crud-detailed">
                <div class="crud-card">
                    <h3>➕ CREATE (Adding New Movie)</h3>
                    
                    <div class="crud-step">
                        <h4>1. Display Form</h4>
                        <div class="code-block">
&lt;form method="POST" action=""&gt;
    &lt;input type="hidden" name="action" value="add"&gt;
    &lt;input type="text" name="title" required&gt;
    &lt;textarea name="description" required&gt;&lt;/textarea&gt;
    &lt;button type="submit"&gt;Add Movie&lt;/button&gt;
&lt;/form&gt;</div>
                        <p>HTML form with hidden field to identify action type.</p>
                    </div>

                    <div class="crud-step">
                        <h4>2. Process Submission</h4>
                        <div class="code-block">
if ($_POST['action'] === 'add') {
    $stmt = $pdo->prepare("
        INSERT INTO movies (title, description, duration, rating) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([
        $_POST['title'],
        $_POST['description'],
        $_POST['duration'],
        $_POST['rating']
    ]);
    $message = 'Movie added successfully!';
}</div>
                        <p>Insert new record into database using prepared statement.</p>
                    </div>
                </div>

                <div class="crud-card">
                    <h3>📖 READ (Displaying Movies)</h3>
                    
                    <div class="crud-step">
                        <h4>1. Query Database</h4>
                        <div class="code-block">
$stmt = $pdo->query("SELECT * FROM movies ORDER BY created_at DESC");
$movies = $stmt->fetchAll();</div>
                        <p>Get all movies from database, ordered by newest first.</p>
                    </div>

                    <div class="crud-step">
                        <h4>2. Display in HTML</h4>
                        <div class="code-block">
&lt;table&gt;
    &lt;?php foreach ($movies as $movie): ?&gt;
        &lt;tr&gt;
            &lt;td&gt;&lt;?php echo htmlspecialchars($movie['title']); ?&gt;&lt;/td&gt;
            &lt;td&gt;&lt;?php echo $movie['duration']; ?&gt; min&lt;/td&gt;
        &lt;/tr&gt;
    &lt;?php endforeach; ?&gt;
&lt;/table&gt;</div>
                        <p>Loop through results and display in HTML table. Use htmlspecialchars() for security.</p>
                    </div>
                </div>

                <div class="crud-card">
                    <h3>✏️ UPDATE (Editing Movie)</h3>
                    
                    <div class="crud-step">
                        <h4>1. Load Existing Data</h4>
                        <div class="code-block">
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editMovie = $stmt->fetch();
}</div>
                        <p>Get movie data to pre-fill the edit form.</p>
                    </div>

                    <div class="crud-step">
                        <h4>2. Update Database</h4>
                        <div class="code-block">
if ($_POST['action'] === 'edit') {
    $stmt = $pdo->prepare("
        UPDATE movies 
        SET title = ?, description = ?, duration = ? 
        WHERE id = ?
    ");
    $stmt->execute([
        $_POST['title'],
        $_POST['description'],
        $_POST['duration'],
        $_POST['movie_id']
    ]);
}</div>
                        <p>Update existing record with new values.</p>
                    </div>
                </div>

                <div class="crud-card">
                    <h3>🗑️ DELETE (Removing Movie)</h3>
                    
                    <div class="crud-step">
                        <h4>1. Confirm Action</h4>
                        <div class="code-block">
&lt;form method="POST" onsubmit="return confirm('Are you sure?')"&gt;
    &lt;input type="hidden" name="action" value="delete"&gt;
    &lt;input type="hidden" name="movie_id" value="&lt;?php echo $movie['id']; ?&gt;"&gt;
    &lt;button type="submit"&gt;Delete&lt;/button&gt;
&lt;/form&gt;</div>
                        <p>JavaScript confirmation dialog prevents accidental deletion.</p>
                    </div>

                    <div class="crud-step">
                        <h4>2. Remove from Database</h4>
                        <div class="code-block">
if ($_POST['action'] === 'delete') {
    $stmt = $pdo->prepare("DELETE FROM movies WHERE id = ?");
    $stmt->execute([$_POST['movie_id']]);
    $message = 'Movie deleted successfully!';
}</div>
                        <p>Remove record from database permanently.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>📊 Analytics Dashboard Explained</h2>
            
            <div class="analytics-breakdown">
                <div class="analytics-step">
                    <h3>1. Data Collection</h3>
                    <div class="code-block">
// Total sales
$stmt = $pdo->query("SELECT COALESCE(SUM(total_amount), 0) as total_sales FROM sales");
$totalSales = $stmt->fetch()['total_sales'];

// Top movies by revenue
$stmt = $pdo->query("
    SELECT m.title, COALESCE(SUM(s.total_amount), 0) as revenue
    FROM movies m
    LEFT JOIN showtimes st ON m.id = st.movie_id
    LEFT JOIN sales s ON st.id = s.showtime_id
    GROUP BY m.id, m.title
    ORDER BY revenue DESC
    LIMIT 5
");
$topMovies = $stmt->fetchAll();</div>
                    <p><strong>What happens:</strong> Run complex SQL queries to calculate statistics and get top performers.</p>
                </div>

                <div class="analytics-step">
                    <h3>2. Data Processing</h3>
                    <div class="code-block">
// Format currency
echo '$' . number_format($totalSales, 2);

// Prepare data for charts
$movieTitles = array_column($topMovies, 'title');
$movieRevenue = array_column($topMovies, 'revenue');</div>
                    <p><strong>What happens:</strong> Format numbers for display and prepare data for JavaScript charts.</p>
                </div>

                <div class="analytics-step">
                    <h3>3. Chart Generation</h3>
                    <div class="code-block">
const moviesChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: &lt;?php echo json_encode($movieTitles); ?&gt;,
        datasets: [{
            data: &lt;?php echo json_encode($movieRevenue); ?&gt;,
            backgroundColor: 'rgba(52, 152, 219, 0.8)'
        }]
    }
});</div>
                    <p><strong>What happens:</strong> Convert PHP data to JavaScript format and create interactive charts.</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>🪑 Interactive Seat Management</h2>
            
            <div class="seat-system">
                <div class="seat-step">
                    <h3>1. Generate Seat Map</h3>
                    <div class="code-block">
// Create seats when showtime is added
$seatStmt = $pdo->prepare("INSERT INTO seats (showtime_id, seat_number) VALUES (?, ?)");
for ($i = 1; $i <= $totalSeats; $i++) {
    $seatNumber = chr(65 + floor(($i - 1) / 10)) . (($i - 1) % 10 + 1);
    $seatStmt->execute([$showtimeId, $seatNumber]);
}</div>
                    <p><strong>What happens:</strong> Automatically generate seats (A1, A2, B1, B2...) when creating showtime.</p>
                </div>

                <div class="seat-step">
                    <h3>2. Display Seat Grid</h3>
                    <div class="code-block">
&lt;div class="seats-grid"&gt;
    &lt;?php foreach ($seats as $seat): ?&gt;
        &lt;button class="seat &lt;?php echo $seat['is_booked'] ? 'booked' : 'available'; ?&gt;"&gt;
            &lt;?php echo $seat['seat_number']; ?&gt;
        &lt;/button&gt;
    &lt;?php endforeach; ?&gt;
&lt;/div&gt;</div>
                    <p><strong>What happens:</strong> Create visual grid with CSS classes for available/booked seats.</p>
                </div>

                <div class="seat-step">
                    <h3>3. Toggle Seat Status</h3>
                    <div class="code-block">
if (isset($_POST['toggle_seat'])) {
    $newStatus = $_POST['current_status'] ? 0 : 1;
    $stmt = $pdo->prepare("UPDATE seats SET is_booked = ? WHERE id = ?");
    $stmt->execute([$newStatus, $_POST['seat_id']]);
    
    // Update available seats count
    $stmt = $pdo->prepare("UPDATE showtimes SET available_seats = 
        (SELECT COUNT(*) FROM seats WHERE showtime_id = ? AND is_booked = 0) 
        WHERE id = ?");
    $stmt->execute([$showtimeId, $showtimeId]);
}</div>
                    <p><strong>What happens:</strong> Toggle seat booking status and update availability count.</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>🛡️ Security Best Practices</h2>
            
            <div class="security-practices">
                <div class="security-item">
                    <h3>🔒 Input Validation</h3>
                    <div class="code-block">
// Always validate and sanitize input
$title = trim($_POST['title']);
if (empty($title)) {
    $error = 'Title is required';
}

// Escape output to prevent XSS
echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8');</div>
                    <p>Never trust user input. Always validate, sanitize, and escape data.</p>
                </div>

                <div class="security-item">
                    <h3>🛡️ SQL Injection Prevention</h3>
                    <div class="code-block">
// WRONG - Vulnerable to SQL injection
$query = "SELECT * FROM movies WHERE title = '" . $_POST['title'] . "'";

// RIGHT - Use prepared statements
$stmt = $pdo->prepare("SELECT * FROM movies WHERE title = ?");
$stmt->execute([$_POST['title']]);</div>
                    <p>Always use prepared statements for database queries.</p>
                </div>

                <div class="security-item">
                    <h3>🔐 Authentication Checks</h3>
                    <div class="code-block">
// Check authentication on every protected page
require_once 'includes/auth.php';
requireAuth();

// Verify user permissions for sensitive actions
if ($_SESSION['admin_id'] !== $expectedAdminId) {
    die('Unauthorized access');
}</div>
                    <p>Always verify user authentication and permissions.</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>🎯 What You Learned</h2>
            <div class="summary-box">
                <h3>✅ Authentication System</h3>
                <ul>
                    <li>Sessions store login state server-side</li>
                    <li>Password hashing protects user credentials</li>
                    <li>Authentication checks protect admin pages</li>
                    <li>Logout properly destroys session data</li>
                </ul>
                
                <h3>✅ CRUD Operations</h3>
                <ul>
                    <li>Create: Form submission → Validation → Database insert</li>
                    <li>Read: Database query → Loop through results → Display HTML</li>
                    <li>Update: Load existing data → Form submission → Database update</li>
                    <li>Delete: Confirmation → Database removal</li>
                </ul>
                
                <h3>✅ Advanced Features</h3>
                <ul>
                    <li>Analytics use complex SQL queries and data visualization</li>
                    <li>Interactive features combine PHP backend with JavaScript frontend</li>
                    <li>Security is built into every operation</li>
                    <li>User experience is enhanced with validation and feedback</li>
                </ul>
            </div>
        </div>

        <div class="navigation">
            <a href="../03-files/file-explanation.php" class="btn btn-secondary">← Previous: File Breakdown</a>
            <a href="../index.php" class="btn btn-primary">Back to Tutorial Home</a>
        </div>
    </div>

    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 10px; text-align: center; margin-bottom: 30px; }
        .section { background: white; padding: 25px; margin: 20px 0; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .feature-breakdown { display: grid; gap: 20px; }
        .step-card { background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #3498db; }
        .concept-box { background: #e8f5e8; padding: 20px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #28a745; }
        .session-flow { display: grid; gap: 20px; }
        .session-step { background: #fff3cd; padding: 20px; border-radius: 8px; border-left: 4px solid #ffc107; }
        .crud-detailed { display: grid; gap: 25px; }
        .crud-card { background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #17a2b8; }
        .crud-step { background: white; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .analytics-breakdown { display: grid; gap: 20px; }
        .analytics-step { background: #e3f2fd; padding: 20px; border-radius: 8px; border-left: 4px solid #2196f3; }
        .seat-system { display: grid; gap: 20px; }
        .seat-step { background: #f3e5f5; padding: 20px; border-radius: 8px; border-left: 4px solid #9c27b0; }
        .security-practices { display: grid; gap: 20px; }
        .security-item { background: #ffebee; padding: 20px; border-radius: 8px; border-left: 4px solid #f44336; }
        .code-block { background: #2c3e50; color: #ecf0f1; padding: 15px; border-radius: 5px; font-family: 'Courier New', monospace; margin: 10px 0; overflow-x: auto; }
        .summary-box { background: #d1ecf1; padding: 20px; border-radius: 8px; border-left: 4px solid #17a2b8; }
        .navigation { text-align: center; margin: 30px 0; }
        .btn { display: inline-block; padding: 12px 25px; margin: 10px; text-decoration: none; border-radius: 5px; transition: all 0.3s; }
        .btn-primary { background: #3498db; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
    </style>
</body>
</html>