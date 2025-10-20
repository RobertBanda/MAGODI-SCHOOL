<?php
require_once 'includes/scalable_auth.php';
require_once 'includes/school_branding.php';

$auth = new ScalableAuth();
$branding = new SchoolBranding();

$error = '';
$success = '';

// Handle login form submission
if ($_POST) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember_me = isset($_POST['remember_me']);
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Validate CSRF token
    if (!$auth->validateCSRF($csrf_token)) {
        $error = 'Invalid security token. Please try again.';
    } else {
        // Attempt authentication
        $result = $auth->authenticate($username, $password, $remember_me);
        
        if ($result['success']) {
            // Redirect based on role
            $role = $result['user']['Role_Name'];
            switch ($role) {
                case 'Super Admin':
                case 'Admin':
                    header('Location: admin/dashboard.php');
                    break;
                case 'Teacher':
                    header('Location: staff/dashboard.php');
                    break;
                case 'Student':
                    header('Location: student/dashboard.php');
                    break;
                case 'Parent':
                    header('Location: parent/dashboard.php');
                    break;
                case 'Librarian':
                    header('Location: library/dashboard.php');
                    break;
                default:
                    header('Location: dashboard.php');
            }
            exit();
        } else {
            $error = $result['error'];
        }
    }
}

// Generate CSRF token
session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo $branding->getSchoolName(); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 400px;
            width: 90%;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .login-body {
            padding: 2rem;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .school-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 1rem;
            object-fit: cover;
        }
        .role-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .role-badge {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.8rem;
        }
        .stats-container {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
        }
        .stat-item {
            text-align: center;
            color: white;
        }
        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .stat-label {
            font-size: 0.8rem;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <?php if ($branding->getSchoolLogo()): ?>
                <img src="<?php echo $branding->getSchoolLogo(); ?>" alt="School Logo" class="school-logo">
            <?php endif; ?>
            <h3 class="mb-2"><?php echo $branding->getSchoolName(); ?></h3>
            <p class="mb-0 opacity-75"><?php echo $branding->getSchoolMotto(); ?></p>
            
            <div class="role-badges">
                <span class="role-badge">Admin</span>
                <span class="role-badge">Teacher</span>
                <span class="role-badge">Student</span>
                <span class="role-badge">Parent</span>
                <span class="role-badge">Librarian</span>
            </div>
            
            <?php
            // Get system statistics
            $stats = $auth->getUserStats();
            ?>
            <div class="stats-container">
                <div class="row">
                    <div class="col-4">
                        <div class="stat-item">
                            <div class="stat-number"><?php echo number_format($stats['total_users']); ?></div>
                            <div class="stat-label">Users</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-item">
                            <div class="stat-number"><?php echo number_format($stats['active_sessions']); ?></div>
                            <div class="stat-label">Active</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-item">
                            <div class="stat-number"><?php echo number_format($stats['failed_attempts_24h']); ?></div>
                            <div class="stat-label">Failed</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="login-body">
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <div class="mb-3">
                    <label for="username" class="form-label">
                        <i class="fas fa-user me-2"></i>Username
                    </label>
                    <input type="text" class="form-control" id="username" name="username" 
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" 
                           required autofocus>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-2"></i>Password
                    </label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                    <label class="form-check-label" for="remember_me">
                        Remember me for 30 days
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary btn-login w-100">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>
            </form>
            
            <div class="text-center mt-3">
                <small class="text-muted">
                    <a href="forgot_password.php" class="text-decoration-none">Forgot Password?</a>
                </small>
            </div>
            
            <hr class="my-4">
            
            <div class="text-center">
                <h6 class="text-muted mb-3">System Capabilities</h6>
                <div class="row">
                    <div class="col-6">
                        <div class="text-center">
                            <i class="fas fa-users text-primary mb-2" style="font-size: 1.5rem;"></i>
                            <div class="small text-muted">1M+ Users</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <i class="fas fa-shield-alt text-success mb-2" style="font-size: 1.5rem;"></i>
                            <div class="small text-muted">Secure</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-focus on username field
        document.getElementById('username').focus();
        
        // Show loading state on form submission
        document.querySelector('form').addEventListener('submit', function() {
            const btn = document.querySelector('.btn-login');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Logging in...';
            btn.disabled = true;
        });
        
        // Real-time statistics update (every 30 seconds)
        setInterval(function() {
            fetch('api/system_stats.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector('.stat-number').textContent = data.stats.total_users.toLocaleString();
                        document.querySelectorAll('.stat-number')[1].textContent = data.stats.active_sessions.toLocaleString();
                        document.querySelectorAll('.stat-number')[2].textContent = data.stats.failed_attempts_24h.toLocaleString();
                    }
                })
                .catch(error => console.log('Stats update failed'));
        }, 30000);
    </script>
</body>
</html>
