<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();

        if ($request->has('department') && $request->department != '') {
            $query->where('department', $request->department);
        }

        $courses = $query->paginate(10);
        return view('courses.index', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_code'  => 'required|unique:courses,course_code|max:10',
            'title'        => 'required|string|max:255',
            'department'   => 'required|string|max:100',
            'credit_units' => 'required|integer|min:1|max:6',
            'capacity'     => 'required|integer|min:5|max:200',
        ]);

        Course::create($validated);

        return redirect()->route('courses.index')->with('success', 'New Course added successfully!');
    }
}
