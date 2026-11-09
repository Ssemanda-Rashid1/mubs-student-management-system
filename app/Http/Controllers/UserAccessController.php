<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserAccessController extends Controller
{
    public function index(): View
    {
        return view('users.index', [
            'users' => User::with('roles')->orderBy('name')->paginate(20),
        ]);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(['admin', 'lecturer', 'student'])],
            'student_number' => ['nullable', 'required_if:role,student', 'string', 'max:255', Rule::unique('students', 'student_number')->ignore($user->student?->id)],
            'employee_number' => ['nullable', 'required_if:role,lecturer', 'string', 'max:255', Rule::unique('lecturers', 'employee_number')->ignore($user->lecturer?->id)],
            'department' => ['nullable', 'string', 'max:255'],
        ]);

        abort_if($user->is(auth()->user()) && $data['role'] !== 'admin', 422, 'You cannot remove your own administrator access.');

        DB::transaction(function () use ($data, $user): void {
            if ($data['role'] === 'student') {
                $user->student()->updateOrCreate([], ['student_number' => $data['student_number']]);
            }

            if ($data['role'] === 'lecturer') {
                $user->lecturer()->updateOrCreate([], [
                    'employee_number' => $data['employee_number'],
                    'department' => $data['department'] ?? null,
                ]);
            }

            $user->syncRoles($data['role']);
            $user->forceFill(['role' => $data['role']])->save();
        });

        return redirect()->route('users.index')->with('status', 'User role updated.');
    }
}
