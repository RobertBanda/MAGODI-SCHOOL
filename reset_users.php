<?php
// Reset and Recreate All Users - Magodi Private School Management System
echo "<h2>🔄 Reset Users - Magodi Private School</h2>";

// Database configuration
$host = "localhost";
$db_name = "magodi_private_school";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✅ Database connection successful</p>";
    
    // Clear existing users (except admin)
    $conn->exec("DELETE FROM User WHERE Username != 'admin'");
    echo "<p style='color: green;'>✅ Cleared existing users</p>";
    
    // Create sample teachers
    $teachers = [
        ['John', 'Mwale', 'Male', '1985-05-15', '2020-01-15', 'B.Ed Mathematics', '0881234567', 'john.mwale@magodischool.mw', 150000, 'Head Teacher'],
        ['Mary', 'Chisale', 'Female', '1988-03-22', '2020-01-15', 'B.Ed English', '0881234568', 'mary.chisale@magodischool.mw', 120000, 'Teacher'],
        ['Peter', 'Banda', 'Male', '1987-08-10', '2020-01-15', 'B.Sc Physics', '0881234569', 'peter.banda@magodischool.mw', 130000, 'Teacher']
    ];
    
    $teacher_ids = [];
    foreach($teachers as $teacher) {
        $query = "INSERT INTO Teacher (First_Name, Last_Name, Gender, Date_of_Birth, Hire_Date, Qualification, Contact, Email, Salary, Position) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute($teacher);
        $teacher_ids[] = $conn->lastInsertId();
    }
    echo "<p style='color: green;'>✅ Teachers created</p>";
    
    // Create sample guardians
    $guardians = [
        ['James Mwale', 'Father', '0881111111', 'james.mwale@email.com', 'Area 23, Lilongwe', 'Businessman'],
        ['Sarah Chisale', 'Mother', '0881111112', 'sarah.chisale@email.com', 'Area 24, Lilongwe', 'Teacher'],
        ['Michael Banda', 'Father', '0881111113', 'michael.banda@email.com', 'Area 25, Lilongwe', 'Engineer']
    ];
    
    $guardian_ids = [];
    foreach($guardians as $guardian) {
        $query = "INSERT INTO Guardian (Full_Name, Relationship, Contact_Number, Email, Address, Occupation) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute($guardian);
        $guardian_ids[] = $conn->lastInsertId();
    }
    echo "<p style='color: green;'>✅ Guardians created</p>";
    
    // Create sample students
    $students = [
        ['Chisomo', 'Mwale', 'Male', '2008-03-15', 'Area 23, Lilongwe', '2023-01-15', 1, $guardian_ids[0], 'ST001', 'Active'],
        ['Tiyamike', 'Chisale', 'Female', '2008-07-22', 'Area 24, Lilongwe', '2023-01-15', 1, $guardian_ids[1], 'ST002', 'Active'],
        ['Kondwani', 'Banda', 'Male', '2007-11-10', 'Area 25, Lilongwe', '2023-01-15', 2, $guardian_ids[2], 'ST003', 'Active']
    ];
    
    $student_ids = [];
    foreach($students as $student) {
        $query = "INSERT INTO Student (First_Name, Last_Name, Gender, Date_of_Birth, Address, Admission_Date, Class_ID, Guardian_ID, Student_Number, Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute($student);
        $student_ids[] = $conn->lastInsertId();
    }
    echo "<p style='color: green;'>✅ Students created</p>";
    
    // Create user accounts for teachers
    $teacher_users = [
        ['john.mwale', 'teacher123', 2, $teacher_ids[0]],
        ['mary.chisale', 'teacher123', 2, $teacher_ids[1]],
        ['peter.banda', 'teacher123', 2, $teacher_ids[2]]
    ];
    
    foreach($teacher_users as $user) {
        $password_hash = password_hash($user[1], PASSWORD_DEFAULT);
        $query = "INSERT INTO User (Username, Password, Role_ID, Related_ID) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$user[0], $password_hash, $user[2], $user[3]]);
    }
    echo "<p style='color: green;'>✅ Teacher accounts created</p>";
    
    // Create user accounts for students
    $student_users = [
        ['chisomo.mwale', 'student123', 3, $student_ids[0]],
        ['tiyamike.chisale', 'student123', 3, $student_ids[1]],
        ['kondwani.banda', 'student123', 3, $student_ids[2]]
    ];
    
    foreach($student_users as $user) {
        $password_hash = password_hash($user[1], PASSWORD_DEFAULT);
        $query = "INSERT INTO User (Username, Password, Role_ID, Related_ID) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$user[0], $password_hash, $user[2], $user[3]]);
    }
    echo "<p style='color: green;'>✅ Student accounts created</p>";
    
    // Create user accounts for guardians
    $guardian_users = [
        ['james.mwale', 'parent123', 4, $guardian_ids[0]],
        ['sarah.chisale', 'parent123', 4, $guardian_ids[1]],
        ['michael.banda', 'parent123', 4, $guardian_ids[2]]
    ];
    
    foreach($guardian_users as $user) {
        $password_hash = password_hash($user[1], PASSWORD_DEFAULT);
        $query = "INSERT INTO User (Username, Password, Role_ID, Related_ID) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$user[0], $password_hash, $user[2], $user[3]]);
    }
    echo "<p style='color: green;'>✅ Guardian accounts created</p>";
    
    // Create staff accounts
    $staff_users = [
        ['accountant', 'staff123', 5, NULL],
        ['librarian', 'staff123', 6, NULL],
        ['staff', 'staff123', 7, NULL]
    ];
    
    foreach($staff_users as $user) {
        $password_hash = password_hash($user[1], PASSWORD_DEFAULT);
        $query = "INSERT INTO User (Username, Password, Role_ID, Related_ID) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$user[0], $password_hash, $user[2], $user[3]]);
    }
    echo "<p style='color: green;'>✅ Staff accounts created</p>";
    
    echo "<div style='background: #d4edda; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    echo "<h3 style='color: #155724;'>🎉 Users Reset Successfully!</h3>";
    echo "<p><strong>Total Users:</strong> 10 accounts created</p>";
    echo "</div>";
    
    echo "<div style='background: #cce5ff; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    echo "<h3 style='color: #004085;'>🔑 Working Login Credentials:</h3>";
    echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;'>";
    
    // Admin
    echo "<div style='background: white; padding: 15px; border-radius: 8px; border-left: 4px solid #dc3545;'>";
    echo "<h4 style='color: #dc3545; margin: 0 0 10px 0;'>👑 Admin</h4>";
    echo "<p><strong>admin</strong> / admin123</p>";
    echo "</div>";
    
    // Teachers
    echo "<div style='background: white; padding: 15px; border-radius: 8px; border-left: 4px solid #28a745;'>";
    echo "<h4 style='color: #28a745; margin: 0 0 10px 0;'>👨‍🏫 Teachers</h4>";
    echo "<p><strong>john.mwale</strong> / teacher123<br>";
    echo "<strong>mary.chisale</strong> / teacher123<br>";
    echo "<strong>peter.banda</strong> / teacher123</p>";
    echo "</div>";
    
    // Students
    echo "<div style='background: white; padding: 15px; border-radius: 8px; border-left: 4px solid #007bff;'>";
    echo "<h4 style='color: #007bff; margin: 0 0 10px 0;'>🎓 Students</h4>";
    echo "<p><strong>chisomo.mwale</strong> / student123<br>";
    echo "<strong>tiyamike.chisale</strong> / student123<br>";
    echo "<strong>kondwani.banda</strong> / student123</p>";
    echo "</div>";
    
    // Parents
    echo "<div style='background: white; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107;'>";
    echo "<h4 style='color: #ffc107; margin: 0 0 10px 0;'>👨‍👩‍👧‍👦 Parents</h4>";
    echo "<p><strong>james.mwale</strong> / parent123<br>";
    echo "<strong>sarah.chisale</strong> / parent123<br>";
    echo "<strong>michael.banda</strong> / parent123</p>";
    echo "</div>";
    
    // Staff
    echo "<div style='background: white; padding: 15px; border-radius: 8px; border-left: 4px solid #6f42c1;'>";
    echo "<h4 style='color: #6f42c1; margin: 0 0 10px 0;'>👨‍💼 Staff</h4>";
    echo "<p><strong>accountant</strong> / staff123<br>";
    echo "<strong>librarian</strong> / staff123<br>";
    echo "<strong>staff</strong> / staff123</p>";
    echo "</div>";
    
    echo "</div>";
    echo "</div>";
    
    echo "<div style='text-center; margin: 20px 0;'>";
    echo "<a href='simple_login.php' class='btn btn-primary btn-lg'>";
    echo "<i class='fas fa-sign-in-alt me-2'></i>Test Login Now";
    echo "</a>";
    echo "</div>";
    
} catch(Exception $e) {
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    echo "<h3 style='color: #721c24;'>❌ Error Creating Users!</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Please ensure your database is set up correctly.</p>";
    echo "</div>";
}
?>
