<?php
require '../config/db.php';
require '../config/functions.php';

requireRole('principal'); 

$id = $_POST['user_id'];
$status = $_POST['is_active']; // The NEW status to set (1 or 0)

try {
    $sql = "UPDATE user SET is_active = ? WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$status, $id]);

    $action = ($status == 1) ? "ACTIVATED" : "DEACTIVATED";
    logAudit($_SESSION['user_id'], $action . "_USER", "$action User ID: $id");

    echo json_encode(['status' => 'success', 'message' => "User status updated."]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>