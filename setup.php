<?php
require_once 'config/database.php';

// Check if setup is already completed
if(file_exists('setup_completed.txt')) {
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3 style='color: #856404;'>⚠️ Setup Already Completed</h3>";
    echo "<p>Setup was completed on: " . file_get_contents('setup_completed.txt') . "</p>";
    echo "<p>If you want to reset the database, <a href='reset_database.php'>click here</a></p>";
    echo "<p>Or <a href='login.php'>go to login page</a> to access the system</p>";
    echo "</div>";
    exit;
}

$database = new Database();
$conn = $database->getConnection();

$success = true;
$errors = [];

try {
    // Read and execute the schema file
    $schema = file_get_contents('database/schema.sql');
    $statements = explode(';', $schema);
    
    foreach($statements as $statement) {
        $statement = trim($statement);
        if(!empty($statement)) {
            $conn->exec($statement);
        }
    }
    
    // Create default admin user
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $query = "INSERT INTO User (Username, Password, Role_ID, Related_ID) VALUES ('admin', ?, 1, NULL)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$admin_password]);
    
    // Create sample data
    createSampleData($conn);
    
    // Mark setup as completed
    file_put_contents('setup_completed.txt', date('Y-m-d H:i:s'));
    
    $success = true;
    $message = "Setup completed successfully! Default admin credentials: username: admin, password: admin123";
    
} catch(Exception $e) {
    $success = false;
    $errors[] = $e->getMessage();
}

