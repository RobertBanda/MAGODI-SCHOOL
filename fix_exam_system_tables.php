<?php
/**
 * Fix and Create Exam System Tables
 * This version removes foreign key constraints that cause issues
 */

require_once 'config/database.php';

echo "<!DOCTYPE html>";
echo "<html><head>";
echo "<title>Fix Exam System Tables</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "</head><body class='bg-light'>";
echo "<div class='container py-5'>";
echo "<h1 class='mb-4'>🔧 Fix & Create Exam System Tables</h1>";

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Step 1: Check and fix exam table engine
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-info text-white'><h5>Step 1: Checking exam table</h5></div>";
    echo "<div class='card-body'>";
    
    $stmt = $conn->query("SHOW TABLE STATUS LIKE 'exam'");
    $examTable = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($examTable) {
        echo "<p class='text-success'>✓ exam table exists</p>";
        echo "<p>Engine: <strong>{$examTable['Engine']}</strong></p>";
        echo "<p>Charset: <strong>{$examTable['Collation']}</strong></p>";
        
        // Make sure it's InnoDB
        if ($examTable['Engine'] !== 'InnoDB') {
            echo "<p class='text-warning'>⚠️ Converting to InnoDB...</p>";
            $conn->exec("ALTER TABLE exam ENGINE=InnoDB");
            echo "<p class='text-success'>✓ Converted to InnoDB</p>";
        }
    } else {
        throw new Exception("exam table doesn't exist!");
    }
    
    echo "</div></div>";
    
    // Step 2: Create exam_question table (WITHOUT foreign keys initially)
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-primary text-white'><h5>Step 2: Creating exam_question table</h5></div>";
    echo "<div class='card-body'>";
    
    // Drop if exists
    $conn->exec("DROP TABLE IF EXISTS exam_question");
    
    $query = "CREATE TABLE exam_question (
        Question_ID INT PRIMARY KEY AUTO_INCREMENT,
        Exam_ID INT NOT NULL,
        Question_Text TEXT NOT NULL,
        Question_Type ENUM('Multiple Choice', 'True/False', 'Short Answer', 'Essay') DEFAULT 'Multiple Choice',
        Option_A VARCHAR(500),
        Option_B VARCHAR(500),
        Option_C VARCHAR(500),
        Option_D VARCHAR(500),
        Correct_Answer VARCHAR(500),
        Points INT DEFAULT 1,
        Question_Order INT,
        Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_exam_id (Exam_ID)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $conn->exec($query);
    echo "<p class='text-success'>✓ exam_question table created!</p>";
    echo "</div></div>";
    
    // Step 3: Create exam_submission table (WITHOUT foreign keys initially)
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-success text-white'><h5>Step 3: Creating exam_submission table</h5></div>";
    echo "<div class='card-body'>";
    
    // Drop if exists
    $conn->exec("DROP TABLE IF EXISTS exam_submission");
    
    $query = "CREATE TABLE exam_submission (
        Submission_ID INT PRIMARY KEY AUTO_INCREMENT,
        Exam_ID INT NOT NULL,
        Student_ID INT NOT NULL,
        Start_Time DATETIME,
        Submit_Time DATETIME,
        Status ENUM('Not Started', 'In Progress', 'Submitted', 'Graded') DEFAULT 'Not Started',
        Total_Score DECIMAL(5,2),
        Percentage DECIMAL(5,2),
        Grade VARCHAR(2),
        Teacher_Comments TEXT,
        Auto_Save_Data JSON,
        IP_Address VARCHAR(45),
        Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_submission (Exam_ID, Student_ID),
        INDEX idx_exam_id (Exam_ID),
        INDEX idx_student_id (Student_ID),
        INDEX idx_status (Status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $conn->exec($query);
    echo "<p class='text-success'>✓ exam_submission table created!</p>";
    echo "</div></div>";
    
    // Step 4: Create exam_answer table (WITHOUT foreign keys initially)
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-warning'><h5>Step 4: Creating exam_answer table</h5></div>";
    echo "<div class='card-body'>";
    
    // Drop if exists
    $conn->exec("DROP TABLE IF EXISTS exam_answer");
    
    $query = "CREATE TABLE exam_answer (
        Answer_ID INT PRIMARY KEY AUTO_INCREMENT,
        Submission_ID INT NOT NULL,
        Question_ID INT NOT NULL,
        Student_Answer TEXT,
        Is_Correct BOOLEAN,
        Points_Earned DECIMAL(5,2),
        Teacher_Feedback TEXT,
        Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_answer (Submission_ID, Question_ID),
        INDEX idx_submission_id (Submission_ID),
        INDEX idx_question_id (Question_ID)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $conn->exec($query);
    echo "<p class='text-success'>✓ exam_answer table created!</p>";
    echo "</div></div>";
    
    // Step 5: Verify all tables
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-secondary text-white'><h5>Step 5: Verification</h5></div>";
    echo "<div class='card-body'>";
    
    $tables = ['exam', 'exam_question', 'exam_submission', 'exam_answer'];
    $allGood = true;
    
    echo "<table class='table table-striped'>";
    echo "<tr><th>Table</th><th>Status</th><th>Rows</th><th>Engine</th></tr>";
    
    foreach ($tables as $table) {
        $stmt = $conn->query("SHOW TABLE STATUS LIKE '$table'");
        $info = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($info) {
            echo "<tr>";
            echo "<td><code>$table</code></td>";
            echo "<td><span class='badge bg-success'>✓ Exists</span></td>";
            echo "<td>{$info['Rows']}</td>";
            echo "<td>{$info['Engine']}</td>";
            echo "</tr>";
        } else {
            echo "<tr>";
            echo "<td><code>$table</code></td>";
            echo "<td><span class='badge bg-danger'>✗ Missing</span></td>";
            echo "<td>-</td>";
            echo "<td>-</td>";
            echo "</tr>";
            $allGood = false;
        }
    }
    
    echo "</table>";
    echo "</div></div>";
    
    if ($allGood) {
        echo "<div class='alert alert-success'>";
        echo "<h3>✅ Success! All Tables Created!</h3>";
        echo "<p><strong>What you can do now:</strong></p>";
        echo "<ul>";
        echo "<li>✓ Students can view exams</li>";
        echo "<li>✓ Students can take exams with auto-save</li>";
        echo "<li>✓ Teachers can create exams</li>";
        echo "<li>✓ System tracks submissions</li>";
        echo "<li>✓ Ready for question management</li>";
        echo "</ul>";
        
        echo "<div class='mt-4'>";
        echo "<a href='student/exams.php' class='btn btn-success btn-lg me-2'>";
        echo "<i class='fas fa-graduation-cap me-2'></i>Student Exams";
        echo "</a>";
        echo "<a href='staff/exams.php' class='btn btn-primary btn-lg'>";
        echo "<i class='fas fa-chalkboard-teacher me-2'></i>Teacher Exams";
        echo "</a>";
        echo "</div>";
        echo "</div>";
    } else {
        echo "<div class='alert alert-danger'>";
        echo "<h4>❌ Some tables are missing!</h4>";
        echo "<p>Please check the errors above.</p>";
        echo "</div>";
    }
    
    // Show note about foreign keys
    echo "<div class='alert alert-info'>";
    echo "<h5><i class='fas fa-info-circle me-2'></i>Note:</h5>";
    echo "<p>Foreign key constraints have been removed to prevent reference errors.</p>";
    echo "<p>The tables use indexes instead for better compatibility.</p>";
    echo "<p><strong>Data integrity is maintained through application logic.</strong></p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>";
    echo "<h4>❌ Error!</h4>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Code:</strong> " . $e->getCode() . "</p>";
    echo "</div>";
    
    echo "<div class='mt-3'>";
    echo "<a href='create_tables.php' class='btn btn-warning'>";
    echo "<i class='fas fa-database me-2'></i>Run Main Database Setup First";
    echo "</a>";
    echo "</div>";
}

echo "<hr>";
echo "<div class='text-center'>";
echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-home me-2'></i>Home</a> ";
echo "<a href='diagnose_login.php' class='btn btn-info'><i class='fas fa-stethoscope me-2'></i>Diagnose System</a>";
echo "</div>";

echo "</div>";
echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>";
echo "</body></html>";
?>

