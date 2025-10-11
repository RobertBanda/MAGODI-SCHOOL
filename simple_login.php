<?php
session_start();

// Simple database connection
$host = "localhost";
$db_name = "magodi_private_school";
$username = "root";
$password = "";

$error = '';

if($_POST) {
    $login_username = $_POST['username'] ?? '';
    $login_password = $_POST['password'] ?? '';
    
    try {
        $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $query = "SELECT u.*, r.Role_Name FROM User u 
                  LEFT JOIN Role r ON u.Role_ID = r.Role_ID 
                  WHERE u.Username = ? AND u.Is_Active = 1";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(1, $login_username);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($login_password, $row['Password'])) {
                $_SESSION['user_id'] = $row['User_ID'];
                $_SESSION['username'] = $row['Username'];
                $_SESSION['role'] = $row['Role_Name'];
                $_SESSION['related_id'] = $row['Related_ID'];
                
                // Redirect based on role
                switch($row['Role_Name']) {
                    case 'Admin':
                        header("Location: admin/dashboard.php");
                        break;
                    case 'Teacher':
                        header("Location: staff/dashboard.php");
                        break;
                    case 'Student':
                        header("Location: student/dashboard.php");
                        break;
                    case 'Parent':
                        header("Location: parent/dashboard.php");
                        break;
                    default:
                        header("Location: index.php");
                }
                exit();
            } else {
                $error = 'Invalid password';
            }
        } else {
            $error = 'Invalid username';
        }
    } catch(Exception $e) {
        $error = 'Database connection failed: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Magodi Private School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
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
        .school-logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #667eea;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
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
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-card">
                    <div class="login-header">
                        <div class="school-logo">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3>Magodi Private School</h3>
                        <p class="mb-0">Area 23, Lilongwe, Malawi</p>
                    </div>
                    <div class="login-body">
                        <?php if($error): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <i class="fas fa-user me-2"></i>Username
                                </label>
                                <input type="text" class="form-control" id="username" name="username" value="admin" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <input type="password" class="form-control" id="password" name="password" value="admin123" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-login w-100">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </form>
                        
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Secure Login Portal
                            </small>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="quick_setup.php" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-cog me-1"></i>Setup System
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
