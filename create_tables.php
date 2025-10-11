<?php
require_once 'config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    echo "<h2>Creating Staff Portal Database Tables</h2>";
    
    // Create Message table
    $sql1 = "CREATE TABLE IF NOT EXISTS `Message` (
      `Message_ID` int(11) NOT NULL AUTO_INCREMENT,
      `Sender_ID` int(11) NOT NULL,
      `Receiver_ID` int(11) NOT NULL,
      `Subject` varchar(255) NOT NULL,
      `Content` text NOT NULL,
      `Is_Read` tinyint(1) DEFAULT 0,
      `Read_At` datetime DEFAULT NULL,
      `Created_At` datetime DEFAULT CURRENT_TIMESTAMP,
      `Updated_At` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`Message_ID`),
      KEY `idx_sender` (`Sender_ID`),
      KEY `idx_receiver` (`Receiver_ID`),
      KEY `idx_created` (`Created_At`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $conn->exec($sql1);
    echo "<p style='color: green;'>✓ Message table created successfully</p>";
    
    // Create Book table
    $sql2 = "CREATE TABLE IF NOT EXISTS `Book` (
      `Book_ID` int(11) NOT NULL AUTO_INCREMENT,
      `Title` varchar(255) NOT NULL,
      `Author` varchar(255) NOT NULL,
      `ISBN` varchar(20) DEFAULT NULL,
      `Category` varchar(100) DEFAULT NULL,
      `Publisher` varchar(255) DEFAULT NULL,
      `Publication_Year` year(4) DEFAULT NULL,
      `Description` text DEFAULT NULL,
      `Status` enum('Available','Borrowed','Overdue','Lost') DEFAULT 'Available',
      `Location` varchar(100) DEFAULT NULL,
      `Created_At` datetime DEFAULT CURRENT_TIMESTAMP,
      `Updated_At` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`Book_ID`),
      KEY `idx_title` (`Title`),
      KEY `idx_author` (`Author`),
      KEY `idx_isbn` (`ISBN`),
      KEY `idx_status` (`Status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $conn->exec($sql2);
    echo "<p style='color: green;'>✓ Book table created successfully</p>";
    
    // Create Book_Borrowing table
    $sql3 = "CREATE TABLE IF NOT EXISTS `Book_Borrowing` (
      `Borrowing_ID` int(11) NOT NULL AUTO_INCREMENT,
      `Book_ID` int(11) NOT NULL,
      `Borrower_ID` int(11) NOT NULL,
      `Borrower_Type` enum('Student','Teacher','Staff') NOT NULL,
      `Borrow_Date` date NOT NULL,
      `Due_Date` date NOT NULL,
      `Return_Date` date DEFAULT NULL,
      `Status` enum('Borrowed','Returned','Overdue','Lost') DEFAULT 'Borrowed',
      `Fine_Amount` decimal(10,2) DEFAULT 0.00,
      `Notes` text DEFAULT NULL,
      `Created_At` datetime DEFAULT CURRENT_TIMESTAMP,
      `Updated_At` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`Borrowing_ID`),
      KEY `idx_book` (`Book_ID`),
      KEY `idx_borrower` (`Borrower_ID`),
      KEY `idx_due_date` (`Due_Date`),
      KEY `idx_status` (`Status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $conn->exec($sql3);
    echo "<p style='color: green;'>✓ Book_Borrowing table created successfully</p>";
    
    // Create Teacher_Subject table
    $sql4 = "CREATE TABLE IF NOT EXISTS `Teacher_Subject` (
      `Teacher_Subject_ID` int(11) NOT NULL AUTO_INCREMENT,
      `Teacher_ID` int(11) NOT NULL,
      `Subject_ID` int(11) NOT NULL,
      `Created_At` datetime DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`Teacher_Subject_ID`),
      UNIQUE KEY `unique_teacher_subject` (`Teacher_ID`, `Subject_ID`),
      KEY `idx_teacher` (`Teacher_ID`),
      KEY `idx_subject` (`Subject_ID`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $conn->exec($sql4);
    echo "<p style='color: green;'>✓ Teacher_Subject table created successfully</p>";
    
    // Insert sample books
    $sql5 = "INSERT IGNORE INTO `Book` (`Title`, `Author`, `ISBN`, `Category`, `Publisher`, `Publication_Year`, `Description`, `Status`, `Location`) VALUES
    ('Mathematics for Secondary Schools', 'Dr. John Smith', '978-1234567890', 'Mathematics', 'Educational Publishers', 2023, 'Comprehensive mathematics textbook for secondary school students', 'Available', 'Shelf A1'),
    ('English Literature Guide', 'Prof. Mary Johnson', '978-1234567891', 'English', 'Literature House', 2023, 'Complete guide to English literature for high school', 'Available', 'Shelf B2'),
    ('Physics Fundamentals', 'Dr. Robert Brown', '978-1234567892', 'Science', 'Science Press', 2023, 'Basic physics concepts and principles', 'Borrowed', 'Shelf C3'),
    ('Chemistry Laboratory Manual', 'Dr. Sarah Wilson', '978-1234567893', 'Science', 'Lab Publications', 2023, 'Practical chemistry experiments and procedures', 'Available', 'Shelf D4'),
    ('History of Malawi', 'Dr. James Mwale', '978-1234567894', 'History', 'National Publishers', 2023, 'Comprehensive history of Malawi', 'Overdue', 'Shelf E5')";
    
    $conn->exec($sql5);
    echo "<p style='color: green;'>✓ Sample books inserted successfully</p>";
    
    // Insert sample messages
    $sql6 = "INSERT IGNORE INTO `Message` (`Sender_ID`, `Receiver_ID`, `Subject`, `Content`, `Is_Read`, `Created_At`) VALUES
    (1, 2, 'Meeting Reminder', 'Don''t forget about the staff meeting tomorrow at 2 PM.', 0, NOW()),
    (2, 1, 'Student Performance', 'I wanted to discuss the performance of some students in my class.', 1, NOW() - INTERVAL 1 DAY),
    (1, 3, 'Library Books', 'Please return the borrowed books by the end of the week.', 0, NOW() - INTERVAL 2 DAY)";
    
    $conn->exec($sql6);
    echo "<p style='color: green;'>✓ Sample messages inserted successfully</p>";
    
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4 style='color: #155724; margin: 0 0 10px 0;'>🎉 Staff Portal Database Setup Complete!</h4>";
    echo "<p style='color: #155724; margin: 0;'>All database tables have been created successfully. You can now use the Staff Portal with full CRUD functionality.</p>";
    echo "</div>";
    
    echo "<h4>What's Available:</h4>";
    echo "<ul>";
    echo "<li>✅ <strong>Messages System</strong> - Send and receive messages between staff</li>";
    echo "<li>✅ <strong>Library Management</strong> - Add, edit, delete, and track books</li>";
    echo "<li>✅ <strong>Book Borrowing</strong> - Track book borrowing and returns</li>";
    echo "<li>✅ <strong>Teacher Profiles</strong> - Complete teacher information management</li>";
    echo "</ul>";
    
    echo "<h4>Access the Staff Portal:</h4>";
    echo "<p><a href='staff/dashboard.php' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Staff Dashboard</a></p>";
    
} catch(Exception $e) {
    echo "<h3 style='color: red;'>Setup Failed!</h3>";
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
