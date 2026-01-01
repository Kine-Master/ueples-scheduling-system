<?php
// backend/audit/get_actions.php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

try {
    // Get unique actions for the filter dropdown
    $stmt = $pdo->query("SELECT DISTINCT user_action FROM audit_log ORDER BY user_action ASC");
    $actions = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode(['status' => 'success', 'data' => $actions]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>