PLACEMENT MANAGEMENT SYSTEM — MAJOR PROJECT 1
================================================

ABOUT
A beginner-friendly Placement Management System built with HTML, CSS, JavaScript,
PHP and MySQL. It includes sample/demo data and working CRUD features.

FEATURES
- Admin login
- Dashboard with student, company, active job and application totals
- Student management: Create, Read, Update, Delete + search
- Company management: Create, Read, Update, Delete
- Job management: Create, Read, Update, Delete
- Applications: view, update status (Pending/Shortlisted/Selected/Rejected), delete
- Prepared SQL statements, password hashing, session login and CSRF form tokens

REQUIREMENTS
- XAMPP (Apache + MySQL)
- Web browser
- Optional: VS Code

INSTALLATION (XAMPP)
1. Extract the folder "Placement_Management_System".
2. Copy the complete folder into: C:\xampp\htdocs\
   Final path should be: C:\xampp\htdocs\Placement_Management_System
3. Open XAMPP Control Panel and start Apache and MySQL.
4. Open http://localhost/phpmyadmin
5. Click Import, choose:
   Placement_Management_System/database/placement_management.sql
   then click Import/Go.
   (The SQL creates the database and inserts fictional sample data.)
6. Open:
   http://localhost/Placement_Management_System/

DEMO LOGIN
Username: admin
Password: password

IMPORTANT
- The included student/company/job/application records are fictional sample data.
- The project is intended for local classroom demonstration, not public production use.
- For production, use a unique strong admin password, HTTPS, stricter validation,
  role-based permissions and additional security review.
- If your MySQL root account has a password, update DB_PASS in config.php.
- Job dates in the sample data are demonstration dates and can be edited.

CRUD DEMONSTRATION FOR VIVA
1. Students: add a student, see it in the list, edit it, then delete it.
2. Companies: add/edit/delete a company.
3. Jobs: add/edit/delete a job opening.
4. Applications: change a status from Pending to Shortlisted/Selected/Rejected.

PROJECT TECHNOLOGIES
HTML5, CSS3, JavaScript, PHP (PDO), MySQL.
