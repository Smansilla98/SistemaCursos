# 🔧 Configuración Railway - Solución Healthcheck

## ⚠️ Problema: Healthcheck Falla

El build se completa correctamente pero el healthcheck falla con "service unavailable".

## ✅ Solución: Configuración Correcta en Railway

### 1. **Settings → Build**

- **Builder**: `Dockerfile` ✅
- **Dockerfile Path**: `Dockerfile` (o `/Dockerfile`)
- **Metal Build Environment**: ✅ Activado (recomendado)

### 2. **Settings → Deploy**

⚠️ **CRÍTICO**: Dejar el **Start Command VACÍO**

- **Start Command**: ⬜ **DEJAR VACÍO** (el Dockerfile ya tiene `CMD ["/var/www/html/start.sh"]`)
- **Healthcheck Path**: `/` ✅
- **Restart Policy**: `On Failure`
- **Max restart retries**: `10`

**❌ NO configures un Start Command personalizado** - El Dockerfile ya lo tiene configurado.

### 3. **Settings → Networking**

- **Public Networking**: ✅ Activado
- **Target Port**: ⬜ **DEJAR VACÍO** (Railway usa `$PORT` automáticamente)
- **Domain**: Tu dominio de Railway

**❌ NO configures un puerto fijo** (como 9000) - Railway asigna el puerto dinámicamente.

### 4. **Variables de Entorno**

Asegúrate de tener estas variables configuradas:

```env
APP_NAME="Sistema de Cursos"
APP_ENV=production
APP_KEY=base64:TU_CLAVE_AQUI
APP_DEBUG=false
APP_URL=https://tu-dominio.up.railway.app

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

PORT=8000
```

### 5. **Verificar Logs**

Si el healthcheck sigue fallando, revisa los logs en Railway:

1. Ve a **Deployments** → Último deployment
2. Click en **View Logs**
3. Busca errores relacionados con:
   - Conexión a la base de datos
   - Variables de entorno faltantes
   - Errores de PHP

## 🔍 Diagnóstico

### Verificar que el servidor inicia

En los logs deberías ver:

```
=== Iniciando Sistema de Cursos ===
=== Variables de Entorno ===
APP_ENV: production
DB_CONNECTION: mysql
...
=== Esperando base de datos ===
✓ Base de datos disponible
=== Limpiando cachés ===
...
=== Servidor iniciado ===
Host: 0.0.0.0
Port: 8000
```

### Si el servidor no inicia

1. **Verificar APP_KEY**: Debe estar configurado
   ```bash
   php artisan key:generate --show
   ```

2. **Verificar conexión a BD**: Las variables de entorno deben estar correctas

3. **Verificar permisos**: El script `start.sh` debe tener permisos de ejecución

## ✅ Checklist Final

- [ ] Dockerfile está en la raíz del proyecto
- [ ] start.sh tiene permisos de ejecución (`chmod +x start.sh`)
- [ ] Start Command en Railway está **VACÍO**
- [ ] Target Port en Railway está **VACÍO**
- [ ] Healthcheck Path está configurado como `/`
- [ ] Variables de entorno están configuradas
- [ ] APP_KEY está generado
- [ ] Base de datos MySQL está conectada

## 🚀 Después de Configurar

1. Haz commit y push de los cambios:
   ```bash
   git add Dockerfile start.sh
   git commit -m "Fix: Dockerfile y start.sh iguales a restaurante-laravel"
   git push origin main
   ```

2. Railway detectará el cambio y hará un nuevo deployment

3. Monitorea los logs para verificar que todo funciona

---

**Nota**: El Dockerfile y start.sh ahora son idénticos a los de `restaurante-laravel` que funciona correctamente.

