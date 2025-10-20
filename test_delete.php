<?php
// Test script to verify exam deletion works
require_once 'config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Check exam table structure
    echo "=== EXAM TABLE STRUCTURE ===\n";
    $stmt = $conn->query('DESCRIBE exam');
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . ' - ' . $row['Type'] . "\n";
    }
    
    echo "\n=== CURRENT EXAMS ===\n";
    $stmt = $conn->query('SELECT Exam_ID, Exam_Name, Created_By FROM exam ORDER BY Exam_ID');
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: {$row['Exam_ID']}, Name: {$row['Exam_Name']}, Created By: {$row['Created_By']}\n";
    }
    
    echo "\n=== TEST DELETE PERMISSION ===\n";
    // Try to delete an exam (change ID as needed)
    $test_exam_id = 13; // Change this to an actual exam ID
    $test_teacher_id = 12; // Change this to the actual teacher ID
    
    // Check if exam exists
    $stmt = $conn->prepare('SELECT Exam_Name, Created_By FROM exam WHERE Exam_ID = ?');
    $stmt->execute([$test_exam_id]);
    $exam = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($exam) {
        echo "Found exam: {$exam['Exam_Name']}, Created by: {$exam['Created_By']}\n";
        if ($exam['Created_By'] == $test_teacher_id) {
            echo "Teacher {$test_teacher_id} has permission to delete this exam.\n";
        } else {
            echo "Teacher {$test_teacher_id} DOES NOT have permission (exam created by {$exam['Created_By']}).\n";
        }
    } else {
        echo "Exam ID {$test_exam_id} not found.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>

