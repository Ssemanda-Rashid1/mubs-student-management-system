@extends('layouts.app')
@section('title', 'Students')
@section('content')
<div class="actions" style="justify-content:space-between"><h1>Students</h1><a class="button" href="{{ route('students.create') }}">Add student</a></div>
<form method="GET" action="{{ route('students.index') }}" class="mb-6 flex gap-2">
    <input type="text" name="search" value="{{ $search ?? '' }}" 
           placeholder="Search by Name, Reg No, or Email..." 
           class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Search</button>
    <a href="{{ route('students.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Reset</a>
</form>
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
