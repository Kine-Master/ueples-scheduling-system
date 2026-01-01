<?php
require '../config/functions.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Security Check
    if (!isset($_POST['csrf_token'])) {
        die("Security Token Missing");
    }
    verifyCsrfToken($_POST['csrf_token']);

    // 2. Get Data
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // 3. Database Lookup
    $sql = "SELECT u.user_id, u.username, u.password_hash, u.first_name, u.last_name, r.role_name 
            FROM user u 
            JOIN role r ON u.role_id = r.role_id 
            WHERE u.username = ? AND u.is_active = 1";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch();
    } catch (PDOException $e) {
        die("Database Error");
    }

    // 4. Verify Password & Redirect
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = strtolower($user['role_name']);
        $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];

        // Redirect based on role
        if ($_SESSION['role'] === 'principal') {
            header("Location: ../../frontend/principal/dashboard/index.php");
        } elseif ($_SESSION['role'] === 'secretary') {
            header("Location: ../../frontend/secretary/dashboard/index.php");
        } elseif ($_SESSION['role'] === 'teacher') {
            header("Location: ../../frontend/teacher/dashboard/index.php");
        }
        
        // --- NEW: AUDIT LOG ENTRY ---
        logAudit($user['user_id'], 'User Logged In'); 
        // ----------------------------
        
        exit;
    } else {
        header("Location: ../../frontend/login/index.php?error=Invalid Credentials");
        exit;
    }
}
?>