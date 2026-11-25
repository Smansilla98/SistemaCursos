<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = $this->seedPassword();

        $admin = User::firstOrCreate(
            ['email' => 'admin@cursos.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make($password),
            ]
        );

        $admin->assignRole('admin');

        // Crear usuario estudiante de ejemplo
        $student = User::firstOrCreate(
            ['email' => 'student@cursos.com'],
            [
                'name' => 'Estudiante Ejemplo',
                'password' => Hash::make($password),
            ]
        );

        $student->assignRole('student');
    }

    /**
     * Contraseña de los usuarios de ejemplo. Se toma de SEED_DEFAULT_PASSWORD;
     * en local/testing se usa 'password' como valor de desarrollo. En producción
     * nunca se usa la contraseña por defecto: si no se define, se genera una aleatoria.
     */
    private function seedPassword(): string
    {
        $configured = env('SEED_DEFAULT_PASSWORD');

        if (! empty($configured)) {
            return $configured;
        }

        if (app()->isProduction()) {
            $generated = Str::password(24);
            $this->command?->warn('SEED_DEFAULT_PASSWORD no definido en producción: se generó una contraseña aleatoria (usar "olvidé mi contraseña" para ingresar).');

            return $generated;
        }

        return 'password';
    }
}
