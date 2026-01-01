<?php
require '../../../backend/config/functions.php';
requireRole('principal'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Principal Profile</title>
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
            <a href="../audit_logs/index.php">Audit Logs</a>
            <a href="index.php" class="active">Profile</a>

            <a href="../../../backend/auth/logout.php" class="btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </nav>
    </header>

    <main class="main-content-centered">
        
        <div class="profile-container">
            
            <div class="profile-card">
                <div class="avatar-large" id="avatarDisplay">
                    </div>
                <h2 id="displayName">Loading...</h2>
                <span class="role-badge" id="displayRole">PRINCIPAL</span>
                
                <div class="info-group-readonly">
                    <label>Department</label>
                    <p id="displayDept">...</p>
                </div>
            </div>

            <div class="profile-forms">
                
                <div class="form-section">
                    <h3><i class="fa-solid fa-user-pen"></i> Personal Information</h3>
                    <form id="profileForm">
                        
                        <div class="form-row">
                            <div class="input-group">
                                <label>First Name</label>
                                <input type="text" name="first_name" id="firstName" required>
                            </div>
                            <div class="input-group">
                                <label>Middle Name</label>
                                <input type="text" name="middle_name" id="middleName" placeholder="(Optional)">
                            </div>
                            <div class="input-group">
                                <label>Last Name</label>
                                <input type="text" name="last_name" id="lastName" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label>Email Address</label>
                                <input type="email" name="email" id="email" required>
                            </div>
                            <div class="input-group">
                                <label>Username</label>
                                <input type="text" name="username" id="username" required>
                            </div>
                        </div>

                        <hr class="divider-small" style="border-top:1px solid #eee; margin: 20px 0;">

                        <div class="form-row">
                            <div class="input-group">
                                <label>Academic Rank</label>
                                <input type="text" name="academic_rank" id="academicRank" placeholder="e.g. Principal IV">
                            </div>
                            <div class="input-group">
                                <label>School / College</label>
                                <input type="text" name="school_college" id="schoolCollege" placeholder="e.g. Laboratory Elementary School">
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Department</label>
                            <input type="text" name="department" id="department" placeholder="e.g. Administration">
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-save">Save Changes</button>
                        </div>
                    </form>
                </div>

                <div class="form-section" style="margin-top:40px;">
                    <h3><i class="fa-solid fa-lock"></i> Security</h3>
                    <form id="passwordForm">
                        <div class="input-group">
                            <label>Current Password</label>
                            <input type="password" name="old_password" required>
                        </div>
                        <div class="form-row">
                            <div class="input-group">
                                <label>New Password</label>
                                <input type="password" name="new_password" required>
                            </div>
                            <div class="input-group">
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" required>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-warning">Update Password</button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </main>

    <script src="script.js"></script>
</body>
</html>