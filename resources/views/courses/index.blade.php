@extends('layouts.app')
@section('title', 'Courses')
@section('content')
<div class="actions" style="justify-content:space-between">
    <div><h1>{{ auth()->user()->hasRole('lecturer') ? 'My assigned courses' : 'Courses' }}</h1><p class="muted">Browse available courses and view course details.</p></div>
    @can('manage courses') <a class="button" href="{{ route('courses.create') }}">Add course</a> @endcan
</div>
<table>
    <thead><tr><th>Code</th><th>Course</th><th>Lecturer</th><th>Credits</th>@unless(auth()->user()->hasRole('student'))<th>Registered students</th>@endunless<th>Actions</th></tr></thead>
    <tbody>
    @forelse ($courses as $course)
        <tr>
            <td>{{ $course->code }}</td><td>{{ $course->title }}</td>
            <td>{{ $course->lecturer->user->name }}</td><td>{{ $course->credit_hours }}</td>
            @unless(auth()->user()->hasRole('student'))<td>{{ $course->students->count() }}</td>@endunless
            <td class="actions">
                <a class="button secondary" href="{{ route('courses.show', $course) }}">Details</a>
                @can('manage courses')
                    <a class="button secondary" href="{{ route('courses.edit', $course) }}">Edit</a>
                    <form method="POST" action="{{ route('courses.destroy', $course) }}" onsubmit="return confirm('Delete this course and its registrations?')">
                        @csrf @method('DELETE')<button class="danger" type="submit">Delete</button>
                    </form>
                @endcan
            </td>
        </tr>
    @empty <tr><td colspan="6">No courses found.</td></tr> @endforelse
    </tbody>
</table>
<p>{{ $courses->links() }}</p>
@endsection
