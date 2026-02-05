<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::where('is_active', true)
            ->with('category');

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        $courses = $query->orderBy('order')->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('home', compact('courses', 'categories'));
    }

    public function showCourse($slug)
    {
        $course = Course::where('slug', $slug)
            ->with(['category', 'teacher', 'modules'])
            ->firstOrFail();

        $hasAccess = auth()->check() && auth()->user()->hasAccessToCourse($course->id);

        return view('courses.show', compact('course', 'hasAccess'));
    }
}
