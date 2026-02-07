# ✅ Checklist Rápido - Solucionar Error 502 en Railway

## 🔴 Pasos Inmediatos

- [ ] **1. Verificar APP_KEY en Variables de Entorno**
  - Ve a Railway → Tu Servicio → Variables
  - Busca `APP_KEY`
  - Si no existe o está vacía:
    ```bash
    # En Railway Shell
    php artisan key:generate --force
    ```
  - O agrega manualmente: `APP_KEY=base64:TU_CLAVE_GENERADA`

- [ ] **2. Verificar Start Command**
  - Railway → Settings → Deploy
  - Start Command debe ser:
    ```bash
    php artisan serve --host=0.0.0.0 --port=$PORT
    ```
  - ⚠️ NO debe tener Nginx, solo PHP built-in server

- [ ] **3. Verificar Variables de Base de Datos**
  - Deben usar el formato: `${{MySQL.VARIABLE}}`
  - NO valores directos como `localhost` o `127.0.0.1`
  - Ejemplo correcto:
    ```
    DB_HOST=${{MySQL.MYSQLHOST}}
    DB_PORT=${{MySQL.MYSQLPORT}}
    DB_DATABASE=${{MySQL.MYSQLDATABASE}}
    DB_USERNAME=${{MySQL.MYSQLUSER}}
    DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
    ```

- [ ] **4. Verificar Build Command**
  - Debe incluir:
    ```bash
    composer install --no-dev --optimize-autoloader --no-interaction && npm ci && npm run build
    ```

- [ ] **5. Revisar Logs**
  - Railway Dashboard → Tu Servicio → Deployments
  - Click en el último deployment
  - Revisa los logs de "Build" y "Runtime"
  - Busca errores en rojo

- [ ] **6. Ejecutar Migraciones**
  - Railway Shell:
    ```bash
    php artisan migrate --force
    php artisan db:seed --force
    php artisan storage:link
    ```

- [ ] **7. Limpiar Caché**
  - Railway Shell:
    ```bash
    php artisan config:clear
    php artisan cache:clear
    php artisan route:clear
    php artisan view:clear
    ```

- [ ] **8. Redeploy**
  - Railway → Settings → Danger Zone → Redeploy
  - O haz un push nuevo a GitHub

## 🔍 Verificación Final

Después de aplicar los pasos:

- [ ] El servicio muestra estado "Active" (verde)
- [ ] Los logs no muestran errores
- [ ] La URL responde (no 502)
- [ ] Puedes acceder a la aplicación

## 📞 Si Persiste el Error

1. Comparte los logs de Railway
2. Verifica que MySQL esté desplegado y activo
3. Revisa [SOLUCION_502_RAILWAY.md](SOLUCION_502_RAILWAY.md) para más detalles

---

**Tiempo estimado**: 5-10 minutos

