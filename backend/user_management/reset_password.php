<?php
require '../config/db.php';
require '../config/functions.php';

requireRole('principal'); 

$id = $_POST['user_id'];
$default_pass = "password123";

try {
    // 1. Get Username for Logging
    $uStmt = $pdo->prepare("SELECT username FROM user WHERE user_id = ?");
    $uStmt->execute([$id]);
    $uName = $uStmt->fetchColumn();

    // 2. Reset
    $hash = password_hash($default_pass, PASSWORD_DEFAULT);
    $sql = "UPDATE user SET password_hash = ? WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$hash, $id]);

    logAudit($_SESSION['user_id'], "RESET_PASSWORD", "Reset password for user: $uName");

    echo json_encode(['status' => 'success', 'message' => "Reset $uName's password to default '$default_pass'."]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>