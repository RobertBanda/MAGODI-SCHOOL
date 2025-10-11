<?php
require_once 'config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    echo "<h2>Creating All Staff Portal Tables in magodi_private_school Database</h2>";
    
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
    
    // Insert the specific book you requested
    $title = "chichewa";
    $author = "robert";
    $isbn = "i";
    $category = "Literature";
    $publisher = "cool";
    $publication_year = 2013;
    $description = "chichewa";
    $location = "shelf A1";
    $status = "Available";
    
    $insert_sql = "INSERT INTO `Book` (`Title`, `Author`, `ISBN`, `Category`, `Publisher`, `Publication_Year`, `Description`, `Status`, `Location`, `Created_At`) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($insert_sql);
    $stmt->bindParam(1, $title);
    $stmt->bindParam(2, $author);
    $stmt->bindParam(3, $isbn);
    $stmt->bindParam(4, $category);
    $stmt->bindParam(5, $publisher);
    $stmt->bindParam(6, $publication_year);
    $stmt->bindParam(7, $description);
    $stmt->bindParam(8, $status);
    $stmt->bindParam(9, $location);
    $stmt->execute();
    
    echo "<p style='color: green;'>✓ Book 'chichewa' by robert added successfully</p>";
    
    // Insert additional sample books
    $sample_books = [
        ['Mathematics for Secondary Schools', 'Dr. John Smith', '978-1234567890', 'Mathematics', 'Educational Publishers', 2023, 'Comprehensive mathematics textbook', 'Available', 'Shelf A2'],
        ['English Literature Guide', 'Prof. Mary Johnson', '978-1234567891', 'English', 'Literature House', 2023, 'Complete guide to English literature', 'Available', 'Shelf B1'],
        ['Physics Fundamentals', 'Dr. Robert Brown', '978-1234567892', 'Science', 'Science Press', 2023, 'Basic physics concepts', 'Borrowed', 'Shelf C1'],
        ['Chemistry Laboratory Manual', 'Dr. Sarah Wilson', '978-1234567893', 'Science', 'Lab Publications', 2023, 'Practical chemistry experiments', 'Available', 'Shelf D1'],
        ['History of Malawi', 'Dr. James Mwale', '978-1234567894', 'History', 'National Publishers', 2023, 'Comprehensive history of Malawi', 'Overdue', 'Shelf E1']
    ];
    
    foreach($sample_books as $book) {
        $stmt = $conn->prepare($insert_sql);
        $stmt->bindParam(1, $book[0]); // Title
        $stmt->bindParam(2, $book[1]); // Author
        $stmt->bindParam(3, $book[2]); // ISBN
        $stmt->bindParam(4, $book[3]); // Category
        $stmt->bindParam(5, $book[4]); // Publisher
        $stmt->bindParam(6, $book[5]); // Publication Year
        $stmt->bindParam(7, $book[6]); // Description
        $stmt->bindParam(8, $book[7]); // Status
        $stmt->bindParam(9, $book[8]); // Location
        $stmt->execute();
    }
    
    echo "<p style='color: green;'>✓ Sample books added successfully</p>";
    
    // Insert sample messages
    $message_sql = "INSERT IGNORE INTO `Message` (`Sender_ID`, `Receiver_ID`, `Subject`, `Content`, `Is_Read`, `Created_At`) VALUES
    (1, 2, 'Meeting Reminder', 'Don''t forget about the staff meeting tomorrow at 2 PM.', 0, NOW()),
    (2, 1, 'Student Performance', 'I wanted to discuss the performance of some students in my class.', 1, NOW() - INTERVAL 1 DAY),
    (1, 3, 'Library Books', 'Please return the borrowed books by the end of the week.', 0, NOW() - INTERVAL 2 DAY)";
    
    $conn->exec($message_sql);
    echo "<p style='color: green;'>✓ Sample messages inserted successfully</p>";
    
    // Verify the tables and data
    $book_count_sql = "SELECT COUNT(*) as book_count FROM Book";
    $stmt = $conn->prepare($book_count_sql);
    $stmt->execute();
    $book_result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $message_count_sql = "SELECT COUNT(*) as message_count FROM Message";
    $stmt = $conn->prepare($message_count_sql);
    $stmt->execute();
    $message_result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4 style='color: #155724; margin: 0 0 10px 0;'>🎉 All Staff Portal Tables Created Successfully!</h4>";
    echo "<p style='color: #155724; margin: 0;'>Total books in database: <strong>" . $book_result['book_count'] . "</strong></p>";
    echo "<p style='color: #155724; margin: 0;'>Total messages in database: <strong>" . $message_result['message_count'] . "</strong></p>";
    echo "</div>";
    
    // Show the specific book that was added
    $show_sql = "SELECT * FROM Book WHERE Title = 'chichewa' AND Author = 'robert'";
    $stmt = $conn->prepare($show_sql);
    $stmt->execute();
    $book = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($book) {
        echo "<h4>Your Book Details:</h4>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
        echo "<tr style='background: #f8f9fa;'><th style='padding: 8px;'>Field</th><th style='padding: 8px;'>Value</th></tr>";
        echo "<tr><td style='padding: 8px;'>Book ID</td><td style='padding: 8px;'>" . $book['Book_ID'] . "</td></tr>";
        echo "<tr><td style='padding: 8px;'>Title</td><td style='padding: 8px;'>" . $book['Title'] . "</td></tr>";
        echo "<tr><td style='padding: 8px;'>Author</td><td style='padding: 8px;'>" . $book['Author'] . "</td></tr>";
        echo "<tr><td style='padding: 8px;'>ISBN</td><td style='padding: 8px;'>" . $book['ISBN'] . "</td></tr>";
        echo "<tr><td style='padding: 8px;'>Category</td><td style='padding: 8px;'>" . $book['Category'] . "</td></tr>";
        echo "<tr><td style='padding: 8px;'>Publisher</td><td style='padding: 8px;'>" . $book['Publisher'] . "</td></tr>";
        echo "<tr><td style='padding: 8px;'>Publication Year</td><td style='padding: 8px;'>" . $book['Publication_Year'] . "</td></tr>";
        echo "<tr><td style='padding: 8px;'>Description</td><td style='padding: 8px;'>" . $book['Description'] . "</td></tr>";
        echo "<tr><td style='padding: 8px;'>Status</td><td style='padding: 8px;'><span style='background: #28a745; color: white; padding: 2px 8px; border-radius: 3px;'>" . $book['Status'] . "</span></td></tr>";
        echo "<tr><td style='padding: 8px;'>Location</td><td style='padding: 8px;'>" . $book['Location'] . "</td></tr>";
        echo "<tr><td style='padding: 8px;'>Created At</td><td style='padding: 8px;'>" . $book['Created_At'] . "</td></tr>";
        echo "</table>";
    }
    
    echo "<h4>What's Now Available:</h4>";
    echo "<ul>";
    echo "<li>✅ <strong>Book Management</strong> - Add, edit, delete, and track books</li>";
    echo "<li>✅ <strong>Messages System</strong> - Send and receive messages between staff</li>";
    echo "<li>✅ <strong>Book Borrowing</strong> - Track book borrowing and returns</li>";
    echo "<li>✅ <strong>Teacher Profiles</strong> - Complete teacher information management</li>";
    echo "</ul>";
    
    echo "<h4>Access the Staff Portal:</h4>";
    echo "<p><a href='staff/library.php' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>📚 Library Management</a></p>";
    echo "<p><a href='staff/messages.php' style='background: #764ba2; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>📧 Messages</a></p>";
    echo "<p><a href='staff/dashboard.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>🏠 Staff Dashboard</a></p>";
    
} catch(Exception $e) {
    echo "<h3 style='color: red;'>Setup Failed!</h3>";
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    echo "<p style='color: red;'>Please check your database connection and try again.</p>";
}
?>
