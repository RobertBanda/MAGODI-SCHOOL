<?php
// Test Students - Debug Student Information
echo "<h2>🔍 Test Students - Debug Information</h2>";

// Database configuration
$host = "localhost";
$db_name = "magodi_private_school";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✅ Database connection successful</p>";
    
    // Check if Student table exists
    $query = "SHOW TABLES LIKE 'Student'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $table_exists = $stmt->fetch();
    
    if(!$table_exists) {
        echo "<p style='color: red;'>❌ Student table does not exist!</p>";
    } else {
        echo "<p style='color: green;'>✅ Student table exists</p>";
        
        // Get all students
        $query = "SELECT Student_ID, First_Name, Last_Name, Student_Number, Status FROM Student ORDER BY Student_ID";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<p style='color: blue;'>Found " . count($students) . " students in database</p>";
        
        if(empty($students)) {
            echo "<p style='color: orange;'>⚠️ No students found in database</p>";
            echo "<p><a href='populate_guardians.php' class='btn btn-primary'>Create Sample Students</a></p>";
        } else {
            echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
            echo "<h3>📋 All Students:</h3>";
            echo "<table class='table table-striped'>";
            echo "<thead><tr><th>Student ID</th><th>Name</th><th>Student Number</th><th>Status</th><th>Actions</th></tr></thead>";
            echo "<tbody>";
            
            foreach($students as $student) {
                echo "<tr>";
                echo "<td>" . $student['Student_ID'] . "</td>";
                echo "<td>" . $student['First_Name'] . ' ' . $student['Last_Name'] . "</td>";
                echo "<td>" . $student['Student_Number'] . "</td>";
                echo "<td>" . $student['Status'] . "</td>";
                echo "<td>";
                echo "<a href='admin/student_credentials.php?student_id=" . $student['Student_ID'] . "' class='btn btn-sm btn-info' target='_blank'>";
                echo "<i class='fas fa-key'></i> View Credentials";
                echo "</a>";
                echo "</td>";
                echo "</tr>";
            }
            
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        }
    }
    
    // Check Student_Credentials table
    $query = "SHOW TABLES LIKE 'Student_Credentials'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $credentials_table_exists = $stmt->fetch();
    
    if(!$credentials_table_exists) {
        echo "<p style='color: red;'>❌ Student_Credentials table does not exist!</p>";
        echo "<p><a href='fix_credentials_table.php' class='btn btn-warning'>Fix Credentials Table</a></p>";
    } else {
        echo "<p style='color: green;'>✅ Student_Credentials table exists</p>";
        
        // Check credentials
        $query = "SELECT COUNT(*) as count FROM Student_Credentials";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $credentials_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        echo "<p style='color: blue;'>Found $credentials_count stored credentials</p>";
    }
    
    echo "<div style='text-center; margin: 20px 0;'>";
    echo "<a href='admin/students.php' class='btn btn-primary btn-lg me-3'>";
    echo "<i class='fas fa-users me-2'></i>Go to Students Page";
    echo "</a>";
    echo "<a href='fix_credentials_table.php' class='btn btn-warning btn-lg me-3'>";
    echo "<i class='fas fa-tools me-2'></i>Fix Credentials";
    echo "</a>";
    echo "<a href='simple_login.php' class='btn btn-success btn-lg'>";
    echo "<i class='fas fa-sign-in-alt me-2'></i>Test Login";
    echo "</a>";
    echo "</div>";
    
} catch(Exception $e) {
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    echo "<h3 style='color: #721c24;'>❌ Database Error!</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Please check your database connection.</p>";
    echo "</div>";
}
?>
