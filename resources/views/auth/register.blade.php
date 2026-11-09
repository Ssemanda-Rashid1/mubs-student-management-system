@extends('layouts.app')
@section('title', 'Create student account')
@section('content')
<section class="card" style="max-width: 520px; margin: 2rem auto;">
    <h1>Create a student account</h1>
    <p class="muted">Student accounts receive student access. Administrators assign staff roles.</p>
    <form method="POST" action="{{ route('register.store') }}">
        @csrf
        <label for="name">Full name</label>
        <input id="name" name="name" value="{{ old('name') }}" required maxlength="255" autocomplete="name">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email">
        <label for="student_number">Student number</label>
        <input id="student_number" name="student_number" value="{{ old('student_number') }}" required maxlength="255">
        <label for="password">Password</label>
        <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password">
        <label for="password_confirmation">Confirm password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password">
        <p><button type="submit">Create account</button></p>
    </form>
</section>
@endsection
