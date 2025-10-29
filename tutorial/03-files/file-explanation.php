<?php
/**
 * CHAPTER 3: FILE BREAKDOWN
 * Understanding what each file does in your project
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapter 3: File Breakdown</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📁 Chapter 3: File Breakdown</h1>
            <p>Understanding what each PHP, HTML, CSS, and JS file does</p>
        </div>

        <div class="section">
            <h2>🎯 What You'll Learn</h2>
            <ul>
                <li>What each file in your project does</li>
                <li>How PHP, HTML, CSS, and JavaScript work together</li>
                <li>How files include and connect to each other</li>
                <li>Best practices for organizing code</li>
            </ul>
        </div>

        <div class="section">
            <h2>🔐 Authentication Files</h2>
            
            <div class="file-breakdown">
                <div class="file-card">
                    <h3>📄 admin/login.php</h3>
                    <div class="file-purpose">
                        <strong>Purpose:</strong> The login page where admins enter credentials
                    </div>
                    
                    <div class="code-section">
                        <h4>🔍 Key Parts:</h4>
                        <div class="code-block">
&lt;?php
require_once '../db/config.php';  // Get database connection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process login form
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Check against database...
}
?&gt;</div>
                        <p><strong>What this does:</strong></p>
                        <ul>
                            <li>Includes database connection</li>
                            <li>Checks if form was submitted (POST method)</li>
                            <li>Validates username and password</li>
                            <li>Creates session if login successful</li>
                            <li>Redirects to dashboard</li>
                        </ul>
                    </div>
                    
                    <div class="html-section">
                        <h4>🌐 HTML Form:</h4>
                        <div class="code-block">
&lt;form method="POST" action=""&gt;
    &lt;input type="text" name="username" required&gt;
    &lt;input type="password" name="password" required&gt;
    &lt;button type="submit"&gt;Login&lt;/button&gt;
&lt;/form&gt;</div>
                        <p><strong>What this does:</strong></p>
                        <ul>
                            <li>Creates input fields for username/password</li>
                            <li>Submits data to same page (action="")</li>
                            <li>Uses POST method (secure for passwords)</li>
                        </ul>
                    </div>
                </div>

                <div class="file-card">
                    <h3>📄 admin/includes/auth.php</h3>
                    <div class="file-purpose">
                        <strong>Purpose:</strong> Reusable authentication functions
                    </div>
                    
                    <div class="code-section">
                        <h4>🔍 Key Functions:</h4>
                        <div class="code-block">
function requireAuth() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}</div>
                        <p><strong>What this does:</strong></p>
                        <ul>
                            <li>Checks if user is logged in</li>
                            <li>Redirects to login if not authenticated</li>
                            <li>Protects admin pages from unauthorized access</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>📊 Dashboard File</h2>
            
            <div class="file-card">
                <h3>📄 admin/dashboard.php</h3>
                <div class="file-purpose">
                    <strong>Purpose:</strong> Main admin page showing analytics and overview
                </div>
                
                <div class="code-section">
                    <h4>🔍 PHP Section (Top of file):</h4>
                    <div class="code-block">
&lt;?php
require_once 'includes/auth.php';  // Check if logged in
requireAuth();                     // Redirect if not logged in

$pdo = getDBConnection();          // Get database connection

// Get analytics data
$stmt = $pdo->query("SELECT SUM(total_amount) as total_sales FROM sales");
$totalSales = $stmt->fetch()['total_sales'];
?&gt;</div>
                    <p><strong>What this does:</strong></p>
                    <ul>
                        <li>Ensures user is logged in</li>
                        <li>Connects to database</li>
                        <li>Runs SQL queries to get statistics</li>
                        <li>Stores data in PHP variables</li>
                    </ul>
                </div>
                
                <div class="html-section">
                    <h4>🌐 HTML Section (Bottom of file):</h4>
                    <div class="code-block">
&lt;div class="card"&gt;
    &lt;h3&gt;Total Sales&lt;/h3&gt;
    &lt;div class="number"&gt;$&lt;?php echo number_format($totalSales, 2); ?&gt;&lt;/div&gt;
&lt;/div&gt;</div>
                    <p><strong>What this does:</strong></p>
                    <ul>
                        <li>Creates HTML structure for analytics cards</li>
                        <li>Embeds PHP variables into HTML</li>
                        <li>Formats numbers for display</li>
                    </ul>
                </div>
                
                <div class="js-section">
                    <h4>⚡ JavaScript Section (Bottom of file):</h4>
                    <div class="code-block">
const moviesChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: &lt;?php echo json_encode($movieTitles); ?&gt;,
        datasets: [{
            data: &lt;?php echo json_encode($movieRevenue); ?&gt;
        }]
    }
});</div>
                    <p><strong>What this does:</strong></p>
                    <ul>
                        <li>Creates interactive charts using Chart.js</li>
                        <li>Gets data from PHP variables</li>
                        <li>Converts PHP arrays to JavaScript format</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>🎬 CRUD Files (Create, Read, Update, Delete)</h2>
            
            <div class="file-card">
                <h3>📄 admin/movies.php</h3>
                <div class="file-purpose">
                    <strong>Purpose:</strong> Manage movies (add, edit, delete)
                </div>
                
                <div class="crud-breakdown">
                    <div class="crud-operation">
                        <h4>➕ CREATE (Add new movie)</h4>
                        <div class="code-block">
if ($_POST['action'] === 'add') {
    $stmt = $pdo->prepare("INSERT INTO movies (title, description) VALUES (?, ?)");
    $stmt->execute([$_POST['title'], $_POST['description']]);
}</div>
                        <p><strong>Steps:</strong> Form submission → Validate data → Insert into database → Show success message</p>
                    </div>
                    
                    <div class="crud-operation">
                        <h4>📖 READ (Show all movies)</h4>
                        <div class="code-block">
$stmt = $pdo->query("SELECT * FROM movies ORDER BY created_at DESC");
$movies = $stmt->fetchAll();

foreach ($movies as $movie) {
    echo "&lt;tr&gt;&lt;td&gt;" . $movie['title'] . "&lt;/td&gt;&lt;/tr&gt;";
}</div>
                        <p><strong>Steps:</strong> Query database → Get all movies → Loop through results → Display in HTML table</p>
                    </div>
                    
                    <div class="crud-operation">
                        <h4>✏️ UPDATE (Edit existing movie)</h4>
                        <div class="code-block">
if ($_POST['action'] === 'edit') {
    $stmt = $pdo->prepare("UPDATE movies SET title = ? WHERE id = ?");
    $stmt->execute([$_POST['title'], $_POST['movie_id']]);
}</div>
                        <p><strong>Steps:</strong> Get movie ID → Load existing data → Show pre-filled form → Update database</p>
                    </div>
                    
                    <div class="crud-operation">
                        <h4>🗑️ DELETE (Remove movie)</h4>
                        <div class="code-block">
if ($_POST['action'] === 'delete') {
    $stmt = $pdo->prepare("DELETE FROM movies WHERE id = ?");
    $stmt->execute([$_POST['movie_id']]);
}</div>
                        <p><strong>Steps:</strong> Confirm deletion → Remove from database → Update display</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>🎨 CSS File Breakdown</h2>
            
            <div class="file-card">
                <h3>📄 assets/css/admin.css</h3>
                <div class="file-purpose">
                    <strong>Purpose:</strong> Makes everything look good and responsive
                </div>
                
                <div class="css-breakdown">
                    <div class="css-section">
                        <h4>🎨 Layout Styles</h4>
                        <div class="code-block">
.admin-container {
    display: flex;           /* Side-by-side layout */
    min-height: 100vh;      /* Full screen height */
}

