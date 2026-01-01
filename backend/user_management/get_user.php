<?php
require '../config/db.php';
require '../config/functions.php';

requireRole('principal'); 

if (!isset($_GET['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User ID required']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT user_id, first_name, middle_name, last_name, email, username, 
                           role_id, department, academic_rank, school_college, is_active 
                           FROM user WHERE user_id = ?");
    $stmt->execute([$_GET['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode(['status' => 'success', 'data' => $user]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>