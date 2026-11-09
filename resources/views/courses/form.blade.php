@extends('layouts.app')
@section('title', $course->exists ? 'Edit course' : 'Add course')
@section('content')
<section class="card" style="max-width: 700px">
    <h1>{{ $course->exists ? 'Edit course' : 'Add course' }}</h1>
    <form method="POST" action="{{ $course->exists ? route('courses.update', $course) : route('courses.store') }}">
        @csrf
        @if ($course->exists) @method('PUT') @endif
        <label for="code">Course code</label>
        <input id="code" name="code" value="{{ old('code', $course->code) }}" required maxlength="50">
        <label for="title">Title</label>
        <input id="title" name="title" value="{{ old('title', $course->title) }}" required maxlength="255">
        <label for="lecturer_id">Lecturer</label>
        <select id="lecturer_id" name="lecturer_id" required>
            <option value="">Select a lecturer</option>
            @foreach ($lecturers as $lecturer)
                <option value="{{ $lecturer->id }}" @selected(old('lecturer_id', $course->lecturer_id) == $lecturer->id)>{{ $lecturer->user->name }} ({{ $lecturer->employee_number }})</option>
            @endforeach
        </select>
        <label for="credit_hours">Credit hours</label>
        <input id="credit_hours" name="credit_hours" type="number" min="1" max="12" value="{{ old('credit_hours', $course->credit_hours ?? 3) }}" required>
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4" maxlength="5000">{{ old('description', $course->description) }}</textarea>
        <p class="actions"><button type="submit">Save course</button><a class="button secondary" href="{{ route('courses.index') }}">Cancel</a></p>
    </form>
</section>
@endsection