function createSampleData($conn) {
    // Create sample classes
    $classes = [
        ['Form 1A', 1, 30],
        ['Form 1B', 2, 30],
        ['Form 2A', 3, 30],
        ['Form 2B', 4, 30],
        ['Form 3A', 5, 30],
        ['Form 3B', 6, 30],
        ['Form 4A', 7, 30],
        ['Form 4B', 8, 30]
    ];
    
    foreach($classes as $class) {
        $query = "INSERT INTO Class (Class_Name, Teacher_ID, Capacity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute($class);
    }
    
    // Create MSCE subjects (Malawi National Examinations Board)
    $subjects = [
        // Core Subjects
        ['English', 'Core English Language and Literature for MSCE', 'ENG'],
        ['Mathematics', 'Core Mathematics for MSCE', 'MATH'],
        ['Chichewa', 'Core Chichewa Language for MSCE', 'CHI'],
        ['Biology', 'Core Biology for MSCE', 'BIO'],
        ['Chemistry', 'Core Chemistry for MSCE', 'CHEM'],
        ['Physics', 'Core Physics for MSCE', 'PHY'],
        // Elective Subjects
        ['Agriculture', 'Agricultural Science - MSCE Elective', 'AGR'],
        ['Geography', 'Geography - MSCE Elective', 'GEO'],
        ['History', 'History - MSCE Elective', 'HIST'],
        ['Bible Knowledge', 'Bible Knowledge - MSCE Elective', 'BIBLE'],
        ['Business Studies', 'Business Studies - MSCE Elective', 'BUS'],
        // Additional subjects
        ['Computer Studies', 'Computer Science and ICT', 'CS'],
        ['Physical Education', 'Physical Education and Sports', 'PE'],
        ['Life Skills', 'Life Skills and Development', 'LS'],
        ['Social Studies', 'Social Studies for Junior Forms', 'SS']
    ];
    
    foreach($subjects as $subject) {
        $query = "INSERT INTO Subject (Subject_Name, Description, Subject_Code) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute($subject);
    }
    
    // Create sample teachers
    $teachers = [
        ['John', 'Mwale', 'Male', '1985-03-15', '2020-01-15', 'B.Ed Mathematics', '0881234567', 'john.mwale@magodischool.mw', 150000, 'Head Teacher'],
        ['Mary', 'Chisale', 'Female', '1988-07-22', '2020-01-15', 'B.Ed English', '0881234568', 'mary.chisale@magodischool.mw', 120000, 'Teacher'],
        ['Peter', 'Banda', 'Male', '1982-11-10', '2020-01-15', 'B.Sc Physics', '0881234569', 'peter.banda@magodischool.mw', 130000, 'Teacher'],
        ['Grace', 'Mkandawire', 'Female', '1990-05-18', '2020-01-15', 'B.Sc Chemistry', '0881234570', 'grace.mkandawire@magodischool.mw', 125000, 'Teacher']
    ];
    
    foreach($teachers as $teacher) {
        $query = "INSERT INTO Teacher (First_Name, Last_Name, Gender, Date_of_Birth, Hire_Date, Qualification, Contact, Email, Salary, Position) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute($teacher);
    }
    
    // Create sample guardians
    $guardians = [
        ['James Mwale', 'Father', '0881111111', 'james.mwale@email.com', 'Area 23, Lilongwe', 'Businessman'],
        ['Sarah Chisale', 'Mother', '0881111112', 'sarah.chisale@email.com', 'Area 24, Lilongwe', 'Teacher'],
        ['Michael Banda', 'Father', '0881111113', 'michael.banda@email.com', 'Area 25, Lilongwe', 'Engineer'],
        ['Ruth Mkandawire', 'Mother', '0881111114', 'ruth.mkandawire@email.com', 'Area 26, Lilongwe', 'Nurse']
    ];
    
    foreach($guardians as $guardian) {
        $query = "INSERT INTO Guardian (Full_Name, Relationship, Contact_Number, Email, Address, Occupation) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute($guardian);
    }
    
    // Create sample students
    $students = [
        ['Alice', 'Mwale', 'Female', '2008-05-15', 'Area 23, Lilongwe', '2023-01-15', 1, 1, 'Active'],
        ['Bob', 'Chisale', 'Male', '2008-08-20', 'Area 24, Lilongwe', '2023-01-15', 1, 2, 'Active'],
        ['Carol', 'Banda', 'Female', '2007-12-10', 'Area 25, Lilongwe', '2023-01-15', 3, 3, 'Active'],
        ['David', 'Mkandawire', 'Male', '2007-03-25', 'Area 26, Lilongwe', '2023-01-15', 3, 4, 'Active']
    ];
    
    foreach($students as $student) {
        $student_number = 'STU' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $query = "INSERT INTO Student (First_Name, Last_Name, Gender, Date_of_Birth, Address, Admission_Date, Class_ID, Guardian_ID, Status, Student_Number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute(array_merge($student, [$student_number]));
    }
    
    // Create class-subject relationships
    $class_subjects = [
        [1, 1, 1], [1, 2, 2], [1, 3, 3], [1, 4, 4],
        [2, 1, 1], [2, 2, 2], [2, 5, 3], [2, 6, 4],
        [3, 1, 1], [3, 2, 2], [3, 3, 3], [3, 7, 4],
        [4, 1, 1], [4, 2, 2], [4, 4, 3], [4, 8, 4]
    ];
    
    foreach($class_subjects as $cs) {
        $query = "INSERT INTO Class_Subject (Class_ID, Subject_ID, Teacher_ID) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute($cs);
    }
    
    // Create sample fee structures
    $fee_structures = [
        [1, 'Term 1', 50000, 2024, 'Tuition'],
        [2, 'Term 1', 50000, 2024, 'Tuition'],
        [3, 'Term 1', 60000, 2024, 'Tuition'],
        [4, 'Term 1', 60000, 2024, 'Tuition']
    ];
    
    foreach($fee_structures as $fee) {
        $query = "INSERT INTO Fee_Structure (Class_ID, Term, Amount, Year, Fee_Type) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute($fee);
    }
    
    // Create sample books
    $books = [
        ['Mathematics for Form 1', 'Dr. John Smith', '978-1234567890', 'Mathematics', 10, 10],
        ['English Literature', 'Jane Doe', '978-1234567891', 'Literature', 8, 8],
        ['Physics Fundamentals', 'Prof. Physics', '978-1234567892', 'Science', 5, 5],
        ['Chemistry Basics', 'Dr. Chem', '978-1234567893', 'Science', 6, 6]
    ];
    
    foreach($books as $book) {
        $query = "INSERT INTO Book (Title, Author, ISBN, Category, Copies_Available, Total_Copies) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute($book);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup - Magodi Private School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .setup-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .setup-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .setup-body {
            padding: 2rem;
        }
        .school-logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="setup-card">
                    <div class="setup-header">
                        <div class="school-logo">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3>Magodi Private School</h3>
                        <p class="mb-0">School Management System Setup</p>
                    </div>
                    <div class="setup-body">
                        <?php if($success): ?>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?php echo $message; ?>
                            </div>
                            
                            <div class="alert alert-info" role="alert">
                                <h6><i class="fas fa-info-circle me-2"></i>What's Next?</h6>
                                <ul class="mb-0">
                                    <li>Database has been created with sample data</li>
                                    <li>Default admin account created</li>
                                    <li>Sample classes, subjects, teachers, and students added</li>
                                    <li>You can now access the system</li>
                                </ul>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="login.php" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login to System
                                </a>
                                <a href="index.php" class="btn btn-outline-primary">
                                    <i class="fas fa-home me-2"></i>Go to Homepage
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Setup failed with the following errors:
                                <ul class="mt-2 mb-0">
                                    <?php foreach($errors as $error): ?>
                                        <li><?php echo $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            
                            <div class="alert alert-warning" role="alert">
                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Troubleshooting</h6>
                                <ul class="mb-0">
                                    <li>Make sure your database server is running</li>
                                    <li>Check your database credentials in config/database.php</li>
                                    <li>Ensure the database user has CREATE privileges</li>
                                    <li>Delete setup_completed.txt and try again</li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
