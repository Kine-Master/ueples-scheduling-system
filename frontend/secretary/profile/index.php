<?php
require_once '../../../backend/config/functions.php';
requireRole('secretary');
?><!DOCTYPE html>
<html lang="en">
<head>
  <script>
    (function(){
      var t = localStorage.getItem('ueples_theme') || 'dark';
      document.documentElement.dataset.theme = t;
      window.addEventListener('DOMContentLoaded', function() {
        var btn = document.getElementById('themeBtn');
        if(btn) btn.textContent = t === 'dark' ? '🌙' : '☀️';
      });
    })();
    function toggleTheme() {
      var next = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
      document.documentElement.dataset.theme = next;
      localStorage.setItem('ueples_theme', next);
      var btn = document.getElementById('themeBtn');
      if(btn) btn.textContent = next === 'dark' ? '🌙' : '☀️';
    }
  </script>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>My Profile — Secretary</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root{--accent:#fbbf24;--accent-bg:rgba(245,158,11,.12)}
    .nav-dropdown{position:relative;display:inline-block}
    .nav-dropbtn{padding:7px 14px;border-radius:var(--radius-sm);font-size:.85rem;font-weight:500;color:var(--text-sub);transition:all var(--transition);display:flex;align-items:center;gap:6px}
    .nav-dropbtn:hover,.nav-dropdown:hover .nav-dropbtn{background:rgba(255,255,255,.05);color:var(--text)}
    .nav-dropdown-content{display:none;position:absolute;top:100%;left:0;background:var(--bg-card);min-width:200px;box-shadow:var(--shadow-lg);border:1px solid var(--border);border-radius:var(--radius-sm);z-index:200;padding:8px 0;margin-top:5px}
    .nav-dropdown-content a{display:block;padding:10px 16px;font-size:.85rem;color:var(--text);border-radius:0}
    .nav-dropdown-content a:hover{background:var(--accent-bg);color:var(--accent)}
    .nav-dropdown:hover .nav-dropdown-content{display:block;animation:dropIn .2s ease}
    @keyframes dropIn{from{opacity:0;transform:translateY(-5px)}to{opacity:1;transform:translateY(0)}}
    @media (max-width: 700px) { .page-content > div[style*="grid"] { grid-template-columns: 1fr !important; } }
  </style>
</head>
<body class="page-body">

<header class="main-header">
  <div class="brand"><i class="fa-solid fa-calendar-days"></i><span>SECRETARY PORTAL<small class="brand-sub">UEP LES System</small></span></div>
  <nav class="top-nav">
    
    <div class="nav-dropdown">
      <a href="#" class="nav-dropbtn"><i class="fa-solid fa-database"></i> Master Data <i class="fa-solid fa-chevron-down" style="font-size:.7rem;margin-left:4px"></i></a>
      <div class="nav-dropdown-content">
        <a href="../master_data/school_year/index.php">School Years</a>
        <a href="../master_data/curriculum/index.php">Curricula</a>
        <a href="../master_data/subject/index.php">Subjects</a>
        <a href="../master_data/building_room/index.php">Buildings & Rooms</a>
        <a href="../master_data/class_section/index.php">Class Sections</a>
        <a href="../master_data/teacher_subject/index.php">Teacher Specialties</a>
      </div>
    </div>

    <a href="../schedule/index.php"><i class="fa-solid fa-table-cells"></i> Schedules</a>
    <a href="index.php" class="active"><i class="fa-solid fa-user-circle"></i> Profile</a>
    <a href="../../../backend/auth/logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
      <button class="theme-btn" id="themeBtn" title="Toggle theme" onclick="toggleTheme()">🌙</button>
  </nav>
</header>

<main class="page-content" style="max-width:900px">
  <div class="page-header">
    <div class="page-header-text"><h2><i class="fa-solid fa-user-circle" style="color:var(--accent)"></i> My Profile</h2><p>Manage your account information and password</p></div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
    
    <div class="section-card">
      <div class="section-header"><h3><i class="fa-solid fa-id-card"></i> Personal Information</h3></div>
      <div style="padding:24px">
        <div id="profileAvatar" style="text-align:center;margin-bottom:24px">
          <div style="width:80px;height:80px;border-radius:50%;background:var(--accent-bg);border:2px solid var(--accent);display:flex;align-items:center;justify-content:center;font-size:2rem;color:var(--accent);margin:0 auto 12px"><i class="fa-solid fa-user-pen"></i></div>
          <div style="font-weight:700;font-size:1.1rem" id="profileFullName">—</div>
          <div><span class="badge badge-warning" style="margin-top:6px">Secretary</span></div>
        </div>
        <form id="profileForm" onsubmit="saveProfile(event)">
          <div class="form-row"><div class="form-group"><label class="form-label">Last Name <span>*</span></label><input class="form-control" id="pLastName" required></div><div class="form-group"><label class="form-label">First Name <span>*</span></label><input class="form-control" id="pFirstName" required></div></div>
          <div class="form-group"><label class="form-label">Middle Name</label><input class="form-control" id="pMiddleName"></div>
          <div class="form-group"><label class="form-label">Email</label><input class="form-control" id="pEmail" type="email"></div>
          <div class="form-group"><label class="form-label">Academic Rank</label><input class="form-control" id="pRank"></div>
          <div class="form-row"><div class="form-group"><label class="form-label">School / College</label><input class="form-control" id="pSchool"></div><div class="form-group"><label class="form-label">Department</label><input class="form-control" id="pDept"></div></div>
          <button type="submit" class="btn btn-primary" style="width:100%"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
        </form>
      </div>
    </div>

    <div class="section-card" style="align-self:start">
      <div class="section-header"><h3><i class="fa-solid fa-lock"></i> Change Password</h3></div>
      <div style="padding:24px">
        <form id="passwordForm" onsubmit="changePassword(event)">
          <div class="form-group"><label class="form-label">Current Password <span>*</span></label><input type="password" class="form-control" id="cCurrentPass" required></div>
          <div class="form-group"><label class="form-label">New Password <span>*</span></label><input type="password" class="form-control" id="cNewPass" placeholder="Min. 6 characters" required></div>
          <div class="form-group"><label class="form-label">Confirm New Password <span>*</span></label><input type="password" class="form-control" id="cConfirmPass" required></div>
          <button type="submit" class="btn btn-danger" style="width:100%"><i class="fa-solid fa-key"></i> Update Password</button>
        </form>
      </div>
    </div>

  </div>
</main>
<div id="toastContainer"></div>
<script src="../../admin/profile/script.js"></script>
</body></html>