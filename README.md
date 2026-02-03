# 🎓 Student Information Management System (SIMS)

**🏆 Awarded 1st Position in Semester Project | Department of Software Engineering**

> A web-based university management system designed to automate academic processes including attendance, course allocation, result management, and payroll logic.

---

## 🚀 Key Features

### 👨‍💼 Admin Panel (HOD)
- **Dashboard:** Overview of total students, teachers, and active courses.
- **Dynamic Allocation:** Assign courses to teachers with **Batch-wise** separation.
- **User Management:** Add, edit, or remove Teachers and Students.
- **Bulk Import:** Add multiple students via CSV file.
- **System Logs:** Tracks every login and action for security.

- ### 👨‍🏫 Clerk Panel
- **Dashboard** View Total Students/Teachers/Staff and Present Teachers.
- **Teacher's Attendance:** Mark daily attendance (Present/Absent).
- **Manage Staff:** Manage Lower staff like Peon/Sweeper/Security Guard.
- **Reset Password:** **Reset Password of any Student If student forget his password.
- **Print Report:** Generate a Report of Any Student/Batch/Teacher/Staff with his personal details in the form of (PDF, Word, other).

### 👨‍🏫 Teacher Panel
- **Course Management:** View assigned subjects and batches.
- **Smart Attendance:** Mark daily attendance (Present/Absent/Leave).
- **Salary Logic:** **Automated deduction** system (If leaves > 3 days, salary deduction applies).
- **Result Ledger:** Upload Marks (Sessional, Mid, Final) and calculate GPA automatically.

### 👨‍🎓 Student Panel
- **Profile:** View personal details and enrollment info.
- **Result Card:** Check marks and grades for assigned subjects.
- **Attendance Status:** View attendance percentage.
  

---

## 🛠️ Technology Stack

| Component | Technology Used |
|-----------|----------------|
| **Frontend** | HTML5, CSS3, Bootstrap 5 (Responsive) |
| **Backend** | PHP 8.2 (Procedural/OOP) |
| **Database** | MySQL (Relational Schema) |
| **Server** | Apache (XAMPP) |

---

## ⚙️ How to Run This Project

### Step 1: Download
Download the project ZIP file or clone this repository to your PC.

### Step 2: Setup Server
1. Copy the project folder.
2. Paste it inside your XAMPP folder: `C:\xampp\htdocs\SWE_Department`

### Step 3: Configure Database
1. Open **XAMPP Control Panel** and start **Apache** & **MySQL**.
2. Go to your browser and type: `http://localhost/phpmyadmin`
3. Create a new database named **`swe_db`**.
4. Click **Import** and upload the `.sql` file provided in the `database/` folder.

### Step 4: Run
Open your browser and visit:
`http://localhost/SWE_Department`

---

## 🔑 Default Login Credentials

Use these accounts to test the system:

| Role | Username | Password |
|------|----------|----------|
| **Admin (HOD)** | `hod@quest.edu.pk` | `12345` |
| **Clerk** | `clerk@quest.edu.pk` | `12345` |
| **Teacher** | `name@quest.edu.pk` | `12345` |
| **Student** | `24SW01` | `12345` |


---

## 📸 Screenshots

---
<img width="432" height="570" alt="Login" src="https://github.com/user-attachments/assets/e05ebf10-e69f-457e-ad1e-c5bdd8c01718" />
<img width="419" height="609" alt="Invalid Username" src="https://github.com/user-attachments/assets/09211c05-018a-40b5-8319-dd3e6ae90e8b" />
<img width="1364" height="632" alt="HOD Panel" src="https://github.com/user-attachments/assets/0376053a-71cb-4a5b-a6eb-49c8678643b7" />
<img width="1363" height="631" alt="Clerk Panel" src="https://github.com/user-attachments/assets/cccc6834-42aa-46a2-a873-7dd80677249c" />
<img width="1343" height="399" alt="Print Report" src="https://github.com/user-attachments/assets/e9452771-e7b0-47c8-8cc7-b9ca5c0a737a" />
<img width="1360" height="632" alt="Teacher Panel" src="https://github.com/user-attachments/assets/2f2913bf-59d9-4aa1-9e39-1869154b9d8c" />
<img width="1075" height="54" alt="Sessional Marks (Valid)" src="https://github.com/user-attachments/assets/7466ec51-8ff5-4269-9ec8-28c7208f1319" />
<img width="1076" height="89" alt="Sessional Marks (Invalid)" src="https://github.com/user-attachments/assets/71a02802-d675-4492-b424-f5335bbdaf54" />
<img width="1343" height="353" alt="Manage Courses" src="https://github.com/user-attachments/assets/fc947ed1-2f34-4fc9-9f72-bb3a608461aa" />
<img width="1357" height="626" alt="Student Panel" src="https://github.com/user-attachments/assets/6cd678d4-1b10-4464-a3be-b5d15cafe226" />
<img width="1358" height="630" alt="Student Marks" src="https://github.com/user-attachments/assets/0d214117-3e82-4511-8475-41cc64cc2739" />


### 👨‍💻 Developed By
**Group Members:**
 **1. Muhammad Musawar**
 **2. Muhammad Paryal**
 **3. Shahriyar Ahmed**
 **4. Zafar Abbas**
## Software Engineering Students


