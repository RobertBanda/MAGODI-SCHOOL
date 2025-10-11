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

$teachers = [
    ['john.mwale', 'teacher123', 'John Mwale', 'Head Teacher'],
    ['mary.chisale', 'teacher123', 'Mary Chisale', 'Teacher'],
    ['peter.banda', 'teacher123', 'Peter Banda', 'Teacher'],
    ['grace.mkandawire', 'teacher123', 'Grace Mkandawire', 'Teacher'],
    ['james.phiri', 'teacher123', 'James Phiri', 'Teacher']
];

foreach($teachers as $teacher) {
    echo "<div class='credential-item'>";
    echo "<div class='row align-items-center'>";
    echo "<div class='col-md-3'><span class='username'>" . $teacher[0] . "</span></div>";
    echo "<div class='col-md-3'><span class='password'>" . $teacher[1] . "</span></div>";
    echo "<div class='col-md-3'><span class='role-badge bg-success text-white'>Teacher</span></div>";
    echo "<div class='col-md-3'><small class='text-muted'>" . $teacher[2] . " - " . $teacher[3] . "</small></div>";
    echo "</div>";
    echo "</div>";
}
echo "</div>";
echo "</div>";

// Student Credentials
echo "<div class='credentials-card'>";
echo "<div class='role-header'>";
echo "<h4><i class='fas fa-user-graduate me-2'></i>Student Accounts</h4>";
echo "</div>";
echo "<div class='p-4'>";

$students = [
    ['chisomo.mwale', 'student123', 'Chisomo Mwale', 'ST001', 'Form 1A'],
    ['tiyamike.chisale', 'student123', 'Tiyamike Chisale', 'ST002', 'Form 1A'],
    ['kondwani.banda', 'student123', 'Kondwani Banda', 'ST003', 'Form 1B'],
    ['thandiwe.phiri', 'student123', 'Thandiwe Phiri', 'ST004', 'Form 1B'],
    ['blessings.mkandawire', 'student123', 'Blessings Mkandawire', 'ST005', 'Form 2A']
];

foreach($students as $student) {
    echo "<div class='credential-item'>";
    echo "<div class='row align-items-center'>";
    echo "<div class='col-md-3'><span class='username'>" . $student[0] . "</span></div>";
    echo "<div class='col-md-3'><span class='password'>" . $student[1] . "</span></div>";
    echo "<div class='col-md-3'><span class='role-badge bg-primary text-white'>Student</span></div>";
    echo "<div class='col-md-3'><small class='text-muted'>" . $student[2] . " (" . $student[3] . ") - " . $student[4] . "</small></div>";
    echo "</div>";
    echo "</div>";
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
