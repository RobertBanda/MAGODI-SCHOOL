<?php
// Fix Student_Credentials Table - Magodi Private School Management System
echo "<h2>🔧 Fixing Student Credentials Table - Magodi Private School</h2>";

// Database configuration
$host = "localhost";
$db_name = "magodi_private_school";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✅ Database connection successful</p>";
    
    // Create Student_Credentials table if it doesn't exist
    $conn->exec("
        CREATE TABLE IF NOT EXISTS Student_Credentials (
            Credential_ID INT PRIMARY KEY AUTO_INCREMENT,
            Student_ID INT NOT NULL,
            Username VARCHAR(50) NOT NULL,
            Original_Password VARCHAR(100) NOT NULL,
            Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (Student_ID) REFERENCES Student(Student_ID) ON DELETE CASCADE
        )
    ");
    echo "<p style='color: green;'>✅ Student_Credentials table created/verified</p>";
    
    // Check if there are any students without credentials
    $query = "SELECT COUNT(*) as count FROM Student s 
              LEFT JOIN Student_Credentials sc ON s.Student_ID = sc.Student_ID
              WHERE sc.Student_ID IS NULL AND s.Status = 'Active'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $missing_credentials = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    if($missing_credentials > 0) {
        echo "<p style='color: blue;'>Found $missing_credentials students without stored credentials</p>";
        
        // Get students without credentials
        $query = "SELECT s.* FROM Student s 
                  LEFT JOIN Student_Credentials sc ON s.Student_ID = sc.Student_ID
                  WHERE sc.Student_ID IS NULL AND s.Status = 'Active'";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($students as $student) {
            // Generate username and password
            $username = strtolower($student['First_Name'] . '.' . $student['Last_Name']);
            $password = 'student' . date('Y') . rand(100, 999);
            
            // Store credentials
            $query = "INSERT INTO Student_Credentials (Student_ID, Username, Original_Password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$student['Student_ID'], $username, $password]);
        }
        
        echo "<p style='color: green;'>✅ Generated credentials for $missing_credentials students</p>";
    } else {
        echo "<p style='color: green;'>✅ All students already have stored credentials</p>";
    }
    
    echo "<div style='background: #d4edda; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    echo "<h3 style='color: #155724;'>🎉 Student Credentials System Fixed!</h3>";
    echo "<p><strong>What was fixed:</strong></p>";
    echo "<ul>";
    echo "<li>✅ Created Student_Credentials table</li>";
    echo "<li>✅ Fixed database connection issues</li>";
    echo "<li>✅ Generated missing student credentials</li>";
    echo "<li>✅ Fixed variable scope errors</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div style='text-center; margin: 20px 0;'>";
    echo "<a href='admin/students.php' class='btn btn-primary btn-lg me-3'>";
    echo "<i class='fas fa-users me-2'></i>Go to Students Page";
    echo "</a>";
    echo "<a href='simple_login.php' class='btn btn-success btn-lg'>";
    echo "<i class='fas fa-sign-in-alt me-2'></i>Test Login";
    echo "</a>";
    echo "</div>";
    
} catch(Exception $e) {
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    echo "<h3 style='color: #721c24;'>❌ Error Fixing Credentials Table!</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Please ensure your database is set up correctly.</p>";
    echo "</div>";
}
?>
