#!/bin/bash

# Script de inicio rápido para Docker
# Sistema de Gestión de Cursos

echo "🚀 Iniciando Sistema de Gestión de Cursos con Docker..."
echo ""

# Verificar si existe .env
if [ ! -f .env ]; then
    echo "📝 Creando archivo .env desde .env.docker.example..."
    cp .env.docker.example .env
    echo "✅ Archivo .env creado"
else
    echo "✅ Archivo .env ya existe"
fi

# Construir y levantar contenedores
echo ""
echo "🔨 Construyendo y levantando contenedores..."
docker-compose up -d --build

# Esperar a que MySQL esté listo
echo ""
echo "⏳ Esperando a que MySQL esté listo..."
sleep 10

# Instalar dependencias
echo ""
echo "📦 Instalando dependencias de Composer..."
docker-compose exec -T app composer install --no-interaction

# Generar clave de aplicación
echo ""
echo "🔑 Generando clave de aplicación..."
docker-compose exec -T app php artisan key:generate --force

# Ejecutar migraciones
echo ""
echo "🗄️  Ejecutando migraciones..."
docker-compose exec -T app php artisan migrate --force

# Ejecutar seeders
echo ""
echo "🌱 Ejecutando seeders..."
docker-compose exec -T app php artisan db:seed --force

# Crear enlace simbólico
echo ""
echo "🔗 Creando enlace simbólico de storage..."
docker-compose exec -T app php artisan storage:link

# Compilar assets (opcional)
echo ""
read -p "¿Deseas compilar los assets ahora? (s/n): " -n 1 -r
echo ""
if [[ $REPLY =~ ^[Ss]$ ]]; then
    echo "🎨 Compilando assets..."
    docker-compose exec -T app npm install
    docker-compose exec -T app npm run build
fi

echo ""
echo "✅ ¡Sistema listo!"
echo ""
echo "🌐 Accede a la aplicación en: http://localhost:8000"
echo "👤 Credenciales por defecto:"
echo "   Email: admin@cursos.com"
echo "   Contraseña: password"
echo ""
echo "📊 Para ver los logs: docker-compose logs -f"
echo "🛑 Para detener: docker-compose down"

