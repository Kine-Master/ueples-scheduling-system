<?php
require '../config/db.php';
require '../config/functions.php';

requireRole('principal'); 

$id = $_POST['user_id'];
$first = $_POST['first_name'];
$middle = $_POST['middle_name'] ?? '';
$last = $_POST['last_name'];
$email = $_POST['email'];
$dept = $_POST['department'];
$rank = $_POST['academic_rank'] ?? null;
$school = $_POST['school_college'] ?? null;
$role_id = $_POST['role_id']; // Principal can change roles

try {
    $sql = "UPDATE user SET first_name=?, middle_name=?, last_name=?, email=?, department=?, academic_rank=?, school_college=?, role_id=? WHERE user_id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$first, $middle, $last, $email, $dept, $rank, $school, $role_id, $id]);

    logAudit($_SESSION['user_id'], "UPDATED_USER", "Updated info for User ID: $id");

    echo json_encode(['status' => 'success', 'message' => 'User updated successfully.']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>