.sidebar {
    width: 250px;           /* Fixed sidebar width */
    background: #2c3e50;    /* Dark blue background */
}</div>
                        <p><strong>What this does:</strong> Creates the main layout with sidebar and content area</p>
                    </div>
                    
                    <div class="css-section">
                        <h4>📱 Responsive Design</h4>
                        <div class="code-block">
@media (max-width: 768px) {
    .sidebar {
        width: 100%;        /* Full width on mobile */
        position: relative; /* Stack vertically */
    }
}</div>
                        <p><strong>What this does:</strong> Adapts layout for mobile devices</p>
                    </div>
                    
                    <div class="css-section">
                        <h4>🎯 Component Styles</h4>
                        <div class="code-block">
.btn {
    padding: 12px 25px;     /* Button spacing */
    border-radius: 5px;     /* Rounded corners */
    transition: all 0.3s;   /* Smooth hover effects */
}

.btn:hover {
    transform: translateY(-2px);  /* Lift effect on hover */
}</div>
                        <p><strong>What this does:</strong> Styles buttons with hover effects</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>⚡ JavaScript File Breakdown</h2>
            
            <div class="file-card">
                <h3>📄 assets/js/admin.js</h3>
                <div class="file-purpose">
                    <strong>Purpose:</strong> Adds interactive features and user experience improvements
                </div>
                
                <div class="js-breakdown">
                    <div class="js-section">
                        <h4>🎯 Form Validation</h4>
                        <div class="code-block">
