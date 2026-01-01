# 📅 UEP LES Scheduling System

A Web-Based Faculty Workload & Scheduling System designed for the Laboratory Elementary School (LES). This system automates the creation of class schedules, workload reports, and conflict detection within a Local Area Network (LAN).

## 🚀 Features

### 👤 Principal (Administrator)
* **Dashboard:** Real-time analytics of faculty and active schedules.
* **Audit Logs:** Track all user activities (logins, updates, deletions).
* **Archives:** View historical schedule data.
* **Monitoring:** View-only access to all active schedules.

### 📝 Secretary (Scheduler)
* **Workload Management:** Create, update, and delete class schedules.
* **Conflict Detection:** Visual alerts for overlapping time slots.
* **Report Generation:** Auto-generate printable workload reports with signature blocks.
* **Schedule Types:** Distinguish between **LES** (Editable) and **COED** (Read-only) loads.

### 👨‍🏫 Teacher (Faculty)
* **My Schedule:** View personal class schedule for the current semester.
* **Profile:** Manage account details.

---

## 🛠️ Tech Stack
* **Frontend:** HTML5, CSS3, JavaScript
* **Backend:** PHP (Native)
* **Database:** MySQL / MariaDB
* **Server:** Apache (via XAMPP)

---

## ⚙️ Installation Guide

### 1. Prerequisites
* Install **XAMPP** (or any PHP/MySQL environment).
* Install **Git**.

### 2. Clone the Repository
Open your terminal in `htdocs` and run:
```bash
git clone [https://github.com/YOUR_USERNAME/ueples-scheduling-system.git](https://github.com/YOUR_USERNAME/ueples-scheduling-system.git) ueples
3. Database SetupTurn on Apache and MySQL in XAMPP.Open your browser and go to:http://localhost/ueples/setup.phpThis script will automatically:Create the database ueples_scheduling_system.Create all tables.Seed default user accounts.IMPORTANT: Delete setup.php after running it for security.4. ConfigurationNavigate to backend/config/.Rename db.example.php to db.php.Open db.php and configure your database credentials (default is usually root/empty):PHP$host = 'localhost';
$dbname = 'ueples_scheduling_system';
$username = 'root';
$password = '';
🔑 Default CredentialsRoleUsernamePasswordPrincipalprincipalpassword123Secretarysecretarypassword123Teacherteacherpassword123🌐 LAN Access (Optional)To access this system from other computers on the same WiFi network:Find your computer's Device Name (Settings > System > About).Rename it to something simple like UEP-SERVER.Other users can access the system via:http://UEP-SERVER/ueples/index.php📄 LicenseThis project is for educational purposes only.
