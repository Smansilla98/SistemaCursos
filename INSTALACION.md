# Guía de Instalación - Sistema de Gestión de Cursos

## 📋 Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

- **PHP >= 8.2** con extensiones: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- **Composer** (gestor de dependencias de PHP)
- **Node.js >= 18** y **NPM**
- **MySQL >= 5.7** o **PostgreSQL >= 10**
- **Git** (opcional)

## 🚀 Pasos de Instalación

### 1. Navegar al directorio del proyecto

```bash
cd sistema-cursos
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

Este comando instalará todas las dependencias necesarias incluyendo:
- Laravel Framework
- Laravel Breeze (autenticación)
- Spatie Laravel Permission (roles y permisos)
- MercadoPago SDK
- Intervention Image

### 3. Instalar dependencias de Node.js

```bash
npm install
```

### 4. Configurar el archivo de entorno

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configurar la base de datos

Edita el archivo `.env` y configura tu base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_base_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 6. Crear la base de datos

Crea una base de datos MySQL:

```sql
CREATE DATABASE nombre_de_tu_base_datos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

O si usas PostgreSQL:

```sql
CREATE DATABASE nombre_de_tu_base_datos;
```

### 7. Ejecutar migraciones

```bash
php artisan migrate
```

Esto creará todas las tablas necesarias:
- users
- roles y permissions (Spatie)
- categories
- courses
- modules
- course_files
- payments
- access_keys
- course_user (tabla pivot)

### 8. Ejecutar seeders

```bash
php artisan db:seed
```

Esto creará:
- Los roles: admin, profesor, alumno
- Un usuario administrador por defecto

### 9. Crear enlace simbólico para storage

```bash
php artisan storage:link
```

Esto permite acceder a los archivos subidos desde la web.

### 10. Compilar assets

Para desarrollo:
```bash
npm run dev
```

Para producción:
```bash
npm run build
```

### 11. Configurar MercadoPago (Opcional)

Si deseas usar MercadoPago para pagos:

1. Crea una cuenta en [MercadoPago Developers](https://www.mercadopago.com.ar/developers)
2. Obtén tu Access Token
3. Agrega en tu `.env`:

```env
MERCADOPAGO_ACCESS_TOKEN=tu_access_token_aqui
```

Si no configuras MercadoPago, el sistema funcionará con pagos manuales que el admin debe aprobar.

## 🎯 Iniciar el Servidor

### Desarrollo

En una terminal:
```bash
php artisan serve
```

En otra terminal (si estás desarrollando):
```bash
npm run dev
```

Accede a: `http://localhost:8000`

### Producción

Asegúrate de:
1. Compilar assets: `npm run build`
2. Optimizar: `php artisan optimize`
3. Configurar tu servidor web (Apache/Nginx) para apuntar a la carpeta `public`

## 👤 Credenciales por Defecto

Después de ejecutar los seeders, puedes iniciar sesión con:

- **Email**: `admin@cursos.com`
- **Contraseña**: `password`

⚠️ **IMPORTANTE**: Cambia esta contraseña inmediatamente después del primer inicio de sesión.

## 🔧 Configuración Adicional

### Permisos de Storage

En Linux/Mac, asegúrate de que las carpetas tengan los permisos correctos:

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Configuración de Queue (Opcional)

Si quieres procesar trabajos en segundo plano:

1. Configura tu driver de queue en `.env`:
```env
QUEUE_CONNECTION=database
```

2. Ejecuta el worker:
```bash
php artisan queue:work
```

## 🐛 Solución de Problemas

### Error: "Class 'Spatie\Permission\Models\Role' not found"

Ejecuta:
```bash
composer dump-autoload
php artisan config:clear
```

### Error: "SQLSTATE[HY000] [2002] Connection refused"

Verifica que:
- MySQL/PostgreSQL esté corriendo
- Las credenciales en `.env` sean correctas
- El puerto sea el correcto

### Error: "The stream or file could not be opened"

Ejecuta:
```bash
php artisan storage:link
chmod -R 775 storage
```

### Assets no se cargan

Ejecuta:
```bash
npm run build
php artisan cache:clear
```

## 📝 Próximos Pasos

1. **Cambiar contraseña del admin**
2. **Crear categorías** desde el panel admin
3. **Crear cursos** y asignar profesores
4. **Generar claves de acceso** si es necesario
5. **Configurar MercadoPago** para pagos automáticos

## 🆘 Soporte

Si encuentras problemas durante la instalación:
1. Revisa los logs en `storage/logs/laravel.log`
2. Verifica que todos los requisitos estén instalados
3. Asegúrate de que las extensiones de PHP estén habilitadas

---

¡Listo! Tu sistema está instalado y listo para usar. 🎉

