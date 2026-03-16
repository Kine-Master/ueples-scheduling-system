<?php
require_once '../../../../backend/config/functions.php';
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
  <title>Teacher Specialties — Admin</title>
  <link rel="stylesheet" href="../../../assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root{--accent:#fbbf24;--accent-bg:rgba(245,158,11,.12)}
    /* Nav Dropdown */
    .nav-dropdown{position:relative;display:inline-block}
    .nav-dropbtn{padding:7px 14px;border-radius:var(--radius-sm);font-size:.85rem;font-weight:500;color:var(--text-sub);transition:all var(--transition);display:flex;align-items:center;gap:6px}
    .nav-dropbtn:hover,.nav-dropdown:hover .nav-dropbtn{background:rgba(255,255,255,.05);color:var(--text)}
    .nav-dropdown-content{display:none;position:absolute;top:100%;left:0;background:var(--bg-card);min-width:200px;box-shadow:var(--shadow-lg);border:1px solid var(--border);border-radius:var(--radius-sm);z-index:200;padding:8px 0;margin-top:0;}
    .nav-dropdown-content::before { content:''; position:absolute; top:-10px; left:0; right:0; height:10px; }
    .nav-dropdown-content a{display:block;padding:10px 16px;font-size:.85rem;color:var(--text);border-radius:0}
    .nav-dropdown-content a:hover{background:var(--accent-bg);color:var(--accent)}
    .nav-dropdown:hover .nav-dropdown-content{display:block;animation:dropIn .2s ease}
    @keyframes dropIn{from{opacity:0;transform:translateY(-5px)}to{opacity:1;transform:translateY(0)}}
  </style>
</head>
<body class="page-body">

<header class="main-header">
  <div class="brand"><i class="fa-solid fa-calendar-days"></i><span>SECRETARY PORTAL<small class="brand-sub">UEP LES System</small></span></div>
  <nav class="top-nav">
    
    <div class="nav-dropdown">
      <a href="#" class="nav-dropbtn active"><i class="fa-solid fa-database"></i> Master Data <i class="fa-solid fa-chevron-down" style="font-size:.7rem;margin-left:4px"></i></a>
      <div class="nav-dropdown-content">
        <a href="../school_year/index.php">School Years</a>
        <a href="../curriculum/index.php">Curricula</a>
        <a href="../subject/index.php">Subjects</a>
        <a href="../building_room/index.php">Buildings & Rooms</a>
        <a href="../class_section/index.php">Class Sections</a>
        <a href="index.php" style="color:var(--accent);background:var(--accent-bg)">Teacher Specialties</a>
      </div>
    </div>

    <a href="../../schedule/index.php"><i class="fa-solid fa-table-cells"></i> Schedules</a>
    <a href="../../profile/index.php"><i class="fa-solid fa-user-circle"></i> Profile</a>
    <a href="../../../../backend/auth/logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
      <button class="theme-btn" id="themeBtn" title="Toggle theme" onclick="toggleTheme()">🌙</button>
  </nav>
</header>

<main class="page-content">
  <div class="page-header">
    <div class="page-header-text"><h2><i class="fa-solid fa-tags" style="color:var(--accent)"></i> Teacher Specialties</h2><p>Assign subject specializations to teachers to enable scheduling conflict detection.</p></div>
    <div class="right"><button class="btn btn-primary" onclick="openAssignModal()"><i class="fa-solid fa-link"></i> Assign Subject</button></div>
  </div>

  <div class="section-card">
    <div class="toolbar">
      <select class="filter-sel" id="teacherFilter" onchange="loadSpecs()"><option value="">All Teachers</option></select>
    </div>
    <div class="table-wrap">
      <table class="data-table">
        <thead><tr><th>Teacher Name</th><th>Subject</th><th>Curriculum Name</th><th>Grade Level</th><th>Actions</th></tr></thead>
        <tbody id="specBody"><tr class="no-data"><td colspan="5"><div class="spinner-wrap"><i class="fa-solid fa-spinner"></i><p>Loading…</p></div></td></tr></tbody>
      </table>
    </div>
  </div>
</main>

<div class="modal-overlay" id="assignModal">
  <div class="modal modal-sm">
    <div class="modal-header"><h3><i class="fa-solid fa-link"></i> Assign Specialty</h3><button class="modal-close" onclick="closeModal('assignModal')"><i class="fa-solid fa-xmark"></i></button></div>
    <form id="assignForm" onsubmit="saveAssign(event)">
      <div class="modal-body">
        <div class="form-group"><label class="form-label">Teacher <span>*</span></label><select class="form-control" id="fTeacher" required></select></div>
        <div class="form-group"><label class="form-label">Subject <span>*</span></label><select class="form-control" id="fSubject" required></select></div>
        <div class="alert-info" style="margin-top:12px;font-size:.85rem;display:flex;gap:8px;padding:12px;background:rgba(56,189,248,.1);border-left:3px solid #38bdf8;border-radius:4px;color:var(--text-sub)"><i class="fa-solid fa-circle-info" style="color:#38bdf8"></i><span>Assigning a specialty means the teacher will appear as a qualified option when creating schedules for this subject.</span></div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-secondary" onclick="closeModal('assignModal')">Cancel</button><button type="submit" class="btn btn-primary">Assign</button></div>
    </form>
  </div>
</div>

<div id="toastContainer"></div>
<script src="script.js"></script>
</body></html>
