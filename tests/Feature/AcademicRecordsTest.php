<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicRecordsTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeders_create_idempotent_users_courses_and_registrations(): void
    {
        $this->seed();
        $this->seed();

        $this->assertDatabaseCount('students', 5);
        $this->assertDatabaseCount('lecturers', 2);
        $this->assertDatabaseCount('courses', 4);
        $this->assertDatabaseCount('course_registrations', 10);
        $this->assertSame(User::ROLE_ADMIN, User::where('email', 'admin@university.test')->value('role'));

        $student = Student::where('student_number', 'STU001')->firstOrFail();
        $this->assertCount(2, $student->courses);

        $course = Course::where('code', 'CS101')->firstOrFail();
        $this->assertCount(5, $course->students);
        $this->assertSame('LEC001', $course->lecturer->employee_number);
    }

    public function test_a_student_cannot_register_for_the_same_course_twice(): void
    {
        $this->seed();
        $registration = CourseRegistration::firstOrFail();

        $this->expectException(QueryException::class);

        CourseRegistration::create([
            'student_id' => $registration->student_id,
            'course_id' => $registration->course_id,
        ]);
    }

    public function test_students_can_only_access_their_registration_pages_and_cannot_manage_students(): void
    {
        $this->seed();
        $student = Student::where('student_number', 'STU001')->firstOrFail();
        $this->actingAs($student->user);

        $this->get('/registrations')->assertOk()->assertSee('My registrations');
        $this->get('/courses')->assertOk()->assertSee('Introduction to Computing');
        $this->get('/students')->assertForbidden();

        $this->post('/registrations', ['course_id' => Course::where('code', 'CS101')->value('id')])
            ->assertSessionHasErrors('course_id');
    }

    public function test_lecturers_only_see_assigned_courses_and_cannot_open_another_lecturers_course(): void
    {
        $this->seed();
        $lecturer = User::where('email', 'ada.lecturer@university.test')->firstOrFail();
        $this->actingAs($lecturer);

        $this->get('/courses')->assertOk()->assertSee('Database Systems')->assertDontSee('Calculus I');
        $this->get('/courses/'.Course::where('code', 'MA101')->value('id'))->assertForbidden();
    }

    public function test_student_self_registration_only_assigns_the_student_role(): void
    {
        $this->seed();

        $this->post('/register', [
            'name' => 'New Student',
            'email' => 'new.student@university.test',
            'student_number' => 'STU006',
            'password' => 'a-strong-password',
            'password_confirmation' => 'a-strong-password',
        ])->assertRedirect('/dashboard');

        $user = User::where('email', 'new.student@university.test')->firstOrFail();
        $this->assertTrue($user->hasRole('student'));
        $this->assertFalse($user->hasRole('admin'));
        $this->assertDatabaseHas('students', ['user_id' => $user->id, 'student_number' => 'STU006']);
    }

    public function test_administrators_can_create_student_and_course_records(): void
    {
        $this->seed();
        $admin = User::where('email', 'admin@university.test')->firstOrFail();
        $this->actingAs($admin);

        $this->post('/students', [
            'name' => 'Created Student',
            'email' => 'created.student@university.test',
            'student_number' => 'STU006',
            'password' => 'a-strong-password',
        ])->assertRedirect('/students');

        $this->post('/courses', [
            'lecturer_id' => $admin->newQuery()->where('email', 'ada.lecturer@university.test')->firstOrFail()->lecturer->id,
            'code' => 'CS303',
            'title' => 'Web Application Development',
            'credit_hours' => 3,
        ])->assertRedirect('/courses');

        $this->assertDatabaseHas('courses', ['code' => 'CS303', 'title' => 'Web Application Development']);
    }
}
