<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Payment;
use Illuminate\Http\Request;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

class PaymentController extends Controller
{
    public function create(Request $request, Course $course)
    {
        $user = auth()->user();

        // Verificar si ya tiene acceso
        if ($user->hasAccessToCourse($course->id)) {
            return redirect()->route('student.courses.show', $course)
                ->with('info', 'Ya tienes acceso a este curso');
        }

        // Verificar si ya tiene un pago pendiente
        $existingPayment = Payment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'pending')
            ->first();

        if ($existingPayment) {
            return redirect()->route('student.courses.show', $course)
                ->with('info', 'Ya tienes un pago pendiente para este curso');
        }

        // Crear pago
        $payment = Payment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'amount' => $course->price,
            'status' => 'pending',
            'payment_method' => 'mercadopago',
        ]);

        // Configurar MercadoPago
        $accessToken = config('services.mercadopago.access_token');
        if ($accessToken) {
            MercadoPagoConfig::setAccessToken($accessToken);
            
            $client = new PreferenceClient();
            $preference = $client->create([
                "items" => [
                    [
                        "title" => $course->title,
                        "quantity" => 1,
                        "unit_price" => (float) $course->price,
                    ]
                ],
                "back_urls" => [
                    "success" => route('student.payments.success'),
                    "failure" => route('student.payments.failure'),
                    "pending" => route('student.payments.pending'),
                ],
                "auto_return" => "approved",
                "external_reference" => $payment->id,
            ]);

            $payment->update([
                'mercadopago_preference_id' => $preference->id,
            ]);

            return redirect($preference->init_point);
        }

        // Si no hay configuración de MercadoPago, solo crear el pago
        return redirect()->route('student.courses.show', $course)
            ->with('info', 'Pago creado. El administrador lo revisará manualmente.');
    }

    public function success(Request $request)
    {
        $paymentId = $request->get('external_reference');
        $payment = Payment::findOrFail($paymentId);

        // Verificar el pago con MercadoPago
        $accessToken = config('services.mercadopago.access_token');
        if ($accessToken) {
            MercadoPagoConfig::setAccessToken($accessToken);
            // Aquí deberías verificar el estado del pago con la API de MercadoPago
        }

        return redirect()->route('student.courses.show', $payment->course)
            ->with('success', 'Pago procesado. El acceso será activado una vez aprobado.');
    }

    public function failure(Request $request)
    {
        return redirect()->route('student.courses.index')
            ->with('error', 'El pago no pudo ser procesado.');
    }

    public function pending(Request $request)
    {
        return redirect()->route('student.courses.index')
            ->with('info', 'El pago está pendiente de confirmación.');
    }
}
