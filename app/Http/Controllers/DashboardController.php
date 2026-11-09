<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('students.index');
        }

        if ($user->hasRole('lecturer')) {
            return redirect()->route('courses.index');
        }

        return redirect()->route('registrations.index');
    }
}
