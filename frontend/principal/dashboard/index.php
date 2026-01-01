<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Principal Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="workspace-body">

<!-- TOP HEADER -->
    <header class="main-header">
        <div class="brand">
            <i class="fa-solid fa-school"></i>
            UEP LES Scheduling System
        </div>
        <nav class="top-nav">
            <a href="index.php" class="active">Dashboard</a>
            <a href="../user_management/index.php">Manage Users</a>
            <a href="../workloads/index.php">Workloads</a>
            <a href="../archive/index.php">Archives</a>
            <a href="../audit_logs/index.php">Audit Logs</a>
            <a href="../profile/index.php">Profile</a>
            
            <a href="../../../backend/auth/logout.php" class="btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </nav>
    </header>

<!-- MAIN CONTENT -->
<main class="main-content-centered">
    <div class="workspace-container">

        <!-- PAGE HEADER -->
        <section class="page-header">
            <div>
                <h2>Welcome, <span id="principalName">Principal</span></h2>
                <p>System-wide overview and administrative insights</p>
            </div>
            <div class="date-display" id="currentDate"></div>
        </section>

        <!-- STATS GRID -->
        <section class="stats-grid">

            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Active Users</h3>
                    <h1 id="totalActiveUsers">0</h1>
                    <p>All active accounts</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Faculty</h3>
                    <h1 id="totalFaculty">0</h1>
                    <p>Active teachers</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon gray">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <div class="stat-info">
                    <h3>Administrators</h3>
                    <h1 id="totalAdmins">0</h1>
                    <p>Principal & Secretary</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <div class="stat-info">
                    <h3>Active Schedules</h3>
                    <h1 id="activeSchedules">0</h1>
                    <p>Currently in use</p>
                </div>
            </div>

        </section>

        <!-- RECENT ACTIVITY -->
        <section class="section-container">
            <div class="section-header">
                <h3><i class="fa-solid fa-clock-rotate-left"></i> Recent System Activity</h3>
            </div>

            <div class="table-wrapper">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="recentLogsBody">
                        <tr>
                            <td colspan="3" class="loading-text">Loading activity...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- RECENT USERS -->
        <section class="section-container">
            <div class="section-header">
                <h3><i class="fa-solid fa-user-plus"></i> Recently Added Users</h3>
            </div>

            <div class="table-wrapper">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Date Created</th>
                        </tr>
                    </thead>
                    <tbody id="recentUsersBody">
                        <tr>
                            <td colspan="3" class="loading-text">Loading users...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </div>
</main>

<script src="script.js"></script>
</body>
</html>
