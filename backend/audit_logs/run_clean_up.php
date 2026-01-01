<?php
// backend/audit/run_cleanup.php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;

if (session_status() === PHP_SESSION_NONE) session_start();

// Security: Only Principal
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

try {
    // 1. Get Threshold
    $stmt = $pdo->query("SELECT deletion_threshold FROM audit_log_deletion_threshold LIMIT 1");
    $row = $stmt->fetch();
    $months = $row ? (int)$row['deletion_threshold'] : 12; // Default 12

    // 2. Perform Deletion
    // Logic: Delete logs OLDER than X months
    $sql = "DELETE FROM audit_log WHERE timestamp < DATE_SUB(NOW(), INTERVAL ? MONTH)";
    $del = $pdo->prepare($sql);
    $del->execute([$months]);
    
    $deletedCount = $del->rowCount();

    // 3. Log the cleanup action (ironic, but necessary)
    logAudit($_SESSION['user_id'], "SYSTEM_CLEANUP", "Manually purged $deletedCount logs older than $months months");

    echo json_encode([
        'status' => 'success', 
        'message' => "Cleanup complete. Removed $deletedCount old records."
    ]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>