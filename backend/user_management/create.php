<?php
require '../config/db.php';
require '../config/functions.php';

requireRole('principal'); 

// Capture Inputs
$role_id = $_POST['role_id'];
$username = $_POST['username'];
$first = $_POST['first_name'];
$middle = $_POST['middle_name'] ?? '';
$last = $_POST['last_name'];
$email = $_POST['email'];
$dept = $_POST['department'];
// Teacher Specifics
$rank = $_POST['academic_rank'] ?? null;
$school = $_POST['school_college'] ?? null;

$default_pass = "123456"; 

try {
    // 1. Duplicate Check
    $check = $pdo->prepare("SELECT user_id FROM user WHERE username = ?");
    $check->execute([$username]);
    if ($check->rowCount() > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Username already taken.']);
        exit;
    }

    // 2. Insert
    $hash = password_hash($default_pass, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO user (role_id, username, password_hash, first_name, middle_name, last_name, email, department, academic_rank, school_college, is_active, date_created) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$role_id, $username, $hash, $first, $middle, $last, $email, $dept, $rank, $school]);

    // 3. Audit
    $new_id = $pdo->lastInsertId();
    logAudit($_SESSION['user_id'], "CREATED_USER", "Created user: $username ($first $last)");

    echo json_encode(['status' => 'success', 'message' => 'User created successfully.']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>