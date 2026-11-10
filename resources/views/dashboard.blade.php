@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<h1>Welcome, {{ auth()->user()->name }}</h1>

@role('admin')
    <div class="actions" style="margin-bottom:1rem">
        <div class="card"><div class="muted">Students</div><h2>{{ $totalStudents }}</h2></div>
        <div class="card"><div class="muted">Courses</div><h2>{{ $totalCourses }}</h2></div>
        <div class="card"><div class="muted">Users</div><h2>{{ $totalUsers }}</h2></div>
    </div>

    <h2>Recently added students</h2>
    <table>
        <thead><tr><th>Student number</th><th>Name</th><th>Email</th><th>Added</th></tr></thead>
        <tbody>
        @forelse ($recentStudents as $student)
            <tr>
                <td>{{ $student->student_number }}</td>
                <td>{{ $student->user->name }}</td>
                <td>{{ $student->user->email }}</td>
                <td>{{ $student->created_at->format('M j, Y') }}</td>
            </tr>
        @empty
            <tr><td colspan="4">No students yet.</td></tr>
        @endforelse
        </tbody>
    </table>
@endrole

@role('lecturer')
    <h2>My assigned courses</h2>
    <table>
        <thead><tr><th>Code</th><th>Course</th><th>Registered students</th><th></th></tr></thead>
        <tbody>
        @forelse ($myCourses as $course)
            <tr>
                <td>{{ $course->code }}</td>
                <td>{{ $course->title }}</td>
                <td>{{ $course->students_count }}</td>
                <td><a class="button secondary" href="{{ route('courses.show', $course) }}">View students</a></td>
            </tr>
        @empty
            <tr><td colspan="4">No courses are assigned to you yet.</td></tr>
        @endforelse
        </tbody>
    </table>
@endrole

@role('student')
    <h2>My registrations</h2>
    <table>
        <thead><tr><th>Course</th><th>Status</th><th>Registered</th></tr></thead>
        <tbody>
        @forelse ($myRegistrations as $registration)
            <tr>
                <td>{{ $registration->course->code }} — {{ $registration->course->title }}</td>
                <td>{{ ucfirst($registration->status) }}</td>
                <td>{{ $registration->created_at->format('M j, Y') }}</td>
            </tr>
        @empty
            <tr><td colspan="3">You have not registered for any course yet.</td></tr>
        @endforelse
        </tbody>
    </table>
    <p><a class="button" href="{{ route('registrations.create') }}">Register for a course</a></p>
@endrole
@endsection