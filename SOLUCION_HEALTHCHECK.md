# 🔧 Solución: Healthcheck Failed en Railway

## ❌ Problema

El healthcheck falla porque el Dockerfile está usando `php-fpm` que requiere Nginx, pero Railway usa el servidor built-in de PHP.

## ✅ Solución Aplicada

He actualizado el `Dockerfile` para Railway:

### Cambios Realizados

1. **Cambiado de `php:8.2-fpm` a `php:8.2-cli`**
   - FPM es para Nginx
   - CLI permite usar `php artisan serve`

2. **Agregado Node.js**
   - Para compilar assets durante el build

3. **Cambiado el CMD**
   - De: `CMD ["php-fpm"]`
   - A: `php artisan serve --host=0.0.0.0 --port=$PORT`

4. **Agregado compilación de assets**
   - `npm ci && npm run build` en el build

## 🚀 Próximos Pasos

### 1. Verificar Start Command en Railway

En Railway → Settings → Deploy → Start Command:

**Debe estar vacío** (el Dockerfile ya tiene el CMD correcto)

O si Railway lo requiere, debe ser:
```bash
php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=$PORT
```

### 2. Verificar Variables de Entorno

Asegúrate de tener:

```env
APP_KEY=base64:TU_CLAVE_AQUI
APP_ENV=production
APP_DEBUG=false
APP_URL=https://sistemacursos-production.up.railway.app

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
```

### 3. Redeploy

1. Haz commit y push del nuevo Dockerfile
2. Railway detectará los cambios automáticamente
3. O haz un redeploy manual en Railway Dashboard

### 4. Ejecutar Migraciones

Después del deploy exitoso, en Railway Shell:

```bash
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
```

## 🔍 Verificación

Después del redeploy:

1. ✅ El build debe completarse sin errores
2. ✅ El healthcheck debe pasar (verde)
3. ✅ La URL debe responder correctamente
4. ✅ Los logs deben mostrar: "Laravel development server started"

## 📝 Nota Importante

Si prefieres NO usar Dockerfile, puedes:

1. En Railway → Settings → Build
2. Cambiar Builder de "Dockerfile" a "Nixpacks"
3. Railway detectará automáticamente que es Laravel
4. Usará el `Procfile` o `nixpacks.toml` que ya creamos

**Recomendación**: Usa **Nixpacks** en lugar de Dockerfile para Laravel. Es más simple y Railway lo maneja mejor automáticamente.

---

El Dockerfile actualizado debería resolver el problema del healthcheck. 🎉

