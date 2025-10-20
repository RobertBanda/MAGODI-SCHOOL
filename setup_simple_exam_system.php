<?php
/**
 * Setup Simple Document-Based Exam System
 * Teachers upload exam documents, students download and view them
 */

require_once 'config/database.php';

echo "<!DOCTYPE html>";
echo "<html><head>";
echo "<title>Setup Simple Exam System</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "</head><body class='bg-light'>";
echo "<div class='container py-5'>";
echo "<h1 class='mb-4'>📄 Setup Simple Document-Based Exam System</h1>";

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Step 1: Create uploads directory
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-primary text-white'><h5>Step 1: Creating uploads directory</h5></div>";
    echo "<div class='card-body'>";
    
    $uploadDir = __DIR__ . '/uploads/exams';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
        echo "<p class='text-success'>✓ Created directory: uploads/exams/</p>";
    } else {
        echo "<p class='text-info'>✓ Directory already exists: uploads/exams/</p>";
    }
    
    echo "</div></div>";
    
    // Step 2: Create exam_documents table
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-success text-white'><h5>Step 2: Creating exam_documents table</h5></div>";
    echo "<div class='card-body'>";
    
    $query = "CREATE TABLE IF NOT EXISTS exam_documents (
        Document_ID INT PRIMARY KEY AUTO_INCREMENT,
        Exam_ID INT NOT NULL,
        File_Name VARCHAR(255) NOT NULL,
        Original_Name VARCHAR(255) NOT NULL,
        File_Type VARCHAR(50),
        File_Size INT,
        Upload_Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        Uploaded_By INT,
        Description TEXT,
        INDEX idx_exam_id (Exam_ID)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $conn->exec($query);
    echo "<p class='text-success'>✓ exam_documents table created!</p>";
    echo "</div></div>";
    
    // Step 3: Create student_submissions table (for uploading answers)
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-info text-white'><h5>Step 3: Creating student_submissions table</h5></div>";
    echo "<div class='card-body'>";
    
    $query = "CREATE TABLE IF NOT EXISTS student_submissions (
        Submission_ID INT PRIMARY KEY AUTO_INCREMENT,
        Exam_ID INT NOT NULL,
        Student_ID INT NOT NULL,
        File_Name VARCHAR(255),
        Original_Name VARCHAR(255),
        File_Type VARCHAR(50),
        File_Size INT,
        Submission_Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        Score DECIMAL(5,2),
        Grade VARCHAR(2),
        Teacher_Comments TEXT,
        Status ENUM('Pending', 'Graded') DEFAULT 'Pending',
        UNIQUE KEY unique_submission (Exam_ID, Student_ID),
        INDEX idx_student_id (Student_ID),
        INDEX idx_exam_id (Exam_ID)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $conn->exec($query);
    echo "<p class='text-success'>✓ student_submissions table created!</p>";
    echo "</div></div>";
    
    // Step 4: Create submissions upload directory
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-warning'><h5>Step 4: Creating submissions directory</h5></div>";
    echo "<div class='card-body'>";
    
    $submissionsDir = __DIR__ . '/uploads/submissions';
    if (!file_exists($submissionsDir)) {
        mkdir($submissionsDir, 0777, true);
        echo "<p class='text-success'>✓ Created directory: uploads/submissions/</p>";
    } else {
        echo "<p class='text-info'>✓ Directory already exists: uploads/submissions/</p>";
    }
    
    echo "</div></div>";
    
    // Success!
    echo "<div class='alert alert-success'>";
    echo "<h3>✅ Setup Complete!</h3>";
    echo "<p><strong>What you can do now:</strong></p>";
    echo "<ul>";
    echo "<li>✓ Teachers can upload exam documents (PDF, Word, etc.)</li>";
    echo "<li>✓ Students can view and download exam documents</li>";
    echo "<li>✓ Students can upload their answer sheets</li>";
    echo "<li>✓ Teachers can download submissions and grade them</li>";
    echo "<li>✓ Teachers can add scores and comments</li>";
    echo "</ul>";
    
    echo "<h5 class='mt-4'>Next Steps:</h5>";
    echo "<div class='row g-3'>";
    echo "<div class='col-md-6'>";
    echo "<div class='card'>";
    echo "<div class='card-body'>";
    echo "<h6>For Teachers:</h6>";
    echo "<ol>";
    echo "<li>Go to Staff > Exams</li>";
    echo "<li>Create an exam</li>";
    echo "<li>Upload exam document (PDF/Word)</li>";
    echo "<li>Students can now see and download it</li>";
    echo "<li>View student submissions</li>";
    echo "<li>Grade and add comments</li>";
    echo "</ol>";
    echo "</div></div></div>";
    
    echo "<div class='col-md-6'>";
    echo "<div class='card'>";
    echo "<div class='card-body'>";
    echo "<h6>For Students:</h6>";
    echo "<ol>";
    echo "<li>Go to Student > My Exams</li>";
    echo "<li>View available exams</li>";
    echo "<li>Download exam document</li>";
    echo "<li>Answer the questions</li>";
    echo "<li>Upload your answer sheet</li>";
    echo "<li>View results after grading</li>";
    echo "</ol>";
    echo "</div></div></div>";
    echo "</div>";
    
    echo "<div class='mt-4'>";
    echo "<a href='staff/exams.php' class='btn btn-primary btn-lg me-2'>";
    echo "<i class='fas fa-chalkboard-teacher me-2'></i>Teacher Dashboard";
    echo "</a>";
    echo "<a href='student/exams.php' class='btn btn-success btn-lg'>";
    echo "<i class='fas fa-graduation-cap me-2'></i>Student Dashboard";
    echo "</a>";
    echo "</div>";
    echo "</div>";
    
    // Show created resources
    echo "<div class='card mt-4'>";
    echo "<div class='card-header bg-secondary text-white'><h5>Created Resources</h5></div>";
    echo "<div class='card-body'>";
    echo "<table class='table table-striped'>";
    echo "<tr><th>Resource</th><th>Status</th><th>Purpose</th></tr>";
    echo "<tr>";
    echo "<td><code>uploads/exams/</code></td>";
    echo "<td><span class='badge bg-success'>✓</span></td>";
    echo "<td>Teachers upload exam documents here</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td><code>uploads/submissions/</code></td>";
    echo "<td><span class='badge bg-success'>✓</span></td>";
    echo "<td>Students upload answer sheets here</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td><code>exam_documents</code></td>";
    echo "<td><span class='badge bg-success'>✓</span></td>";
    echo "<td>Database table for exam files</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td><code>student_submissions</code></td>";
    echo "<td><span class='badge bg-success'>✓</span></td>";
    echo "<td>Database table for student answers</td>";
    echo "</tr>";
    echo "</table>";
    echo "</div></div>";
    
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

