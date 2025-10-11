<?php
// Generate Student Credentials - Magodi Private School Management System
echo "<h2>🎓 Generate Student Credentials - Magodi Private School</h2>";

// Database configuration
$host = "localhost";
$db_name = "magodi_private_school";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✅ Database connection successful</p>";
    
    // Get all students without user accounts
    $query = "SELECT s.*, c.Class_Name FROM Student s 
              LEFT JOIN Class c ON s.Class_ID = c.Class_ID 
              LEFT JOIN User u ON s.Student_ID = u.Related_ID AND u.Role_ID = 3
              WHERE u.User_ID IS NULL AND s.Status = 'Active'
              ORDER BY s.First_Name, s.Last_Name";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if(empty($students)) {
        echo "<div style='background: #d4edda; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
        echo "<h3 style='color: #155724;'>✅ All Students Have Accounts</h3>";
        echo "<p>All active students already have login accounts.</p>";
        echo "</div>";
    } else {
        echo "<p style='color: blue;'>Found " . count($students) . " students without login accounts</p>";
        
        $created_accounts = [];
        
        foreach($students as $student) {
            // Generate username and password
            $username = strtolower($student['First_Name'] . '.' . $student['Last_Name']);
            $password = 'student' . date('Y') . rand(100, 999);
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Create user account
            $query = "INSERT INTO User (Username, Password, Role_ID, Related_ID) VALUES (?, ?, 3, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$username, $password_hash, $student['Student_ID']]);
            
            $created_accounts[] = [
                'name' => $student['First_Name'] . ' ' . $student['Last_Name'],
                'student_id' => $student['Student_Number'],
                'class' => $student['Class_Name'],
                'username' => $username,
                'password' => $password
            ];
        }
        
        echo "<p style='color: green;'>✅ Created " . count($created_accounts) . " student accounts</p>";
        
        echo "<div style='background: #cce5ff; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
        echo "<h3 style='color: #004085;'>🎓 Generated Student Credentials:</h3>";
        echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px;'>";
        
        foreach($created_accounts as $account) {
            echo "<div style='background: white; padding: 15px; border-radius: 8px; border-left: 4px solid #007bff;'>";
            echo "<h4 style='color: #007bff; margin: 0 0 10px 0;'>🎓 " . $account['name'] . "</h4>";
            echo "<p><strong>Student ID:</strong> " . $account['student_id'] . "</p>";
            echo "<p><strong>Class:</strong> " . $account['class'] . "</p>";
            echo "<p><strong>Username:</strong> " . $account['username'] . "</p>";
            echo "<p><strong>Password:</strong> " . $account['password'] . "</p>";
            echo "</div>";
        }
        
        echo "</div>";
        echo "</div>";
    }
    
    // Show all existing student accounts
    $query = "SELECT s.*, c.Class_Name, u.Username FROM Student s 
              LEFT JOIN Class c ON s.Class_ID = c.Class_ID 
              LEFT JOIN User u ON s.Student_ID = u.Related_ID AND u.Role_ID = 3
              WHERE s.Status = 'Active'
              ORDER BY s.First_Name, s.Last_Name";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $all_students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    echo "<h3 style='color: #495057;'>📋 All Student Accounts:</h3>";
    echo "<div class='table-responsive'>";
    echo "<table class='table table-striped'>";
    echo "<thead><tr><th>Name</th><th>Student ID</th><th>Class</th><th>Username</th><th>Status</th></tr></thead>";
    echo "<tbody>";
    
    foreach($all_students as $student) {
        $status = $student['Username'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-warning">No Account</span>';
        echo "<tr>";
        echo "<td>" . $student['First_Name'] . ' ' . $student['Last_Name'] . "</td>";
        echo "<td>" . $student['Student_Number'] . "</td>";
        echo "<td>" . $student['Class_Name'] . "</td>";
        echo "<td>" . ($student['Username'] ?: 'N/A') . "</td>";
        echo "<td>" . $status . "</td>";
        echo "</tr>";
    }
    
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo "</div>";
    
    echo "<div style='text-center; margin: 20px 0;'>";
    echo "<a href='admin/students.php' class='btn btn-primary btn-lg me-3'>";
    echo "<i class='fas fa-users me-2'></i>Manage Students";
    echo "</a>";
    echo "<a href='simple_login.php' class='btn btn-success btn-lg'>";
    echo "<i class='fas fa-sign-in-alt me-2'></i>Test Login";
    echo "</a>";
    echo "</div>";
    
} catch(Exception $e) {
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    echo "<h3 style='color: #721c24;'>❌ Error Generating Credentials!</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Please ensure your database is set up correctly.</p>";
    echo "</div>";
}
?>
