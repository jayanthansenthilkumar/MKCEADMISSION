<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MKCE - Admission Portal</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container">
    <div class="left-panel">
        <h1>MKCE</h1>
        <p>Admission Portal</p>
    </div>
    <div class="right-panel">
        <div class="login-container">
            <h2>Login</h2>
            <form id="loginForm">
                <input type="text" name="id" placeholder="Faculty ID" required>
                <input type="password" name="pass" placeholder="Password" required>
                <button type="submit">Login</button>
                <a href="#" class="forgot">Forgot password</a>
            </form>
        </div>
    </div>
</div>

<script src="assets/js/main.js"></script>
</body>
</html>