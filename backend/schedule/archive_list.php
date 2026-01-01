<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

// 1. Capture Parameters
$teacher_id = $_GET['teacher_id'] ?? ''; // <--- NEW: Optional Filter
$search = $_GET['search'] ?? '';
$sort_by = $_GET['sort_by'] ?? 'date_created';
$order = strtoupper($_GET['order'] ?? 'DESC');

// Whitelist columns
$allowed_sorts = ['date_created', 'school_year', 'teacher_name', 'subject', 'schedule_type'];
if (!in_array($sort_by, $allowed_sorts)) {
    $sort_by = 'date_created';
}

try {
    // 2. Base Query (Always Active=0)
    $sql = "SELECT s.*, CONCAT(u.last_name, ', ', u.first_name) as teacher_name
            FROM schedule s
            JOIN user u ON s.teacher_id = u.user_id
            WHERE s.is_active = 0";

    $params = [];

    // 3. Optional Teacher Filter (Crucial for Teacher View)
    if (!empty($teacher_id)) {
        $sql .= " AND s.teacher_id = ?";
        $params[] = $teacher_id;
    }

    // 4. Dynamic Search
    if (!empty($search)) {
        $sql .= " AND (s.subject LIKE ? OR u.last_name LIKE ? OR u.first_name LIKE ?)";
        $term = "%$search%";
        $params[] = $term;
        $params[] = $term;
        $params[] = $term;
    }

    // 5. Dynamic Sorting
    $sql .= " ORDER BY $sort_by $order";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $archives = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'data' => $archives]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>