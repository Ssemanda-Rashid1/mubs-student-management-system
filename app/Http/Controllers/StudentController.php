<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(): View
    {
        return view('students.index', [
            'students' => Student::with('user')->orderBy('student_number')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('students.form', ['student' => new Student()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($data): void {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => User::ROLE_STUDENT,
            ]);
            $user->student()->create(['student_number' => $data['student_number']]);
            $user->assignRole('student');
        });

        return redirect()->route('students.index')->with('status', 'Student account created.');
    }

    public function edit(Student $student): View
    {
        return view('students.form', ['student' => $student->load('user')]);
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $data = $this->validated($request, $student);

        DB::transaction(function () use ($data, $student): void {
            $student->user->update(['name' => $data['name'], 'email' => $data['email']]);
            $student->update(['student_number' => $data['student_number']]);

            if (($data['password'] ?? null) !== null) {
                $student->user->update(['password' => $data['password']]);
            }
        });

        return redirect()->route('students.index')->with('status', 'Student record updated.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->user->delete();

        return redirect()->route('students.index')->with('status', 'Student record deleted.');
    }

    private function validated(Request $request, ?Student $student = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($student?->user_id)],
            'student_number' => ['required', 'string', 'max:255', Rule::unique('students', 'student_number')->ignore($student?->id)],
            'password' => [$student ? 'nullable' : 'required', 'string', 'min:8'],
        ]);
    }
}
