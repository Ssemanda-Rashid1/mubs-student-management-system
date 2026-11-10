@extends('layouts.app')
@section('title', 'Students')
@section('content')
<div class="actions" style="justify-content:space-between; margin-bottom: 1rem;">
    <h1>Students</h1>
    <a class="button" href="{{ route('students.create') }}">Add student</a>
</div>

<form method="GET" action="{{ route('students.index') }}" style="display: flex; gap: .5rem; margin-bottom: 1.5rem; align-items: center;">
    <input type="text" name="search" value="{{ $search ?? '' }}" 
           placeholder="Search by Name, Reg No, or Email..." 
           style="max-width: 350px;">
    <button type="submit" class="button">Search</button>
    <a href="{{ route('students.index') }}" class="button secondary" style="text-decoration: none;">Reset</a>
</form>

<table>
    <thead>
        <tr>
            <th>Student number</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @forelse ($students as $student)
        <tr>
            <td>{{ $student->student_number }}</td>
            <td>{{ $student->user->name ?? 'N/A' }}</td>
            <td>{{ $student->user->email ?? 'N/A' }}</td>
            <td class="actions">
                <a class="button secondary" href="{{ route('students.edit', $student) }}">Edit</a>
                <form method="POST" action="{{ route('students.destroy', $student) }}" class="inline" onsubmit="return confirm('Delete this student account and its registrations?')">
                    @csrf 
                    @method('DELETE')
                    <button class="danger" type="submit">Delete</button>
                </form>
            </td>
        </tr>
    @empty 
        <tr>
            <td colspan="4">No students found.</td>
        </tr> 
    @endforelse
    </tbody>
</table>

<div style="margin-top: 1.5rem;">
    {{ $students->links() }}
</div>
@endsection