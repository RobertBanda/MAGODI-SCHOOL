<?php
/**
 * Complete Login Diagnosis Tool
 * Checks everything related to student login
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Diagnosis - Magodi School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px; }
        .card { margin-bottom: 20px; }
        .success { background: #d4edda; padding: 10px; border-radius: 5px; margin: 5px 0; }
        .error { background: #f8d7da; padding: 10px; border-radius: 5px; margin: 5px 0; }
        .info { background: #cfe2ff; padding: 10px; border-radius: 5px; margin: 5px 0; }
        .warning { background: #fff3cd; padding: 10px; border-radius: 5px; margin: 5px 0; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3><i class="fas fa-stethoscope me-2"></i>Login System Diagnosis</h3>
        </div>
        <div class="card-body">
<?php

$host = "localhost";
$db_name = "magodi_private_school";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div class='success'><strong>✓ Database connected successfully!</strong></div>";
    
    // Check 1: Does student_credentials table exist?
    echo "<hr><h4>1️⃣ Checking student_credentials table...</h4>";
    try {
        $stmt = $conn->query("SHOW TABLES LIKE 'student_credentials'");
        if ($stmt->rowCount() > 0) {
            echo "<div class='success'><strong>✓ student_credentials table EXISTS</strong></div>";
            
            // Check how many credentials
            $stmt = $conn->query("SELECT COUNT(*) as count FROM student_credentials");
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            echo "<div class='info'>Found $count student credentials in database</div>";
        } else {
            echo "<div class='error'><strong>✗ student_credentials table DOES NOT EXIST!</strong><br>";
            echo "👉 <a href='fix_student_credentials.php' class='btn btn-danger btn-sm'>Fix Now</a></div>";
        }
    } catch (Exception $e) {
        echo "<div class='error'>✗ Table doesn't exist: " . $e->getMessage() . "</div>";
    }
    
    // Check 2: Student Role ID
    echo "<hr><h4>2️⃣ Checking Role configuration...</h4>";
    $stmt = $conn->query("SELECT * FROM role WHERE Role_Name = 'Student'");
    $student_role = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($student_role) {
        echo "<div class='success'><strong>✓ Student Role exists</strong><br>";
        echo "Role ID: <code>{$student_role['Role_ID']}</code><br>";
        echo "Role Name: <code>{$student_role['Role_Name']}</code></div>";
        $student_role_id = $student_role['Role_ID'];
    } else {
        echo "<div class='error'><strong>✗ Student Role NOT found in database!</strong></div>";
        $student_role_id = 3; // Default
    }
    
    // Check 3: Students in database
    echo "<hr><h4>3️⃣ Checking Students...</h4>";
    $stmt = $conn->query("SELECT COUNT(*) as count FROM student WHERE Is_Active = 1");
    $student_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "<div class='info'>Total Active Students: <strong>$student_count</strong></div>";
    
    // Check 4: User accounts for students
    echo "<hr><h4>4️⃣ Checking User accounts...</h4>";
    $query = "SELECT COUNT(*) as count FROM user WHERE Role_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$student_role_id]);
    $user_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "<div class='info'>Student User Accounts: <strong>$user_count</strong></div>";
    
    if ($user_count < $student_count) {
        echo "<div class='warning'><strong>⚠️ Some students don't have login accounts!</strong><br>";
        echo "Students: $student_count | User Accounts: $user_count<br>";
        echo "👉 <a href='fix_student_credentials.php' class='btn btn-warning btn-sm'>Generate Missing Accounts</a></div>";
    }
    
    // Check 5: Sample student test
    echo "<hr><h4>5️⃣ Testing Sample Student Login...</h4>";
    
    // Get first student
    $query = "SELECT s.*, u.Username, u.Password, u.Is_Active, sc.Original_Password
              FROM student s
              LEFT JOIN user u ON s.Student_ID = u.Related_ID AND u.Role_ID = ?
              LEFT JOIN student_credentials sc ON s.Student_ID = sc.Student_ID
              WHERE s.Is_Active = 1
              LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->execute([$student_role_id]);
    $test_student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($test_student) {
        echo "<div class='card bg-light'>";
        echo "<div class='card-body'>";
        echo "<h5>Test Student: {$test_student['First_Name']} {$test_student['Last_Name']}</h5>";
        echo "<table class='table table-sm'>";
        echo "<tr><td><strong>Student ID:</strong></td><td>{$test_student['Student_ID']}</td></tr>";
        echo "<tr><td><strong>Student Number:</strong></td><td>{$test_student['Student_Number']}</td></tr>";
        echo "<tr><td><strong>Username:</strong></td><td>" . ($test_student['Username'] ?? '<span class="text-danger">NOT SET</span>') . "</td></tr>";
        echo "<tr><td><strong>Original Password:</strong></td><td>" . ($test_student['Original_Password'] ?? '<span class="text-danger">NOT SET</span>') . "</td></tr>";
        echo "<tr><td><strong>Has User Account:</strong></td><td>" . ($test_student['Password'] ? '✓ YES' : '<span class="text-danger">✗ NO</span>') . "</td></tr>";
        echo "<tr><td><strong>Account Active:</strong></td><td>" . ($test_student['Is_Active'] ? '✓ YES' : '<span class="text-danger">✗ NO</span>') . "</td></tr>";
        echo "</table>";
        
        // Test password if exists
        if ($test_student['Username'] && $test_student['Password'] && $test_student['Original_Password']) {
            echo "<h6>🔐 Password Verification Test:</h6>";
            $test_pass = $test_student['Original_Password'];
            if (password_verify($test_pass, $test_student['Password'])) {
                echo "<div class='success'><strong>✓ Password verification SUCCESSFUL!</strong><br>";
                echo "Login should work with:<br>";
                echo "Username: <code>{$test_student['Username']}</code><br>";
                echo "Password: <code>$test_pass</code></div>";
                
                echo "<a href='simple_login.php' class='btn btn-success btn-sm mt-2'>Test Login Now</a>";
            } else {
                echo "<div class='error'><strong>✗ Password verification FAILED!</strong><br>";
                echo "The password in student_credentials doesn't match the hashed password in user table.<br>";
                echo "👉 <a href='fix_student_credentials.php' class='btn btn-danger btn-sm'>Regenerate Credentials</a></div>";
            }
        } else {
            echo "<div class='error'><strong>✗ Student credentials are incomplete!</strong><br>";
            echo "Missing: ";
            if (!$test_student['Username']) echo "Username ";
            if (!$test_student['Password']) echo "Password ";
            if (!$test_student['Original_Password']) echo "Original_Password ";
            echo "<br>👉 <a href='fix_student_credentials.php' class='btn btn-danger btn-sm'>Fix Now</a></div>";
        }
        
        echo "</div></div>";
    } else {
        echo "<div class='error'><strong>✗ No students found in database!</strong></div>";
    }
    
    // Check 6: All students status
    echo "<hr><h4>6️⃣ All Students Status:</h4>";
    $query = "SELECT s.Student_ID, s.First_Name, s.Last_Name, s.Student_Number,
              u.Username, u.Is_Active as User_Active,
              sc.Original_Password,
              CASE 
                WHEN u.User_ID IS NULL THEN 'No Account'
                WHEN sc.Original_Password IS NULL THEN 'No Password Stored'
                ELSE 'Complete'
              END as Status
              FROM student s
              LEFT JOIN user u ON s.Student_ID = u.Related_ID AND u.Role_ID = ?
              LEFT JOIN student_credentials sc ON s.Student_ID = sc.Student_ID
              WHERE s.Is_Active = 1
              ORDER BY s.First_Name, s.Last_Name
              LIMIT 10";
    $stmt = $conn->prepare($query);
    $stmt->execute([$student_role_id]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<div class='table-responsive'>";
    echo "<table class='table table-sm table-striped'>";
    echo "<thead class='table-dark'>";
    echo "<tr><th>Name</th><th>Student #</th><th>Username</th><th>Password</th><th>Status</th><th>Active</th></tr>";
    echo "</thead><tbody>";
    
    $complete = 0;
    $incomplete = 0;
    
    foreach ($students as $s) {
        $status_class = $s['Status'] == 'Complete' ? 'success' : 'danger';
        $status = $s['Status'];
        
        if ($status == 'Complete') {
            $complete++;
        } else {
            $incomplete++;
        }
        
        echo "<tr>";
        echo "<td>{$s['First_Name']} {$s['Last_Name']}</td>";
        echo "<td>{$s['Student_Number']}</td>";
        echo "<td>" . ($s['Username'] ?? '<span class="text-muted">-</span>') . "</td>";
        echo "<td>" . ($s['Original_Password'] ?? '<span class="text-muted">-</span>') . "</td>";
        echo "<td><span class='badge bg-$status_class'>$status</span></td>";
        echo "<td>" . ($s['User_Active'] ? '✓' : '✗') . "</td>";
        echo "</tr>";
    }
    
    echo "</tbody></table>";
    echo "</div>";
    
    echo "<div class='alert alert-info'>";
    echo "<strong>Summary:</strong> $complete students have complete login info, $incomplete need fixing";
    echo "</div>";
    
    // Final Recommendations
    echo "<hr><h4>🎯 Recommendations:</h4>";
    
    if ($incomplete > 0) {
        echo "<div class='error'>";
        echo "<h5>❌ Action Required!</h5>";
        echo "<p>Some students cannot login. Please run the fix script:</p>";
        echo "<a href='fix_student_credentials.php' class='btn btn-danger btn-lg'>🔧 Fix All Student Credentials</a>";
        echo "</div>";
    } else {
        echo "<div class='success'>";
        echo "<h5>✅ Everything looks good!</h5>";
        echo "<p>All students should be able to login. If you still have issues:</p>";
        echo "<ol>";
        echo "<li>Make sure you're using the exact username and password shown above</li>";
        echo "<li>Check that username/password are case-sensitive</li>";
        echo "<li>Clear browser cache and try again</li>";
        echo "<li>Check browser console (F12) for errors</li>";
        echo "</ol>";
        echo "<a href='simple_login.php' class='btn btn-success btn-lg'>🔐 Go to Login Page</a>";
        echo "</div>";
    }
    
} catch(Exception $e) {
    echo "<div class='error'>";
    echo "<h5>❌ Database Error!</h5>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}

?>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5>📚 Quick Links</h5>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-3">
                    <a href="fix_student_credentials.php" class="btn btn-danger w-100">
                        <i class="fas fa-tools me-2"></i>Fix Credentials
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="simple_login.php" class="btn btn-primary w-100">
                        <i class="fas fa-sign-in-alt me-2"></i>Test Login
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="view_credentials.php" class="btn btn-success w-100">
                        <i class="fas fa-eye me-2"></i>View All
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="admin/student_credentials.php?student_id=1" class="btn btn-info w-100">
                        <i class="fas fa-id-card me-2"></i>Admin View
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

