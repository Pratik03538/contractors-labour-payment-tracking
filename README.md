<div align="center">

# 🏗️ Contractors Labour Payment Tracking System

### Smart Workforce Management & Labour Payment Tracking Platform

<p align="center">
  <img src="https://img.shields.io/badge/PHP-Backend-777BB4?style=for-the-badge&logo=php">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white">
  <img src="https://img.shields.io/badge/XAMPP-Local_Server-FB7A24?style=for-the-badge&logo=xampp&logoColor=white">
  <img src="https://img.shields.io/badge/Bootstrap-Responsive_UI-7952B3?style=for-the-badge&logo=bootstrap">
  <img src="https://img.shields.io/badge/Management-System-success?style=for-the-badge">
</p>

### ⚡ Contractor • Labour • Attendance • Salary • Expense Management

</div>

---

# 🌍 Overview

**Contractors Labour Payment Tracking System** is a modern workforce management platform designed for contractors, site supervisors, and labour managers to efficiently handle labour attendance, payments, expenses, and project tracking.

The system simplifies daily workforce operations by providing:

- Labour attendance management
- Payment tracking
- Advance salary handling
- Contractor expense monitoring
- Daily wage calculations
- Worker record management

Built using **PHP**, **MySQL**, and **Bootstrap**, the platform is lightweight, responsive, and easy to deploy on local or production servers.

---

# 🚀 Core Features

## 👷 Labour Management

- Add & manage workers
- Store worker details securely
- Worker-wise payment records
- Daily wage tracking

---

## 📅 Attendance Tracking

- Daily attendance management
- Present / Absent records
- Date-wise labour tracking
- Site-wise attendance handling

---

## 💰 Salary & Payment System

- Daily wage calculations
- Advance payment tracking
- Pending balance management
- Payment history records

---

## 📊 Expense Monitoring

Track contractor expenses including:

- Labour payments
- Site expenses
- Material expenses
- Daily operational costs

---

## 📈 Dashboard Analytics

Interactive dashboard with:

- Total workers
- Total expenses
- Payment summaries
- Attendance statistics

---

## 🔍 Search & Record Tracking

- Search workers instantly
- Filter attendance records
- Payment history lookup
- Quick labour management tools

---

# 📸 Dashboard Previews

## 🖥️ Main Dashboard

<p align="center">
 <img width="401" height="812" alt="001" src="https://github.com/user-attachments/assets/593397f8-cc38-440e-a7f2-3a1111cf809a" />

</p>


---

## 📊 Labour Payment & Attendance Panel

<p align="center">
  <img width="407" height="805" alt="003" src="https://github.com/user-attachments/assets/d12ad4c3-89fc-4a97-823c-20bf1ebfc33f" />

</p>


---

# 📁 Project Structure

```text
contractors-labour-payment-tracking/
│
├── index.php
├── dashboard.php
├── login.php
├── logout.php
├── config.php
├── database.sql
├── README.md
│
├── assets/
│   ├── css/
│   ├── js/
│   ├── images/
│   └── bootstrap/
│
├── workers/
│   ├── add_worker.php
│   ├── manage_workers.php
│   └── worker_profile.php
│
├── attendance/
│   ├── mark_attendance.php
│   ├── attendance_history.php
│   └── reports.php
│
├── payments/
│   ├── add_payment.php
│   ├── payment_history.php
│   └── pending_dues.php
│
└── expenses/
    ├── add_expense.php
    └── expense_reports.php
```

---

# 🛠️ Technologies Used

| Technology | Purpose |
|---|---|
| PHP | Backend Development |
| MySQL | Database Management |
| Bootstrap | Responsive UI Design |
| JavaScript | Frontend Interactivity |
| XAMPP | Local Development Server |

---

# 📋 Prerequisites

Before starting, ensure the following are installed:

| Requirement | Version |
|---|---|
| PHP | 8.0+ |
| MySQL | Latest |
| XAMPP / WAMP | Recommended |
| Browser | Chrome / Edge |

---

# ⚙️ Installation & Setup

## 1️⃣ Clone Repository

```bash
git clone https://github.com/Pratik03538/contractors-labour-payment-tracking.git

cd contractors-labour-payment-tracking
```

---

## 2️⃣ Move Project To XAMPP htdocs

Copy the project folder into:

```text
C:\xampp\htdocs\
```

---

## 3️⃣ Start Apache & MySQL

Open **XAMPP Control Panel** and start:

- Apache
- MySQL

---

## 4️⃣ Import Database

1. Open phpMyAdmin
2. Create a database
3. Import `lms.sql`

---

## 5️⃣ Configure Database Connection

