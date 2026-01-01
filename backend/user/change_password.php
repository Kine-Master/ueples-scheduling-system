<?php
require '../config/db.php';
require '../config/functions.php';

// 1. Check Session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$old_pass = $_POST['old_password'];
$new_pass = $_POST['new_password'];
$confirm_pass = $_POST['confirm_password'];

// 2. Validate Match
if ($new_pass !== $confirm_pass) {
    echo json_encode(['status' => 'error', 'message' => 'New passwords do not match.']);
    exit;
}

try {
    // 3. Verify Old Password
    // Note: Using 'password_hash' column based on your schema
    $stmt = $pdo->prepare("SELECT password_hash FROM user WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($old_pass, $user['password_hash'])) {
        echo json_encode(['status' => 'error', 'message' => 'Incorrect current password.']);
        exit;
    }

    // 4. Update Password
    $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
    
    $update = $pdo->prepare("UPDATE user SET password_hash = ? WHERE user_id = ?");
    $update->execute([$hashed, $user_id]);

    // --- AUDIT LOGGING ---
    logAudit($user_id, "Changed account password");

    echo json_encode(['status' => 'success', 'message' => 'Password changed successfully.']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>