<?php
// backend/audit/settings.php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/functions.php'; // For logAudit

// Handle GET (Read Setting)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->query("SELECT deletion_threshold FROM audit_log_deletion_threshold LIMIT 1");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // Default to 12 months if not set
        $threshold = $result ? $result['deletion_threshold'] : 12;
        echo json_encode(['status' => 'success', 'threshold' => $threshold]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}

// Handle POST (Update Setting)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (session_status() === PHP_SESSION_NONE) session_start();
    
    // Security: Only Principal (Role 1)
    if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }

    try {
        $months = (int)$_POST['threshold'];
        if ($months < 1) $months = 1; // Minimum 1 month

        // Update or Insert logic
        // We assume row ID 1 exists, otherwise INSERT
        $check = $pdo->query("SELECT COUNT(*) FROM audit_log_deletion_threshold");
        if ($check->fetchColumn() > 0) {
            $stmt = $pdo->prepare("UPDATE audit_log_deletion_threshold SET deletion_threshold = ?");
            $stmt->execute([$months]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO audit_log_deletion_threshold (deletion_threshold) VALUES (?)");
            $stmt->execute([$months]);
        }

        logAudit($_SESSION['user_id'], "UPDATED_AUDIT_SETTINGS", "Changed retention policy to $months months");

        echo json_encode(['status' => 'success', 'message' => 'Settings updated']);

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}
?>