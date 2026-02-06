# Sistema de Gestión de Cursos - Centro de Estética y Uñas

Sistema web completo para la gestión de cursos online con roles de administrador, profesor y alumno, integración con MercadoPago, y sistema de claves de acceso.

## 🚀 Características

- **Sistema de Roles**: Admin, Profesor, Alumno
- **Gestión de Cursos**: CRUD completo con categorías, módulos y archivos multimedia
- **Sistema de Pagos**: Integración con MercadoPago
- **Claves de Acceso**: Sistema de claves para desbloquear cursos
- **Gestión de Archivos**: Subida y organización de videos, PDFs, imágenes
- **Paneles Personalizados**: Diferentes interfaces según el rol del usuario

## 📋 Requisitos

- PHP >= 8.2
- Composer
- Node.js y NPM
- MySQL o PostgreSQL
- Extensiones PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## 🔧 Instalación

### 🐳 Opción 1: Usando Docker (Recomendado)

La forma más fácil de levantar el proyecto es usando Docker:

1. **Clonar el repositorio o navegar al directorio**:
```bash
cd sistema-cursos
```

2. **Usar el script de inicio automático**:
```bash
./docker-start.sh
```

O manualmente:

```bash
# Copiar archivo de entorno
cp .env.docker.example .env

# Levantar contenedores
docker-compose up -d --build

# Instalar dependencias
docker-compose exec app composer install

# Generar clave
docker-compose exec app php artisan key:generate

# Ejecutar migraciones
docker-compose exec app php artisan migrate

# Ejecutar seeders
docker-compose exec app php artisan db:seed

# Crear enlace de storage
docker-compose exec app php artisan storage:link
```

3. **Acceder a la aplicación**:
- URL: http://localhost:8000
- MySQL: localhost:3306
  - Usuario: `laravel_user`
  - Contraseña: `laravel_password`
  - Base de datos: `sistema_cursos`

📖 **Ver documentación completa de Docker**: [DOCKER.md](DOCKER.md)

### 💻 Opción 2: Instalación Manual

1. **Clonar el repositorio o navegar al directorio**:
```bash
cd sistema-cursos
```

2. **Instalar dependencias de PHP**:
```bash
composer install
```

3. **Instalar dependencias de Node.js**:
```bash
npm install
```

4. **Configurar el archivo .env**:
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configurar la base de datos en .env**:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base_datos
DB_USERNAME=usuario
DB_PASSWORD=contraseña
```

6. **Configurar MercadoPago (opcional)**:
```env
MERCADOPAGO_ACCESS_TOKEN=tu_access_token
```

7. **Ejecutar migraciones y seeders**:
```bash
php artisan migrate
php artisan db:seed
```

8. **Crear el enlace simbólico para storage**:
```bash
php artisan storage:link
```

9. **Compilar assets**:
```bash
npm run build
```

## 👤 Usuario Administrador por Defecto

- **Email**: admin@cursos.com
- **Contraseña**: password

⚠️ **IMPORTANTE**: Cambia la contraseña después del primer inicio de sesión.

## 🎯 Roles del Sistema

### Admin
- Dashboard con estadísticas
- CRUD completo de cursos
- Gestión de usuarios y roles
- Aprobación/rechazo de pagos
- Generación y gestión de claves de acceso
- Visualización de comprobantes de pago

### Profesor
- Ver cursos asignados
- Subir contenido a sus cursos
- Ver alumnos inscritos
- Gestionar material del curso

### Alumno
- Ver cursos disponibles
- Comprar cursos vía MercadoPago
- Ingresar claves de acceso
- Acceder al material de cursos desbloqueados
- Ver progreso en los cursos

## 📁 Estructura del Proyecto

```
sistema-cursos/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/        # Controladores del panel admin
│   │       ├── Student/      # Controladores del panel alumno
│   │       └── Teacher/      # Controladores del panel profesor
│   └── Models/               # Modelos Eloquent
├── database/
│   ├── migrations/           # Migraciones de base de datos
│   └── seeders/             # Seeders para datos iniciales
├── resources/
│   └── views/               # Vistas Blade
│       ├── admin/           # Vistas del panel admin
│       ├── student/         # Vistas del panel alumno
│       └── teacher/        # Vistas del panel profesor
└── routes/
    └── web.php              # Rutas de la aplicación
```

## 🔐 Sistema de Claves de Acceso

El sistema permite generar claves de acceso para cursos:

1. **Claves de un solo uso**: Se desactivan después de ser utilizadas
2. **Claves reutilizables**: Pueden ser usadas múltiples veces
3. **Claves asociadas a usuario**: Se vinculan a un usuario específico

## 💳 Integración con MercadoPago

Para usar MercadoPago:

1. Obtén tu Access Token desde el panel de MercadoPago
2. Agrega la variable `MERCADOPAGO_ACCESS_TOKEN` en tu `.env`
3. Los pagos se procesarán automáticamente

Si no configuras MercadoPago, el sistema funcionará con pagos manuales que el admin debe aprobar.

## 📝 Comandos Útiles

```bash
# Ejecutar servidor de desarrollo
php artisan serve

# Compilar assets en desarrollo
npm run dev

# Compilar assets para producción
npm run build

# Ejecutar migraciones
php artisan migrate

# Crear un nuevo seeder
php artisan make:seeder NombreSeeder

# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 🛠️ Desarrollo

### Crear un nuevo curso (Admin)

1. Ir a `/admin/courses`
2. Click en "Crear Curso"
3. Completar el formulario
4. Subir imagen de portada
5. Asignar categoría y profesor (opcional)

### Generar claves de acceso (Admin)

1. Ir a `/admin/access-keys`
2. Click en "Generar Clave"
3. Seleccionar el curso
4. Configurar tipo de clave (un solo uso o reutilizable)

### Comprar un curso (Alumno)

1. Ver cursos disponibles en `/student/courses`
2. Click en "Comprar" o "Ver más"
3. Completar el pago con MercadoPago
4. El acceso se desbloqueará automáticamente al aprobarse el pago

### Usar clave de acceso (Alumno)

1. Ir al curso deseado
2. Ingresar la clave en el campo correspondiente
3. El acceso se desbloqueará inmediatamente

## 📦 Tecnologías Utilizadas

- **Laravel 12**: Framework PHP
- **Laravel Breeze**: Autenticación y scaffolding
- **Spatie Laravel Permission**: Gestión de roles y permisos
- **MercadoPago SDK**: Integración de pagos
- **Intervention Image**: Procesamiento de imágenes
- **Tailwind CSS**: Framework CSS
- **Alpine.js**: JavaScript framework ligero

## 📄 Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📞 Soporte

Para soporte, envía un email a soporte@ejemplo.com o abre un issue en el repositorio.

---

Desarrollado con ❤️ para centros de estética y uñas
