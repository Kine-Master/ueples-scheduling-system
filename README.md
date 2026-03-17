# 📅 UEP LES Scheduling System

A Path-to-Premium Web-Based Faculty Workload & Scheduling System designed for the Laboratory Elementary School (LES). This system automates class scheduling, workload reporting, and conflict detection with a modern, high-end user interface.

## 🚀 Role-Based Features

### 💻 System Administrator
* **User Management:** Full control over account creation, role assignment, and password resets.
* **Audit Logs:** Monitor all system activities (logins, updates, deletions) with IP tracking.
* **System Archives:** Manage historical schedule data and cleanup thresholds.

### 👤 School Principal
* **Read-Only Oversight:** Monitor school operations without risk of data modification.
* **Live Tracking Board:** Real-time visibility into active classes, faculty locations, and room status.
* **Analytics Dashboard:** Visual insights into faculty distribution and system-wide scheduling.

### 📝 School Secretary
* **Primary Scheduler:** Full management of **LES** (internal) and **COED** (external) schedules.
* **Master Data Management:** Maintain buildings, rooms, curricula, and academic subjects.
* **Conflict Detection:** Real-time automated validation for time, teacher, and room overlaps.
* **Report Generation:** Export and print professional faculty workload reports.

### 👨‍🏫 Faculty Teacher
* **Classroom Management:** Manage student lists and enrollment for assigned LES sections.
* **Personalized Schedule:** Quick access to personal workload and class timetables.
* **Responsive Dashboard:** View schedules on any device (mobile/desktop).

---

## 💎 Premium UI & UX
* **Glassmorphism Design:** A modern, frosted-glass aesthetic for a premium feel.
* **Dynamic Dark/Light Mode:** seamless theme switching across the entire platform.
* **Smooth Animations:** micro-interactions and transitions for enhanced usability.
* **Responsive Layout:** fully functional on tablets and mobile devices.

---

## 🛠️ Tech Stack
* **Frontend:** HTML5, CSS3 (Vanilla), JavaScript (ES6+)
* **Backend:** PHP (Native)
* **Database:** MySQL / MariaDB
* **Server:** Apache (via XAMPP)

---

## ⚙️ Installation Guide

### 1. Prerequisites
* **XAMPP** (or any PHP/MySQL environment).
* **Git** installed on your system.

### 2. Clone the Repository
```bash
git clone https://github.com/Kine-Master/ueples-scheduling-system.git ueples
```

### 3. Database Setup
1. Start **Apache** and **MySQL** in XAMPP.
2. Visit `http://localhost/ueples/setup.php` in your browser.
3. The script will automatically:
    - Create the `ueples_scheduling_system` database.
    - Setup all necessary tables.
    - Seed default administrative and faculty accounts.
4. **Important:** Delete `setup.php` after successful installation.

### 4. Configuration
1. Navigate to `backend/config/`.
2. Rename `db.example.php` to `db.php`.
3. Configure your credentials if different from default (`root` with no password).

### 🔑 Default Credentials
| Role | Username | Password |
| :--- | :--- | :--- |
| **Admin** | `admin` | `password123` |
| **Principal** | `principal` | `password123` |
| **Secretary** | `secretary` | `password123` |
| **Teacher** | `teacher` | `password123` |

---

## 🌐 LAN Access
To access the system across your local network:
1. Find your server's local IP or rename the computer to `UEP-SERVER`.
2. Other devices can access via: `http://UEP-SERVER/ueples/`

📄 **License:** For educational purposes only.
