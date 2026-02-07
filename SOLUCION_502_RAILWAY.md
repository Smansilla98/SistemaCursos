# 🔧 Solución Error 502 en Railway

El error 502 "Bad Gateway" en Railway generalmente se debe a que la aplicación no está respondiendo correctamente. Aquí están las soluciones:

## ✅ Solución Rápida

### 1. Verificar Variables de Entorno

En Railway Dashboard → Tu Servicio → Variables, asegúrate de tener:

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
```

**⚠️ IMPORTANTE**: Usa el formato `${{MySQL.VARIABLE}}` para las variables de base de datos.

### 2. Generar APP_KEY

Si no tienes `APP_KEY`, en Railway Shell ejecuta:

```bash
php artisan key:generate --force
```

O genera una localmente y cópiala:

```bash
php artisan key:generate --show
```

### 3. Verificar Start Command

En Railway → Settings → Deploy, el Start Command debe ser:

```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

O con optimizaciones:

```bash
php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=$PORT
```

### 4. Verificar Build Command

El Build Command debe ser:

```bash
composer install --no-dev --optimize-autoloader --no-interaction && npm ci && npm run build
```

## 🔍 Diagnóstico

### Ver Logs en Railway

1. Ve a Railway Dashboard
2. Click en tu servicio
3. Click en "Deployments"
4. Click en el último deployment
5. Revisa los logs de build y runtime

### Comandos de Diagnóstico en Railway Shell

```bash
# Verificar que PHP está funcionando
php -v

# Verificar que Laravel puede iniciar
php artisan --version

# Verificar conexión a base de datos
php artisan tinker
>>> DB::connection()->getPdo();

# Ver logs de Laravel
tail -f storage/logs/laravel.log

# Verificar variables de entorno
php artisan tinker
>>> config('app.env');
>>> config('database.default');
```

## 🛠️ Soluciones Comunes

### Problema 1: APP_KEY no configurada

**Síntoma**: Error "No application encryption key"

**Solución**:
```bash
# En Railway Shell
php artisan key:generate --force
```

O agrega manualmente en Variables:
```
APP_KEY=base64:TU_CLAVE_GENERADA
```

### Problema 2: Base de datos no conecta

**Síntoma**: Error de conexión a MySQL

**Solución**:
1. Verifica que el servicio MySQL esté desplegado
2. Usa el formato correcto: `${{MySQL.MYSQLHOST}}`
3. Verifica que ambos servicios estén en el mismo proyecto

### Problema 3: Puerto incorrecto

**Síntoma**: Aplicación no responde

**Solución**:
Asegúrate de usar `$PORT` en el Start Command:
```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

### Problema 4: Permisos de storage

**Síntoma**: Errores al escribir archivos

**Solución**:
```bash
# En Railway Shell
chmod -R 775 storage bootstrap/cache
```

### Problema 5: Assets no compilados

**Síntoma**: CSS/JS no cargan

**Solución**:
Verifica que el Build Command incluya:
```bash
npm ci && npm run build
```

## 📝 Configuración Correcta para Railway

### Build Command
```bash
composer install --no-dev --optimize-autoloader --no-interaction && composer dump-autoload --optimize && npm ci && npm run build
```

### Start Command
```bash
php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=$PORT
```

### Variables de Entorno Mínimas

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:TU_CLAVE
APP_URL=https://tu-dominio.railway.app

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
```

## 🚀 Pasos para Corregir el 502

1. **Verifica los logs** en Railway Dashboard
2. **Revisa las variables de entorno** (especialmente APP_KEY)
3. **Verifica el Start Command** usa `$PORT`
4. **Ejecuta migraciones** si es necesario:
   ```bash
   php artisan migrate --force
   ```
5. **Limpia caché**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```
6. **Reconstruye** el deployment en Railway

## 🔄 Redeploy

Si nada funciona, haz un redeploy completo:

1. En Railway → Settings → Danger Zone
2. Click en "Redeploy"
3. O simplemente haz un push nuevo a GitHub

## 📞 Verificación Final

Después de aplicar las correcciones:

1. Espera 2-3 minutos para que Railway termine el deploy
2. Verifica que el servicio esté "Active" (verde)
3. Accede a la URL de tu aplicación
4. Si sigue el 502, revisa los logs en tiempo real

---

Si el problema persiste, comparte los logs de Railway y te ayudo a diagnosticar el problema específico.

