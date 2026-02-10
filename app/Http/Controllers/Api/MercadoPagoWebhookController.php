<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;

class MercadoPagoWebhookController extends Controller
{
    public function handle(Request $request)
    {
        try {
            // Obtener el access token de MercadoPago
            $accessToken = config('services.mercadopago.access_token');
            
            if (!$accessToken) {
                Log::error('MercadoPago: Access token no configurado');
                return response()->json(['error' => 'Configuración incompleta'], 500);
            }

            MercadoPagoConfig::setAccessToken($accessToken);

            // Obtener el ID del pago desde el webhook
            $paymentId = $request->input('data.id') ?? $request->input('id');

            if (!$paymentId) {
                Log::warning('MercadoPago Webhook: No se recibió payment ID');
                return response()->json(['error' => 'Payment ID no encontrado'], 400);
            }

            // Consultar el pago en MercadoPago
            $client = new PaymentClient();
            $payment = $client->get($paymentId);

            if (!$payment) {
                Log::error("MercadoPago: No se pudo obtener el pago con ID: {$paymentId}");
                return response()->json(['error' => 'Pago no encontrado'], 404);
            }

            // Buscar la compra por external_reference (ID de la compra) o payment_id
            $externalReference = $payment->external_reference ?? null;
            
            if ($externalReference) {
                $purchase = Purchase::find($externalReference);
            } else {
                $purchase = Purchase::where('payment_id', $paymentId)->first();
            }

            if (!$purchase) {
                Log::warning("MercadoPago: Compra no encontrada para payment_id: {$paymentId}, external_reference: {$externalReference}");
                return response()->json(['error' => 'Compra no encontrada'], 404);
            }

            // Actualizar el payment_id si no estaba guardado
            if (!$purchase->payment_id) {
                $purchase->update(['payment_id' => $paymentId]);
            }

            // Actualizar el estado de la compra según el estado de MercadoPago
            $status = $this->mapMercadoPagoStatus($payment->status);
            
            $purchase->update([
                'status' => $status,
            ]);

            // Si el pago está aprobado, desbloquear el curso automáticamente
            if ($status === 'approved') {
                $this->unlockCourse($purchase);
                
                Log::info("MercadoPago: Curso desbloqueado para usuario {$purchase->user_id}, curso {$purchase->course_id}");
            }

            return response()->json([
                'success' => true,
                'purchase_id' => $purchase->id,
                'status' => $status,
            ]);

        } catch (\Exception $e) {
            Log::error('MercadoPago Webhook Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Error procesando webhook',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mapear el estado de MercadoPago a nuestro sistema
     */
    private function mapMercadoPagoStatus(string $mpStatus): string
    {
        return match($mpStatus) {
            'approved' => 'approved',
            'pending', 'in_process', 'in_mediation' => 'pending',
            'rejected', 'cancelled', 'refunded', 'charged_back' => 'rejected',
            default => 'pending',
        };
    }

    /**
     * Desbloquear el curso para el usuario
     */
    private function unlockCourse(Purchase $purchase): void
    {
        $user = $purchase->user;
        $course = $purchase->course;

        // Verificar si el usuario ya tiene acceso
        if (!$user->courses()->where('courses.id', $course->id)->exists()) {
            // Asignar el curso al usuario
            $user->courses()->attach($course->id);
        }

        // Desbloquear todas las lecciones del curso
        $course->lessons()->update(['is_locked' => false]);
    }
}
