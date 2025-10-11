<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized Access - Magodi Private School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .error-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .error-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            border-radius: 50%;
            margin: 0 auto 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="error-card text-center p-5">
                    <div class="error-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h1 class="display-4 fw-bold text-danger mb-3">403</h1>
                    <h2 class="h3 mb-4">Access Denied</h2>
                    <p class="lead text-muted mb-4">
                        You don't have permission to access this resource. 
                        Please contact your administrator if you believe this is an error.
                    </p>
                    <div class="d-grid gap-2 d-md-block">
                        <a href="index.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-home me-2"></i>Go Home
                        </a>
                        <a href="login.php" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Login Again
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
