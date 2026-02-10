# ✅ Configuración Final para Railway - Sistema de Cursos

## 🎯 Configuración Basada en Sistema Funcional

Esta configuración está basada en tu proyecto `SistemaDeGestion` que ya funciona en Railway.

## 📋 Configuración en Railway

### Build Settings
- **Builder**: Dockerfile
- **Dockerfile Path**: `/Dockerfile` (o `Dockerfile` si está en la raíz)
- **Metal Build Environment**: ✅ Activado (recomendado)

### Deploy Settings
- **Start Command**: ⚠️ **DEJAR VACÍO** (el Dockerfile ya tiene el CMD con start.sh)
- **Healthcheck Path**: `/` (recomendado)
- **Restart Policy**: On Failure
- **Max restart retries**: 10

### Networking
- **Public Networking**: ✅ Activado
- **Target Port**: ⚠️ **DEJAR VACÍO** (Railway usa $PORT automáticamente)
- **Domain**: `sistemacursos-production.up.railway.app`

## 🔧 Variables de Entorno Requeridas

En Railway → Variables, configura:

```env
APP_NAME="Sistema de Cursos"
APP_ENV=production
APP_KEY=base64:TU_CLAVE_AQUI
APP_DEBUG=false
APP_URL=https://sistemacursos-production.up.railway.app

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

## 📝 Archivos Creados

### 1. Dockerfile
- ✅ Basado en el Dockerfile funcional de SistemaDeGestion
- ✅ Usa `php:8.2-cli`
- ✅ Instala Node.js para compilar assets
- ✅ Ejecuta `start.sh` al iniciar

### 2. start.sh
- ✅ Script de inicio que:
  - Limpia cachés
  - Optimiza la aplicación
  - Ejecuta migraciones
  - Crea enlace de storage
  - Inicia el servidor en el puerto correcto

## 🚀 Pasos para Desplegar

### 1. Hacer Commit y Push

```bash
cd sistema-cursos
git add Dockerfile start.sh
git commit -m "Fix: Dockerfile y start.sh para Railway basado en SistemaDeGestion"
git push origin main
```

### 2. Configurar en Railway

1. **Eliminar Start Command** (si existe):
   - Railway → Settings → Deploy
   - Start Command: **DEJAR VACÍO**

2. **Verificar Dockerfile Path**:
   - Railway → Settings → Build
   - Dockerfile Path: `Dockerfile` (o `/Dockerfile`)

3. **Eliminar Target Port**:
   - Railway → Settings → Networking → Public Networking
   - Target Port: **DEJAR VACÍO**

4. **Agregar Healthcheck**:
   - Railway → Settings → Deploy
   - Healthcheck Path: `/`

### 3. Verificar Variables de Entorno

Asegúrate de tener todas las variables configuradas, especialmente:
- `APP_KEY` (generar si no existe)
- Variables de base de datos con formato `${{MySQL.VARIABLE}}`

### 4. Ejecutar Migraciones (Primera Vez)

Después del primer deploy exitoso, en Railway Shell:

```bash
php artisan migrate --force
php artisan db:seed --force
```

O el script `start.sh` las ejecutará automáticamente.

## ✅ Verificación

Después del deploy:

1. ✅ Build debe completarse sin errores
2. ✅ Healthcheck debe pasar (verde)
3. ✅ Logs deben mostrar: "Iniciando servidor Laravel en puerto XXXX"
4. ✅ URL debe responder correctamente

## 🔍 Diferencias con SistemaDeGestion

- Mismo patrón de Dockerfile
- Mismo script start.sh
- Mismas configuraciones de Railway
- Solo cambian las variables de entorno específicas del proyecto

## 📞 Si Hay Problemas

1. **Revisa los logs** en Railway Dashboard
2. **Verifica APP_KEY** está configurada
3. **Verifica variables de BD** usan formato `${{MySQL.VARIABLE}}`
4. **Verifica que Start Command esté VACÍO** (usa CMD del Dockerfile)

---

**Esta configuración debería funcionar igual que tu SistemaDeGestion.** 🎉

