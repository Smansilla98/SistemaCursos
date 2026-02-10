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
        
        // Cursos del estudiante
        $myCourses = $user->courses()
            ->where('is_active', true)
            ->get();

        // Todos los cursos disponibles (que no tiene)
        $availableCourses = Course::where('is_active', true)
            ->whereDoesntHave('users', function($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->get();

        return view('student.courses.index', compact('myCourses', 'availableCourses'));
    }

    public function show(Course $course)
    {
        $user = auth()->user();
        $hasAccess = $user->courses()->where('courses.id', $course->id)->exists();
        
        $course->load('lessons');

        return view('student.courses.show', compact('course', 'hasAccess'));
    }
}
