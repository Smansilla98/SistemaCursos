<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $courses = Course::where('teacher_id', $user->id)
            ->with(['category', 'modules'])
            ->latest()
            ->get();

        return view('teacher.courses.index', compact('courses'));
    }

    public function show(Course $course)
    {
        // Verificar que el profesor sea el dueño del curso
        if ($course->teacher_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver este curso');
        }

        $course->load(['modules.files', 'files', 'category', 'users']);

        return view('teacher.courses.show', compact('course'));
    }
}
