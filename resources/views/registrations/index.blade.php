@extends('layouts.app')
@section('title', 'Registrations')
@section('content')
<div class="actions" style="justify-content:space-between">
    <div><h1>{{ auth()->user()->hasRole('student') ? 'My registrations' : 'Course registrations' }}</h1>
        <p class="muted">{{ auth()->user()->hasRole('lecturer') ? 'Registrations in your assigned courses.' : 'Course and student registration records.' }}</p>
    </div>
    @can('register for courses') <a class="button" href="{{ route('registrations.create') }}">Register for a course</a> @endcan
</div>
<table>
    <thead><tr>@unless(auth()->user()->hasRole('student'))<th>Student</th>@endunless<th>Course</th><th>Lecturer</th><th>Status</th><th>Registered</th>@can('manage registrations')<th>Action</th>@endcan</tr></thead>
    <tbody>
    @forelse ($registrations as $registration)
        <tr>
            @unless(auth()->user()->hasRole('student'))<td>{{ $registration->student->user->name }} ({{ $registration->student->student_number }})</td>@endunless
            <td>{{ $registration->course->code }} — {{ $registration->course->title }}</td>
            <td>{{ $registration->course->lecturer->user->name }}</td><td>{{ ucfirst($registration->status) }}</td><td>{{ $registration->created_at->format('M j, Y') }}</td>
            @can('manage registrations')
                <td><form method="POST" action="{{ route('registrations.destroy', $registration) }}" onsubmit="return confirm('Remove this registration?')">@csrf @method('DELETE')<button class="danger" type="submit">Remove</button></form></td>
            @endcan
        </tr>
    @empty <tr><td colspan="6">No registrations found.</td></tr> @endforelse
    </tbody>
</table>
<p>{{ $registrations->links() }}</p>
@endsection
