<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function index(): View
    {
        $registrations = CourseRegistration::with(['student.user', 'course.lecturer.user'])
            ->when(Auth::user()->hasRole('student'), fn ($query) => $query->whereHas(
                'student',
                fn ($students) => $students->where('user_id', Auth::id()),
            ))
            ->when(Auth::user()->hasRole('lecturer'), fn ($query) => $query->whereHas(
                'course.lecturer',
                fn ($lecturers) => $lecturers->where('user_id', Auth::id()),
            ))
            ->latest()
            ->paginate(20);

        return view('registrations.index', ['registrations' => $registrations]);
    }

    public function create(): View
    {
        $student = Auth::user()->student;
        abort_unless($student, 403, 'A student profile is required to register for courses.');

        return view('registrations.create', [
            'courses' => Course::with('lecturer.user')
                ->whereDoesntHave('students', fn ($query) => $query->where('students.id', $student->id))
                ->orderBy('code')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $student = Auth::user()->student;
        abort_unless($student, 403, 'A student profile is required to register for courses.');

        $data = $request->validate([
            'course_id' => [
                'required',
                'integer',
                Rule::exists('courses', 'id'),
                Rule::unique('course_registrations', 'course_id')->where('student_id', $student->id),
            ],
        ], [
            'course_id.unique' => 'You are already registered for this course.',
        ]);

        CourseRegistration::create([
            'student_id' => $student->id,
            'course_id' => $data['course_id'],
        ]);

        return redirect()->route('registrations.index')->with('status', 'Course registration completed.');
    }

    public function destroy(CourseRegistration $registration): RedirectResponse
    {
        $registration->delete();

        return redirect()->route('registrations.index')->with('status', 'Registration removed.');
    }
}
