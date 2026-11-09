@extends('layouts.app')
@section('title', 'User access')
@section('content')
<h1>User access</h1>
<p class="muted">Assign a role to each account. Student accounts can only manage their own registrations; lecturers can only view courses assigned to them.</p>
<table>
    <thead><tr><th>User</th><th>Email</th><th>Current role</th><th>Update role</th></tr></thead>
    <tbody>
    @foreach ($users as $user)
        <tr>
            <td>{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->getRoleNames()->join(', ') ?: 'No role' }}</td>
            <td>
                <form class="actions" method="POST" action="{{ route('users.role', $user) }}">
                    @csrf @method('PATCH')
                    <select aria-label="Role for {{ $user->name }}" name="role" required>
                        @foreach (['admin' => 'Administrator', 'lecturer' => 'Lecturer', 'student' => 'Student'] as $role => $label)
                            <option value="{{ $role }}" @selected($user->hasRole($role))>{{ $label }}</option>
                        @endforeach
                    </select>
                    <input aria-label="Student number (required for student role)" name="student_number" placeholder="Student number" value="{{ $user->student?->student_number }}">
                    <input aria-label="Employee number (required for lecturer role)" name="employee_number" placeholder="Employee number" value="{{ $user->lecturer?->employee_number }}">
                    <input aria-label="Department (for lecturer role)" name="department" placeholder="Department" value="{{ $user->lecturer?->department }}">
                    <button type="submit">Save role</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<p>{{ $users->links() }}</p>
@endsection
