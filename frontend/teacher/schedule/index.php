<?php
require '../../../backend/config/functions.php';
requireRole('teacher');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Workload</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../secretary/workloads/style.css"> <link rel="stylesheet" href="style.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="workspace-body">

    <header class="main-header">
        <div class="brand">
            <i class="fa-solid fa-chalkboard-user"></i> TEACHER PORTAL
        </div>
        <nav class="top-nav">
            <a href="../dashboard/index.php">Dashboard</a>
            <a href="index.php" class="active">My Workload</a>
            <a href="../archive/index.php">Archives</a>
            <a href="../profile/index.php">Profile</a>

            <a href="../../../backend/auth/logout.php" class="btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </nav>
    </header>

    <main class="main-content-centered">
        
        <div class="workspace-container">
            
            <div class="ws-header">
                <div>
                    <h2>My Schedule</h2>
                    <div class="meta-badges">
                        <span class="badge sy-badge">SY: <span id="displaySY">2025-2026</span></span>
                    </div>
                </div>
                <div class="ws-actions">
                    <button onclick="printReport()" class="btn-action btn-print">
                        <i class="fa-solid fa-print"></i> Print Report
                    </button>
                    <button onclick="openAddModal()" class="btn-action btn-add-coed">
                        <i class="fa-solid fa-plus"></i> Add COED Load
                    </button>
                </div>
            </div>

            <div class="toolbar">
                
                <div class="view-toggle">
                    <button class="toggle-btn active" onclick="switchView('grid')" title="Timetable View">
                        <i class="fa-solid fa-calendar-week"></i>
                    </button>
                    <button class="toggle-btn" onclick="switchView('list')" title="List View">
                        <i class="fa-solid fa-list"></i>
                    </button>
                </div>

                <div id="gridControls" class="control-group">
                    <label>Filter:</label>
                    <select id="filterSemester" onchange="reloadData()">
                        <option value="">All Semesters</option>
                        <option value="1" selected>1st Semester</option>
                        <option value="2">2nd Semester</option>
                        <option value="Summer">Summer</option>
                    </select>
                </div>

                <div id="listControls" class="control-group" style="display:none;">
                    <label>Sort By:</label>
                    <select id="sortOption" onchange="reloadData()">
                        <option value="time_in">Time (Default)</option>
                        <option value="schedule_type">Type (LES/COED)</option>
                        <option value="created_at">Date Created</option>
                    </select>
                </div>
                
                <div id="gridLegend" class="legend-inline">
                    <span><span class="dot les"></span> LES (Locked)</span>
                    <span><span class="dot coed"></span> COED (Editable)</span>
                </div>
            </div>

            <div class="content-area">
                
                <input type="hidden" id="myUserId" value="<?= $_SESSION['user_id'] ?>">

                <div id="viewGrid" class="view-section">
                    <div class="timetable-grid" id="gridContent">
                        </div>
                </div>

                <div id="viewList" class="view-section" style="display:none;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Type</th>
                                <th>Day & Time</th>
                                <th>Room</th>
                                <th>Sem</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="listContent">
                            </tbody>
                    </table>
                </div>

            </div>
        </div>
    </main>

    <?php include 'modal.php'; ?>
    <script src="script.js"></script>
</body>
</html>