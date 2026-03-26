<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CourseController extends Controller
{
    private const LESSON_FILE_MAX_MB = 150; // 150 MB para videos/documentos

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
        foreach ($course->lessons as $lesson) {
            if ($lesson->file_path && ! $lesson->isLink()) {
                Storage::disk('public')->delete($lesson->file_path);
            }
        }
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso eliminado exitosamente');
    }

    // --- Lecciones: alta
    public function addLesson(Request $request, Course $course)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'order' => 'required|integer|min:0',
            'file_type' => 'required|in:' . implode(',', Lesson::fileTypes()),
            'is_locked' => 'boolean',
            // Permite recursos externos (Drive) sin subir archivo
            'url' => 'nullable|url|max:2000',
            'file' => 'nullable|file|max:' . (self::LESSON_FILE_MAX_MB * 1024),
        ];

        $validated = $request->validate($rules);

        // Validación cruzada: link => requiere url; otros => requiere (file o url)
        $hasFile = $request->hasFile('file');
        $hasUrl = !empty($validated['url']);
        if ($validated['file_type'] === Lesson::TYPE_LINK) {
            if (!$hasUrl) {
                throw ValidationException::withMessages(['url' => 'La URL es obligatoria para el tipo Enlace.']);
            }
            if ($hasFile) {
                throw ValidationException::withMessages(['file' => 'Para el tipo Enlace no se sube archivo.']);
            }
        } else {
            if (!($hasFile || $hasUrl)) {
                throw ValidationException::withMessages([
                    'file' => 'Subí un archivo o pegá una URL externa (por ejemplo Google Drive).',
                    'url' => 'Pegá una URL externa o subí un archivo.',
                ]);
            }
        }

        $filePath = null;
        if ($hasUrl) {
            $filePath = $validated['url'];
        } elseif ($hasFile) {
            $filePath = $request->file('file')->store('lessons', 'public');
        }

        Lesson::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'order' => (int) $validated['order'],
            'file_type' => $validated['file_type'],
            'file_path' => $filePath,
            'is_locked' => $request->boolean('is_locked'),
        ]);

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Lección agregada exitosamente');
    }

    // --- Lecciones: edición
    public function editLesson(Lesson $lesson)
    {
        $lesson->load('course');
        return view('admin.lessons.edit', compact('lesson'));
    }

    public function updateLesson(Request $request, Lesson $lesson)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'order' => 'required|integer|min:0',
            'file_type' => 'required|in:' . implode(',', Lesson::fileTypes()),
            'is_locked' => 'boolean',
            'url' => 'nullable|url|max:2000',
            'file' => 'nullable|file|max:' . (self::LESSON_FILE_MAX_MB * 1024),
        ];

        $validated = $request->validate($rules);

        $hasFile = $request->hasFile('file');
        $hasUrl = !empty($validated['url']);

        if ($validated['file_type'] === Lesson::TYPE_LINK) {
            if (!$hasUrl) {
                throw ValidationException::withMessages(['url' => 'La URL es obligatoria para el tipo Enlace.']);
            }
            if ($hasFile) {
                throw ValidationException::withMessages(['file' => 'Para el tipo Enlace no se sube archivo.']);
            }
        } else {
            // Si cambia de enlace a archivo sin URL, necesita un archivo
            if (!($hasFile || $hasUrl) && ($lesson->isLink() || $lesson->isExternal())) {
                throw ValidationException::withMessages([
                    'file' => 'Subí un archivo o pegá una URL externa.',
                    'url' => 'Pegá una URL externa o subí un archivo.',
                ]);
            }
        }

        $filePath = $lesson->file_path;

        if ($hasUrl) {
            if ($lesson->file_path && ! $lesson->isExternal() && ! $lesson->isLink()) {
                Storage::disk('public')->delete($lesson->file_path);
            }
            $filePath = $validated['url'];
        } elseif ($hasFile) {
            if ($lesson->file_path && ! $lesson->isExternal() && ! $lesson->isLink()) {
                Storage::disk('public')->delete($lesson->file_path);
            }
            $filePath = $request->file('file')->store('lessons', 'public');
        } elseif ($validated['file_type'] === Lesson::TYPE_LINK) {
            // Ya validado: debe existir url
            $filePath = $validated['url'];
        }
        // Si es archivo y no subió uno nuevo, se mantiene file_path actual

        $lesson->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'order' => (int) $validated['order'],
            'file_type' => $validated['file_type'],
            'file_path' => $filePath,
            'is_locked' => $request->boolean('is_locked'),
        ]);

        return redirect()->route('admin.courses.show', $lesson->course)
            ->with('success', 'Lección actualizada exitosamente');
    }

    public function deleteLesson(Lesson $lesson)
    {
        $course = $lesson->course;

        // Sólo borrar si es un archivo local en storage
        if ($lesson->file_path && ! $lesson->isExternal() && ! $lesson->isLink()) {
            Storage::disk('public')->delete($lesson->file_path);
        }

        $lesson->delete();

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Lección eliminada exitosamente');
    }
}
