<?php require '../../backend/config/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UEP Scheduling System</title>
    
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="login-wrapper">
        <div class="login-container">
            
            <div class="login-header">
                <img src="../assets/images/uep-logo.png" alt="UEP Logo" class="school-logo">
                
                <h2>UEP LES Scheduling System</h2>
                <p>Laboratory Elementary School</p>
            </div>

            <?php if(isset($_GET['error'])): ?>
                <div class="alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i> <?= e($_GET['error']) ?>
                </div>
            <?php endif; ?>

            <form action="../../backend/auth/login.php" method="POST" id="loginForm">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

                <div class="form-group">
                    <label for="username">Username / ID</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-solid fa-user input-icon"></i>
                        <input type="text" id="username" name="username" placeholder="Enter your ID" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <i class="fa-solid fa-eye toggle-password" id="toggleBtn"></i>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    Sign In <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </form>

            <div class="login-footer">
                <small>&copy; <?= date('Y') ?> UEP Laboratory Elementary School</small>
            </div>
        </div>
    </div>

    <script src="script.js"></script>

</body>
</html>