<?php
require '../config/db.php';
require '../config/functions.php';

requireRole('principal'); 

// 1. CAPTURE PARAMETERS
$search = $_GET['search'] ?? '';
$role_filter = $_GET['role'] ?? ''; // Filter by Role (Teacher/Secretary)
$sort_by = $_GET['sort'] ?? 'last_name';
$order = strtoupper($_GET['order'] ?? 'ASC');

// Security: Whitelist sort columns
$allowed_sorts = ['last_name', 'first_name', 'username', 'department', 'role_name', 'is_active'];
if (!in_array($sort_by, $allowed_sorts)) $sort_by = 'last_name';

try {
    // 2. BUILD QUERY
    $sql = "SELECT u.user_id, u.username, u.first_name, u.last_name, u.email, 
                   u.department, u.is_active, u.role_id, r.role_name
            FROM user u
            JOIN role r ON u.role_id = r.role_id
            WHERE 1=1"; // Base condition

    $params = [];

    // Apply Search (Name or Username)
    if (!empty($search)) {
        $sql .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR u.username LIKE ?)";
        $term = "%$search%";
        $params[] = $term; $params[] = $term; $params[] = $term;
    }

    // Apply Role Filter
    if (!empty($role_filter)) {
        $sql .= " AND u.role_id = ?";
        $params[] = $role_filter;
    }

    // Apply Sorting
    $sql .= " ORDER BY $sort_by $order";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'data' => $users]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>