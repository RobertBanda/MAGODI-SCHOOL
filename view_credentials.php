<?php
// View All Login Credentials - Magodi Private School Management System
echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Login Credentials - Magodi Private School</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' rel='stylesheet'>";
echo "<style>";
echo "body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }";
echo ".credentials-card { background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin: 20px 0; }";
echo ".role-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; border-radius: 10px 10px 0 0; }";
echo ".credential-item { padding: 10px; border-bottom: 1px solid #eee; }";
echo ".credential-item:last-child { border-bottom: none; }";
echo ".username { font-weight: bold; color: #007bff; }";
echo ".password { color: #28a745; font-family: monospace; }";
echo ".role-badge { font-size: 0.8rem; padding: 4px 8px; border-radius: 12px; }";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<div class='container py-5'>";
echo "<div class='row justify-content-center'>";
echo "<div class='col-lg-10'>";

echo "<div class='text-center mb-5'>";
echo "<h1 class='text-white mb-3'><i class='fas fa-key me-2'></i>Login Credentials</h1>";
echo "<h3 class='text-white-50'>Magodi Private School Management System</h3>";
echo "<p class='text-white-50'>Complete list of all user accounts and passwords</p>";
echo "</div>";

// Admin Credentials
echo "<div class='credentials-card'>";
echo "<div class='role-header'>";
echo "<h4><i class='fas fa-crown me-2'></i>Administrator Access</h4>";
echo "</div>";
echo "<div class='p-4'>";
echo "<div class='credential-item'>";
echo "<div class='row align-items-center'>";
echo "<div class='col-md-3'><span class='username'>admin</span></div>";
echo "<div class='col-md-3'><span class='password'>admin123</span></div>";
echo "<div class='col-md-3'><span class='role-badge bg-danger text-white'>Administrator</span></div>";
echo "<div class='col-md-3'><small class='text-muted'>Full system access</small></div>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";

// Teacher Credentials
echo "<div class='credentials-card'>";
echo "<div class='role-header'>";
echo "<h4><i class='fas fa-chalkboard-teacher me-2'></i>Teacher Accounts</h4>";
echo "</div>";
echo "<div class='p-4'>";

// Fetch actual teacher credentials from database
require_once 'config/database.php';
try {
    $db = new Database();
    $conn = $db->getConnection();
    
    $query = "SELECT t.*, tc.Username, tc.Original_Password 
              FROM teacher t 
              LEFT JOIN teacher_credentials tc ON t.Teacher_ID = tc.Teacher_ID 
              WHERE t.Is_Active = 1 
              ORDER BY t.First_Name, t.Last_Name";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if(empty($teachers)) {
        echo "<div class='text-center text-muted py-3'>No teacher credentials found.</div>";
    } else {
        foreach($teachers as $teacher) {
            echo "<div class='credential-item'>";
            echo "<div class='row align-items-center'>";
            echo "<div class='col-md-3'><span class='username'>" . htmlspecialchars($teacher['Username'] ?? 'N/A') . "</span></div>";
            echo "<div class='col-md-3'><span class='password'>" . htmlspecialchars($teacher['Original_Password'] ?? 'N/A') . "</span></div>";
            echo "<div class='col-md-3'><span class='role-badge bg-success text-white'>Teacher</span></div>";
            echo "<div class='col-md-3'><small class='text-muted'>" . htmlspecialchars($teacher['First_Name'] . ' ' . $teacher['Last_Name']) . " - " . htmlspecialchars($teacher['Position']) . "</small></div>";
            echo "</div>";
            echo "</div>";
        }
    }
} catch(Exception $e) {
    echo "<div class='text-center text-danger py-3'>Error loading teacher credentials: " . htmlspecialchars($e->getMessage()) . "</div>";
}
echo "</div>";
echo "</div>";

// Student Credentials
echo "<div class='credentials-card'>";
echo "<div class='role-header'>";
echo "<h4><i class='fas fa-user-graduate me-2'></i>Student Accounts</h4>";
echo "</div>";
echo "<div class='p-4'>";

