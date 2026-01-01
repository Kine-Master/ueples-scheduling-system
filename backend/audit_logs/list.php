<?php
// backend/audit/list.php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

// 1. CAPTURE FILTERS
$search = $_GET['search'] ?? '';         // General search (Name/Action)
$action_filter = $_GET['action'] ?? '';  // Specific dropdown filter
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

// Pagination (Default to 50 for speed, unless 'all' is requested for Reports)
$limit = isset($_GET['limit']) && $_GET['limit'] === 'all' ? 100000 : 50;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

try {
    // 2. BUILD QUERY
    // We join 'user' to get the actual name of the person who did the action
    $sql = "SELECT a.log_id, a.user_action, a.timestamp, a.ip_address,
                   u.first_name, u.last_name, u.role_id, u.username
            FROM audit_log a
            LEFT JOIN user u ON a.user_id = u.user_id
            WHERE 1=1";

    $params = [];

    // --- A. SEARCH LOGIC (Fixes the 'Login' issue) ---
    if (!empty($search)) {
        // Allows searching by Name OR the Action string
        $sql .= " AND (
            u.first_name LIKE ? OR 
            u.last_name LIKE ? OR 
            a.user_action LIKE ? 
        )";
        $term = "%$search%";
        $params[] = $term; 
        $params[] = $term; 
        $params[] = $term;
    }

    // --- B. ACTION CATEGORY FILTER ---
    if (!empty($action_filter)) {
        // Strict filter for dropdowns
        $sql .= " AND a.user_action = ?";
        $params[] = $action_filter;
    }

    // --- C. DATE RANGE FILTER (For Reports) ---
    if (!empty($start_date)) {
        $sql .= " AND a.timestamp >= ?";
        $params[] = "$start_date 00:00:00";
    }
    if (!empty($end_date)) {
        $sql .= " AND a.timestamp <= ?";
        $params[] = "$end_date 23:59:59";
    }

    // --- D. ORDERING ---
    $sql .= " ORDER BY a.timestamp DESC";

    // --- E. PAGINATION ---
    // Only apply limit if we aren't generating a full report
    if ($limit !== 100000) {
        $sql .= " LIMIT $limit OFFSET $offset";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'data' => $logs]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>