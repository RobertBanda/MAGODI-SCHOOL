<?php
require_once 'config/database.php';

echo "<h2>Magodi Private School - System Status Check</h2>";

try {
    $database = new Database();
    $conn = $database->getConnection();
    echo "<p style='color: green;'>✅ Database connection successful</p>";
    
    // Check if database exists
    $query = "SELECT DATABASE() as current_db";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $db_name = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p><strong>Current Database:</strong> " . $db_name['current_db'] . "</p>";
    
    // Check existing tables
    $query = "SHOW TABLES";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Existing Tables:</h3>";
    if(count($tables) > 0) {
        echo "<ul>";
        foreach($tables as $table) {
            echo "<li style='color: green;'>✅ $table</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>❌ No tables found</p>";
    }
    
    // Check if admin user exists
    if(in_array('User', $tables)) {
        $query = "SELECT COUNT(*) as user_count FROM User";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $user_count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p><strong>Total Users:</strong> " . $user_count['user_count'] . "</p>";
        
        $query = "SELECT * FROM User WHERE Username = 'admin'";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $admin_user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($admin_user) {
            echo "<p style='color: green;'>✅ Admin user exists</p>";
            echo "<p><strong>Admin Username:</strong> " . $admin_user['Username'] . "</p>";
            echo "<p><strong>Admin Role ID:</strong> " . $admin_user['Role_ID'] . "</p>";
        } else {
            echo "<p style='color: red;'>❌ Admin user not found</p>";
        }
    }
    
    // Check setup completion
    if(file_exists('setup_completed.txt')) {
        echo "<p style='color: green;'>✅ Setup completed on: " . file_get_contents('setup_completed.txt') . "</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Setup not marked as completed</p>";
    }
    
    // System recommendations
    echo "<h3>System Status:</h3>";
    if(count($tables) > 0 && isset($admin_user)) {
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h4 style='color: #155724;'>✅ System Ready!</h4>";
        echo "<p>Your system is ready to use.</p>";
        echo "<p><a href='login.php' class='btn btn-primary'>Go to Login</a></p>";
        echo "</div>";
    } elseif(count($tables) > 0) {
        echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h4 style='color: #856404;'>⚠️ Tables Exist but Admin User Missing</h4>";
        echo "<p>Tables exist but admin user is missing. You may need to reset the database.</p>";
        echo "<p><a href='reset_database.php' class='btn btn-warning'>Reset Database</a></p>";
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h4 style='color: #721c24;'>❌ Database Not Initialized</h4>";
        echo "<p>No tables found. You need to initialize the database.</p>";
        echo "<p><a href='init_database.php' class='btn btn-primary'>Initialize Database</a></p>";
        echo "</div>";
    }
    
} catch(Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3 style='color: #721c24;'>❌ Database Connection Failed!</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Please check your database configuration in config/database.php</p>";
    echo "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Status - Magodi Private School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="check_status.php" class="btn btn-info w-100 mb-2">Check Status</a>
                            </div>
                            <div class="col-md-3">
                                <a href="init_database.php" class="btn btn-primary w-100 mb-2">Initialize DB</a>
                            </div>
                            <div class="col-md-3">
                                <a href="reset_database.php" class="btn btn-warning w-100 mb-2">Reset DB</a>
                            </div>
                            <div class="col-md-3">
                                <a href="login.php" class="btn btn-success w-100 mb-2">Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