// Fetch actual student credentials from database
try {
    $query = "SELECT s.*, u.Username, c.Class_Name, sc.Original_Password
              FROM student s 
              LEFT JOIN user u ON s.Student_ID = u.Related_ID 
              LEFT JOIN class c ON s.Class_ID = c.Class_ID 
              LEFT JOIN student_credentials sc ON s.Student_ID = sc.Student_ID
              WHERE s.Is_Active = 1 
              ORDER BY s.First_Name, s.Last_Name";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if(empty($students)) {
        echo "<div class='text-center text-muted py-3'>No student credentials found.</div>";
    } else {
        foreach($students as $student) {
            $password = $student['Original_Password'] ?? 'N/A - Run fix_student_credentials.php';
            echo "<div class='credential-item'>";
            echo "<div class='row align-items-center'>";
            echo "<div class='col-md-3'><span class='username'>" . htmlspecialchars($student['Username'] ?? 'N/A') . "</span></div>";
            echo "<div class='col-md-3'><span class='password'>" . htmlspecialchars($password) . "</span></div>";
            echo "<div class='col-md-3'><span class='role-badge bg-primary text-white'>Student</span></div>";
            echo "<div class='col-md-3'><small class='text-muted'>" . htmlspecialchars($student['First_Name'] . ' ' . $student['Last_Name']) . " (" . htmlspecialchars($student['Student_Number']) . ") - " . htmlspecialchars($student['Class_Name'] ?? 'No Class') . "</small></div>";
            echo "</div>";
            echo "</div>";
        }
    }
} catch(Exception $e) {
    echo "<div class='text-center text-danger py-3'>Error loading student credentials: " . htmlspecialchars($e->getMessage()) . "</div>";
}
echo "</div>";
echo "</div>";

// Parent/Guardian Credentials
echo "<div class='credentials-card'>";
echo "<div class='role-header'>";
echo "<h4><i class='fas fa-users me-2'></i>Parent/Guardian Accounts</h4>";
echo "</div>";
echo "<div class='p-4'>";

$guardians = [
    ['james.mwale', 'parent123', 'James Mwale', 'Father'],
    ['sarah.chisale', 'parent123', 'Sarah Chisale', 'Mother'],
    ['michael.banda', 'parent123', 'Michael Banda', 'Father'],
    ['patricia.phiri', 'parent123', 'Patricia Phiri', 'Mother'],
    ['david.mkandawire', 'parent123', 'David Mkandawire', 'Father']
];

foreach($guardians as $guardian) {
    echo "<div class='credential-item'>";
    echo "<div class='row align-items-center'>";
    echo "<div class='col-md-3'><span class='username'>" . $guardian[0] . "</span></div>";
    echo "<div class='col-md-3'><span class='password'>" . $guardian[1] . "</span></div>";
    echo "<div class='col-md-3'><span class='role-badge bg-warning text-dark'>Parent</span></div>";
    echo "<div class='col-md-3'><small class='text-muted'>" . $guardian[2] . " - " . $guardian[3] . "</small></div>";
    echo "</div>";
    echo "</div>";
}
echo "</div>";
echo "</div>";

// Staff Credentials
echo "<div class='credentials-card'>";
echo "<div class='role-header'>";
echo "<h4><i class='fas fa-user-tie me-2'></i>Staff Accounts</h4>";
echo "</div>";
echo "<div class='p-4'>";

$staff = [
    ['accountant', 'staff123', 'Accountant', 'Financial Management'],
    ['librarian', 'staff123', 'Librarian', 'Library Management'],
    ['staff', 'staff123', 'General Staff', 'Basic Access']
];

foreach($staff as $staff_member) {
    echo "<div class='credential-item'>";
    echo "<div class='row align-items-center'>";
    echo "<div class='col-md-3'><span class='username'>" . $staff_member[0] . "</span></div>";
    echo "<div class='col-md-3'><span class='password'>" . $staff_member[1] . "</span></div>";
    echo "<div class='col-md-3'><span class='role-badge bg-info text-white'>Staff</span></div>";
    echo "<div class='col-md-3'><small class='text-muted'>" . $staff_member[2] . " - " . $staff_member[3] . "</small></div>";
    echo "</div>";
    echo "</div>";
}
echo "</div>";
echo "</div>";

// Quick Access Buttons
echo "<div class='text-center mt-5'>";
echo "<div class='row g-3'>";
echo "<div class='col-md-4'>";
echo "<a href='simple_login.php' class='btn btn-light btn-lg w-100'>";
echo "<i class='fas fa-sign-in-alt me-2'></i>Go to Login";
echo "</a>";
echo "</div>";
echo "<div class='col-md-4'>";
echo "<a href='create_sample_users.php' class='btn btn-outline-light btn-lg w-100'>";
echo "<i class='fas fa-users me-2'></i>Create Users";
echo "</a>";
echo "</div>";
echo "<div class='col-md-4'>";
echo "<a href='quick_setup.php' class='btn btn-outline-light btn-lg w-100'>";
echo "<i class='fas fa-database me-2'></i>Setup Database";
echo "</a>";
echo "</div>";
echo "</div>";
echo "</div>";

// Security Notice
echo "<div class='alert alert-warning mt-4'>";
echo "<h5><i class='fas fa-exclamation-triangle me-2'></i>Security Notice</h5>";
echo "<ul class='mb-0'>";
echo "<li>All passwords are case-sensitive</li>";
echo "<li>Users should change passwords after first login</li>";
echo "<li>Admin can reset any user's password</li>";
echo "<li>Each role has different access permissions</li>";
echo "</ul>";
echo "</div>";

echo "</div>";
echo "</div>";
echo "</div>";

echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>";
echo "</body>";
echo "</html>";
?>