Open:

```text
config.php
```

Update database credentials:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "lms";
```

---

# 🖥️ Run The Project

Open browser and visit:

```text
http://localhost/contractors-labour-payment-tracking
```

---

# 🔐 Demo Login Credentials

For testing and demo purposes, use the following login credentials:

```text
Mobile NO : 1111111111
Password: pratik
```

> 📌 These credentials are intended only for local testing and development.
> Change default credentials before deploying in a production environment.


## Database Structure

### contractor
Stores contractor account information and login credentials.

| Column | Type | Description |
|---|---|---|
| id | INT | Primary Key |
| name | VARCHAR | Contractor Name |
| mobile | VARCHAR | Login Mobile Number |
| password | VARCHAR | Encrypted Password |
| photo | VARCHAR | Profile Photo |

---

### labours
Stores labour/worker details linked to contractors.

| Column | Type | Description |
|---|---|---|
| id | INT | Primary Key |
| contractor_id | INT | Linked Contractor ID |
| name | VARCHAR | Labour Name |
| mobile | VARCHAR | Labour Mobile Number |

---

### attendance
Stores daily attendance records for workers.

| Column | Type | Description |
|---|---|---|
| id | INT | Primary Key |
| labour_id | INT | Labour ID |
| date | DATE | Attendance Date |
| status | VARCHAR | full / half |
| custom_amount | DECIMAL | Custom Wage |
| group_id | INT | Group Reference |
| contractor_id | INT | Contractor ID |
| confirmed_at | TIMESTAMP | Attendance Confirmation Time |
| type | VARCHAR | Attendance Type |
| attendance_type | VARCHAR | Attendance Category |
| is_removed | TINYINT | Soft Delete Flag |
| bonus | DECIMAL | Extra Bonus |

---

### payments
Stores salary and payment history of workers.

| Column | Type | Description |
|---|---|---|
| id | INT | Primary Key |
| labour_id | INT | Labour ID |
| total_amount | DECIMAL | Total Salary |
| payed_amount | DECIMAL | Paid Amount |
| remaining_amount | DECIMAL | Pending Amount |
| from_date | DATE | Salary Start Date |
| to_date | DATE | Salary End Date |
| created_at | TIMESTAMP | Payment Entry Time |
| note | TEXT | Payment Notes |

---

### bonus
Stores bonus records for workers.

| Column | Type | Description |
|---|---|---|
| id | INT | Primary Key |
| labour_id | INT | Labour ID |
| contractor_id | INT | Contractor ID |
| amount | DECIMAL | Bonus Amount |
| date | DATE | Bonus Date |

---

### labour_groups
Stores labour grouping information.

| Column | Type | Description |
|---|---|---|
| id | INT | Primary Key |
| contractor_id | INT | Contractor ID |
| group_name | VARCHAR | Labour Group Name |

---

### labour_group_members
Stores mapping between labour and groups.

| Column | Type | Description |
|---|---|---|
| id | INT | Primary Key |
| labour_id | INT | Labour ID |
| group_id | INT | Group ID |

---

### attendance_groups
Stores grouped attendance records.

| Column | Type | Description |
|---|---|---|
| id | INT | Primary Key |
| contractor_id | INT | Contractor ID |
| group_name | VARCHAR | Attendance Group Name |

---

### attendance_temp
Temporary attendance storage table.

| Column | Type | Description |
|---|---|---|
| id | INT | Primary Key |
| labour_id | INT | Labour ID |
| contractor_id | INT | Contractor ID |
| status | VARCHAR | Attendance Status |
| created_at | TIMESTAMP | Temporary Entry Time |



# 💎 System Highlights

- Lightweight & Fast
- Mobile Responsive UI
- Simple Labour Management
- Real-Time Payment Tracking
- Easy Attendance System
- Beginner-Friendly Setup
- Suitable for Contractors & Site Managers

---

# 🎯 Use Cases

This system is ideal for:

- Construction Contractors
- Site Supervisors
- Labour Managers
- Small Construction Companies
- Workforce Tracking
- Daily Wage Management

---

# ⚠️ Disclaimer

This project is intended for:

- Educational purposes
- Workforce management learning
- Contractor payment tracking
- Small business operations

Always maintain secure backups and proper authentication before deploying in production environments.

---

# ⭐ Support The Project

If you found this project useful:

- ⭐ Star the repository
- 🍴 Fork the project
- 🛠️ Contribute improvements
- 🧠 Share feedback

---

# 📜 License

This project is licensed under the MIT License.

---

<div align="center">

### 🚀 Built for Smart Contractor & Labour Management

</div>
