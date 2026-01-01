<?php
require '../config/db.php';
require '../config/functions.php';

$user_id = $_SESSION['user_id'];

try {
    // UPDATED: Added middle_name, academic_rank, school_college
    $stmt = $pdo->prepare("SELECT user_id, username, first_name, middle_name, last_name, email, academic_rank, school_college, department, role_id FROM user WHERE user_id = ?");
    $stmt->execute([$user_id]);
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