<?php
// Quick Setup for Magodi Private School Management System
echo "<h2>🚀 Quick Setup - Magodi Private School</h2>";

// Database configuration
$host = "localhost";
$db_name = "magodi_private_school";
$username = "root";
$password = "";

try {
    // Connect to MySQL server
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $conn->exec("CREATE DATABASE IF NOT EXISTS $db_name");
    $conn->exec("USE $db_name");
    
    echo "<p style='color: green;'>✅ Database connection successful</p>";
    
    // Drop existing tables if they exist
    $tables = ['Audit_Log', 'Message', 'Inventory', 'Book_Issue', 'Book', 'Payment', 
               'Fee_Structure', 'Exam_Result', 'Exam', 'Attendance', 'Class_Subject', 
               'Student', 'Non_Teaching_Staff', 'Teacher', 'Subject', 'Class', 
               'User', 'Role', 'Guardian', 'Timetable', 'School_Info', 'Student_Credentials'];
    
    foreach($tables as $table) {
        $conn->exec("DROP TABLE IF EXISTS `$table`");
    }
    echo "<p style='color: green;'>✅ Cleaned existing tables</p>";
    
    // Create tables
    $conn->exec("
        CREATE TABLE Role (
            Role_ID INT PRIMARY KEY AUTO_INCREMENT,
            Role_Name VARCHAR(50) NOT NULL UNIQUE,
            Description TEXT,
            Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    $conn->exec("
        CREATE TABLE User (
            User_ID INT PRIMARY KEY AUTO_INCREMENT,
            Username VARCHAR(50) NOT NULL UNIQUE,
            Password VARCHAR(255) NOT NULL,
            Role_ID INT NOT NULL,
            Related_ID INT,
            Is_Active BOOLEAN DEFAULT TRUE,
            Last_Login TIMESTAMP NULL,
            Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (Role_ID) REFERENCES Role(Role_ID)
        )
    ");
    
    $conn->exec("
        CREATE TABLE Guardian (
            Guardian_ID INT PRIMARY KEY AUTO_INCREMENT,
            Full_Name VARCHAR(100) NOT NULL,
            Relationship VARCHAR(50) NOT NULL,
            Contact_Number VARCHAR(20) NOT NULL,
            Email VARCHAR(100),
            Address TEXT,
            Occupation VARCHAR(100),
            Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    
    $conn->exec("
        CREATE TABLE Class (
            Class_ID INT PRIMARY KEY AUTO_INCREMENT,
            Class_Name VARCHAR(50) NOT NULL,
            Teacher_ID INT,
            Capacity INT DEFAULT 30,
            Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    
    $conn->exec("
        CREATE TABLE Subject (
            Subject_ID INT PRIMARY KEY AUTO_INCREMENT,
            Subject_Name VARCHAR(100) NOT NULL,
            Description TEXT,
            Subject_Code VARCHAR(20) UNIQUE,
            Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    
    $conn->exec("
        CREATE TABLE Teacher (
            Teacher_ID INT PRIMARY KEY AUTO_INCREMENT,
            First_Name VARCHAR(50) NOT NULL,
            Last_Name VARCHAR(50) NOT NULL,
            Gender VARCHAR(10) NOT NULL,
            Date_of_Birth DATE,
            Hire_Date DATE NOT NULL,
            Qualification VARCHAR(100),
            Contact VARCHAR(20) NOT NULL,
            Email VARCHAR(100),
            Salary DECIMAL(10,2),
            Position VARCHAR(50) DEFAULT 'Teacher',
            Address TEXT,
            Is_Active BOOLEAN DEFAULT TRUE,
            Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    
    $conn->exec("
        CREATE TABLE Student (
            Student_ID INT PRIMARY KEY AUTO_INCREMENT,
            First_Name VARCHAR(50) NOT NULL,
            Last_Name VARCHAR(50) NOT NULL,
            Gender VARCHAR(10) NOT NULL,
            Date_of_Birth DATE NOT NULL,
            Address TEXT,
            Admission_Date DATE NOT NULL,
            Class_ID INT,
            Guardian_ID INT,
            Status VARCHAR(20) DEFAULT 'Active',
            Student_Number VARCHAR(20) UNIQUE,
            Photo VARCHAR(255),
            Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (Class_ID) REFERENCES Class(Class_ID),
            FOREIGN KEY (Guardian_ID) REFERENCES Guardian(Guardian_ID)
        )
    ");
    
    $conn->exec("
        CREATE TABLE School_Info (
            School_ID INT PRIMARY KEY AUTO_INCREMENT,
            School_Name VARCHAR(200) NOT NULL,
            Address TEXT NOT NULL,
            Phone VARCHAR(20),
            Email VARCHAR(100),
            Website VARCHAR(100),
            Principal_Name VARCHAR(100),
            Established_Year INT,
            Motto TEXT,
            Logo VARCHAR(255),
            Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    
    $conn->exec("
        CREATE TABLE Student_Credentials (
            Credential_ID INT PRIMARY KEY AUTO_INCREMENT,
            Student_ID INT NOT NULL,
            Username VARCHAR(50) NOT NULL,
            Original_Password VARCHAR(100) NOT NULL,
            Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (Student_ID) REFERENCES Student(Student_ID) ON DELETE CASCADE
        )
    ");
    
    echo "<p style='color: green;'>✅ Core tables created</p>";
    
    // Insert default data
    $conn->exec("
        INSERT INTO Role (Role_Name, Description) VALUES 
        ('Admin', 'System Administrator with full access'),
        ('Teacher', 'Teaching staff with limited access'),
        ('Student', 'Student with basic access'),
        ('Parent', 'Parent/Guardian access'),
        ('Accountant', 'Financial management access'),
        ('Librarian', 'Library management access'),
        ('Staff', 'Non-teaching staff access')
    ");
    
    $conn->exec("
        INSERT INTO School_Info (School_Name, Address, Phone, Email, Website, Principal_Name, Established_Year, Motto) VALUES 
        ('Magodi Private School', 'Area 23, Lilongwe, Malawi', '+265 123 456 789', 'info@magodischool.mw', 'https://www.facebook.com/share/1CsgegFzDj/', 'Dr. John Mwale', 2010, 'Excellence Through Education')
    ");
    
    $conn->exec("
        INSERT INTO Subject (Subject_Name, Description, Subject_Code) VALUES 
        ('English', 'Core English Language and Literature for MSCE', 'ENG'),
        ('Mathematics', 'Core Mathematics for MSCE', 'MATH'),
        ('Chichewa', 'Core Chichewa Language for MSCE', 'CHI'),
        ('Biology', 'Core Biology for MSCE', 'BIO'),
        ('Chemistry', 'Core Chemistry for MSCE', 'CHEM'),
        ('Physics', 'Core Physics for MSCE', 'PHY'),
        ('Agriculture', 'Agricultural Science - MSCE Elective', 'AGR'),
        ('Geography', 'Geography - MSCE Elective', 'GEO'),
        ('History', 'History - MSCE Elective', 'HIST'),
        ('Bible Knowledge', 'Bible Knowledge - MSCE Elective', 'BIBLE'),
        ('Business Studies', 'Business Studies - MSCE Elective', 'BUS'),
        ('Computer Studies', 'Computer Science and ICT', 'CS'),
        ('Physical Education', 'Physical Education and Sports', 'PE'),
        ('Life Skills', 'Life Skills and Development', 'LS'),
        ('Social Studies', 'Social Studies for Junior Forms', 'SS')
    ");
    
    $conn->exec("
        INSERT INTO Class (Class_Name, Capacity) VALUES 
        ('Form 1A', 30), ('Form 1B', 30), ('Form 2A', 30), ('Form 2B', 30),
        ('Form 3A', 30), ('Form 3B', 30), ('Form 4A', 30), ('Form 4B', 30)
    ");
    
    $conn->exec("
        INSERT INTO Teacher (First_Name, Last_Name, Gender, Hire_Date, Qualification, Contact, Email, Salary, Position) VALUES 
        ('John', 'Mwale', 'Male', '2020-01-15', 'B.Ed Mathematics', '0881234567', 'john.mwale@magodischool.mw', 150000, 'Head Teacher'),
        ('Mary', 'Chisale', 'Female', '2020-01-15', 'B.Ed English', '0881234568', 'mary.chisale@magodischool.mw', 120000, 'Teacher'),
        ('Peter', 'Banda', 'Male', '2020-01-15', 'B.Sc Physics', '0881234569', 'peter.banda@magodischool.mw', 130000, 'Teacher')
    ");
    
    $conn->exec("
        INSERT INTO Guardian (Full_Name, Relationship, Contact_Number, Email, Address, Occupation) VALUES 
        ('James Mwale', 'Father', '0881111111', 'james.mwale@email.com', 'Area 23, Lilongwe', 'Businessman'),
        ('Sarah Chisale', 'Mother', '0881111112', 'sarah.chisale@email.com', 'Area 24, Lilongwe', 'Teacher'),
        ('Michael Banda', 'Father', '0881111113', 'michael.banda@email.com', 'Area 25, Lilongwe', 'Engineer')
    ");
    
    // Create admin user
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO User (Username, Password, Role_ID, Related_ID) VALUES ('admin', ?, 1, NULL)");
    $stmt->execute([$admin_password]);
    
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
    
    echo "<p style='color: green;'>✅ Sample data inserted</p>";
    echo "<p style='color: green;'>✅ All users created</p>";
    
    // Mark setup as completed
    file_put_contents('setup_completed.txt', date('Y-m-d H:i:s'));
    
    echo "<div style='background: #d4edda; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    echo "<h3 style='color: #155724;'>🎉 Setup Complete!</h3>";
    echo "<p><strong>Database:</strong> $db_name</p>";
    echo "<p><strong>Setup Date:</strong> " . date('Y-m-d H:i:s') . "</p>";
    echo "</div>";
    
    echo "<div style='background: #cce5ff; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    echo "<h3 style='color: #004085;'>🔑 Login Credentials Created:</h3>";
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
    
    echo "<div style='background: #cce5ff; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    echo "<h3 style='color: #004085;'>🚀 Next Steps:</h3>";
    echo "<ol>";
    echo "<li><a href='login.php' style='color: #007bff; font-weight: bold;'>Go to Login Page</a></li>";
    echo "<li>Login with: <strong>admin</strong> / <strong>admin123</strong></li>";
    echo "<li>Start using the School Management System!</li>";
    echo "</ol>";
    echo "</div>";
    
} catch(Exception $e) {
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    echo "<h3 style='color: #721c24;'>❌ Setup Failed!</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Please check your database configuration and try again.</p>";
    echo "</div>";
}
?>
