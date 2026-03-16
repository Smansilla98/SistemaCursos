<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $myCourses = $user->courses()
            ->where('is_active', true)
            ->get();

        $availableCourses = Course::where('is_active', true)
            ->whereDoesntHave('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->get();

        return view('student.courses.index', compact('myCourses', 'availableCourses'));
    }

    public function show(Course $course)
    {
        $user = auth()->user();
        $hasAccess = $user->courses()->where('courses.id', $course->id)->exists();

        $course->load(['lessons' => fn ($q) => $q->orderBy('order')]);

        $completedLessonIds = [];
        $completedCount = 0;
        $firstLessonUrl = null;
        $nextLessonUrl = null;

        if ($hasAccess && $course->lessons->isNotEmpty()) {
            $completedLessonIds = $user->completedLessons()
                ->where('course_id', $course->id)
                ->pluck('lessons.id')
                ->all();
            $completedCount = count($completedLessonIds);
            $first = $course->lessons->first();
            $firstLessonUrl = route('student.courses.lesson', [$course, $first]);

            $next = $course->lessons->first(fn ($l) => ! in_array($l->id, $completedLessonIds));
            $nextLessonUrl = $next ? route('student.courses.lesson', [$course, $next]) : null;
        }

        return view('student.courses.show', compact(
            'course',
            'hasAccess',
            'completedLessonIds',
            'completedCount',
            'firstLessonUrl',
            'nextLessonUrl'
        ));
    }

    /** Vista reproductor: una lección dentro del curso (player + sidebar de módulos) */
    public function showLesson(Course $course, Lesson $lesson, Request $request)
    {
        $user = auth()->user();

        if (! $user->courses()->where('courses.id', $course->id)->exists()) {
            abort(403, 'No tenés acceso a este curso.');
        }

        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $lessons = $course->lessons()->orderBy('order')->get();
        $completedIds = $user->completedLessons()
            ->whereIn('lessons.id', $lessons->pluck('id'))
            ->pluck('lessons.id')
            ->all();

        $currentIndex = $lessons->search(fn ($l) => $l->id === $lesson->id);
        $prevLesson = $currentIndex > 0 ? $lessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex !== false && $currentIndex < $lessons->count() - 1
            ? $lessons[$currentIndex + 1]
            : null;

        $totalLessons = $lessons->count();
        $completedCount = count(array_intersect($lessons->pluck('id')->all(), $completedIds));
        $progressPercent = $totalLessons > 0 ? (int) round(($completedCount / $totalLessons) * 100) : 0;

        return view('student.courses.lesson', compact(
            'course',
            'lesson',
            'lessons',
            'completedIds',
            'prevLesson',
            'nextLesson',
            'completedCount',
            'totalLessons',
            'progressPercent'
        ));
    }

    /** Marcar lección como completada (AJAX o redirect) */
    public function completeLesson(Lesson $lesson, Request $request)
    {
        $user = auth()->user();

        if (! $user->courses()->where('courses.id', $lesson->course_id)->exists()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Sin acceso'], 403);
            }
            abort(403);
        }

        $user->completedLessons()->syncWithoutDetaching([
            $lesson->id => ['completed_at' => now()],
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        $course = $lesson->course;
        $next = $course->lessons()->orderBy('order')->where('order', '>', $lesson->order)->first();

        if ($next) {
            return redirect()->route('student.courses.lesson', [$course, $next])
                ->with('success', 'Lección completada. Siguiente: ' . $next->title);
        }

        return redirect()->route('student.courses.show', $course)
            ->with('success', '¡Felicitaciones! Completaste todas las lecciones de este curso.');
    }
}
