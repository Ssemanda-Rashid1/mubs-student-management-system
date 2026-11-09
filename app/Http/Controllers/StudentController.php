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
  public function index(Request $request)
    {
        $search = $request->input('search');

        $students = Student::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('reg_number', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
        })->paginate(10);

        return view('students.index', compact('students', 'search'));
    }

public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'reg_number'    => 'required|unique:students,reg_number',
            'email'         => 'required|email|unique:students,email',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('students', 'public');
            $validated['profile_photo'] = $path;
        }

        Student::create($validated);

        return redirect()->route('students.index')->with('success', 'Student profile created!');
    }
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
