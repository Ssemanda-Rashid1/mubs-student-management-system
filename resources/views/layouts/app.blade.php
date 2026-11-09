<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'University Student Management')</title>
    <style>
        :root { font-family: system-ui, sans-serif; color: #172033; background: #f5f7fb; }
        body { margin: 0; }
        a { color: #1d4ed8; }
        header, footer { background: #14213d; color: #fff; padding: 1rem max(1rem, calc((100% - 1100px) / 2)); }
        header { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; }
        header a, footer a { color: white; text-decoration: none; }
        nav { display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; }
        main { max-width: 1100px; margin: 2rem auto; padding: 0 1rem; min-height: 65vh; }
        h1 { margin-top: 0; }
        .card, table { width: 100%; background: white; border: 1px solid #dfe5ef; border-radius: .5rem; }
        .card { box-sizing: border-box; padding: 1.25rem; }
        table { border-collapse: collapse; overflow: hidden; }
        th, td { padding: .8rem; border-bottom: 1px solid #e8edf4; text-align: left; vertical-align: top; }
        th { background: #eef2f8; }
        input, select, textarea { box-sizing: border-box; width: 100%; padding: .65rem; border: 1px solid #b8c2d1; border-radius: .3rem; font: inherit; }
        label { display: block; font-weight: 600; margin: .8rem 0 .35rem; }
        button, .button { display: inline-block; padding: .55rem .85rem; border: 0; border-radius: .3rem; background: #1d4ed8; color: #fff; font: inherit; text-decoration: none; cursor: pointer; }
        .secondary { background: #475569; }
        .danger { background: #b91c1c; }
        .inline { display: inline; }
        .notice { padding: .8rem 1rem; margin-bottom: 1rem; border-radius: .35rem; background: #dcfce7; color: #166534; }
        .error { color: #b91c1c; }
        .muted { color: #64748b; }
        .actions { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; }
        @media (max-width: 680px) { table { display: block; overflow-x: auto; } }
    </style>
</head>
<body>
<header>
    <a href="{{ route('dashboard') }}"><strong>University Student Management</strong></a>
    @auth
        <nav aria-label="Main navigation">
            @can('view courses') <a href="{{ route('courses.index') }}">Courses</a> @endcan
            @can('manage students') <a href="{{ route('students.index') }}">Students</a> @endcan
            @can('view registrations') <a href="{{ route('registrations.index') }}">Registrations</a> @endcan
            @can('manage users') <a href="{{ route('users.index') }}">User access</a> @endcan
            <span>{{ auth()->user()->name }} ({{ auth()->user()->getRoleNames()->join(', ') }})</span>
            <form class="inline" method="POST" action="{{ route('logout') }}">@csrf<button class="secondary" type="submit">Sign out</button></form>
        </nav>
    @endauth
</header>
<main>
    @if (session('status')) <div class="notice" role="status">{{ session('status') }}</div> @endif
    @if ($errors->any())
        <div class="notice error" role="alert">
            <strong>Please correct the following:</strong>
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif
    @yield('content')
</main>
<footer>University Student Management System</footer>
</body>
</html>
