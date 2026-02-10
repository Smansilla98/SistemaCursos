<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('home', compact('courses'));
    }

    public function showCourse(Course $course)
    {
        $course->load('lessons');
        
        $hasAccess = auth()->check() && auth()->user()->hasAccessToCourse($course->id);

        return view('courses.show', compact('course', 'hasAccess'));
    }
}
