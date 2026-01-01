<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;

try {
    $id = $_POST['schedule_id'];

    // 1. Fetch details before deleting (for the log)
    $stmt = $pdo->prepare("SELECT subject, teacher_id FROM schedule WHERE schedule_id = ?");
    $stmt->execute([$id]);
    $sched = $stmt->fetch();

    if (!$sched) {
        echo json_encode(['status' => 'error', 'message' => 'Schedule not found']);
        exit;
    }

    // 2. Soft Delete (Set is_active = 0)
    $update = $pdo->prepare("UPDATE schedule SET is_active = 0 WHERE schedule_id = ?");
    $update->execute([$id]);

    // 3. Audit Log
    session_start();
    logAudit($_SESSION['user_id'], "Removed/Deactivated schedule ID: $id (Subject: {$sched['subject']})");

    echo json_encode(['status' => 'success']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>