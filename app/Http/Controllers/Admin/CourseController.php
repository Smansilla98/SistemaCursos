<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::latest()->paginate(15);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);
        
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        $course = Course::create($validated);

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Curso creado exitosamente');
    }

    public function show(Course $course)
    {
        $course->load('lessons');
        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        $course->update($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso actualizado exitosamente');
    }

    public function destroy(Course $course)
    {
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso eliminado exitosamente');
    }

    // Gestión de lecciones
    public function addLesson(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
            'file_type' => 'required|in:video,pdf',
            'file' => 'required|file|max:102400', // 100MB máximo
        ]);

        $filePath = $request->file('file')->store('lessons', 'public');
        
        Lesson::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'order' => $validated['order'],
            'file_type' => $validated['file_type'],
            'file_path' => $filePath,
            'is_locked' => true,
        ]);

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Lección agregada exitosamente');
    }

    public function deleteLesson(Lesson $lesson)
    {
        $course = $lesson->course;
        
        if ($lesson->file_path) {
            Storage::disk('public')->delete($lesson->file_path);
        }
        
        $lesson->delete();

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Lección eliminada exitosamente');
    }
}
