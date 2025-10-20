<?php
// Test script to check student login credentials
$host = "localhost";
$db_name = "magodi_private_school";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Checking Student Login Credentials</h2>";
    
    // Check if user exists
    $test_username = "chisomo.mwale";
    
    $query = "SELECT u.*, r.Role_Name, s.First_Name, s.Last_Name 
              FROM User u 
              LEFT JOIN Role r ON u.Role_ID = r.Role_ID 
              LEFT JOIN student s ON u.Related_ID = s.Student_ID
              WHERE u.Username = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([$test_username]);
    
    if($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<strong>✓ User Found!</strong><br>";
        echo "Username: " . $user['Username'] . "<br>";
        echo "Role: " . $user['Role_Name'] . "<br>";
        echo "Is Active: " . ($user['Is_Active'] ? 'Yes' : 'No') . "<br>";
        echo "Student Name: " . $user['First_Name'] . " " . $user['Last_Name'] . "<br>";
        echo "Password Hash: " . substr($user['Password'], 0, 30) . "...<br>";
        echo "</div>";
        
        // Test password
        $test_password = "student2025315";
        echo "<h3>Testing Password: '$test_password'</h3>";
        
        if(password_verify($test_password, $user['Password'])) {
            echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px;'>";
            echo "<strong>✓ Password is CORRECT!</strong><br>";
            echo "Login should work with these credentials.";
            echo "</div>";
        } else {
            echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
            echo "<strong>✗ Password is INCORRECT!</strong><br>";
            echo "The password in the database does not match 'student2025315'<br>";
            echo "The credentials might need to be regenerated.";
            echo "</div>";
        }
        
    } else {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
        echo "<strong>✗ User NOT Found!</strong><br>";
        echo "Username '$test_username' does not exist in the database.<br>";
        echo "You need to generate student credentials first.";
        echo "</div>";
    }
    
    // Show all students
    echo "<hr><h3>All Students in Database:</h3>";
    $query = "SELECT s.Student_ID, s.First_Name, s.Last_Name, s.Student_Number, 
              u.Username, u.Is_Active, r.Role_Name
              FROM student s
              LEFT JOIN User u ON u.Related_ID = s.Student_ID AND u.Role_ID = (SELECT Role_ID FROM Role WHERE Role_Name = 'Student')
              LEFT JOIN Role r ON u.Role_ID = r.Role_ID
              ORDER BY s.Student_ID
              LIMIT 10";
    
    $stmt = $conn->query($query);
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Student ID</th><th>Name</th><th>Student Number</th><th>Username</th><th>Has Login?</th></tr>";
    
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $hasLogin = $row['Username'] ? '✓ Yes' : '✗ No';
        $style = $row['Username'] ? '' : 'background: #fff3cd;';
        echo "<tr style='$style'>";
        echo "<td>" . $row['Student_ID'] . "</td>";
        echo "<td>" . $row['First_Name'] . " " . $row['Last_Name'] . "</td>";
        echo "<td>" . $row['Student_Number'] . "</td>";
        echo "<td>" . ($row['Username'] ?: 'Not created') . "</td>";
        echo "<td>" . $hasLogin . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<hr><div style='background: #cfe2ff; padding: 15px; border-radius: 5px;'>";
    echo "<strong>Next Steps:</strong><br>";
    echo "1. If credentials don't exist, run: <a href='generate_student_credentials.php'>generate_student_credentials.php</a><br>";
    echo "2. Then try logging in again with the generated credentials<br>";
    echo "3. Check <a href='view_credentials.php'>view_credentials.php</a> to see all credentials";
    echo "</div>";
    
} catch(Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
    echo "<strong>Database Error:</strong><br>" . $e->getMessage();
    echo "</div>";
}
?>

