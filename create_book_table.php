<?php
require_once 'config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    echo "<h2>Creating Book Table in magodi_private_school Database</h2>";
    
    // Create Book table
    $sql = "CREATE TABLE IF NOT EXISTS `Book` (
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
    
    $conn->exec($sql);
    echo "<p style='color: green;'>✓ Book table created successfully in magodi_private_school database</p>";
    
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
    
    // Insert additional sample books for testing
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
    
    // Verify the table and data
    $verify_sql = "SELECT COUNT(*) as book_count FROM Book";
    $stmt = $conn->prepare($verify_sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4 style='color: #155724; margin: 0 0 10px 0;'>🎉 Book Table Setup Complete!</h4>";
    echo "<p style='color: #155724; margin: 0;'>Total books in database: <strong>" . $result['book_count'] . "</strong></p>";
    echo "</div>";
    
    // Show the specific book that was added
    $show_sql = "SELECT * FROM Book WHERE Title = 'chichewa' AND Author = 'robert'";
    $stmt = $conn->prepare($show_sql);
    $stmt->execute();
    $book = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($book) {
        echo "<h4>Your Book Details:</h4>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Field</th><th>Value</th></tr>";
        echo "<tr><td>Book ID</td><td>" . $book['Book_ID'] . "</td></tr>";
        echo "<tr><td>Title</td><td>" . $book['Title'] . "</td></tr>";
        echo "<tr><td>Author</td><td>" . $book['Author'] . "</td></tr>";
        echo "<tr><td>ISBN</td><td>" . $book['ISBN'] . "</td></tr>";
        echo "<tr><td>Category</td><td>" . $book['Category'] . "</td></tr>";
        echo "<tr><td>Publisher</td><td>" . $book['Publisher'] . "</td></tr>";
        echo "<tr><td>Publication Year</td><td>" . $book['Publication_Year'] . "</td></tr>";
        echo "<tr><td>Description</td><td>" . $book['Description'] . "</td></tr>";
        echo "<tr><td>Status</td><td>" . $book['Status'] . "</td></tr>";
        echo "<tr><td>Location</td><td>" . $book['Location'] . "</td></tr>";
        echo "<tr><td>Created At</td><td>" . $book['Created_At'] . "</td></tr>";
        echo "</table>";
    }
    
    echo "<h4>Access the Staff Portal:</h4>";
    echo "<p><a href='staff/library.php' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Library Management</a></p>";
    echo "<p><a href='staff/dashboard.php' style='background: #764ba2; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Staff Dashboard</a></p>";
    
} catch(Exception $e) {
    echo "<h3 style='color: red;'>Setup Failed!</h3>";
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    echo "<p style='color: red;'>Please check your database connection and try again.</p>";
}
?>
