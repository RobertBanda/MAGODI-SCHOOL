<?php
require_once 'config/database.php';

echo "<h2>Resetting Magodi Private School Database...</h2>";

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Drop all tables if they exist
    $tables = [
        'Audit_Log', 'Message', 'Inventory', 'Book_Issue', 'Book', 'Payment', 
        'Fee_Structure', 'Exam_Result', 'Exam', 'Attendance', 'Class_Subject', 
        'Student', 'Non_Teaching_Staff', 'Teacher', 'Subject', 'Class', 
        'User', 'Role', 'Guardian', 'Timetable', 'School_Info'
    ];
    
    echo "<p>Dropping existing tables...</p>";
    foreach($tables as $table) {
        try {
            $conn->exec("DROP TABLE IF EXISTS `$table`");
            echo "<p style='color: green;'>✅ Dropped table: $table</p>";
        } catch(Exception $e) {
            echo "<p style='color: orange;'>⚠️ Could not drop $table: " . $e->getMessage() . "</p>";
        }
    }
    
    // Remove setup completion file
    if(file_exists('setup_completed.txt')) {
        unlink('setup_completed.txt');
        echo "<p style='color: green;'>✅ Removed setup_completed.txt</p>";
    }
    
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3 style='color: #155724;'>✅ Database Reset Complete!</h3>";
    echo "<p>All tables have been dropped. You can now run the setup again.</p>";
    echo "</div>";
    
    echo "<div style='background: #cce5ff; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3 style='color: #004085;'>🚀 Next Steps:</h3>";
    echo "<ol>";
    echo "<li>Go to <a href='init_database.php'>init_database.php</a> to create fresh tables</li>";
    echo "<li>Or go to <a href='setup.php'>setup.php</a> for the full setup</li>";
    echo "<li>Then login with admin / admin123</li>";
    echo "</ol>";
    echo "</div>";
    
} catch(Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3 style='color: #721c24;'>❌ Database Reset Failed!</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Please check your database configuration and try again.</p>";
    echo "</div>";
}
?>
