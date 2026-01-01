<?php
require '../../../backend/config/db.php';
require '../../../backend/config/functions.php';
requireRole('secretary');

// 1. INPUT VALIDATION
// Unlike the teacher who prints their own, the Secretary must specify WHICH teacher to print.
if (!isset($_GET['teacher_id'])) {
    die("Error: Teacher ID is required.");
}
$teacher_id = $_GET['teacher_id']; 

// 2. CAPTURE FILTERS
$semester_filter = $_GET['semester'] ?? '';
$school_year_filter = $_GET['school_year'] ?? '';

// 3. FETCH TEACHER INFO
$stmt = $pdo->prepare("SELECT * FROM user WHERE user_id = ?");
$stmt->execute([$teacher_id]);
$teacher = $stmt->fetch();

if (!$teacher) {
    die("Error: Teacher not found.");
}

// 4. FETCH SECRETARY INFO (For "Prepared by" Signature)
$secStmt = $pdo->prepare("SELECT first_name, last_name FROM user WHERE user_id = ?");
$secStmt->execute([$_SESSION['user_id']]);
$secretary = $secStmt->fetch();
$secretary_name = $secretary ? strtoupper($secretary['first_name'] . ' ' . $secretary['last_name']) : "SECRETARY";

// 5. BUILD DYNAMIC QUERY
// Base: Active schedules for the selected teacher
$sql = "SELECT * FROM schedule WHERE teacher_id = ? AND is_active = 1";
$params = [$teacher_id];

// Apply Semester Filter
if (!empty($semester_filter)) {
    $sql .= " AND semester = ?";
    $params[] = $semester_filter;
}

// Apply School Year Filter
if (!empty($school_year_filter)) {
    $sql .= " AND school_year = ?";
    $params[] = $school_year_filter;
}

$sql .= " ORDER BY schedule_type DESC, subject ASC";

$schedStmt = $pdo->prepare($sql);
$schedStmt->execute($params);
$schedules = $schedStmt->fetchAll(PDO::FETCH_ASSOC);

// 6. DYNAMIC HEADER INFO
// Priority: Filter Value -> Data Value -> Default
$displaySY = !empty($school_year_filter) 
    ? $school_year_filter 
    : (count($schedules) > 0 ? $schedules[0]['school_year'] : (date('Y') . "-" . (date('Y')+1)));

$semRaw = !empty($semester_filter) 
    ? $semester_filter 
    : (count($schedules) > 0 ? $schedules[0]['semester'] : 1);

if ($semRaw == '1') $semLabel = "1st Semester";
elseif ($semRaw == '2') $semLabel = "2nd Semester";
elseif ($semRaw == 'Summer') $semLabel = "Summer";
else $semLabel = "All Semesters";

// 7. FETCH PRINCIPAL
$pStmt = $pdo->prepare("SELECT first_name, last_name FROM user WHERE role_id = 1 AND is_active = 1 LIMIT 1");
$pStmt->execute();
$principal = $pStmt->fetch();
$principal_name = $principal ? strtoupper($principal['first_name'] . ' ' . $principal['last_name']) : "PRINCIPAL NOT ASSIGNED";

