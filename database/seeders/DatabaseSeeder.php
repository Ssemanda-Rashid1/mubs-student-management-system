<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'manage students',
            'manage courses',
            'manage registrations',
            'manage users',
            'view courses',
            'view registrations',
            'register for courses',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $lecturerRole = Role::firstOrCreate(['name' => 'lecturer', 'guard_name' => 'web']);
        $studentRole = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        $adminRole->syncPermissions($permissions);
        $lecturerRole->syncPermissions(['view courses', 'view registrations']);
        $studentRole->syncPermissions(['view courses', 'view registrations', 'register for courses']);

        User::firstOrCreate(
            ['email' => 'admin@university.test'],
            ['name' => 'University Administrator', 'password' => 'password', 'role' => User::ROLE_ADMIN],
        )->syncRoles($adminRole);

        $lecturers = collect([
            ['name' => 'Ada Lecturer', 'email' => 'ada.lecturer@university.test', 'number' => 'LEC001', 'department' => 'Computer Science'],
            ['name' => 'Alan Lecturer', 'email' => 'alan.lecturer@university.test', 'number' => 'LEC002', 'department' => 'Mathematics'],
        ])->mapWithKeys(function (array $data) use ($lecturerRole): array {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => 'password', 'role' => User::ROLE_LECTURER],
            );
            $user->forceFill(['role' => User::ROLE_LECTURER])->save();
            $user->syncRoles($lecturerRole);

            $lecturer = Lecturer::updateOrCreate(
                ['user_id' => $user->id],
                ['employee_number' => $data['number'], 'department' => $data['department']],
            );

            return [$data['number'] => $lecturer];
        });

        $studentData = [
            ['name' => 'Maya Student', 'email' => 'maya.student@university.test', 'number' => 'STU001'],
            ['name' => 'Noah Student', 'email' => 'noah.student@university.test', 'number' => 'STU002'],
            ['name' => 'Zara Student', 'email' => 'zara.student@university.test', 'number' => 'STU003'],
            ['name' => 'Ethan Student', 'email' => 'ethan.student@university.test', 'number' => 'STU004'],
            ['name' => 'Leah Student', 'email' => 'leah.student@university.test', 'number' => 'STU005'],
        ];

        $students = collect($studentData)->map(function (array $data) use ($studentRole): Student {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => 'password', 'role' => User::ROLE_STUDENT],
            );
            $user->forceFill(['role' => User::ROLE_STUDENT])->save();
            $user->syncRoles($studentRole);

            return Student::updateOrCreate(
                ['user_id' => $user->id],
                ['student_number' => $data['number']],
            );
        });

        $courses = collect([
            ['code' => 'CS101', 'title' => 'Introduction to Computing', 'lecturer' => 'LEC001'],
            ['code' => 'CS202', 'title' => 'Database Systems', 'lecturer' => 'LEC001'],
            ['code' => 'MA101', 'title' => 'Calculus I', 'lecturer' => 'LEC002'],
            ['code' => 'MA202', 'title' => 'Linear Algebra', 'lecturer' => 'LEC002'],
        ])->map(function (array $data) use ($lecturers): Course {
            return Course::updateOrCreate(
                ['code' => $data['code']],
                [
                    'lecturer_id' => $lecturers[$data['lecturer']]->id,
                    'title' => $data['title'],
                    'credit_hours' => 3,
                ],
            );
        });

        foreach ($students as $student) {
            foreach ($courses->take(2) as $course) {
                CourseRegistration::firstOrCreate([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                ]);
            }
        }
    }
}
