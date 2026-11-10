# Student Management System

A Laravel application for managing students, courses, lecturers and course registrations.
Built for BUC3131 Advanced Web Application Development, Coursework Two (2026/2027), Makerere University Business School.

## Group Members

| Name | Registration No. | Feature Branch |
|---|---|---|
| [Name] | [Reg No] | [feature/...] |
| [Name] | [Reg No] | [feature/...] |

## Requirements

- PHP [version]
- Composer
- MySQL
- Git

## Installation

1. Clone the repository:
   ```bash
   git clone [repository URL]
   cd [project folder]
   ```
2. Install dependencies:
   ```bash
   composer install
   ```
3. Copy the environment file:
   ```bash
   cp .env.example .env
   ```
4. Create an empty MySQL database named `studentmgtsystem`.
5. Open `.env` and set your database details:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=studentmgtsystem
   DB_USERNAME=your_mysql_username
   DB_PASSWORD=your_mysql_password
   ```
6. Generate the application key:
   ```bash
   php artisan key:generate
   ```
7. Create the tables and load the sample data:
   ```bash
   php artisan migrate --seed
   ```
8. Start the server:
   ```bash
   php artisan serve
   ```
9. Open http://127.0.0.1:8000/login

To reset the database completely, run `php artisan migrate:fresh --seed`.

## Database

- Users have one role: `admin`, `lecturer` or `student` (Spatie Laravel Permission).
- Students and lecturers have their own profile records linked to a user.
- Each course belongs to a lecturer.
- Students and courses are linked through course registrations.
- Foreign keys enforce referential integrity, and a unique student/course pair prevents duplicate registrations.
- Seeders can be run repeatedly without creating duplicate records.

## User Roles

| Role | What they can do |
|---|---|
| Administrator | Manage students, courses, registrations and user access |
| Lecturer | View their assigned courses and the students registered in them |
| Student | View available courses and manage their own registrations |

Students can create their own accounts at `/register`. These accounts are always given the student role.

## Sample Login Credentials

All seeded accounts use the password `password`.

| Role | Email |
|---|---|
| Administrator | admin@university.test |
| Lecturer | ada.lecturer@university.test |
| Lecturer | alan.lecturer@university.test |
| Student | maya.student@university.test |
| Student | noah.student@university.test |
| Student | zara.student@university.test |
| Student | ethan.student@university.test |
| Student | leah.student@university.test |

Change these passwords before deploying the application.

## Eloquent Examples

```php
$student->courses;   // courses a student is registered for
$course->students;   // students registered for a course
$lecturer->courses;  // courses assigned to a lecturer
```

## Screenshots

### Login
![Login](docs/screenshots/01-login.png)

### Admin: Students
![Admin students](docs/screenshots/02-admin-students.png)

### Courses
![Courses](docs/screenshots/03-courses.png)

### Course Registration
![Registration](docs/screenshots/04-registration.png)

### Duplicate Registration Error
![Duplicate error](docs/screenshots/05-duplicate-error.png)

### Lecturer: Assigned Courses
![Lecturer view](docs/screenshots/06-lecturer.png)

### Student: My Registrations
![Student view](docs/screenshots/07-student.png)