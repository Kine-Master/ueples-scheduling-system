<?php
require '../../../backend/config/functions.php';
requireRole('principal');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>System Audit Log</title>
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
            <a href="../workloads/index.php">Workloads</a>
            <a href="../archive/index.php">Archives</a>
            <a href="index.php" class="active">Audit Logs</a>
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
                    <h2>System Audit Logs</h2>
                    <p>Monitor user activities, system changes, and security events.</p>
                </div>
                <div class="actions">
                     <button onclick="openSettingsModal()" class="btn-secondary">
                        <i class="fa-solid fa-gear"></i> Settings
                    </button>
                    <button onclick="generateReport()" class="btn-primary">
                        <i class="fa-solid fa-print"></i> Generate Report
                    </button>
                </div>
            </div>

            <div id="settingsModal" class="modal">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <h3>Audit Settings</h3>
                        <span onclick="closeSettingsModal()" class="close-btn">&times;</span>
                    </div>

                    <div class="modal-body">
                        <p>Configure the automatic retention policy for audit logs. Logs older than this threshold will be eligible for deletion.</p>

                        <div class="modal-input-group">
                            <label>Auto-Delete Threshold (Months)</label>
                            <input type="number" id="retentionMonths" class="modal-input" min="1" placeholder="e.g. 12">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button onclick="runCleanup()" class="btn-modal-danger">
                            <i class="fa-solid fa-trash"></i> Run Cleanup
                        </button>
                        <button onclick="saveSettings()" class="btn-modal-save">
                            Save Changes
                        </button>
                    </div>

                </div>
            </div>

            <div class="toolbar" style="display:flex; gap:15px; flex-wrap:wrap; padding:15px; background:white; border:1px solid #ddd;">
                
                <div class="filter-group">
                    <label>Search:</label>
                    <input type="text" id="searchInput" placeholder="User, Action..." onkeyup="debounceLoad()">
                </div>

                <div class="filter-group">
                    <label>Action Type:</label>
                    <select id="actionFilter" onchange="loadLogs()">
                        <option value="">All Actions</option>
                        </select>
                </div>

                <div class="filter-group">
                    <label>From:</label>
                    <input type="date" id="startDate" onchange="loadLogs()">
                </div>

                <div class="filter-group">
                    <label>To:</label>
                    <input type="date" id="endDate" onchange="loadLogs()">
                </div>
                
                <button onclick="resetFilters()">Reset</button>
            </div>

            <div class="table-wrapper">
                <table style="width:100%; text-align:left; border-collapse:collapse;">
                    <thead>
                        <tr style="border-bottom:2px solid #ddd;">
                            <th>ID</th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Action</th>
                            <th>IP Address</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody id="auditTableBody">
                        </tbody>
                </table>
            </div>

            <div id="paginationControls" style="margin-top:20px; display:flex; gap:10px; justify-content:center;">
                <button onclick="changePage(-1)" id="btnPrev">Previous</button>
                <span id="pageIndicator">Page 1</span>
                <button onclick="changePage(1)" id="btnNext">Next</button>
            </div>

        </div>
    </main>

    <script src="script.js"></script>
</body>
</html>