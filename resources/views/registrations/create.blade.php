@extends('layouts.app')
@section('title', 'Register for a course')
@section('content')
<section class="card" style="max-width: 650px">
    <h1>Register for a course</h1>
    <form method="POST" action="{{ route('registrations.store') }}">
        @csrf
        <label for="course_id">Available course</label>
        <select id="course_id" name="course_id" required>
            <option value="">Select a course</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->code }} — {{ $course->title }} ({{ $course->lecturer->user->name }})</option>
            @endforeach
        </select>
        @if ($courses->isEmpty()) <p class="muted">You are already registered for every available course.</p> @endif
        <p class="actions"><button type="submit" @disabled($courses->isEmpty())>Register</button><a class="button secondary" href="{{ route('registrations.index') }}">Cancel</a></p>
    </form>
</section>
@endsection
