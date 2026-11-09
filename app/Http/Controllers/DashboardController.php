<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Models\Student;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;



class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $totalCourses = Course::count();
        $totalUsers = User::count();
        $recentStudents = Student::latest()->take(5)->get();

        return view('dashboard', compact('totalStudents', 'totalCourses', 'totalUsers', 'recentStudents'));

    }
}
