<?php
require '../../../backend/config/functions.php';
requireRole('secretary');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Secretary Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="workspace-body">

    <header class="main-header">
        <div class="brand">
            <i class="fa-solid fa-user-pen"></i> SECRETARY PORTAL
        </div>
        <nav class="top-nav">
            <a href="index.php" class="active">Dashboard</a>
            <a href="../workloads/index.php">Workloads</a>
            <a href="../archive/index.php">Archives</a>
            <a href="../profile/index.php">Profile</a>
            
            <a href="../../../backend/auth/logout.php" class="btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </nav>
    </header>

    <main class="main-content-centered">
        <div class="workspace-container">
            
            <div class="page-header">
                <div>
                    <h2>Welcome back, <span id="secretaryName">Secretary</span>!</h2>
                    <p>Overview of faculty and academic schedules.</p>
                </div>
                <div class="date-display">
                    <?php echo date('l, F j, Y'); ?>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon orange">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="totalFaculty">-</h3>
                        <p>Total Faculty</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon blue">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="totalClasses">-</h3>
                        <p>Total Classes</p>
                    </div>
                </div>
            </div>

            <div class="section-container">
                <div class="section-header">
                    <h3>Recent Schedules</h3>
                    <a href="../workloads/index.php" class="btn-link">
                        Manage All Schedules <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="table-wrapper">
                    <table class="data-table dashboard-table">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Teacher</th>
                                <th>Type</th>
                                <th>Created Date</th>
                            </tr>
                        </thead>
                        <tbody id="recentSchedulesBody">
                            <tr><td colspan="4" style="text-align:center;">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <script src="script.js"></script>
</body>
</html>