@extends('layouts.app')
@section('title', 'Sign in')
@section('content')
<section class="card" style="max-width: 460px; margin: 3rem auto;">
    <h1>Sign in</h1>
    <p class="muted">Use your university account to continue.</p>
    <form method="POST" action="{{ route('login.store') }}">
        @csrf
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username">
        <label for="password">Password</label>
        <input id="password" name="password" type="password" required autocomplete="current-password">
        <label><input style="width:auto" type="checkbox" name="remember" value="1"> Remember me</label>
        <p><button type="submit">Sign in</button></p>
    </form>
    <p>New student? <a href="{{ route('register') }}">Create a student account</a></p>
</section>
@endsection
