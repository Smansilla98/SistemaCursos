# 🔧 Configuración Correcta para Railway

## ⚠️ Problemas Detectados en tu Configuración Actual

1. **Puerto fijo 9000** - Debe usar `$PORT` (variable dinámica)
2. **Builder: Dockerfile** - Para Laravel es mejor usar Nixpacks (detección automática)
3. **Falta Start Command** - Crítico para que funcione
4. **Falta Healthcheck Path** - Para verificar que la app está viva

## ✅ Configuración Correcta

### 1. Cambiar Builder

En Railway → Settings → Build:

**Cambiar de:**
- Builder: `Dockerfile`

**A:**
- Builder: `Nixpacks` (Automatically Detected)

O si prefieres mantener Dockerfile, necesitas uno específico para Railway (ver abajo).

### 2. Configurar Start Command

En Railway → Settings → Deploy → Start Command:

```bash
php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=$PORT
```

**⚠️ IMPORTANTE**: 
- Usa `$PORT` (no 9000)
- Host debe ser `0.0.0.0` (no 127.0.0.1)

### 3. Configurar Healthcheck Path

En Railway → Settings → Deploy → Healthcheck Path:

```
/
```

### 4. Configurar Puerto

En Railway → Settings → Networking → Public Networking:

**NO configures un puerto fijo**. Railway asigna automáticamente el puerto a través de `$PORT`.

Si ves "Port 9000", elimínalo o déjalo vacío. Railway usará la variable `$PORT` automáticamente.

### 5. Build Command (si usas Nixpacks)

Railway lo detectará automáticamente, pero puedes configurarlo manualmente:

```bash
composer install --no-dev --optimize-autoloader --no-interaction && composer dump-autoload --optimize && npm ci && npm run build
```

## 🐳 Si Prefieres Usar Dockerfile

Si quieres mantener Dockerfile, necesitas uno específico para Railway:

### Dockerfile para Railway

```dockerfile
FROM php:8.2-cli

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

WORKDIR /app

# Copiar archivos
COPY . .

# Instalar dependencias
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && composer dump-autoload --optimize \
    && npm ci \
    && npm run build

# Exponer puerto (Railway lo maneja automáticamente)
EXPOSE $PORT

# Comando de inicio
CMD php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=$PORT
```

## 📝 Checklist de Configuración

- [ ] Builder: `Nixpacks` (o Dockerfile configurado correctamente)
- [ ] Start Command: `php artisan serve --host=0.0.0.0 --port=$PORT`
- [ ] Healthcheck Path: `/`
- [ ] Puerto: NO configurar puerto fijo (dejar que Railway use `$PORT`)
- [ ] Variables de entorno configuradas (APP_KEY, DB_*, etc.)

## 🔍 Verificación

Después de aplicar estos cambios:

1. **Redeploy** el servicio
2. Verifica que el estado sea "Active" (verde)
3. Revisa los logs para confirmar que está escuchando en el puerto correcto
4. Accede a la URL: `sistemacursos-production.up.railway.app`

## 🚨 Si Sigue el Error 502

1. Verifica que `APP_KEY` esté configurada
2. Revisa los logs en Railway Dashboard
3. Verifica que las variables de base de datos usen `${{MySQL.VARIABLE}}`
4. Ejecuta migraciones en Railway Shell:
   ```bash
   php artisan migrate --force
   ```

---

**Recomendación**: Usa **Nixpacks** (detección automática) en lugar de Dockerfile para Laravel. Es más simple y Railway lo maneja mejor.

