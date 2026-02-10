<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Purchase;
use Illuminate\Http\Request;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

class PurchaseController extends Controller
{
    public function create(Request $request, Course $course)
    {
        $user = auth()->user();

        // Verificar si ya tiene acceso
        if ($user->courses()->where('courses.id', $course->id)->exists()) {
            return redirect()->route('student.courses.show', $course)
                ->with('info', 'Ya tienes acceso a este curso');
        }

        // Verificar si ya tiene una compra pendiente
        $existingPurchase = Purchase::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'pending')
            ->first();

        if ($existingPurchase) {
            return redirect()->route('student.courses.show', $course)
                ->with('info', 'Ya tienes una compra pendiente para este curso');
        }

        // Crear compra
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'amount' => $course->price,
            'status' => 'pending',
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
                    "success" => route('student.purchases.success'),
                    "failure" => route('student.purchases.failure'),
                    "pending" => route('student.purchases.pending'),
                ],
                "auto_return" => "approved",
                "external_reference" => $purchase->id,
            ]);

            // El payment_id se actualizará cuando MercadoPago notifique el pago
            // Por ahora guardamos el preference_id temporalmente
            // Nota: En producción, el payment_id vendrá del webhook

            return redirect($preference->init_point);
        }

        // Si no hay configuración de MercadoPago
        return redirect()->route('student.courses.show', $course)
            ->with('info', 'Compra creada. El administrador la revisará manualmente.');
    }

    public function success(Request $request)
    {
        $purchaseId = $request->get('external_reference');
        $purchase = Purchase::findOrFail($purchaseId);

        return redirect()->route('student.courses.show', $purchase->course)
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

