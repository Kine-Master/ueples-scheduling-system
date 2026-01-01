<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

try {
    // Fetch users who are Teachers (3) or Secretaries (2)
    // We select specific columns to keep the payload light
    $sql = "SELECT user_id, first_name, last_name, department, academic_rank 
            FROM user 
            WHERE role_id IN (2, 3) AND is_active = 1 
            ORDER BY last_name ASC";
            
    $stmt = $pdo->query($sql);
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'data' => $teachers]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>