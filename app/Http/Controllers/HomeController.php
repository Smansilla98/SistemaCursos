<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query()->where('is_active', true);

        $search = $request->filled('q') ? trim($request->q) : null;
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        $sort = $request->get('sort', 'recomendados');
        if ($sort === 'recientes') {
            $query->latest();
        } elseif ($sort === 'precio_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'precio_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $courses = $query->withCount('users')->paginate(12)->withQueryString();

        return view('home', compact('courses', 'search', 'sort'));
    }

    public function showCourse(Course $course)
    {
        $course->load('lessons');

        $hasAccess = auth()->check() && auth()->user()->hasAccessToCourse($course->id);

        return view('courses.show', compact('course', 'hasAccess'));
    }
}
