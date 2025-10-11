<?php
require_once 'config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    echo "<h2>Setting up Staff Portal Database Tables</h2>";
    
    // Read and execute the SQL file
    $sql = file_get_contents('database/staff_tables.sql');
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    $success_count = 0;
    $error_count = 0;
    
    foreach($statements as $statement) {
        if(empty($statement)) continue;
        
        try {
            $conn->exec($statement);
            $success_count++;
            echo "<p style='color: green;'>✓ Executed: " . substr($statement, 0, 50) . "...</p>";
        } catch(PDOException $e) {
            $error_count++;
            echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<h3>Setup Complete!</h3>";
    echo "<p><strong>Successfully executed:</strong> $success_count statements</p>";
    echo "<p><strong>Errors:</strong> $error_count statements</p>";
    
    if($error_count == 0) {
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h4 style='color: #155724; margin: 0 0 10px 0;'>🎉 Staff Portal is Ready!</h4>";
        echo "<p style='color: #155724; margin: 0;'>All database tables have been created successfully. You can now use the Staff Portal with full CRUD functionality.</p>";
        echo "</div>";
        
        echo "<h4>What's Available:</h4>";
        echo "<ul>";
        echo "<li>✅ <strong>Messages System</strong> - Send and receive messages between staff</li>";
        echo "<li>✅ <strong>Library Management</strong> - Add, edit, delete, and track books</li>";
        echo "<li>✅ <strong>Book Borrowing</strong> - Track book borrowing and returns</li>";
        echo "<li>✅ <strong>Teacher Profiles</strong> - Complete teacher information management</li>";
        echo "</ul>";
        
        echo "<h4>Access the Staff Portal:</h4>";
        echo "<p><a href='staff/dashboard.php' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Staff Dashboard</a></p>";
    }
    
} catch(Exception $e) {
    echo "<h3 style='color: red;'>Setup Failed!</h3>";
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
