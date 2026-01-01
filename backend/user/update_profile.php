<?php
require '../config/db.php';
require '../config/functions.php';

// 1. Check Session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

// 2. Capture Inputs
$first_name = $_POST['first_name'];
$middle_name = $_POST['middle_name'] ?? '';
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$username = $_POST['username'];

// Professional Details
$rank = $_POST['academic_rank'] ?? null;
$school = $_POST['school_college'] ?? null;
$dept = $_POST['department'] ?? null;

try {
    // 3. Check for Duplicate Username
    $check = $pdo->prepare("SELECT user_id FROM user WHERE username = ? AND user_id != ?");
    $check->execute([$username, $user_id]);
    if ($check->rowCount() > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Username already taken.']);
        exit;
    }

    // 4. Update Database
    $sql = "UPDATE user SET 
            first_name=?, middle_name=?, last_name=?, email=?, username=?, 
            academic_rank=?, school_college=?, department=? 
            WHERE user_id=?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$first_name, $middle_name, $last_name, $email, $username, $rank, $school, $dept, $user_id]);

    // 5. Update Session Name Immediately
    $_SESSION['full_name'] = $first_name . ' ' . $last_name;

    // --- AUDIT LOGGING ---
    // We use your existing function here.
    logAudit($user_id, "Updated profile information");

    echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>