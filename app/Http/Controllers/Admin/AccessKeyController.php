<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessKey;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class AccessKeyController extends Controller
{
    public function index()
    {
        $keys = AccessKey::with(['course', 'user'])->latest()->paginate(20);
        return view('admin.access-keys.index', compact('keys'));
    }

    public function create()
    {
        $courses = Course::where('is_active', true)->get();
        $users = User::role('alumno')->get();
        return view('admin.access-keys.create', compact('courses', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id' => 'nullable|exists:users,id',
            'is_single_use' => 'boolean',
            'notes' => 'nullable|string',
            'quantity' => 'nullable|integer|min:1|max:100',
        ]);

        $quantity = $validated['quantity'] ?? 1;
        $keys = [];

        for ($i = 0; $i < $quantity; $i++) {
            $keys[] = AccessKey::create([
                'course_id' => $validated['course_id'],
                'user_id' => $validated['user_id'] ?? null,
                'is_single_use' => $validated['is_single_use'] ?? true,
                'notes' => $validated['notes'] ?? null,
            ]);
        }

        if ($quantity === 1) {
            return redirect()->route('admin.access-keys.index')
                ->with('success', 'Clave generada exitosamente: ' . $keys[0]->key);
        }

        return redirect()->route('admin.access-keys.index')
            ->with('success', "Se generaron {$quantity} claves exitosamente");
    }

    public function show(AccessKey $accessKey)
    {
        $accessKey->load(['course', 'user']);
        return view('admin.access-keys.show', compact('accessKey'));
    }

    public function destroy(AccessKey $accessKey)
    {
        $accessKey->delete();
        return redirect()->route('admin.access-keys.index')
            ->with('success', 'Clave eliminada exitosamente');
    }
}
