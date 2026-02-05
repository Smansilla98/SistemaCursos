<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Cursos del alumno
        $myCourses = $user->courses()
            ->wherePivot('is_unlocked', true)
            ->with('category')
            ->get();

        // Todos los cursos disponibles
        $availableCourses = Course::where('is_active', true)
            ->with('category')
            ->whereDoesntHave('users', function($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->get();

        return view('student.courses.index', compact('myCourses', 'availableCourses'));
    }

    public function show(Course $course)
    {
        $user = auth()->user();
        $hasAccess = $user->hasAccessToCourse($course->id);
        
        $course->load(['modules.files', 'files', 'category', 'teacher']);

        if ($hasAccess) {
            return view('student.courses.show', compact('course', 'hasAccess'));
        }

        return view('student.courses.show', compact('course', 'hasAccess'));
    }
}
