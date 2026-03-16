<?php
// backend/schedule/create_les.php
// Creates an internal (LES) schedule using FK references via the guided dropdown flow.
header('Content-Type: application/json');
require_once __DIR__ . '/../config/functions.php';
requireRoleApi('secretary');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['status'=>'error','message'=>'POST only']); exit; }

try {
    // Required fields
    $school_year_id   = (int)($_POST['school_year_id']   ?? 0);
    $semester         = trim($_POST['semester']          ?? '');
    $class_section_id = (int)($_POST['class_section_id'] ?? 0);
    $subject_id       = (int)($_POST['subject_id']       ?? 0);
    $teacher_id       = (int)($_POST['teacher_id']       ?? 0);
    $room_id          = (int)($_POST['room_id']          ?? 0);
    $day_of_week      = trim($_POST['day_of_week']       ?? '');
    $time_in          = trim($_POST['time_in']  ?? $_POST['start_time'] ?? '');
    $time_out         = trim($_POST['time_out'] ?? $_POST['end_time']   ?? '');

    if (!$school_year_id || !$semester || !$class_section_id || !$subject_id ||
        !$teacher_id || !$room_id || !$day_of_week || !$time_in || !$time_out) {
        throw new Exception("All fields are required for an internal (LES) schedule.");
    }
    if (!in_array($semester, ['1', '2', 'Summer'])) throw new Exception("Invalid semester value.");
    if ($time_in >= $time_out) throw new Exception("Time Out must be after Time In.");

    // (Specialist check is advisory — all active teachers are allowed to be assigned)

    // Conflict check: teacher busy OR room occupied at the same day/time/semester/SY
    $sql_conflict = "SELECT schedule_id FROM schedule
                     WHERE is_active = 1 AND school_year_id = ? AND semester = ? AND day_of_week = ?
                       AND (teacher_id = ? OR room_id = ?)
                       AND (time_in < ? AND time_out > ?)";
    $stmt = $pdo->prepare($sql_conflict);
    $stmt->execute([$school_year_id, $semester, $day_of_week, $teacher_id, $room_id, $time_out, $time_in]);
    if ($conflict = $stmt->fetch()) {
        throw new Exception("Conflict detected! A schedule already exists for this teacher or room at this time. (Schedule #" . $conflict['schedule_id'] . ")");
    }

    // Insert LES schedule
    $pdo->prepare(
        "INSERT INTO schedule (schedule_type, school_year_id, semester, teacher_id, day_of_week,
                               time_in, time_out, class_section_id, subject_id, room_id)
         VALUES ('LES', ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    )->execute([$school_year_id, $semester, $teacher_id, $day_of_week, $time_in, $time_out,
                $class_section_id, $subject_id, $room_id]);

    $id = $pdo->lastInsertId();

    // Audit with context names
    $stmt = $pdo->prepare("SELECT name FROM subject WHERE subject_id = ?"); $stmt->execute([$subject_id]);
    $subName = $stmt->fetchColumn();
    $stmt = $pdo->prepare("SELECT CONCAT(last_name,', ',first_name) FROM user WHERE user_id = ?"); $stmt->execute([$teacher_id]);
    $teacherName = $stmt->fetchColumn();
    logAudit($_SESSION['user_id'], 'CREATE_SCHEDULE_LES', "LES schedule #$id: '$subName' → $teacherName on $day_of_week $time_in-$time_out");

    echo json_encode(['status' => 'success', 'schedule_id' => $id]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
