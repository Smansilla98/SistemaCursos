<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['user', 'course'])
            ->latest()
            ->paginate(20);

        return view('admin.purchases.index', compact('purchases'));
    }

    public function approve(Purchase $purchase)
    {
        $purchase->update([
            'status' => 'approved',
        ]);

        // Desbloquear el curso para el usuario
        $user = $purchase->user;
        $course = $purchase->course;

        // Asignar curso al usuario si no lo tiene
        if (!$user->courses()->where('courses.id', $course->id)->exists()) {
            $user->courses()->attach($course->id);
        }

        // Desbloquear todas las lecciones
        $course->lessons()->update(['is_locked' => false]);

        return redirect()->route('admin.purchases.index')
            ->with('success', 'Compra aprobada y curso desbloqueado');
    }

    public function reject(Purchase $purchase)
    {
        $purchase->update([
            'status' => 'rejected',
        ]);

        return redirect()->route('admin.purchases.index')
            ->with('success', 'Compra rechazada');
    }
}

