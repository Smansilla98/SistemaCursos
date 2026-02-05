<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AccessKey;
use App\Models\Course;
use Illuminate\Http\Request;

class AccessKeyController extends Controller
{
    public function validate(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = auth()->user();
        $course = Course::findOrFail($request->course_id);

        // Verificar si ya tiene acceso
        if ($user->hasAccessToCourse($course->id)) {
            return back()->with('info', 'Ya tienes acceso a este curso');
        }

        // Buscar la clave
        $accessKey = AccessKey::where('key', strtoupper($request->key))
            ->where('course_id', $course->id)
            ->first();

        if (!$accessKey) {
            return back()->with('error', 'Clave inválida');
        }

        if (!$accessKey->canBeUsed()) {
            return back()->with('error', 'Esta clave ya ha sido utilizada');
        }

        // Asignar acceso
        $user->courses()->attach($course->id, [
            'access_type' => 'key',
            'is_unlocked' => true,
            'unlocked_at' => now(),
            'access_key_id' => $accessKey->id,
        ]);

        // Marcar clave como usada si es de un solo uso
        if ($accessKey->is_single_use) {
            $accessKey->use();
        } else {
            $accessKey->update(['user_id' => $user->id]);
        }

        return redirect()->route('student.courses.show', $course)
            ->with('success', '¡Acceso desbloqueado exitosamente!');
    }
}
