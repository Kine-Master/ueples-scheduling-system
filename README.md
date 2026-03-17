# 📅 UEP LES Scheduling System

A Path-to-Premium Web-Based Faculty Workload & Scheduling System designed for the Laboratory Elementary School (LES). This system automates class scheduling, workload reporting, and conflict detection with a modern, high-end user interface.

## 🚀 Key Features

### 👤 Principal (Administrator)
* **Live Tracking Board:** real-time monitoring of active classes, teachers, and room utilization.
* **Dashboard Analytics:** Visual insights into faculty distribution and scheduling efficiency.
* **Audit logs & archives:** complete history of user actions and historical data.

### 📝 Secretary (Scheduler)
* **Unified Schedule Creation:** A streamlined interface for both LES (editable) and COED (read-only) schedules.
* **Real-time conflict detection:** instant visual feedback for overlapping time slots or room double-bookings.
* **Automated reports:** one-click generation of printable faculty workload reports with signature blocks.
* **Flattened Master Data:** direct access to curriculum, subjects, and room management.

### 👨‍🏫 Teacher (Faculty)
* **Personalized Dashboard:** quick view of the current semester's schedule.
* **Responsive View:** optimized for mobile and desktop access.

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
| **Principal** | `principal` | `password123` |
| **Secretary** | `secretary` | `password123` |
| **Teacher** | `teacher` | `password123` |

---

## 🌐 LAN Access
To access the system across your local network:
1. Find your server's local IP or rename the computer to `UEP-SERVER`.
2. Other devices can access via: `http://UEP-SERVER/ueples/`

📄 **License:** For educational purposes only.
