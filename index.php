<?php
session_start();

// Redirect to admission page if already logged in
if(isset($_SESSION['username']) || isset($_SESSION['id'])){
    header("Location: admission.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MKCE | ADMISSION</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* MKCE Admission Portal - Login Design */
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Inter', sans-serif; 
        }

        :root {
            --primary-color: #667eea;
            --primary-dark: #5a67d8;
            --secondary-color: #764ba2;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --border-radius: 16px;
            --shadow-light: 0 4px 20px rgba(0,0,0,0.08);
            --shadow-medium: 0 8px 30px rgba(0,0,0,0.12);
        }

        body, html { 
            height: 100%; 
            width: 100%; 
            background: var(--light-color);
            overflow-x: hidden;
        }

        .container { 
            display: flex; 
            height: 100vh; 
        }

        .left-panel { 
            flex: 1; 
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            align-items: center; 
            color: white; 
            text-align: center; 
            padding: 40px; 
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .left-panel h1 { 
            font-size: 60px; 
            font-weight: 700; 
            margin-bottom: 10px; 
            position: relative;
            z-index: 2;
        }

        .left-panel p { 
            font-size: 20px; 
            font-weight: 500; 
            margin-top: 0; 
            opacity: 0.9; 
            position: relative;
            z-index: 2;
        }

        .left-panel .features {
            margin-top: 40px;
            position: relative;
            z-index: 2;
        }

        .feature-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin: 12px 0;
            font-size: 16px;
            opacity: 0.9;
        }

        .feature-item i {
            font-size: 18px;
        }

        .right-panel { 
            flex: 1; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            background: var(--light-color); 
        }

        .login-container { 
            background: white; 
            padding: 50px 40px; 
            border-radius: var(--border-radius); 
            box-shadow: var(--shadow-medium); 
            width: 400px; 
            text-align: center; 
            transition: all 0.3s ease; 
        }

        .login-container:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .login-header {
            margin-bottom: 30px;
        }

        .login-header h2 { 
            color: var(--dark-color); 
            font-weight: 600; 
            font-size: 28px; 
            margin-bottom: 8px;
        }

        .login-header p {
            color: #64748b;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 8px;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 16px;
        }

        .form-control { 
            width: 100%; 
            padding: 14px 16px 14px 40px; 
            border: 2px solid #e2e8f0; 
            border-radius: 12px; 
            outline: none; 
            font-size: 16px; 
            transition: all 0.3s ease; 
            background: white;
        }

        .form-control:focus { 
            border-color: var(--primary-color); 
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); 
        }

        .btn-primary { 
            width: 100%; 
            padding: 14px; 
            margin-top: 20px; 
            border: none; 
            border-radius: 12px; 
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); 
            color: white; 
            font-size: 16px; 
            font-weight: 600; 
            cursor: pointer; 
            transition: all 0.3s ease; 
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover { 
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary .loading {
            display: none;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .btn-primary.loading .btn-text {
            opacity: 0;
        }

        .btn-primary.loading .loading {
            display: block;
        }

        .forgot-link { 
            display: block; 
            margin-top: 15px; 
            font-size: 14px; 
            color: #64748b; 
            text-decoration: none; 
            transition: 0.3s; 
        }

        .forgot-link:hover { 
            color: var(--primary-color); 
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top: 2px solid white;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media(max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .left-panel, .right-panel {
                flex: none;
                width: 100%;
            }

            .left-panel {
                height: 40vh;
                padding: 20px;
            }

            .right-panel {
                height: 60vh;
            }
            
            .left-panel h1 {
                font-size: 48px;
            }
            
            .left-panel p {
                font-size: 18px;
            }

            .features {
                display: none;
            }
            
            .login-container {
                width: 90%;
                padding: 30px 20px;
                margin: 20px;
            }
        }

        @media(max-width: 480px) {
            .left-panel h1 {
                font-size: 36px;
            }
            
            .login-container {
                padding: 20px 15px;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <h1>MKCE</h1>
            <p>Admission Portal</p>
            <div class="features">
                <div class="feature-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>Secure Access</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-users"></i>
                    <span>Student Management</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Analytics Dashboard</span>
                </div>
            </div>
        </div>
        <div class="right-panel">
            <div class="login-container">
                <div class="login-header">
                    <h2>Welcome Back</h2>
                    <p>Please sign in to your account</p>
                </div>
                
                <div id="alertContainer"></div>
                
                <form id="loginForm">
                    <div class="form-group">
                        <label for="facultyId">Faculty ID</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user"></i>
                            <input type="text" id="facultyId" name="id" class="form-control" placeholder="Enter your faculty ID" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="pass" class="form-control" placeholder="Enter your password" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-primary">
                        <span class="btn-text">Sign In</span>
                        <div class="loading">
                            <div class="loading-spinner"></div>
                        </div>
                    </button>
                    
                    <a href="#" class="forgot-link">Forgot your password?</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Handle form submission
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                handleLogin();
            });

            // Handle forgot password
            $('.forgot-link').on('click', function(e) {
                e.preventDefault();
                showAlert('info', 'Please contact your administrator for password recovery.');
            });

            // Auto-focus on first input
            $('#facultyId').focus();
        });

        function handleLogin() {
            const submitBtn = $('.btn-primary');
            const formData = new FormData($('#loginForm')[0]);

            // Validate inputs
            const facultyId = $('#facultyId').val().trim();
            const password = $('#password').val().trim();

            if (!facultyId || !password) {
                showAlert('error', 'Please enter both Faculty ID and Password.');
                return;
            }

            // Show loading state
            submitBtn.addClass('loading');
            clearAlerts();

            // Send login request
            $.ajax({
                url: 'api.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        showAlert('success', 'Login successful! Redirecting...');
                        setTimeout(() => {
                            window.location.href = 'admission.php';
                        }, 1000);
                    } else {
                        showAlert('error', response.message || 'Login failed. Please try again.');
                        submitBtn.removeClass('loading');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Login error:', error);
                    showAlert('error', 'Network error. Please try again.');
                    submitBtn.removeClass('loading');
                }
            });
        }

        function showAlert(type, message) {
            const alertClass = type === 'error' ? 'alert-error' : 
                             type === 'success' ? 'alert-success' : 'alert-info';
            const alertHtml = `<div class="alert ${alertClass}">${message}</div>`;
            $('#alertContainer').html(alertHtml);
        }

        function clearAlerts() {
            $('#alertContainer').empty();
        }
    </script>
</body>
</html>