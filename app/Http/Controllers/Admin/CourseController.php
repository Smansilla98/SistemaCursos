<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['category', 'teacher'])->latest()->paginate(15);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $teachers = User::role('profesor')->get();
        return view('admin.courses.create', compact('categories', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'teacher_id' => 'nullable|exists:users,id',
            'price' => 'required|numeric|min:0',
            'cover_image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'requires_payment' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('courses', 'public');
        }

        Course::create($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso creado exitosamente');
    }

    public function show(Course $course)
    {
        $course->load(['category', 'teacher', 'modules.files', 'files']);
        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $categories = Category::where('is_active', true)->get();
        $teachers = User::role('profesor')->get();
        return view('admin.courses.edit', compact('course', 'categories', 'teachers'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'teacher_id' => 'nullable|exists:users,id',
            'price' => 'required|numeric|min:0',
            'cover_image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'requires_payment' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('cover_image')) {
            if ($course->cover_image) {
                Storage::disk('public')->delete($course->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('courses', 'public');
        }

        $course->update($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso actualizado exitosamente');
    }

    public function destroy(Course $course)
    {
        if ($course->cover_image) {
            Storage::disk('public')->delete($course->cover_image);
        }
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso eliminado exitosamente');
    }
}