// HELPER
function calculateHours($in, $out) {
    $start = strtotime($in);
    $end = strtotime($out);
    return round(abs($end - $start) / 3600, 2);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Workload Report - <?= e($teacher['last_name']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; padding: 20px; }
        
        /* HEADER LAYOUT */
        .report-header-table { width: 100%; border-collapse: collapse; margin-bottom: 0; }
        .report-header-table td { padding: 10px; background-color: #999; color: white; font-weight: bold; border: 1px solid #666; }
        .header-title { text-transform: uppercase; font-size: 14px; }
        
        /* MAIN DATA TABLE */
        .workload-table { width: 100%; border-collapse: collapse; margin-top: -1px; } 
        .workload-table th, .workload-table td { 
            border: 1px solid #333; 
            padding: 8px 4px; 
            text-align: center; 
            font-size: 11px;
            vertical-align: middle;
        }
        .workload-table th { font-weight: bold; text-transform: uppercase; height: 40px; }
        
        /* Column Widths */
        .col-subject { width: 15%; }
        .col-title { width: 25%; }
        .col-units { width: 5%; }
        .col-time { width: 12%; }
        .col-days { width: 8%; }
        
        /* Footer/Signatures */
        .signatures { margin-top: 40px; display: flex; justify-content: space-between; page-break-inside: avoid; }
        .sig-block { text-align: center; width: 200px; }
        .sig-line { border-top: 1px solid black; margin-top: 40px; padding-top: 5px; font-weight: bold; }

        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            .report-header-table td { background-color: #999 !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom:20px; text-align:right;">
        <button onclick="window.print()" style="padding:10px 20px; font-weight:bold; cursor:pointer;">PRINT REPORT</button>
        <button onclick="window.close()" style="padding:10px 20px; cursor:pointer; margin-left:10px;">CLOSE</button>
    </div>

    <table class="report-header-table">
        <tr>
            <td width="50%" class="header-title">
                <?= e(strtoupper($teacher['last_name'] . ', ' . $teacher['first_name'])) ?> WORKLOAD REPORT
            </td>
            <td width="50%">
                SCHOOL YEAR: <?= e($displaySY) ?><br>
                SEMESTER: <?= e($semLabel) ?>
            </td>
        </tr>
    </table>

    <table class="workload-table">
        <thead>
            <tr>
                <th rowspan="2" class="col-subject">SUBJECT</th> <th rowspan="2" class="col-title">DESCRIPTIVE TITLE</th>
                <th rowspan="2">CLASS ID</th> <th colspan="2">UNITS</th>
                <th rowspan="2" class="col-time">TIME</th>
                <th rowspan="2" class="col-days">DAYS</th>
                <th rowspan="2">HOURS<br>PER WEEK</th>
                <th rowspan="2">ROOM</th>
                <th rowspan="2">COURSE/YEAR</th>
            </tr>
            <tr>
                <th class="col-units">LEC</th>
                <th class="col-units">LAB</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $totalLec = 0;
            $totalLab = 0;
            $totalHours = 0;

            // Ensure we have at least 8 rows for layout consistency
            $rowCount = max(count($schedules), 8);

            for($i = 0; $i < $rowCount; $i++):
                if(isset($schedules[$i])):
                    $s = $schedules[$i];
                    $lec = ($s['class_type'] === 'Lecture') ? $s['units'] : '';
                    $lab = ($s['class_type'] === 'Laboratory') ? $s['units'] : '';
                    
                    if(is_numeric($lec)) $totalLec += $lec;
                    if(is_numeric($lab)) $totalLab += $lab;
                    
                    $hours = calculateHours($s['time_in'], $s['time_out']);
                    $totalHours += $hours;

                    $marker = ($s['schedule_type'] == 'COED') ? ' (COED)' : '';
            ?>
            <tr>
                <td><?= e($s['subject']) ?></td>
                <td><?= e($s['subject']) . $marker ?></td> 
                <td><?= $s['schedule_id'] ?></td>
                <td><?= $lec ?></td>
                <td><?= $lab ?></td>
                <td><?= date('h:i A', strtotime($s['time_in'])) . '-' . date('h:i A', strtotime($s['time_out'])) ?></td>
                <td><?= e($s['day_of_week']) ?></td>
                <td><?= $hours ?></td>
                <td><?= e($s['room']) ?></td>
                <td><?= e($s['course_year']) ?></td>
            </tr>
            <?php else: ?>
            <tr>
                <td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
            </tr>
            <?php endif; endfor; ?>

            <tr style="font-weight:bold; background-color:#f0f0f0;">
                <td colspan="3" style="text-align:right; padding-right:10px;">TOTAL</td>
                <td><?= $totalLec ?: '-' ?></td>
                <td><?= $totalLab ?: '-' ?></td>
                <td colspan="2"></td>
                <td><?= $totalHours ?></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    <div class="signatures">
        <div class="sig-block">
            <p>Prepared by:</p>
            <div class="sig-line"><?= $secretary_name ?></div> 
            <span>School Secretary</span>
        </div>
        
        <div class="sig-block">
            <p>Confirmed:</p>
            <div class="sig-line"><?= strtoupper($teacher['first_name'] . ' ' . $teacher['last_name']) ?></div>
            <span>Faculty Member</span>
        </div>

        <div class="sig-block">
            <p>Approved by:</p>
            <div class="sig-line"><?= $principal_name ?></div>
            <span>School Principal</span>
        </div>
    </div>

</body>
</html>