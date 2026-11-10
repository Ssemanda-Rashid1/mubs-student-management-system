<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $data = [
            'totalStudents' => null,
            'totalCourses' => null,
            'totalUsers' => null,
            'recentStudents' => collect(),
            'myCourses' => collect(),
            'myRegistrations' => collect(),
        ];

        if ($user->hasRole('admin')) {
            $data['totalStudents'] = Student::count();
            $data['totalCourses'] = Course::count();
            $data['totalUsers'] = User::count();
            $data['recentStudents'] = Student::with('user')->latest()->take(5)->get();
        } elseif ($user->hasRole('lecturer')) {
            $data['myCourses'] = Course::withCount('students')
                ->whereHas('lecturer', fn ($query) => $query->where('user_id', $user->id))
                ->orderBy('code')
                ->get();
        } else {
            $data['myRegistrations'] = CourseRegistration::with('course')
                ->whereHas('student', fn ($query) => $query->where('user_id', $user->id))
                ->latest()
                ->get();
        }

        return view('dashboard', $data);
    }
}