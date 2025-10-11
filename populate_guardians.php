<?php
// Populate Guardian Table - Magodi Private School Management System
echo "<h2>👨‍👩‍👧‍👦 Populating Guardians - Magodi Private School</h2>";

// Database configuration
$host = "localhost";
$db_name = "magodi_private_school";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✅ Database connection successful</p>";
    
    // Clear existing guardians
    $conn->exec("DELETE FROM Guardian");
    echo "<p style='color: green;'>✅ Cleared existing guardians</p>";
    
    // Create sample guardians
    $guardians = [
        ['James Mwale', 'Father', '0881111111', 'james.mwale@email.com', 'Area 23, Lilongwe', 'Businessman'],
        ['Sarah Chisale', 'Mother', '0881111112', 'sarah.chisale@email.com', 'Area 24, Lilongwe', 'Teacher'],
        ['Michael Banda', 'Father', '0881111113', 'michael.banda@email.com', 'Area 25, Lilongwe', 'Engineer'],
        ['Patricia Phiri', 'Mother', '0881111114', 'patricia.phiri@email.com', 'Area 26, Lilongwe', 'Nurse'],
        ['David Mkandawire', 'Father', '0881111115', 'david.mkandawire@email.com', 'Area 27, Lilongwe', 'Accountant'],
        ['Grace Mwale', 'Mother', '0881111116', 'grace.mwale@email.com', 'Area 28, Lilongwe', 'Doctor'],
        ['Peter Chisale', 'Father', '0881111117', 'peter.chisale@email.com', 'Area 29, Lilongwe', 'Lawyer'],
        ['Mary Banda', 'Mother', '0881111118', 'mary.banda@email.com', 'Area 30, Lilongwe', 'Banker']
    ];
    
    foreach($guardians as $guardian) {
        $query = "INSERT INTO Guardian (Full_Name, Relationship, Contact_Number, Email, Address, Occupation) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute($guardian);
    }
    echo "<p style='color: green;'>✅ Sample guardians created</p>";
    
    // Create user accounts for guardians
    $guardian_users = [
        ['james.mwale', 'parent123', 4, 1],
        ['sarah.chisale', 'parent123', 4, 2],
        ['michael.banda', 'parent123', 4, 3],
        ['patricia.phiri', 'parent123', 4, 4],
        ['david.mkandawire', 'parent123', 4, 5],
        ['grace.mwale', 'parent123', 4, 6],
        ['peter.chisale', 'parent123', 4, 7],
        ['mary.banda', 'parent123', 4, 8]
    ];
    
    // Clear existing guardian users
    $conn->exec("DELETE FROM User WHERE Role_ID = 4");
    
    foreach($guardian_users as $user) {
        $password_hash = password_hash($user[1], PASSWORD_DEFAULT);
        $query = "INSERT INTO User (Username, Password, Role_ID, Related_ID) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$user[0], $password_hash, $user[2], $user[3]]);
    }
    echo "<p style='color: green;'>✅ Guardian user accounts created</p>";
    
    echo "<div style='background: #d4edda; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    echo "<h3 style='color: #155724;'>🎉 Guardians Populated Successfully!</h3>";
    echo "<p><strong>Total Guardians:</strong> " . count($guardians) . " guardians created</p>";
    echo "</div>";
    
    echo "<div style='background: #cce5ff; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    echo "<h3 style='color: #004085;'>👨‍👩‍👧‍👦 Guardian Login Credentials:</h3>";
    echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px;'>";
    
    foreach($guardian_users as $index => $user) {
        echo "<div style='background: white; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107;'>";
        echo "<h4 style='color: #ffc107; margin: 0 0 10px 0;'>👨‍👩‍👧‍👦 " . $guardians[$index][0] . "</h4>";
        echo "<p><strong>Username:</strong> " . $user[0] . "<br><strong>Password:</strong> " . $user[1] . "</p>";
        echo "<p><strong>Relationship:</strong> " . $guardians[$index][1] . "<br><strong>Occupation:</strong> " . $guardians[$index][5] . "</p>";
        echo "</div>";
    }
    
    echo "</div>";
    echo "</div>";
    
    echo "<div style='text-center; margin: 20px 0;'>";
    echo "<a href='admin/guardians.php' class='btn btn-primary btn-lg me-3'>";
    echo "<i class='fas fa-user-friends me-2'></i>Manage Guardians";
    echo "</a>";
    echo "<a href='simple_login.php' class='btn btn-success btn-lg'>";
    echo "<i class='fas fa-sign-in-alt me-2'></i>Test Login";
    echo "</a>";
    echo "</div>";
    
} catch(Exception $e) {
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
    echo "<h3 style='color: #721c24;'>❌ Error Populating Guardians!</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Please ensure your database is set up correctly.</p>";
    echo "</div>";
}
?>
