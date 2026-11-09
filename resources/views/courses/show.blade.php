@extends('layouts.app')
@section('title', $course->code)
@section('content')
<section class="card">
    <p class="muted">{{ $course->code }} · {{ $course->credit_hours }} credit hours</p>
    <h1>{{ $course->title }}</h1>
    <p>{{ $course->description ?: 'No course description provided.' }}</p>
    <p><strong>Lecturer:</strong> {{ $course->lecturer->user->name }}</p>
    @role('lecturer')
        <h2>Registered students</h2>
        <table>
            <thead><tr><th>Student number</th><th>Name</th><th>Email</th></tr></thead>
            <tbody>
            @forelse ($course->students as $student)
                <tr><td>{{ $student->student_number }}</td><td>{{ $student->user->name }}</td><td>{{ $student->user->email }}</td></tr>
            @empty <tr><td colspan="3">No students are registered yet.</td></tr> @endforelse
            </tbody>
        </table>
    @endrole
    <p><a class="button secondary" href="{{ route('courses.index') }}">Back to courses</a></p>
</section>
@endsection
