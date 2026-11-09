@extends('layouts.app')
@section('title', $student->exists ? 'Edit student' : 'Add student')
@section('content')
<section class="card" style="max-width: 620px">
    <h1>{{ $student->exists ? 'Edit student' : 'Add student' }}</h1>
    <form method="POST" action="{{ $student->exists ? route('students.update', $student) : route('students.store') }}">
        @csrf
        @if ($student->exists) @method('PUT') @endif
        <label for="name">Full name</label>
        <input id="name" name="name" value="{{ old('name', $student->user->name ?? '') }}" required maxlength="255">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $student->user->email ?? '') }}" required maxlength="255">
        <label for="student_number">Student number</label>
        <input id="student_number" name="student_number" value="{{ old('student_number', $student->student_number) }}" required maxlength="255">
        <label for="password">{{ $student->exists ? 'New password (leave blank to keep current)' : 'Initial password' }}</label>
        <input id="password" name="password" type="password" {{ $student->exists ? '' : 'required' }} minlength="8" autocomplete="new-password">
        <p class="actions"><button type="submit">Save student</button><a class="button secondary" href="{{ route('students.index') }}">Cancel</a></p>
    </form>
</section>
@endsection
