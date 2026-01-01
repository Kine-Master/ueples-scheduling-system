<?php
require '../../../backend/config/functions.php';
requireRole('teacher');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="workspace-body">

    <header class="main-header">
        <div class="brand">
            <i class="fa-solid fa-chalkboard-user"></i> TEACHER PORTAL
        </div>
        <nav class="top-nav">
            <a href="index.php" class="active">Dashboard</a>
            <a href="../schedule/index.php">My Workload</a>
            <a href="../archive/index.php">Archives</a>
            <a href="../profile/index.php">Profile</a>

            <a href="../../../backend/auth/logout.php" class="btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </nav>
    </header>

    <main class="dashboard-container">
        
        <div class="welcome-banner">
            <div>
                <h1>Welcome back, <span id="teacherName">Teacher</span>!</h1>
                <p>Here is your schedule summary for today, <span id="currentDate" style="font-weight:bold;">...</span></p>
            </div>
            <div class="date-badge">
                <i class="fa-regular fa-calendar"></i> <span id="currentDay">...</span>
            </div>
        </div>

        <div class="stats-grid">
            
            <div class="stat-card">
                <div class="icon-box teal">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Subjects</h3>
                    <h1 id="totalSubjects">...</h1>
                    <p>Active Workload</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="icon-box yellow">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>Classes Today</h3>
                    <h1 id="todayCount">...</h1>
                    <p>Scheduled Sessions</p>
                </div>
            </div>

            <div class="stat-card action-card" onclick="window.location.href='../schedule/index.php'">
                <div class="icon-box gray">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <div class="stat-info">
                    <h3>View Full Schedule</h3>
                    <p>Go to Timetable <i class="fa-solid fa-arrow-right"></i></p>
                </div>
            </div>
        </div>

        <div class="schedule-panel">
            <div class="panel-header">
                <h3><i class="fa-solid fa-list-check"></i> Today's Timetable</h3>
                <a href="../schedule/index.php" class="btn-sm">Manage Schedule</a>
            </div>
            
            <div class="table-responsive">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Subject</th>
                            <th>Room</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="todayTableBody">
                        <tr><td colspan="5" class="loading-text">Loading schedule...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script src="script.js"></script>
</body>
</html>