<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Permissions
        $permissions = [
            'manage students',
            'manage courses',
            'view courses',
            'view registrations',
            'register for courses',
            'manage registrations',
            'manage users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $lecturer = Role::firstOrCreate(['name' => 'lecturer', 'guard_name' => 'web']);
        $student = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        $admin->syncPermissions($permissions);
        $lecturer->syncPermissions(['view courses', 'view registrations']);
        $student->syncPermissions(['view courses', 'view registrations', 'register for courses']);

        // Users
        $makeUser = function (string $name, string $email, string $role): User {
            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => $name, 'password' => 'password', 'role' => $role]
            );
            $user->syncRoles($role);

            return $user;
        };

        $makeUser('System Administrator', 'admin@university.test', 'admin');

        // Lecturers
        $ada = Lecturer::firstOrCreate(
            ['user_id' => $makeUser('Ada Lovelace', 'ada.lecturer@university.test', 'lecturer')->id],
            ['employee_number' => 'LEC001', 'department' => 'Computing']
        );
        $alan = Lecturer::firstOrCreate(
            ['user_id' => $makeUser('Alan Turing', 'alan.lecturer@university.test', 'lecturer')->id],
            ['employee_number' => 'LEC002', 'department' => 'Mathematics']
        );

        // Students
        $studentData = [
            ['Maya Student', 'maya.student@university.test', 'STU001'],
            ['Noah Student', 'noah.student@university.test', 'STU002'],
            ['Zara Student', 'zara.student@university.test', 'STU003'],
            ['Ethan Student', 'ethan.student@university.test', 'STU004'],
            ['Leah Student', 'leah.student@university.test', 'STU005'],
        ];

        $students = [];
        foreach ($studentData as [$name, $email, $number]) {
            $students[$number] = Student::firstOrCreate(
                ['user_id' => $makeUser($name, $email, 'student')->id],
                ['student_number' => $number]
            );
        }

        // Courses
        $courseData = [
            ['CS101', 'Introduction to Computing', $ada, 'Fundamentals of computing and programming.', 3],
            ['CS201', 'Database Systems', $ada, 'Relational design, SQL and normalisation.', 4],
            ['MA101', 'Calculus I', $alan, 'Limits, derivatives and integrals.', 3],
            ['MA201', 'Business Statistics', $alan, 'Descriptive and inferential statistics.', 3],
        ];

        $courses = [];
        foreach ($courseData as [$code, $title, $owner, $description, $credits]) {
            $courses[$code] = Course::firstOrCreate(
                ['code' => $code],
                [
                    'lecturer_id' => $owner->id,
                    'title' => $title,
                    'description' => $description,
                    'credit_hours' => $credits,
                ]
            );
        }

        // Registrations
        $registrations = [
            'CS101' => ['STU001', 'STU002', 'STU003', 'STU004', 'STU005'],
            'CS201' => ['STU001', 'STU002', 'STU003'],
            'MA101' => ['STU004', 'STU005'],
        ];

        foreach ($registrations as $code => $numbers) {
            foreach ($numbers as $number) {
                CourseRegistration::firstOrCreate(
                    ['student_id' => $students[$number]->id, 'course_id' => $courses[$code]->id],
                    ['status' => 'registered']
                );
            }
        }
    }
}