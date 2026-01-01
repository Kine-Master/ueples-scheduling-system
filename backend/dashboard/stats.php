<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/functions.php';

// Silence PHP warnings that might break JSON
error_reporting(E_ALL);
ini_set('display_errors', 0);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- FIX: Auto-Map Role Name to ID if ID is missing ---
if (!isset($_SESSION['role_id']) && isset($_SESSION['role'])) {
    $r = strtolower($_SESSION['role']);
    if ($r === 'principal') $_SESSION['role_id'] = 1;
    elseif ($r === 'secretary') $_SESSION['role_id'] = 2;
    elseif ($r === 'teacher') $_SESSION['role_id'] = 3;
}
// -----------------------------------------------------

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$role_id = (int)$_SESSION['role_id'];

try {
    $data = [];

    // 1. PRINCIPAL
    if ($role_id === 1) {

        // Total active users
        $stmt = $pdo->query(
            "SELECT COUNT(*) FROM user WHERE is_active = 1"
        );
        $totalActiveUsers = $stmt->fetchColumn();

        // Total faculty
        $stmt = $pdo->query(
            "SELECT COUNT(*) FROM user 
            WHERE role_id = 3 AND is_active = 1"
        );
        $totalFaculty = $stmt->fetchColumn();

        // Total administrators (principal + secretary)
        $stmt = $pdo->query(
            "SELECT COUNT(*) FROM user 
            WHERE role_id IN (1,2) AND is_active = 1"
        );
        $totalAdmins = $stmt->fetchColumn();

        // Total schedules
        $stmt = $pdo->query(
            "SELECT COUNT(*) FROM schedule"
        );
        $totalSchedules = $stmt->fetchColumn();

        // Active schedules
        $stmt = $pdo->query(
            "SELECT COUNT(*) FROM schedule WHERE is_active = 1"
        );
        $activeSchedules = $stmt->fetchColumn();

        // Recent audit logs
        $sql = "
            SELECT 
                a.user_action,
                a.timestamp,
                u.first_name,
                u.last_name
            FROM audit_log a
            LEFT JOIN user u ON a.user_id = u.user_id
            ORDER BY a.timestamp DESC
            LIMIT 5
        ";
        $stmt = $pdo->query($sql);
        $recentLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Recently added users
        $sql = "
            SELECT first_name, last_name, role_id, date_created
            FROM user
            ORDER BY date_created DESC
            LIMIT 5
        ";
        $stmt = $pdo->query($sql);
        $recentUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'role' => 'principal',
            'stats' => [
                'total_active_users' => $totalActiveUsers,
                'total_faculty' => $totalFaculty,
                'total_admins' => $totalAdmins,
                'total_schedules' => $totalSchedules,
                'active_schedules' => $activeSchedules,
                'recent_logs' => $recentLogs,
                'recent_users' => $recentUsers
            ]
        ];
    }

    // 2. SECRETARY
    elseif ($role_id === 2) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM user WHERE role_id = 3 AND is_active = 1");
        $totalFaculty = $stmt->fetchColumn();

        $stmt = $pdo->query("SELECT COUNT(*) FROM schedule WHERE is_active = 1");
        $totalClasses = $stmt->fetchColumn();

        $sql = "SELECT s.subject, s.date_created, s.schedule_type, u.last_name, u.first_name 
                FROM schedule s 
                JOIN user u ON s.teacher_id = u.user_id 
                WHERE s.is_active = 1 
                ORDER BY s.date_created DESC LIMIT 5";
        $stmt = $pdo->query($sql);
        $recentSchedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'role' => 'secretary',
            'stats' => [
                'total_faculty' => $totalFaculty,
                'total_classes' => $totalClasses,
                'recent_schedules' => $recentSchedules
            ]
        ];
    }

    // 3. TEACHER (Your Dashboard)
    elseif ($role_id === 3) {
        // Total Active Subjects
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM schedule WHERE teacher_id = ? AND is_active = 1");
        $stmt->execute([$user_id]);
        $totalSubjects = $stmt->fetchColumn();

        // Today's Count
        $currentDay = date('l'); 
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM schedule WHERE teacher_id = ? AND is_active = 1 AND day_of_week = ?");
        $stmt->execute([$user_id, $currentDay]);
        $todayCount = $stmt->fetchColumn();

        // Today's Schedule Table
        $sql = "SELECT subject, time_in, time_out, room, schedule_type 
                FROM schedule 
                WHERE teacher_id = ? AND is_active = 1 AND day_of_week = ? 
                ORDER BY time_in ASC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $currentDay]);
        $todaysSchedule = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'role' => 'teacher',
            'current_day' => $currentDay,
            'stats' => [
                'total_subjects' => $totalSubjects,
                'today_count' => $todayCount,
                'todays_schedule' => $todaysSchedule
            ]
        ];
    }
    
    else {
        throw new Exception("Role ID $role_id not supported.");
    }

    echo json_encode(['status' => 'success', 'data' => $data]);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>