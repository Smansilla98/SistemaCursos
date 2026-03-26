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
            return $this->redirectToCheckout($existingPurchase, $course);
        }

        // Crear compra
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'amount' => $course->price,
            'status' => 'pending',
        ]);

        return $this->redirectToCheckout($purchase, $course);
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

    private function redirectToCheckout(Purchase $purchase, Course $course)
    {
        $accessToken = config('services.mercadopago.access_token');
        if (!$accessToken) {
            return redirect()->route('student.courses.show', $course)
                ->with('info', 'Compra creada. El administrador la revisará manualmente.');
        }

        MercadoPagoConfig::setAccessToken($accessToken);

        $client = new PreferenceClient();
        $preference = $client->create([
            "items" => [
                [
                    "title" => $course->title,
                    "quantity" => 1,
                    "unit_price" => (float) $course->price,
                ],
            ],
            "back_urls" => [
                "success" => route('student.purchases.success'),
                "failure" => route('student.purchases.failure'),
                "pending" => route('student.purchases.pending'),
            ],
            "notification_url" => route('api.mercadopago.webhook'),
            "auto_return" => "approved",
            "external_reference" => (string) $purchase->id,
        ]);

        $isTestToken = str_starts_with($accessToken, 'TEST-');
        $checkoutUrl = $isTestToken && !empty($preference->sandbox_init_point)
            ? $preference->sandbox_init_point
            : $preference->init_point;

        return redirect($checkoutUrl);
    }
}

