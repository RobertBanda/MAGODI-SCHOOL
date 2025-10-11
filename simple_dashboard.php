<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: simple_login.php");
    exit();
}

// Simple database connection
$host = "localhost";
$db_name = "magodi_private_school";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get user info
    $query = "SELECT u.*, r.Role_Name FROM User u 
              LEFT JOIN Role r ON u.Role_ID = r.Role_ID 
              WHERE u.User_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(1, $_SESSION['user_id']);
    $stmt->execute();
    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get school info
    $query = "SELECT * FROM School_Info LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $school_info = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get statistics
    $stats = [];
    
    // Count students
    $query = "SELECT COUNT(*) as count FROM Student WHERE Status = 'Active'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $stats['students'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Count teachers
    $query = "SELECT COUNT(*) as count FROM Teacher WHERE Is_Active = 1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $stats['teachers'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Count classes
    $query = "SELECT COUNT(*) as count FROM Class";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $stats['classes'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Count subjects
    $query = "SELECT COUNT(*) as count FROM Subject";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $stats['subjects'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
} catch(Exception $e) {
    $error = "Database connection failed: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Magodi Private School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 0;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <div class="p-3">
                        <h4 class="text-white mb-0">
                            <i class="fas fa-graduation-cap me-2"></i>
                            Magodi School
                        </h4>
                        <small class="text-white-50"><?php echo $user_info['Role_Name']; ?> Panel</small>
                    </div>
                    
                    <nav class="nav flex-column p-3">
                        <a class="nav-link active" href="simple_dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="admin/students.php">
                            <i class="fas fa-users me-2"></i>Students
                        </a>
                        <a class="nav-link" href="admin/teachers.php">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Teachers
                        </a>
                        <a class="nav-link" href="admin/classes.php">
                            <i class="fas fa-school me-2"></i>Classes
                        </a>
                        <a class="nav-link" href="admin/subjects.php">
                            <i class="fas fa-book me-2"></i>Subjects
                        </a>
                        <a class="nav-link" href="admin/attendance.php">
                            <i class="fas fa-calendar-check me-2"></i>Attendance
                        </a>
                        <a class="nav-link" href="admin/exams.php">
                            <i class="fas fa-clipboard-list me-2"></i>Exams
                        </a>
                        <a class="nav-link" href="admin/fees.php">
                            <i class="fas fa-dollar-sign me-2"></i>Fees
                        </a>
                        <a class="nav-link" href="admin/library.php">
                            <i class="fas fa-book-open me-2"></i>Library
                        </a>
                        <a class="nav-link" href="admin/guardians.php">
                            <i class="fas fa-user-friends me-2"></i>Guardians
                        </a>
                        <a class="nav-link" href="admin/reports.php">
                            <i class="fas fa-chart-bar me-2"></i>Reports
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content p-4">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="h3 mb-0">Welcome to Magodi Private School</h2>
                            <p class="text-muted">Hello, <?php echo $user_info['Username']; ?>! (<?php echo $user_info['Role_Name']; ?>)</p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-2"></i><?php echo $user_info['Username']; ?>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="simple_logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- School Info -->
                    <div class="alert alert-info mb-4">
                        <h5><i class="fas fa-school me-2"></i><?php echo $school_info['School_Name']; ?></h5>
                        <p class="mb-1"><strong>Address:</strong> <?php echo $school_info['Address']; ?></p>
                        <p class="mb-1"><strong>Phone:</strong> <?php echo $school_info['Phone']; ?></p>
                        <p class="mb-1"><strong>Email:</strong> <?php echo $school_info['Email']; ?></p>
                        <p class="mb-0"><strong>Motto:</strong> <?php echo $school_info['Motto']; ?></p>
                    </div>
                    
                    <!-- Statistics Cards -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="stat-card p-4">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon bg-primary text-white me-3">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div>
                                        <h3 class="h4 mb-0"><?php echo $stats['students']; ?></h3>
                                        <p class="text-muted mb-0">Total Students</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="stat-card p-4">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon bg-success text-white me-3">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <div>
                                        <h3 class="h4 mb-0"><?php echo $stats['teachers']; ?></h3>
                                        <p class="text-muted mb-0">Teachers</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="stat-card p-4">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon bg-warning text-white me-3">
                                        <i class="fas fa-school"></i>
                                    </div>
                                    <div>
                                        <h3 class="h4 mb-0"><?php echo $stats['classes']; ?></h3>
                                        <p class="text-muted mb-0">Classes</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="stat-card p-4">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon bg-info text-white me-3">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div>
                                        <h3 class="h4 mb-0"><?php echo $stats['subjects']; ?></h3>
                                        <p class="text-muted mb-0">Subjects</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="stat-card p-4">
                                <h5 class="mb-3">Quick Actions</h5>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <a href="admin/students.php" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-users me-2"></i>Manage Students
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="admin/teachers.php" class="btn btn-outline-success w-100">
                                            <i class="fas fa-chalkboard-teacher me-2"></i>Manage Teachers
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="admin/subjects.php" class="btn btn-outline-info w-100">
                                            <i class="fas fa-book me-2"></i>Manage Subjects
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="admin/classes.php" class="btn btn-outline-warning w-100">
                                            <i class="fas fa-school me-2"></i>Manage Classes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
