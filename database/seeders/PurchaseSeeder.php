<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    /**
     * Crea compras de ejemplo: una aprobada (estudiante con acceso a un curso)
     * y opcionalmente una pendiente para probar el flujo admin.
     */
    public function run(): void
    {
        $student = User::where('email', 'student@cursos.com')->first();
        $course = Course::where('is_active', true)->first();

        if (!$student || !$course) {
            return;
        }

        // Compra aprobada: el estudiante ya tiene acceso a este curso
        $approved = Purchase::firstOrCreate(
            [
                'user_id' => $student->id,
                'course_id' => $course->id,
                'status' => 'approved',
            ],
            [
                'payment_id' => 'SEED_APPROVED_' . uniqid(),
                'amount' => $course->price,
            ]
        );

        if ($approved->wasRecentlyCreated && !$student->courses()->where('courses.id', $course->id)->exists()) {
            $student->courses()->attach($course->id);
            $course->lessons()->update(['is_locked' => false]);
        }

        // Segundo curso: compra pendiente (para probar aprobación manual en admin)
        $secondCourse = Course::where('is_active', true)->where('id', '!=', $course->id)->first();
        if ($secondCourse) {
            Purchase::firstOrCreate(
                [
                    'user_id' => $student->id,
                    'course_id' => $secondCourse->id,
                    'status' => 'pending',
                ],
                [
                    'amount' => $secondCourse->price,
                ]
            );
        }
    }
}
