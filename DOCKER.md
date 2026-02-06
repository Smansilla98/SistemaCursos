# 🐳 Guía de Docker - Sistema de Gestión de Cursos

Esta guía te ayudará a levantar el proyecto completo usando Docker con MySQL.

## 📋 Requisitos Previos

- Docker Desktop instalado (o Docker Engine + Docker Compose)
- Git (opcional, si clonas el repositorio)

## 🚀 Inicio Rápido

### 1. Configurar el archivo .env

Copia el archivo de ejemplo para Docker:

```bash
cp .env.docker.example .env
```

O crea manualmente el `.env` con estas credenciales:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sistema_cursos
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_password
```

### 2. Construir y levantar los contenedores

```bash
docker-compose up -d --build
```

Este comando:
- Construye la imagen de PHP con todas las extensiones
- Levanta MySQL con la base de datos configurada
- Levanta Nginx como servidor web
- Configura la red entre contenedores

### 3. Instalar dependencias de PHP

```bash
docker-compose exec app composer install
```

### 4. Generar la clave de la aplicación

```bash
docker-compose exec app php artisan key:generate
```

### 5. Ejecutar migraciones

```bash
docker-compose exec app php artisan migrate
```

### 6. Ejecutar seeders

```bash
docker-compose exec app php artisan db:seed
```

### 7. Crear enlace simbólico de storage

```bash
docker-compose exec app php artisan storage:link
```

### 8. Compilar assets (opcional)

```bash
docker-compose exec app npm install
docker-compose exec app npm run build
```

O si prefieres usar el servicio Node separado:

```bash
docker-compose --profile build run --rm node
```

## 🌐 Acceder a la Aplicación

Una vez levantado todo, accede a:

- **Aplicación**: http://localhost:8000
- **MySQL**: localhost:3306
  - Usuario: `laravel_user`
  - Contraseña: `laravel_password`
  - Base de datos: `sistema_cursos`
  - Root password: `root_password`

## 👤 Credenciales por Defecto

Después de ejecutar los seeders:

- **Email**: admin@cursos.com
- **Contraseña**: password

## 📝 Comandos Útiles

### Ver logs
```bash
# Todos los servicios
docker-compose logs -f

# Solo un servicio
docker-compose logs -f app
docker-compose logs -f mysql
docker-compose logs -f nginx
```

### Ejecutar comandos Artisan
```bash
docker-compose exec app php artisan [comando]
```

Ejemplos:
```bash
docker-compose exec app php artisan migrate
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan tinker
```

### Acceder a MySQL
```bash
docker-compose exec mysql mysql -u laravel_user -plaravel_password sistema_cursos
```

O con root:
```bash
docker-compose exec mysql mysql -u root -proot_password
```

### Detener los contenedores
```bash
docker-compose down
```

### Detener y eliminar volúmenes (⚠️ elimina la base de datos)
```bash
docker-compose down -v
```

### Reconstruir contenedores
```bash
docker-compose up -d --build --force-recreate
```

### Ver contenedores en ejecución
```bash
docker-compose ps
```

## 🔧 Configuración de Servicios

### MySQL

- **Puerto**: 3306
- **Base de datos**: sistema_cursos
- **Usuario**: laravel_user
- **Contraseña**: laravel_password
- **Root password**: root_password
- **Volumen**: Los datos persisten en `mysql_data`

### PHP-FPM

- **Versión**: PHP 8.2
- **Extensiones instaladas**:
  - pdo_mysql
  - mbstring
  - exif
  - pcntl
  - bcmath
  - gd
  - zip

### Nginx

- **Puerto**: 8000
- **Document root**: /var/www/html/public
- **Configuración**: docker/nginx/default.conf

## 🛠️ Desarrollo

Para desarrollo con hot-reload y herramientas adicionales:

```bash
docker-compose -f docker-compose.dev.yml up -d --build
```

Este archivo incluye:
- Node.js en el contenedor de app
- Volúmenes montados para desarrollo
- Configuración optimizada para desarrollo

## 📦 Estructura de Archivos Docker

```
sistema-cursos/
├── Dockerfile              # Imagen de producción
├── Dockerfile.dev          # Imagen de desarrollo
├── docker-compose.yml      # Configuración de producción
├── docker-compose.dev.yml  # Configuración de desarrollo
├── docker/
│   ├── mysql/
│   │   └── init.sql        # Script de inicialización MySQL
│   ├── nginx/
│   │   └── default.conf    # Configuración Nginx
│   └── php/
│       └── php.ini         # Configuración PHP
└── .env.docker.example     # Variables de entorno para Docker
```

## 🔍 Solución de Problemas

### Error: "Connection refused" en MySQL

Espera a que MySQL esté completamente iniciado:

```bash
docker-compose logs mysql
```

Verifica el healthcheck:
```bash
docker-compose ps
```

### Error: "Permission denied" en storage

```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Error: "Class not found"

```bash
docker-compose exec app composer dump-autoload
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
```

### Reiniciar un servicio específico

```bash
docker-compose restart app
docker-compose restart mysql
docker-compose restart nginx
```

### Ver el estado de los servicios

```bash
docker-compose ps
```

### Limpiar todo y empezar de nuevo

```bash
# Detener y eliminar contenedores, redes y volúmenes
docker-compose down -v

# Eliminar imágenes
docker-compose down --rmi all

# Reconstruir desde cero
docker-compose up -d --build
```

## 🔐 Cambiar Credenciales

Si quieres cambiar las credenciales de MySQL, edita `docker-compose.yml`:

```yaml
environment:
  MYSQL_DATABASE: tu_base_datos
  MYSQL_ROOT_PASSWORD: tu_password_root
  MYSQL_USER: tu_usuario
  MYSQL_PASSWORD: tu_password
```

Y actualiza el `.env` con las mismas credenciales.

## 📊 Monitoreo

### Ver uso de recursos

```bash
docker stats
```

### Ver logs en tiempo real

```bash
docker-compose logs -f --tail=100
```

## 🚀 Producción

Para producción, considera:

1. Cambiar `APP_DEBUG=false` en `.env`
2. Usar variables de entorno seguras
3. Configurar SSL/TLS en Nginx
4. Usar un volumen externo para MySQL
5. Configurar backups automáticos
6. Optimizar imágenes Docker

---

¡Listo! Tu sistema está corriendo en Docker. 🎉

