-- Script de inicialización de MySQL
-- Este script se ejecuta automáticamente al crear el contenedor

-- Crear base de datos si no existe (por si acaso)
CREATE DATABASE IF NOT EXISTS sistema_cursos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Otorgar permisos al usuario
GRANT ALL PRIVILEGES ON sistema_cursos.* TO 'laravel_user'@'%';
FLUSH PRIVILEGES;

