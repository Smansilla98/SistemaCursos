<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['user', 'course'])
            ->latest()
            ->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    public function approve(Payment $payment)
    {
        $payment->update([
            'status' => 'approved',
            'paid_at' => now(),
        ]);

        // Desbloquear el curso para el usuario
        $user = $payment->user;
        $course = $payment->course;

        if (!$user->hasAccessToCourse($course->id)) {
            $user->courses()->attach($course->id, [
                'access_type' => 'payment',
                'is_unlocked' => true,
                'unlocked_at' => now(),
                'payment_id' => $payment->id,
            ]);
        }

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pago aprobado y curso desbloqueado');
    }

    public function reject(Payment $payment)
    {
        $payment->update([
            'status' => 'rejected',
        ]);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pago rechazado');
    }

    public function uploadProof(Request $request, Payment $payment)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);

        if ($payment->payment_proof) {
            Storage::disk('public')->delete($payment->payment_proof);
        }

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');
        $payment->update(['payment_proof' => $path]);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Comprobante subido exitosamente');
    }
}
