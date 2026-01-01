<?php
// 1. Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Include DB
require_once 'db.php'; 

// 3. CSRF Token Generator
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// 4. CSRF Token Verifier
function verifyCsrfToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        die("Security Error: Invalid Token. Please refresh the page.");
    }
}

// 5. Output Sanitizer
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// 6. Role Check (Gatekeeper)
function requireRole($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        header("Location: ../../frontend/login/index.php?error=Unauthorized");
        exit;
    }
}

// 7. AUDIT LOGGING FUNCTION
function logAudit($userId, $action) {
    global $pdo; // Use the database connection from db.php

    try {
        // Capture IP Address (Source 167)
        $ip = $_SERVER['REMOTE_ADDR'];

        // Insert into audit_log table (Source 166)
        $sql = "INSERT INTO audit_log (user_id, user_action, ip_address) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $action, $ip]);

    } catch (PDOException $e) {
        // Silently fail so we don't crash the app if logging fails
        error_log("Audit Log Error: " . $e->getMessage());
    }
}

?>