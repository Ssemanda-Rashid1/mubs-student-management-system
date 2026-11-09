<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lecturer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::with(['lecturer.user', 'students'])
            ->when(Auth::user()->hasRole('lecturer'), fn ($query) => $query->whereHas(
                'lecturer',
                fn ($lecturers) => $lecturers->where('user_id', Auth::id()),
            ))
            ->orderBy('code')
            ->paginate(15);

        return view('courses.index', ['courses' => $courses]);
    }

    public function show(Course $course): View
    {
        if (Auth::user()->hasRole('lecturer')) {
            abort_unless($course->lecturer()->where('user_id', Auth::id())->exists(), 403);
            $course->load('students.user');
        }

        return view('courses.show', ['course' => $course->loadMissing('lecturer.user')]);
    }

    public function create(): View
    {
        return view('courses.form', ['course' => new Course(), 'lecturers' => $this->lecturers()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Course::create($this->validated($request));

        return redirect()->route('courses.index')->with('status', 'Course created.');
    }

    public function edit(Course $course): View
    {
        return view('courses.form', ['course' => $course, 'lecturers' => $this->lecturers()]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $course->update($this->validated($request, $course));

        return redirect()->route('courses.index')->with('status', 'Course updated.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()->route('courses.index')->with('status', 'Course and its registrations deleted.');
    }

    private function lecturers()
    {
        return Lecturer::with('user')->orderBy('employee_number')->get();
    }

    private function validated(Request $request, ?Course $course = null): array
    {
        return $request->validate([
            'lecturer_id' => ['required', 'integer', Rule::exists('lecturers', 'id')],
            'code' => ['required', 'string', 'max:50', Rule::unique('courses', 'code')->ignore($course?->id)],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'credit_hours' => ['required', 'integer', 'between:1,12'],
        ]);
    }
}
