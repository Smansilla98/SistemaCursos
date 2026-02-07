# 🚂 Guía de Despliegue en Railway

Esta guía te ayudará a desplegar el Sistema de Gestión de Cursos en Railway.

## 📋 Requisitos Previos

- Cuenta en [Railway](https://railway.app)
- Repositorio en GitHub (recomendado)
- Base de datos MySQL (Railway ofrece MySQL como servicio)

## 🚀 Pasos para Desplegar

### 1. Preparar el Repositorio

Asegúrate de que todos los archivos estén en GitHub:
- `railway.json` o `railway.toml`
- `Procfile`
- `.env.example` con todas las variables necesarias

### 2. Crear Proyecto en Railway

1. Ve a [Railway Dashboard](https://railway.app/dashboard)
2. Click en "New Project"
3. Selecciona "Deploy from GitHub repo"
4. Conecta tu repositorio `SistemaCursos`
5. Railway detectará automáticamente el proyecto

### 3. Configurar Base de Datos MySQL

1. En tu proyecto de Railway, click en "+ New"
2. Selecciona "Database" → "Add MySQL"
3. Railway creará una base de datos MySQL automáticamente
4. Copia las variables de conexión que Railway te proporciona

### 4. Configurar Variables de Entorno

En Railway, ve a tu servicio web → Variables y agrega:

#### Variables Requeridas

```env
APP_NAME="Sistema de Cursos"
APP_ENV=production
APP_KEY=base64:TU_CLAVE_AQUI
APP_DEBUG=false
APP_URL=https://tu-dominio.railway.app

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# MercadoPago (opcional)
MERCADOPAGO_ACCESS_TOKEN=tu_token_aqui
```

**Nota importante**: Railway usa variables de referencia para la base de datos. Usa el formato `${{MySQL.VARIABLE}}` para conectarte automáticamente.

### 5. Generar APP_KEY

Antes del primer despliegue, necesitas generar una clave:

```bash
# Localmente o en Railway Shell
php artisan key:generate --show
```

Copia la clave y agrégala como variable de entorno `APP_KEY` en Railway.

### 6. Configurar el Build

Railway debería detectar automáticamente que es un proyecto Laravel. Si no:

1. Ve a Settings → Build
2. Asegúrate de que el Build Command sea:
   ```bash
   composer install --no-dev --optimize-autoloader --no-interaction && composer dump-autoload --optimize && npm ci && npm run build
   ```

3. El Start Command debería ser:
   ```bash
   php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=$PORT
   ```

**⚠️ IMPORTANTE**: 
- Usa `$PORT` (no un puerto fijo)
- El host debe ser `0.0.0.0` (no `127.0.0.1`)
- Railway NO usa Nginx, usa el servidor built-in de PHP

### 7. Ejecutar Migraciones

Después del primer despliegue, necesitas ejecutar las migraciones:

**Opción 1: Railway Shell**
1. Ve a tu servicio en Railway
2. Click en "Shell"
3. Ejecuta:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   php artisan storage:link
   ```

**Opción 2: Comando en Deploy**
Agrega esto al Build Command:
```bash
composer install --no-dev --optimize-autoloader && npm install && npm run build && php artisan migrate --force && php artisan db:seed --force
```

### 8. Configurar Storage

Para que los archivos subidos funcionen:

1. En Railway, ve a tu servicio
2. Agrega un volumen persistente para `storage/app/public`
3. O configura un servicio de almacenamiento externo (S3, Cloudinary, etc.)

## 🔧 Solución de Problemas

### Error 502 Bad Gateway ⚠️

**Causa común**: La aplicación no está respondiendo correctamente.

**Soluciones paso a paso**:

1. **Verifica APP_KEY**:
   ```bash
   # En Railway Shell
   php artisan key:generate --force
   ```
   O agrega manualmente en Variables: `APP_KEY=base64:TU_CLAVE`

2. **Verifica el Start Command**:
   Debe ser exactamente:
   ```bash
   php artisan serve --host=0.0.0.0 --port=$PORT
   ```
   ⚠️ NO uses Nginx, Railway usa el servidor built-in de PHP

3. **Verifica variables de base de datos**:
   Usa el formato: `${{MySQL.MYSQLHOST}}` (no valores directos)

4. **Revisa los logs**:
   Railway Dashboard → Tu Servicio → Deployments → Ver logs

5. **Limpia caché y redeploy**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```

📖 **Ver guía completa**: [SOLUCION_502_RAILWAY.md](SOLUCION_502_RAILWAY.md)

### Error: "No application encryption key has been specified"

**Solución**:
```bash
# En Railway Shell
php artisan key:generate --force
```

O agrega `APP_KEY` manualmente en las variables de entorno.

### Error de Conexión a Base de Datos

**Solución**:
1. Verifica que las variables de MySQL usen el formato `${{MySQL.VARIABLE}}`
2. Asegúrate de que el servicio MySQL esté desplegado
3. Verifica que ambos servicios estén en el mismo proyecto

### Error: "Class not found" o "Composer autoload"

**Solución**:
Agrega al Build Command:
```bash
composer dump-autoload --optimize
```

### Assets no se cargan

**Solución**:
1. Verifica que `npm run build` se ejecute en el build
2. Asegúrate de que `APP_URL` esté correctamente configurada
3. Verifica que `public/build` exista después del build

## 📝 Configuración Recomendada

### Build Command
```bash
composer install --no-dev --optimize-autoloader --no-interaction && composer dump-autoload --optimize && npm ci && npm run build
```

### Start Command
```bash
php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=$PORT
```

### Healthcheck
Railway puede configurar un healthcheck automático. Asegúrate de tener una ruta `/` accesible.

## 🔐 Variables de Entorno Importantes

```env
# Producción
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.railway.app

# Base de datos (usar referencias de Railway)
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

# Optimización
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

## 🚀 Comandos Útiles en Railway Shell

```bash
# Ver logs
tail -f storage/logs/laravel.log

# Ejecutar migraciones
php artisan migrate --force

# Limpiar caché
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimizar
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verificar conexión a BD
php artisan tinker
>>> DB::connection()->getPdo();
```

## 📊 Monitoreo

Railway proporciona:
- Logs en tiempo real
- Métricas de uso
- Health checks automáticos
- Alertas de errores

## 🔄 Actualizaciones

Para actualizar el proyecto:

1. Haz push a tu repositorio
2. Railway detectará los cambios automáticamente
3. Se ejecutará un nuevo build y deploy

O manualmente:
1. Ve a tu servicio en Railway
2. Click en "Redeploy"

## 💡 Tips

1. **Usa variables de entorno** para toda la configuración sensible
2. **No subas `.env`** a GitHub
3. **Configura backups** de la base de datos
4. **Usa un dominio personalizado** si es necesario
5. **Monitorea los logs** regularmente

---

¡Tu aplicación debería estar funcionando en Railway! 🎉

