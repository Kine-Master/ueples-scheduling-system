<?php
require '../../../backend/config/functions.php';
requireRole('principal');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Faculty Workloads</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="workspace-body">

    <header class="main-header">
        <div class="brand">
            <i class="fa-solid fa-user-tie"></i> PRINCIPAL PORTAL
        </div>
        <nav class="top-nav">
            <a href="../dashboard/index.php">Dashboard</a>
            <a href="../user_management/index.php">Manage Users</a>
            <a href="index.php" class="active">Workloads</a>
            <a href="../archive/index.php">Archives</a>
            <a href="../audit_logs/index.php">Audit Logs</a>
            <a href="../profile/index.php">Profile</a>

            <a href="../../../backend/auth/logout.php" class="btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </nav>
    </header>

    <main class="main-content-full">
        <div class="split-layout">
            
            <aside class="teacher-panel">
                <div class="panel-header">
                    <h3>Faculty List</h3>
                    <div class="search-box">
                        <i class="fa-solid fa-search"></i>
                        <input type="text" id="searchTeacher" placeholder="Search faculty...">
                    </div>
                </div>
                <div id="teacherList" class="list-container">
                    <div class="loading">Loading faculty...</div>
                </div>
            </aside>

            <section class="workspace-panel">
                
                <div id="emptyState" class="empty-state">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    <h2>Select a Faculty Member</h2>
                    <p>Click on a teacher to view their current workload and schedule.</p>
                </div>

                <div id="scheduleWorkspace" style="display:none;">
                    
                    <div class="ws-header">
                        <div>
                            <h2 id="selectedTeacherName">Teacher Name</h2>
                            <div class="meta-badges">
                                <span id="selectedTeacherDept" class="badge dept-badge">Department</span>
                                <span class="badge sy-badge">SY: <span id="displaySY">...</span></span>
                            </div>
                        </div>
                        <div class="ws-actions">
                            <button onclick="printReport()" class="btn-primary">
                                <i class="fa-solid fa-print"></i> Generate Report
                            </button>
                        </div>
                    </div>

                    <div class="toolbar">
                        <div class="view-toggle">
                            <button class="toggle-btn active" onclick="switchView('grid')" title="Timetable View">
                                <i class="fa-solid fa-calendar-week"></i> Grid
                            </button>
                            <button class="toggle-btn" onclick="switchView('list')" title="List View">
                                <i class="fa-solid fa-list"></i> List
                            </button>
                        </div>

                        <div class="control-group">
                            <label><i class="fa-solid fa-filter"></i> Semester:</label>
                            <select id="filterSemester" onchange="reloadData()">
                                <option value="1" selected>1st Semester</option>
                                <option value="2">2nd Semester</option>
                                <option value="Summer">Summer</option>
                            </select>
                        </div>

                        <div class="legend-inline">
                            <span><span class="dot les"></span> LES</span>
                            <span><span class="dot coed"></span> COED</span>
                        </div>
                    </div>

                    <div class="content-area">
                        <div id="viewGrid" class="view-section">
                            <div class="timetable-grid" id="gridContent"></div>
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
                                    </tr>
                                </thead>
                                <tbody id="listContent"></tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </section>
        </div>
    </main>

    <script src="script.js"></script>
</body>
</html>