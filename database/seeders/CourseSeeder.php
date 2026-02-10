<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear cursos de ejemplo
        $courses = [
            [
                'title' => 'Curso Completo de Uñas Acrílicas',
                'description' => 'Aprende todo sobre la técnica de uñas acrílicas desde cero. Incluye preparación, aplicación, diseño y mantenimiento.',
                'price' => 15000.00,
                'is_active' => true,
            ],
            [
                'title' => 'Técnicas de Pestañas pelo a pelo',
                'description' => 'Domina la técnica de extensión de pestañas pelo a pelo. Aprende colocación, diseño y cuidados.',
                'price' => 12000.00,
                'is_active' => true,
            ],
            [
                'title' => 'Estética Facial Profesional',
                'description' => 'Curso completo de tratamientos faciales: limpieza, hidratación, anti-edad y más.',
                'price' => 18000.00,
                'is_active' => true,
            ],
        ];

        foreach ($courses as $courseData) {
            $course = Course::create($courseData);

            // Crear lecciones de ejemplo para cada curso
            $lessons = [
                [
                    'title' => 'Introducción y Materiales',
                    'order' => 1,
                    'file_type' => 'pdf',
                    'file_path' => 'lessons/intro.pdf', // Ruta de ejemplo
                    'is_locked' => true,
                ],
                [
                    'title' => 'Técnica Básica',
                    'order' => 2,
                    'file_type' => 'video',
                    'file_path' => 'lessons/tecnica-basica.mp4', // Ruta de ejemplo
                    'is_locked' => true,
                ],
                [
                    'title' => 'Técnica Avanzada',
                    'order' => 3,
                    'file_type' => 'video',
                    'file_path' => 'lessons/tecnica-avanzada.mp4', // Ruta de ejemplo
                    'is_locked' => true,
                ],
            ];

            foreach ($lessons as $lessonData) {
                Lesson::create(array_merge($lessonData, ['course_id' => $course->id]));
            }
        }
    }
}

