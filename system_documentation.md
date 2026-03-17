# 📘 UEP LES Scheduling System - Comprehensive Documentation

## 1. System Overview
The **UEP LES Scheduling System** is a modern, web-based platform designed to automate the complex process of faculty workload management and class scheduling for the Laboratory Elementary School (LES). It transitions from traditional, error-prone manual methods to a streamlined, digital workflow with real-time conflict detection and professional report generation.

---

## 2. Technical Architecture

### 🛠️ Core Tech Stack
- **Frontend:** HTML5, Vanilla CSS3 (Custom Design System), JavaScript (ES6+ Mobile-Responsive)
- **Backend:** Native PHP (Modular Backend Services)
- **Database:** MySQL / MariaDB (Relational Schema)
- **Environment:** Apache (via XAMPP)

### 🏗️ Directory Structure
- `/frontend`: Contains role-based UI dashboards and public facing pages.
- `/backend`: Modular PHP scripts for core business logic (auth, database, scheduling, students).
- `/backend/config`: System-wide settings and database connections.
- `/assets`: Shared CSS, JS libraries, and iconography (FontAwesome).

---

## 3. Role-Based Access Control (RBAC)

The system is built on a four-tier privilege model to ensure security and operational efficiency.

| Role | Access Level | Primary Focus |
| :--- | :--- | :--- |
| **System Admin** | Global Read/Write | Infrastructure, Security, & Users |
| **Secretary** | Full Operational | Scheduling & Data Management |
| **Principal** | Read-Only | Oversight & Strategic Monitoring |
| **Teacher** | Restricted Read/Write | Classroom & Personal Scheduling |

### 🛠️ Admin (UID: 1)
- **User management:** Create, update, and manage all system accounts.
- **Security:** monitor system-wide audit logs with IP tracking.
- **Maintenance:** manage database archives and automated cleanup thresholds.

### 📝 Secretary (UID: 3)
- **Master Data Specialist:** maintain lists of buildings, rooms, curricula, and subjects.
- **Unified Scheduling:** create and manage both internal (LES) and external (COED) schedules.
- **Conflict Management:** resolve time, room, and teacher overlaps.
- **Reporting:** generate professional-grade faculty workload reports.

### 👤 Principal (UID: 2)
- **Executive Dashboard:** view real-time statistics on active schedules and faculty workload.
- **Live Tracking Board:** real-time tracking of which teacher is in which room for which class.
- **Read-Only Access:** full oversight without the ability to modify core data.

### 👨‍🏫 Teacher (UID: 4)
- **Student Enrollment:** manage names and details of students in assigned sections.
- **Personal Timetable:** dynamic view of their specific schedule for the current semester.
- **Personalized Dashboard:** quick access to key classroom metrics.

---

## 4. Key Functional Features

### 📅 Advanced Scheduling Engine
- **Unified Interface:** a single page for creating both internal school schedules (LES) and external college loads (COED).
- **Conflict Prevention:** automatic validation logic prevents a teacher or room from being in two places at once.
- **Color-Coded Timetable:** visual grid representation of the entire school week.

### 📊 Professional Workload Reporting
- **Automated Calculations:** system automatically tallies total units and hours per teacher.
- **Print-Ready Layouts:** reports include official branding and designated signature blocks.

### 🔍 Live Tracking Board
- **Real-Time Visibility:** dynamically displays "What's Happening Now" across the entire school.
- **Room Utilization:** quickly see which rooms are occupied or available at any given time.

---

## 5. Design System & UI/UX
The system employs a **"Path-to-Premium"** design philosophy:
- **Glassmorphism:** modern frosted-glass effects for cards and panels.
- **Dynamic Themes:** seamless toggle between high-contrast dark mode and clean light mode.
- **Micro-Animations:** subtle transitions for modal pop-ups and sidebars.
- **Responsive Layout:** fully optimized for use on standard PCs, tablets, and mobile devices.

---

## 6. Database Schema Brief
- `user` / `role`: manages identity and permissions.
- `schedule`: the core table storing both LES and COED time slots.
- `school_year` / `grade_level` / `class_section`: define the academic hierarchy.
- `room` / `building`: manage physical infrastructure.
- `subject` / `curriculum`: manage academic offerings.
- `audit_log`: stores a history of all critical actions for accountability.

---

## 🌐 Deployment & Access
- **Local Server:** system is typically hosted on a designated `UEP-SERVER`.
- **LAN Access:** other users access the system via the local network (WiFi/Ethernet).
- **Automation:** installer (`setup.php`) handles full database creation and seeding.

---
📄 *This documentation is for internal project reference only.*
