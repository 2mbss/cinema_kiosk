<?php
/**
 * CHAPTER 1: PROJECT STRUCTURE
 * Understanding how web projects are organized
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapter 1: Project Structure</title>
    <link rel="stylesheet" href="../tutorial-style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏗️ Chapter 1: Project Structure</h1>
            <p>Understanding how files and folders work together</p>
        </div>

        <div class="section">
            <h2>🎯 What You'll Learn</h2>
            <ul>
                <li>How web projects are organized</li>
                <li>What each folder contains</li>
                <li>Why organization matters</li>
                <li>How files connect to each other</li>
            </ul>
        </div>

        <div class="section">
            <h2>📁 Your Project Structure</h2>
            <div class="code-block">
<pre>cinema-kiosk/                    ← Main project folder
├── admin/                        ← Admin panel (where managers work)
│   ├── includes/                 ← Reusable code pieces
│   │   ├── auth.php             ← Login checking functions
│   │   └── sidebar.php          ← Navigation menu
│   ├── dashboard.php            ← Main admin page (analytics)
│   ├── movies.php               ← Manage movies
│   ├── showtimes.php            ← Manage showtimes
│   ├── seats.php                ← Manage seats
│   ├── extras.php               ← Manage snacks/drinks
│   └── login.php                ← Admin login page
├── assets/                       ← Static files (don't change)
│   ├── css/                     ← Styling files
│   │   └── admin.css            ← Makes everything look nice
│   ├── js/                      ← JavaScript files
│   │   └── admin.js             ← Interactive features
│   └── images/                  ← Pictures and icons
├── db/                          ← Database related files
│   ├── config.php               ← Database connection settings
│   └── cinema_kiosk.sql         ← Database structure and sample data
├── kiosk/                       ← Customer interface (future)
└── tutorial/                    ← Learning materials (this!)</pre>
            </div>
        </div>

        <div class="section">
            <h2>🤔 Why This Structure?</h2>
            
            <div class="concept-box">
                <h3>🏢 Think of it like a building:</h3>
                <ul>
                    <li><strong>admin/</strong> = Manager's office (restricted access)</li>
                    <li><strong>assets/</strong> = Storage room (CSS, JS, images)</li>
                    <li><strong>db/</strong> = Safe (database connection and data)</li>
                    <li><strong>kiosk/</strong> = Customer area (public access)</li>
                </ul>
            </div>

            <div class="concept-box">
                <h3>📋 Benefits of good organization:</h3>
                <ul>
                    <li><strong>Easy to find files</strong> - Everything has its place</li>
                    <li><strong>Easy to maintain</strong> - Update one file, affects whole site</li>
                    <li><strong>Security</strong> - Separate admin from public areas</li>
                    <li><strong>Teamwork</strong> - Other developers can understand quickly</li>
                </ul>
            </div>
        </div>

        <div class="section">
            <h2>🔗 How Files Connect</h2>
            
            <div class="flow-diagram">
                <div class="flow-step">
                    <h4>1. User visits login.php</h4>
                    <p>Browser requests the login page</p>
                </div>
                <div class="arrow">↓</div>
                <div class="flow-step">
                    <h4>2. login.php includes config.php</h4>
                    <p>Gets database connection function</p>
                </div>
                <div class="arrow">↓</div>
                <div class="flow-step">
                    <h4>3. User submits login form</h4>
                    <p>PHP checks username/password in database</p>
                </div>
                <div class="arrow">↓</div>
                <div class="flow-step">
                    <h4>4. Redirect to dashboard.php</h4>
                    <p>If login successful, go to main admin page</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>📝 File Types Explained</h2>
            
            <div class="file-types">
                <div class="file-type">
                    <h3>📄 .php files</h3>
                    <p><strong>What:</strong> Server-side code that runs before sending to browser</p>
                    <p><strong>Does:</strong> Database operations, user authentication, business logic</p>
                    <p><strong>Example:</strong> login.php checks if password is correct</p>
                </div>
                
                <div class="file-type">
                    <h3>🎨 .css files</h3>
                    <p><strong>What:</strong> Styling rules that make pages look good</p>
                    <p><strong>Does:</strong> Colors, fonts, layouts, responsive design</p>
                    <p><strong>Example:</strong> admin.css makes buttons blue and forms centered</p>
                </div>
                
                <div class="file-type">
                    <h3>⚡ .js files</h3>
                    <p><strong>What:</strong> Client-side code that runs in the browser</p>
                    <p><strong>Does:</strong> Interactive features, form validation, charts</p>
                    <p><strong>Example:</strong> admin.js creates the analytics charts</p>
                </div>
                
                <div class="file-type">
                    <h3>🗄️ .sql files</h3>
                    <p><strong>What:</strong> Database commands and structure</p>
                    <p><strong>Does:</strong> Create tables, insert sample data</p>
                    <p><strong>Example:</strong> cinema_kiosk.sql creates all database tables</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>🎯 Key Concepts You Learned</h2>
            <div class="summary-box">
                <h3>✅ Project Organization</h3>
                <ul>
                    <li>Separate admin and public areas</li>
                    <li>Group similar files together (CSS, JS, images)</li>
                    <li>Keep database files separate</li>
                    <li>Use descriptive folder names</li>
                </ul>
                
                <h3>✅ File Relationships</h3>
                <ul>
                    <li>PHP files can include other PHP files</li>
                    <li>HTML links to CSS and JS files</li>
                    <li>All files work together to create the application</li>
                </ul>
            </div>
        </div>

        <div class="navigation">
            <a href="../index.php" class="btn btn-secondary">← Back to Main</a>
            <a href="../02-database/database-intro.php" class="btn btn-primary">Next: Database Basics →</a>
        </div>
    </div>

    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f8f9fa; }
        .container { max-width: 1000px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 10px; text-align: center; margin-bottom: 30px; }
        .section { background: white; padding: 25px; margin: 20px 0; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .code-block { background: #2c3e50; color: #ecf0f1; padding: 20px; border-radius: 8px; font-family: 'Courier New', monospace; overflow-x: auto; }
        .concept-box { background: #e8f5e8; padding: 20px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #28a745; }
        .flow-diagram { display: flex; flex-direction: column; align-items: center; }
        .flow-step { background: #e3f2fd; padding: 15px; border-radius: 8px; margin: 10px; text-align: center; max-width: 300px; }
        .arrow { font-size: 24px; color: #3498db; margin: 10px; }
        .file-types { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .file-type { background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #3498db; }
        .summary-box { background: #fff3cd; padding: 20px; border-radius: 8px; border-left: 4px solid #ffc107; }
        .navigation { text-align: center; margin: 30px 0; }
        .btn { display: inline-block; padding: 12px 25px; margin: 10px; text-decoration: none; border-radius: 5px; transition: all 0.3s; }
        .btn-primary { background: #3498db; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
    </style>
</body>
</html>