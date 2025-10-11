<?php
require_once 'includes/auth.php';

$auth = new Auth();

echo "<h2>Testing Magodi Private School Login System</h2>";

// Test database connection
try {
    require_once 'config/database.php';
    $database = new Database();
    $conn = $database->getConnection();
    echo "<p style='color: green;'>✅ Database connection successful</p>";
} catch(Exception $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
    exit;
}

// Test if admin user exists
try {
    $query = "SELECT * FROM User WHERE Username = 'admin'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $admin_user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($admin_user) {
        echo "<p style='color: green;'>✅ Admin user exists</p>";
        echo "<p><strong>Username:</strong> admin</p>";
        echo "<p><strong>Password:</strong> admin123</p>";
        echo "<p><strong>Role ID:</strong> " . $admin_user['Role_ID'] . "</p>";
    } else {
        echo "<p style='color: red;'>❌ Admin user not found</p>";
        echo "<p>Please run the database initialization first.</p>";
    }
} catch(Exception $e) {
    echo "<p style='color: red;'>❌ Error checking admin user: " . $e->getMessage() . "</p>";
}

// Test login
if(isset($_POST['test_login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if($auth->login($username, $password)) {
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h3 style='color: #155724;'>✅ Login Successful!</h3>";
        echo "<p>You can now access the system.</p>";
        echo "<p><a href='index.php'>Go to Homepage</a> | <a href='admin/dashboard.php'>Go to Admin Dashboard</a></p>";
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h3 style='color: #721c24;'>❌ Login Failed!</h3>";
        echo "<p>Please check your credentials.</p>";
        echo "</div>";
    }
}

// Check if setup is completed
if(file_exists('setup_completed.txt')) {
    echo "<p style='color: green;'>✅ Setup completed on: " . file_get_contents('setup_completed.txt') . "</p>";
} else {
    echo "<p style='color: orange;'>⚠️ Setup not completed. Please run database initialization first.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Login - Magodi Private School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Test Login</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="admin" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" value="admin123" required>
                            </div>
                            <button type="submit" name="test_login" class="btn btn-primary">Test Login</button>
                        </form>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h5>Quick Links:</h5>
                    <ul>
                        <li><a href="init_database.php">Initialize Database</a></li>
                        <li><a href="login.php">Go to Login Page</a></li>
                        <li><a href="index.php">Go to Homepage</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
