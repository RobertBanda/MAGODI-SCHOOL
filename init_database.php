<?php
require_once 'config/database.php';

echo "<h2>Initializing Magodi Private School Database...</h2>";

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Check if tables already exist
    $query = "SHOW TABLES LIKE 'User'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $user_table_exists = $stmt->fetch();
    
    if($user_table_exists) {
        echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h3 style='color: #856404;'>⚠️ Database Already Initialized</h3>";
        echo "<p>Tables already exist in the database.</p>";
        echo "<p>If you want to reset, <a href='reset_database.php'>click here</a></p>";
        echo "<p>Or <a href='login.php'>go to login page</a> to access the system</p>";
        echo "</div>";
        exit;
    }
    
    // Read and execute the schema file
    $schema = file_get_contents('database/schema.sql');
    $statements = explode(';', $schema);
    
    $success_count = 0;
    $error_count = 0;
    
    foreach($statements as $statement) {
        $statement = trim($statement);
        if(!empty($statement) && !preg_match('/^(CREATE DATABASE|USE)/i', $statement)) {
            try {
                $conn->exec($statement);
                $success_count++;
            } catch(Exception $e) {
                $error_count++;
                echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
            }
        }
    }
    
    // Create default admin user
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $query = "INSERT INTO User (Username, Password, Role_ID, Related_ID) VALUES ('admin', ?, 1, NULL)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$admin_password]);
    
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3 style='color: #155724;'>✅ Database Initialization Complete!</h3>";
    echo "<p><strong>Successful statements:</strong> $success_count</p>";
    echo "<p><strong>Errors:</strong> $error_count</p>";
    echo "<p><strong>Admin user created:</strong> admin / admin123</p>";
    echo "</div>";
    
    echo "<div style='background: #cce5ff; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3 style='color: #004085;'>🚀 Next Steps:</h3>";
    echo "<ol>";
    echo "<li>Go to <a href='login.php'>login.php</a> to access the system</li>";
    echo "<li>Login with: <strong>admin</strong> / <strong>admin123</strong></li>";
    echo "<li>Start using the School Management System!</li>";
    echo "</ol>";
    echo "</div>";
    
    // Mark setup as completed
    file_put_contents('setup_completed.txt', date('Y-m-d H:i:s'));
    
} catch(Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3 style='color: #721c24;'>❌ Database Initialization Failed!</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Please check your database configuration and try again.</p>";
    echo "</div>";
}
?>
