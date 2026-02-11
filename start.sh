#!/bin/sh
set -e

echo "=========================================="
echo "=== Iniciando Sistema de Cursos ==="
echo "=========================================="

# Mostrar variables básicas (sin secretos)
echo "=== Variables de Entorno ==="
echo "APP_ENV: ${APP_ENV:-no configurado}"
echo "DB_CONNECTION: ${DB_CONNECTION:-no configurado}"
echo "DB_HOST: ${DB_HOST:-no configurado}"
echo "DB_PORT: ${DB_PORT:-no configurado}"
echo "DB_DATABASE: ${DB_DATABASE:-no configurado}"
echo "DB_USERNAME: ${DB_USERNAME:-no configurado}"
echo ""

# Verificar si las variables están configuradas
if [ -z "$DB_HOST" ] || [ -z "$DB_DATABASE" ]; then
    echo "⚠️  ADVERTENCIA: Variables de base de datos no configuradas"
    echo "   DB_HOST: ${DB_HOST:-no configurado}"
    echo "   DB_DATABASE: ${DB_DATABASE:-no configurado}"
    echo "   El sistema continuará pero puede fallar al acceder a la base de datos"
    echo ""
fi

# Esperar DB solo si las variables están configuradas
if [ -n "$DB_HOST" ] && [ -n "$DB_DATABASE" ]; then
    echo "=== Esperando base de datos ==="
    echo "Intentando conectar a: ${DB_HOST}:${DB_PORT:-3306}/${DB_DATABASE}"
    
    for i in $(seq 1 10); do
        if php -r "
            try {
                \$host = getenv('DB_HOST') ?: '127.0.0.1';
                \$port = getenv('DB_PORT') ?: '3306';
                \$database = getenv('DB_DATABASE') ?: '';
                \$username = getenv('DB_USERNAME') ?: 'root';
                \$password = getenv('DB_PASSWORD') ?: '';
                
                \$pdo = new PDO(
                    'mysql:host='.\$host.';port='.\$port.';dbname='.\$database,
                    \$username,
                    \$password,
                    [PDO::ATTR_TIMEOUT => 2]
                );
                exit(0);
            } catch (Exception \$e) {
                exit(1);
            }
        " 2>/dev/null; then
            echo "✓ Base de datos disponible"
            break
        fi
        if [ $i -eq 10 ]; then
            echo "⚠️  No se pudo conectar a la base de datos después de 10 intentos"
            echo "   Host: ${DB_HOST}"
            echo "   Database: ${DB_DATABASE}"
            echo "   Continuando de todas formas..."
        else
            echo "Intento $i/10..."
            sleep 2
        fi
    done
else
    echo "⚠️  Saltando verificación de base de datos: variables no configuradas"
fi

# Limpiar cachés ANTES de ejecutar migraciones
# Usar --no-interaction para evitar problemas si la BD no está disponible
echo "=== Limpiando cachés ==="
php artisan config:clear --no-interaction || true
php artisan cache:clear --no-interaction || true
php artisan route:clear --no-interaction || true
php artisan view:clear --no-interaction || true

# Verificar si la base de datos tiene tablas (solo si está configurada)
if [ -n "$DB_HOST" ] && [ -n "$DB_DATABASE" ]; then
    echo "=== Verificando estado de la base de datos ==="
    DB_HAS_TABLES=$(php -r "
    try {
        \$host = getenv('DB_HOST') ?: '127.0.0.1';
        \$port = getenv('DB_PORT') ?: '3306';
        \$database = getenv('DB_DATABASE') ?: '';
        \$username = getenv('DB_USERNAME') ?: 'root';
        \$password = getenv('DB_PASSWORD') ?: '';
        
        \$pdo = new PDO(
            'mysql:host='.\$host.';port='.\$port.';dbname='.\$database,
            \$username,
            \$password
        );
        \$stmt = \$pdo->query('SHOW TABLES');
        \$tables = \$stmt->fetchAll(PDO::FETCH_COLUMN);
        echo count(\$tables) > 0 ? 'yes' : 'no';
    } catch (Exception \$e) {
        echo 'no';
    }
    " 2>/dev/null || echo "no")

    if [ "$DB_HAS_TABLES" = "yes" ]; then
        echo "✓ Base de datos tiene tablas existentes"
        echo "=== Ejecutando migraciones (modo seguro) ==="
        php artisan migrate --force --no-interaction || {
            echo "⚠️  ADVERTENCIA: Las migraciones fallaron. Verificá los logs."
            echo "   El sistema puede funcionar con funcionalidad limitada."
        }
    else
        echo "Base de datos vacía"
        echo "=== Ejecutando migraciones iniciales ==="
        php artisan migrate --force --no-interaction || {
            echo "⚠️  ADVERTENCIA: Las migraciones fallaron. Verificá los logs."
        }
    fi
else
    echo "⚠️  Saltando migraciones: variables de base de datos no configuradas"
fi

# Regenerar autoloader de Composer (por si hay cambios en clases)
composer dump-autoload --no-interaction --optimize || true

# Storage
echo "=== Verificando storage ==="
php artisan storage:link || true

echo ""
echo "=========================================="
echo "=== Servidor iniciado ==="
echo "Host: 0.0.0.0"
echo "Port: ${PORT:-8000}"
echo "=========================================="
echo ""

exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
