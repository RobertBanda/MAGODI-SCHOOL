<?php
/**
 * Create Exam System Tables
 * Creates tables for exam questions, student submissions, and answers
 */

require_once 'config/database.php';

echo "<!DOCTYPE html>";
echo "<html><head>";
echo "<title>Create Exam System Tables</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "</head><body class='bg-light'>";
echo "<div class='container py-5'>";
echo "<h1 class='mb-4'>🎓 Create Exam System Tables</h1>";

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Check if exam table exists first
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-warning'><h5>Checking Prerequisites</h5></div>";
    echo "<div class='card-body'>";
    
    $stmt = $conn->query("SHOW TABLES LIKE 'exam'");
    if ($stmt->rowCount() == 0) {
        echo "<p class='text-danger'><strong>❌ Error:</strong> The 'exam' table doesn't exist yet!</p>";
        echo "<p>The exam system tables require the main 'exam' table to exist first.</p>";
        echo "<p><strong>Solution:</strong></p>";
        echo "<ol>";
        echo "<li><a href='create_exam_table.php' class='btn btn-primary'>Create Exam Table First</a></li>";
        echo "<li>Or run: <a href='create_tables.php' class='btn btn-secondary'>Main Database Setup</a></li>";
        echo "</ol>";
        echo "</div></div>";
        throw new Exception("Please create the exam table first by clicking the link above.");
    }
    
    echo "<p class='text-success'>✓ exam table exists</p>";
    
    // Check other required tables
    $required = ['subject', 'class', 'teacher', 'student'];
    foreach ($required as $table) {
        $stmt = $conn->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<p class='text-success'>✓ $table table exists</p>";
        } else {
            echo "<p class='text-danger'>✗ $table table missing</p>";
        }
    }
    
    echo "</div></div>";
    
    // Table 1: exam_question
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-primary text-white'><h5>Creating exam_question table</h5></div>";
    echo "<div class='card-body'>";
    
    $query = "CREATE TABLE IF NOT EXISTS exam_question (
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
        FOREIGN KEY (Exam_ID) REFERENCES exam(Exam_ID) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $conn->exec($query);
    echo "<p class='text-success'>✓ exam_question table created successfully!</p>";
    echo "</div></div>";
    
    // Table 2: exam_submission
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-info text-white'><h5>Creating exam_submission table</h5></div>";
    echo "<div class='card-body'>";
    
    $query = "CREATE TABLE IF NOT EXISTS exam_submission (
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
        FOREIGN KEY (Exam_ID) REFERENCES exam(Exam_ID) ON DELETE CASCADE,
        FOREIGN KEY (Student_ID) REFERENCES student(Student_ID) ON DELETE CASCADE,
        UNIQUE KEY unique_submission (Exam_ID, Student_ID)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $conn->exec($query);
    echo "<p class='text-success'>✓ exam_submission table created successfully!</p>";
    echo "</div></div>";
    
    // Table 3: exam_answer
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-success text-white'><h5>Creating exam_answer table</h5></div>";
    echo "<div class='card-body'>";
    
    $query = "CREATE TABLE IF NOT EXISTS exam_answer (
        Answer_ID INT PRIMARY KEY AUTO_INCREMENT,
        Submission_ID INT NOT NULL,
        Question_ID INT NOT NULL,
        Student_Answer TEXT,
        Is_Correct BOOLEAN,
        Points_Earned DECIMAL(5,2),
        Teacher_Feedback TEXT,
        Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (Submission_ID) REFERENCES exam_submission(Submission_ID) ON DELETE CASCADE,
        FOREIGN KEY (Question_ID) REFERENCES exam_question(Question_ID) ON DELETE CASCADE,
        UNIQUE KEY unique_answer (Submission_ID, Question_ID)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $conn->exec($query);
    echo "<p class='text-success'>✓ exam_answer table created successfully!</p>";
    echo "</div></div>";
    
    // Add indexes for performance
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-warning'><h5>Adding indexes</h5></div>";
    echo "<div class='card-body'>";
    
    try {
        $conn->exec("ALTER TABLE exam_question ADD INDEX idx_exam_id (Exam_ID)");
        echo "<p class='text-success'>✓ Index added to exam_question.Exam_ID</p>";
    } catch (Exception $e) {
        echo "<p class='text-muted'>Index already exists on exam_question.Exam_ID</p>";
    }
    
    try {
        $conn->exec("ALTER TABLE exam_submission ADD INDEX idx_student_id (Student_ID)");
        echo "<p class='text-success'>✓ Index added to exam_submission.Student_ID</p>";
    } catch (Exception $e) {
        echo "<p class='text-muted'>Index already exists on exam_submission.Student_ID</p>";
    }
    
    try {
        $conn->exec("ALTER TABLE exam_submission ADD INDEX idx_status (Status)");
        echo "<p class='text-success'>✓ Index added to exam_submission.Status</p>";
    } catch (Exception $e) {
        echo "<p class='text-muted'>Index already exists on exam_submission.Status</p>";
    }
    
    echo "</div></div>";
    
    // Summary
    echo "<div class='alert alert-success'>";
    echo "<h4>✅ All Tables Created Successfully!</h4>";
    echo "<p><strong>Created Tables:</strong></p>";
    echo "<ul>";
    echo "<li><code>exam_question</code> - Stores exam questions and answer choices</li>";
    echo "<li><code>exam_submission</code> - Tracks student exam attempts and submissions</li>";
    echo "<li><code>exam_answer</code> - Stores student answers to questions</li>";
    echo "</ul>";
    echo "<p><strong>Features Enabled:</strong></p>";
    echo "<ul>";
    echo "<li>✓ Students can take exams online</li>";
    echo "<li>✓ Auto-save functionality for exam progress</li>";
    echo "<li>✓ Teachers can create questions</li>";
    echo "<li>✓ Teachers can grade submissions</li>";
    echo "<li>✓ Automatic grading for multiple choice</li>";
    echo "<li>✓ Manual grading for essays</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div class='text-center'>";
    echo "<a href='staff/exams.php' class='btn btn-primary btn-lg me-2'>";
    echo "<i class='fas fa-chalkboard-teacher me-2'></i>Teacher Exam Management";
    echo "</a>";
    echo "<a href='student/exams.php' class='btn btn-success btn-lg'>";
    echo "<i class='fas fa-graduation-cap me-2'></i>Student Exams";
    echo "</a>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>";
    echo "<h4>❌ Error!</h4>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}

echo "</div>";
echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>";
echo "</body></html>";
?>

