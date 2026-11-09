@extends('layouts.app')
@section('title', 'Students')
@section('content')
<div class="actions" style="justify-content:space-between"><h1>Students</h1><a class="button" href="{{ route('students.create') }}">Add student</a></div>
<table>
    <thead><tr><th>Student number</th><th>Name</th><th>Email</th><th>Actions</th></tr></thead>
    <tbody>
    @forelse ($students as $student)
        <tr>
            <td>{{ $student->student_number }}</td><td>{{ $student->user->name }}</td><td>{{ $student->user->email }}</td>
            <td class="actions">
                <a class="button secondary" href="{{ route('students.edit', $student) }}">Edit</a>
                <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Delete this student account and its registrations?')">
                    @csrf @method('DELETE')<button class="danger" type="submit">Delete</button>
                </form>
            </td>
        </tr>
    @empty <tr><td colspan="4">No students found.</td></tr> @endforelse
    </tbody>
</table>
<p>{{ $students->links() }}</p>
@endsection
