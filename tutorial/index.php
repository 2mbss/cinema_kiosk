<?php
/**
 * COMPLETE BEGINNER'S GUIDE TO CINEMA KIOSK SYSTEM
 * Learn web development step by step!
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎓 Cinema Kiosk - Complete Beginner's Guide</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 10px; text-align: center; margin-bottom: 30px; }
        .section { background: white; padding: 25px; margin: 20px 0; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .lesson-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .lesson-card { background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #3498db; }
        .lesson-card h3 { color: #2c3e50; margin-top: 0; }
        .btn { display: inline-block; padding: 12px 25px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; margin: 5px; transition: background 0.3s; }
        .btn:hover { background: #2980b9; }
        .progress { background: #e9ecef; height: 20px; border-radius: 10px; overflow: hidden; margin: 10px 0; }
        .progress-bar { background: #28a745; height: 100%; width: 0%; transition: width 0.3s; }
        .tech-stack { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0; }
        .tech-item { background: #e3f2fd; padding: 15px; border-radius: 8px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎬 Cinema Kiosk System</h1>
            <h2>Complete Beginner's Guide to Web Development</h2>
            <p>Learn PHP, MySQL, HTML, CSS, and JavaScript by building a real project!</p>
        </div>

        <div class="section">
            <h2>🎯 What You'll Learn</h2>
            <div class="tech-stack">
                <div class="tech-item">
                    <h3>🌐 HTML</h3>
                    <p>Structure and content of web pages</p>
                </div>
                <div class="tech-item">
                    <h3>🎨 CSS</h3>
                    <p>Styling and responsive design</p>
                </div>
                <div class="tech-item">
                    <h3>⚡ JavaScript</h3>
                    <p>Interactive features and charts</p>
                </div>
                <div class="tech-item">
                    <h3>🐘 PHP</h3>
                    <p>Server-side programming</p>
                </div>
                <div class="tech-item">
                    <h3>🗄️ MySQL</h3>
                    <p>Database management</p>
                </div>
                <div class="tech-item">
                    <h3>🔐 Security</h3>
                    <p>Authentication and data protection</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>📚 Learning Path</h2>
            <div class="lesson-grid">
                <div class="lesson-card">
                    <h3>🏗️ Chapter 1: Project Structure</h3>
                    <p>Understand how files and folders are organized in a web project.</p>
                    <a href="01-basics/project-structure.php" class="btn">Start Learning</a>
                </div>
                
                <div class="lesson-card">
                    <h3>🗄️ Chapter 2: Database Basics</h3>
                    <p>Learn how databases store and organize information.</p>
                    <a href="02-database/database-intro.php" class="btn">Learn Database</a>
                </div>
                
                <div class="lesson-card">
                    <h3>📁 Chapter 3: File Breakdown</h3>
                    <p>Understand what each PHP, HTML, CSS, and JS file does.</p>
                    <a href="03-files/file-explanation.php" class="btn">Explore Files</a>
                </div>
                
                <div class="lesson-card">
                    <h3>⚙️ Chapter 4: How Features Work</h3>
                    <p>See how login, CRUD operations, and analytics work behind the scenes.</p>
                    <a href="04-features/how-it-works.php" class="btn">See Features</a>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>🚀 Quick Start Guide</h2>
            <ol style="font-size: 16px; line-height: 1.6;">
                <li><strong>Install XAMPP</strong> - Download from <a href="https://www.apachefriends.org/" target="_blank">apachefriends.org</a></li>
                <li><strong>Start Services</strong> - Open XAMPP Control Panel, start Apache and MySQL</li>
                <li><strong>Access Project</strong> - Go to <code>http://localhost/cinema-kiosk/admin/login.php</code></li>
                <li><strong>Login</strong> - Use username: <code>admin</code>, password: <code>admin123</code></li>
                <li><strong>Explore</strong> - Try adding movies, creating showtimes, managing seats!</li>
            </ol>
        </div>

        <div class="section">
            <h2>🎯 Project Features You'll Master</h2>
            <div class="lesson-grid">
                <div class="lesson-card">
                    <h3>🔐 User Authentication</h3>
                    <p>Login system with sessions and password security</p>
                </div>
                <div class="lesson-card">
                    <h3>🎥 Movie Management</h3>
                    <p>Add, edit, delete movies with form handling</p>
                </div>
                <div class="lesson-card">
                    <h3>🕐 Showtime Scheduling</h3>
                    <p>Create movie schedules with date/time management</p>
                </div>
                <div class="lesson-card">
                    <h3>🪑 Seat Management</h3>
                    <p>Interactive seat maps with real-time updates</p>
                </div>
                <div class="lesson-card">
                    <h3>🍿 Inventory System</h3>
                    <p>Manage snacks and drinks with categories</p>
                </div>
                <div class="lesson-card">
                    <h3>📊 Analytics Dashboard</h3>
                    <p>Charts and statistics with Chart.js</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>💡 Learning Tips</h2>
            <ul style="font-size: 16px; line-height: 1.8;">
                <li><strong>Start with basics</strong> - Don't skip the foundation chapters</li>
                <li><strong>Practice as you learn</strong> - Try modifying the code yourself</li>
                <li><strong>Use browser developer tools</strong> - Press F12 to inspect elements</li>
                <li><strong>Read error messages</strong> - They tell you exactly what's wrong</li>
                <li><strong>Take breaks</strong> - Learning programming takes time and patience</li>
            </ul>
        </div>

        <div class="section" style="text-align: center;">
            <h2>🎬 Ready to Start?</h2>
            <p style="font-size: 18px; margin-bottom: 30px;">Begin your web development journey with our step-by-step tutorials!</p>
            <a href="01-basics/project-structure.php" class="btn" style="font-size: 18px; padding: 15px 30px;">🚀 Start Chapter 1</a>
        </div>
    </div>
</body>
</html>