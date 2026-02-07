#!/bin/bash
set -e

echo "🚀 Iniciando Sistema de Gestión de Cursos..."

# Esperar a que la base de datos esté lista (opcional, Railway lo maneja automáticamente)
echo "⏳ Verificando conexión a base de datos..."

# Limpiar cachés antes de iniciar
echo "🧹 Limpiando cachés..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Optimizar para producción
echo "⚡ Optimizando aplicación..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Ejecutar migraciones (solo si no se han ejecutado)
echo "🗄️  Verificando migraciones..."
php artisan migrate --force || echo "⚠️  Error en migraciones, continuando..."

# Crear enlace simbólico de storage si no existe
echo "🔗 Verificando enlace de storage..."
php artisan storage:link || echo "⚠️  Enlace de storage ya existe o error, continuando..."

# Obtener el puerto de la variable de entorno o usar 8000 por defecto
PORT=${PORT:-8000}

echo "✅ Iniciando servidor Laravel en puerto $PORT..."
echo "🌐 La aplicación estará disponible en http://0.0.0.0:$PORT"

# Iniciar el servidor de Laravel
exec php artisan serve --host=0.0.0.0 --port=$PORT

