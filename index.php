<?php
require_once 'includes/auth.php';
require_once 'includes/school_branding.php';

$auth = new Auth();
$isLoggedIn = $auth->isLoggedIn();
$branding = new SchoolBranding();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $branding->getSchoolName(); ?> - School Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }
        .school-logo {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 50%;
            margin: 0 auto 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #667eea;
        }
        .portal-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: none;
        }
        .portal-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        .portal-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1rem;
        }
        .admin-icon { background: linear-gradient(135deg, #ff6b6b, #ee5a24); }
        .teacher-icon { background: linear-gradient(135deg, #4ecdc4, #44a08d); }
        .student-icon { background: linear-gradient(135deg, #45b7d1, #96c93d); }
        .parent-icon { background: linear-gradient(135deg, #f093fb, #f5576c); }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-graduation-cap me-2"></i>
                <?php echo $branding->getSchoolName(); ?>
            </a>
            <div class="navbar-nav ms-auto">
                <?php if($isLoggedIn): ?>
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </a>
                <?php else: ?>
                    <a class="nav-link" href="login.php">
                        <i class="fas fa-sign-in-alt me-1"></i>Login
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <div class="school-logo">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h1 class="display-4 fw-bold mb-4"><?php echo $branding->getSchoolName(); ?></h1>
            <p class="lead mb-4"><?php echo $branding->getSchoolMotto(); ?></p>
            <p class="mb-5"><?php echo $branding->getSchoolAddress(); ?></p>
            
            <?php if(!$isLoggedIn): ?>
                <a href="login.php" class="btn btn-light btn-lg px-5 py-3">
                    <i class="fas fa-sign-in-alt me-2"></i>Access Portal
                </a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-5 fw-bold">School Management System</h2>
                    <p class="lead text-muted">Comprehensive digital solution for modern school management</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-users fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Student Management</h5>
                            <p class="card-text">Complete student records, attendance tracking, and academic progress monitoring.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-chalkboard-teacher fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Staff Management</h5>
                            <p class="card-text">Teacher and staff records, payroll management, and performance tracking.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-chart-line fa-3x text-warning mb-3"></i>
                            <h5 class="card-title">Academic Management</h5>
                            <p class="card-text">Examinations, results, timetables, and comprehensive academic reporting.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-dollar-sign fa-3x text-info mb-3"></i>
                            <h5 class="card-title">Financial Management</h5>
                            <p class="card-text">Fee collection, payment tracking, and comprehensive financial reporting.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-book fa-3x text-danger mb-3"></i>
                            <h5 class="card-title">Library Management</h5>
                            <p class="card-text">Book catalog, issue tracking, and library resource management.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-comments fa-3x text-secondary mb-3"></i>
                            <h5 class="card-title">Communication</h5>
                            <p class="card-text">Parent-teacher communication, notifications, and messaging system.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portal Access Section -->
    <?php if($isLoggedIn): ?>
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-5 fw-bold">Access Your Portal</h2>
                    <p class="lead text-muted">Choose your role to access the appropriate dashboard</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card portal-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="portal-icon admin-icon text-white">
                                <i class="fas fa-crown"></i>
                            </div>
                            <h5 class="card-title">Admin Portal</h5>
                            <p class="card-text">Complete system administration and management.</p>
                            <a href="admin/dashboard.php" class="btn btn-outline-primary">Access</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card portal-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="portal-icon teacher-icon text-white">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <h5 class="card-title">Staff Portal</h5>
                            <p class="card-text">Teaching staff dashboard and tools.</p>
                            <a href="staff/dashboard.php" class="btn btn-outline-success">Access</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card portal-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="portal-icon student-icon text-white">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <h5 class="card-title">Student Portal</h5>
                            <p class="card-text">Student dashboard and academic information.</p>
                            <a href="student/dashboard.php" class="btn btn-outline-info">Access</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card portal-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="portal-icon parent-icon text-white">
                                <i class="fas fa-user-friends"></i>
                            </div>
                            <h5 class="card-title">Parent Portal</h5>
                            <p class="card-text">Parent access to student information.</p>
                            <a href="parent/dashboard.php" class="btn btn-outline-warning">Access</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><?php echo $branding->getSchoolName(); ?></h5>
                    <p><?php echo $branding->getSchoolAddress(); ?></p>
                    <p><?php echo $branding->getSchoolMotto(); ?></p>
                    <div class="mt-3">
                        <?php $branding->renderSocialLinks(); ?>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; <?php echo date('Y'); ?> Magodi Private School. All rights reserved.</p>
                    <p>School Management System v1.0</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