function validateForm(formId) {
    const inputs = form.querySelectorAll('input[required]');
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = '#e74c3c';  // Red border for errors
        }
    });
}</div>
                        <p><strong>What this does:</strong> Checks forms before submission, highlights errors</p>
                    </div>
                    
                    <div class="js-section">
                        <h4>⏰ Auto-hide Alerts</h4>
                        <div class="code-block">
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';  // Fade out after 5 seconds
        }, 5000);
    });
});</div>
                        <p><strong>What this does:</strong> Automatically hides success/error messages</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>🔗 How Files Work Together</h2>
            
            <div class="flow-diagram">
                <div class="flow-step">
                    <h4>1. User visits page</h4>
                    <p>Browser requests PHP file</p>
                </div>
                <div class="arrow">↓</div>
                <div class="flow-step">
                    <h4>2. PHP processes</h4>
                    <p>Includes other files, runs database queries</p>
                </div>
                <div class="arrow">↓</div>
                <div class="flow-step">
                    <h4>3. HTML generated</h4>
                    <p>PHP outputs HTML with data embedded</p>
                </div>
                <div class="arrow">↓</div>
                <div class="flow-step">
                    <h4>4. Browser loads</h4>
                    <p>CSS styles the page, JavaScript adds interactivity</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>🎯 What You Learned</h2>
            <div class="summary-box">
                <h3>✅ File Organization</h3>
                <ul>
                    <li>PHP files handle server-side logic and database operations</li>
                    <li>HTML structures the content and forms</li>
                    <li>CSS makes everything look good and responsive</li>
                    <li>JavaScript adds interactive features</li>
                </ul>
                
                <h3>✅ Code Flow</h3>
                <ul>
                    <li>Files include other files to share functionality</li>
                    <li>PHP runs first (server-side), then HTML/CSS/JS (client-side)</li>
                    <li>CRUD operations follow consistent patterns</li>
                    <li>Security is built into every step</li>
                </ul>
            </div>
        </div>

        <div class="navigation">
            <a href="../02-database/database-intro.php" class="btn btn-secondary">← Previous: Database Basics</a>
            <a href="../04-features/how-it-works.php" class="btn btn-primary">Next: How Features Work →</a>
        </div>
    </div>

    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 10px; text-align: center; margin-bottom: 30px; }
        .section { background: white; padding: 25px; margin: 20px 0; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .file-breakdown { display: grid; gap: 20px; }
        .file-card { background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #3498db; }
        .file-purpose { background: #e3f2fd; padding: 10px; border-radius: 5px; margin: 10px 0; font-weight: bold; }
        .code-section, .html-section, .js-section { margin: 15px 0; }
        .code-block { background: #2c3e50; color: #ecf0f1; padding: 15px; border-radius: 5px; font-family: 'Courier New', monospace; margin: 10px 0; overflow-x: auto; }
        .crud-breakdown { display: grid; gap: 15px; }
        .crud-operation { background: white; padding: 15px; border-radius: 5px; border-left: 3px solid #28a745; }
        .css-breakdown, .js-breakdown { display: grid; gap: 15px; }
        .css-section, .js-section { background: white; padding: 15px; border-radius: 5px; }
        .flow-diagram { display: flex; flex-direction: column; align-items: center; margin: 20px 0; }
        .flow-step { background: #e3f2fd; padding: 15px; border-radius: 8px; margin: 10px; text-align: center; max-width: 300px; }
        .arrow { font-size: 24px; color: #3498db; margin: 10px; }
        .summary-box { background: #d1ecf1; padding: 20px; border-radius: 8px; border-left: 4px solid #17a2b8; }
        .navigation { text-align: center; margin: 30px 0; }
        .btn { display: inline-block; padding: 12px 25px; margin: 10px; text-decoration: none; border-radius: 5px; transition: all 0.3s; }
        .btn-primary { background: #3498db; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
    </style>
</body>
</html>