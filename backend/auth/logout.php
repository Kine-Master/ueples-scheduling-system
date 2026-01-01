<?php
session_start();

// 1. Include necessary files for Database and Audit function
// Adjust paths if your directory structure differs, but based on context:
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/functions.php';

// 2. Check if a user is actually logged in before logging
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $username = $_SESSION['username'] ?? 'Unknown';

    // 3. Log the Audit Entry
    // We use a try-catch block to ensure that if the database fails, 
    // the user is STILL logged out successfully (security first).
    try {
        logAudit($userId, 'User Logged Out', "User $username logged out.");
    } catch (Exception $e) {
        // Optionally log to a file if DB logging fails, 
        // but don't stop the logout process.
        error_log("Audit Error on Logout: " . $e->getMessage());
    }
}

// 4. Destroy the session (The original logic)
session_unset();
session_destroy();

// 5. Redirect
header("Location: ../../frontend/login/index.php");
exit;
?>