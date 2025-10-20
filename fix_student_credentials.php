<?php
/**
 * Fix Student Credentials System
 * Creates student_credentials table and regenerates student login credentials
 */

echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>Fix Student Credentials</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "</head>";
echo "<body class='bg-light'>";
echo "<div class='container py-5'>";
echo "<h1 class='mb-4'>🔧 Fix Student Credentials System</h1>";

require_once 'config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Step 1: Create student_credentials table
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-primary text-white'><h5>Step 1: Creating student_credentials table</h5></div>";
    echo "<div class='card-body'>";
    
    $query = "CREATE TABLE IF NOT EXISTS student_credentials (
        Credential_ID INT PRIMARY KEY AUTO_INCREMENT,
        Student_ID INT NOT NULL,
        Username VARCHAR(100) NOT NULL,
        Original_Password VARCHAR(100) NOT NULL,
        Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (Student_ID) REFERENCES student(Student_ID) ON DELETE CASCADE,
        UNIQUE KEY unique_student (Student_ID)
    )";
    
    $conn->exec($query);
    echo "<p class='text-success'>✓ Table created successfully!</p>";
    echo "</div></div>";
    
    // Step 2: Get student role ID
    $stmt = $conn->query("SELECT Role_ID FROM role WHERE Role_Name = 'Student'");
    $role = $stmt->fetch(PDO::FETCH_ASSOC);
    $student_role_id = $role['Role_ID'];
    
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-info text-white'><h5>Step 2: Student Role ID = $student_role_id</h5></div>";
    echo "</div>";
    
    // Step 3: Get all active students
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-warning'><h5>Step 3: Processing Students</h5></div>";
    echo "<div class='card-body'>";
    
    $query = "SELECT s.*, c.Class_Name, u.User_ID, u.Username as Current_Username
              FROM student s
              LEFT JOIN class c ON s.Class_ID = c.Class_ID
              LEFT JOIN user u ON s.Student_ID = u.Related_ID AND u.Role_ID = ?
              WHERE s.Is_Active = 1
              ORDER BY s.First_Name, s.Last_Name";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([$student_role_id]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p>Found " . count($students) . " active students</p>";
    
    $created = 0;
    $updated = 0;
    $credentials_list = [];
    
    foreach ($students as $student) {
        // Generate username
        $first_name = strtolower(preg_replace('/[^a-zA-Z]/', '', $student['First_Name']));
        $last_name = strtolower(preg_replace('/[^a-zA-Z]/', '', $student['Last_Name']));
        $username = $first_name . '.' . $last_name;
        
        // Generate password
        $password = 'student' . date('Y') . rand(100, 999);
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $conn->beginTransaction();
        
        try {
            if ($student['User_ID']) {
                // Update existing user
                $query = "UPDATE user SET Password = ?, Username = ? WHERE User_ID = ?";
                $stmt = $conn->prepare($query);
                $stmt->execute([$password_hash, $username, $student['User_ID']]);
                
                // Update or create credential record
                $query = "INSERT INTO student_credentials (Student_ID, Username, Original_Password) 
                         VALUES (?, ?, ?)
                         ON DUPLICATE KEY UPDATE Username = ?, Original_Password = ?";
                $stmt = $conn->prepare($query);
                $stmt->execute([
                    $student['Student_ID'], 
                    $username, 
                    $password,
                    $username,
                    $password
                ]);
                
                $updated++;
            } else {
                // Create new user
                $query = "INSERT INTO user (Username, Password, Role_ID, Related_ID, Is_Active) 
                         VALUES (?, ?, ?, ?, 1)";
                $stmt = $conn->prepare($query);
                $stmt->execute([$username, $password_hash, $student_role_id, $student['Student_ID']]);
                
                // Create credential record
                $query = "INSERT INTO student_credentials (Student_ID, Username, Original_Password) 
                         VALUES (?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->execute([$student['Student_ID'], $username, $password]);
                
                $created++;
            }
            
            $conn->commit();
            
            $credentials_list[] = [
                'name' => $student['First_Name'] . ' ' . $student['Last_Name'],
                'student_number' => $student['Student_Number'],
                'class' => $student['Class_Name'] ?? 'No Class',
                'username' => $username,
                'password' => $password
            ];
            
        } catch (Exception $e) {
            $conn->rollBack();
            echo "<p class='text-danger'>Error for {$student['First_Name']} {$student['Last_Name']}: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<p class='text-success'><strong>✓ Created: $created new accounts</strong></p>";
    echo "<p class='text-info'><strong>✓ Updated: $updated existing accounts</strong></p>";
    echo "</div></div>";
    
    // Step 4: Display all credentials
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-success text-white'><h5>Step 4: All Student Login Credentials</h5></div>";
    echo "<div class='card-body'>";
    echo "<div class='table-responsive'>";
    echo "<table class='table table-striped table-bordered'>";
    echo "<thead class='table-dark'>";
    echo "<tr>";
    echo "<th>Student Name</th>";
    echo "<th>Student Number</th>";
    echo "<th>Class</th>";
    echo "<th>Username</th>";
    echo "<th>Password</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    foreach ($credentials_list as $cred) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($cred['name']) . "</td>";
        echo "<td>" . htmlspecialchars($cred['student_number']) . "</td>";
        echo "<td>" . htmlspecialchars($cred['class']) . "</td>";
        echo "<td><strong class='text-primary'>" . htmlspecialchars($cred['username']) . "</strong></td>";
        echo "<td><strong class='text-success font-monospace'>" . htmlspecialchars($cred['password']) . "</strong></td>";
        echo "</tr>";
    }
    
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo "</div></div>";
    
    // Final instructions
    echo "<div class='alert alert-info'>";
    echo "<h5>✅ Success! Next Steps:</h5>";
    echo "<ol>";
    echo "<li>All student credentials have been generated and saved</li>";
    echo "<li>Students can now login using their username and password shown above</li>";
    echo "<li>Test login at: <a href='simple_login.php' class='alert-link'>simple_login.php</a></li>";
    echo "<li>View all credentials at: <a href='view_credentials.php' class='alert-link'>view_credentials.php</a></li>";
    echo "<li>Student credentials page at: <a href='admin/student_credentials.php' class='alert-link'>admin/student_credentials.php</a></li>";
    echo "</ol>";
    echo "</div>";
    
    echo "<div class='text-center'>";
    echo "<a href='simple_login.php' class='btn btn-primary btn-lg me-2'><i class='fas fa-sign-in-alt me-2'></i>Test Login</a>";
    echo "<a href='view_credentials.php' class='btn btn-success btn-lg'><i class='fas fa-eye me-2'></i>View All Credentials</a>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>";
    echo "<h5>❌ Error!</h5>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}

echo "</div>";
echo "</body>";
echo "</html>";
?>

