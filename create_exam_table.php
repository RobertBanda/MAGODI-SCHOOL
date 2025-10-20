<?php
/**
 * Create Exam Table
 * This creates the main exam table that other tables reference
 */

require_once 'config/database.php';

echo "<!DOCTYPE html>";
echo "<html><head>";
echo "<title>Create Exam Table</title>";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "</head><body class='bg-light'>";
echo "<div class='container py-5'>";
echo "<h1 class='mb-4'>🎓 Create Exam Table</h1>";

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Check if exam table exists
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-info text-white'><h5>Checking if exam table exists...</h5></div>";
    echo "<div class='card-body'>";
    
    $stmt = $conn->query("SHOW TABLES LIKE 'exam'");
    $exists = $stmt->rowCount() > 0;
    
    if ($exists) {
        echo "<p class='text-success'>✓ exam table already exists!</p>";
        
        // Show table structure
        $stmt = $conn->query("DESCRIBE exam");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h6>Current Structure:</h6>";
        echo "<table class='table table-sm'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
        foreach ($columns as $col) {
            echo "<tr>";
            echo "<td>{$col['Field']}</td>";
            echo "<td>{$col['Type']}</td>";
            echo "<td>{$col['Null']}</td>";
            echo "<td>{$col['Key']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='text-warning'>⚠️ exam table does not exist. Creating it now...</p>";
        
        // Create exam table
        $query = "CREATE TABLE exam (
            Exam_ID INT PRIMARY KEY AUTO_INCREMENT,
            Exam_Name VARCHAR(200) NOT NULL,
            Exam_Type ENUM('Quiz', 'Test', 'Midterm', 'Final', 'Assignment') DEFAULT 'Test',
            Subject_ID INT,
            Class_ID INT,
            Start_Date DATETIME,
            End_Date DATETIME,
            Description TEXT,
            Created_By INT,
            Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            Status ENUM('Pending', 'Open', 'Completed', 'Cancelled') DEFAULT 'Pending',
            Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            Total_Marks INT DEFAULT 100,
            Passing_Marks INT DEFAULT 50,
            Duration_Minutes INT,
            Instructions TEXT,
            FOREIGN KEY (Subject_ID) REFERENCES subject(Subject_ID) ON DELETE SET NULL,
            FOREIGN KEY (Class_ID) REFERENCES class(Class_ID) ON DELETE SET NULL,
            FOREIGN KEY (Created_By) REFERENCES teacher(Teacher_ID) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $conn->exec($query);
        echo "<p class='text-success'>✓ exam table created successfully!</p>";
    }
    
    echo "</div></div>";
    
    // Now check other required tables
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-primary text-white'><h5>Checking Required Tables</h5></div>";
    echo "<div class='card-body'>";
    
    $required_tables = ['subject', 'class', 'teacher', 'student'];
    $all_exist = true;
    
    foreach ($required_tables as $table) {
        $stmt = $conn->query("SHOW TABLES LIKE '$table'");
        $exists = $stmt->rowCount() > 0;
        
        if ($exists) {
            echo "<p class='text-success'>✓ $table table exists</p>";
        } else {
            echo "<p class='text-danger'>✗ $table table is MISSING!</p>";
            $all_exist = false;
        }
    }
    
    echo "</div></div>";
    
    if ($all_exist) {
        echo "<div class='alert alert-success'>";
        echo "<h4>✅ All Required Tables Exist!</h4>";
        echo "<p>You can now create the exam system tables.</p>";
        echo "<a href='create_exam_system_tables.php' class='btn btn-success btn-lg'>";
        echo "<i class='fas fa-arrow-right me-2'></i>Continue to Create Exam System Tables";
        echo "</a>";
        echo "</div>";
    } else {
        echo "<div class='alert alert-danger'>";
        echo "<h4>❌ Some Tables are Missing!</h4>";
        echo "<p>Please run the main database setup first:</p>";
        echo "<a href='create_tables.php' class='btn btn-danger btn-lg'>";
        echo "<i class='fas fa-database me-2'></i>Run Database Setup";
        echo "</a>";
        echo "</div>";
    }
    
    // Show all exam-related tables
    echo "<div class='card mb-3'>";
    echo "<div class='card-header bg-secondary text-white'><h5>Exam-Related Tables Status</h5></div>";
    echo "<div class='card-body'>";
    
    $exam_tables = [
        'exam' => 'Main exam table',
        'exam_question' => 'Exam questions',
        'exam_submission' => 'Student submissions',
        'exam_answer' => 'Student answers',
        'exam_result' => 'Legacy results table'
    ];
    
    echo "<table class='table table-striped'>";
    echo "<tr><th>Table</th><th>Description</th><th>Status</th></tr>";
    
    foreach ($exam_tables as $table => $desc) {
        $stmt = $conn->query("SHOW TABLES LIKE '$table'");
        $exists = $stmt->rowCount() > 0;
        
        $status = $exists ? 
            '<span class="badge bg-success">Exists</span>' : 
            '<span class="badge bg-warning">Not Created</span>';
        
        echo "<tr>";
        echo "<td><code>$table</code></td>";
        echo "<td>$desc</td>";
        echo "<td>$status</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "</div></div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>";
    echo "<h4>❌ Error!</h4>";
    echo "<p><strong>Error Message:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Error Code:</strong> " . $e->getCode() . "</p>";
    
    if (strpos($e->getMessage(), 'referenced table') !== false) {
        echo "<hr>";
        echo "<h5>🔧 How to Fix:</h5>";
        echo "<ol>";
        echo "<li>The exam table needs other tables (subject, class, teacher) to exist first</li>";
        echo "<li>Run the main database setup: <a href='create_tables.php'>create_tables.php</a></li>";
        echo "<li>Or run quick setup: <a href='quick_setup.php'>quick_setup.php</a></li>";
        echo "<li>Then come back and run this script again</li>";
        echo "</ol>";
    }
    
    echo "</div>";
}

echo "<div class='mt-4'>";
echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-home me-2'></i>Back to Home</a> ";
echo "<a href='create_tables.php' class='btn btn-primary'><i class='fas fa-database me-2'></i>Main Database Setup</a>";
echo "</div>";

echo "</div>";
echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>";
echo "</body></html>";
?>

