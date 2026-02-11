# ✅ Checklist de Configuración Railway

## 🔧 Configuración Crítica en Railway

### 1. **Build Settings** (Settings → Build)

```
Builder: Dockerfile ✅
Dockerfile Path: Dockerfile (o /Dockerfile)
Metal Build Environment: ✅ Activado
```

### 2. **Deploy Settings** (Settings → Deploy)

⚠️ **MUY IMPORTANTE**:

```
Start Command: ⬜ DEJAR COMPLETAMENTE VACÍO
```

**❌ NO pongas nada aquí** - El Dockerfile ya tiene `CMD ["/var/www/html/start.sh"]`

```
Healthcheck Path: /
Restart Policy: On Failure
Max restart retries: 10
```

### 3. **Networking** (Settings → Networking)

⚠️ **MUY IMPORTANTE**:

```
Public Networking: ✅ Activado
Target Port: ⬜ DEJAR VACÍO (NO poner 9000 ni ningún número)
```

**❌ NO configures un puerto fijo** - Railway asigna el puerto automáticamente a través de `$PORT`

### 4. **Variables de Entorno** (Settings → Variables)

```env
APP_NAME="Sistema de Cursos"
APP_ENV=production
APP_KEY=base64:TU_CLAVE_GENERADA_AQUI
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

### 5. **Base de Datos MySQL**

1. En Railway, agrega un servicio **MySQL**
2. Railway generará automáticamente las variables `${{MySQL.*}}`
3. Asegúrate de que las variables de entorno usen estas referencias

## 🔍 Verificación Post-Deploy

### 1. Revisar Logs

Ve a **Deployments** → Último deployment → **View Logs**

Deberías ver:
```
=== Iniciando Sistema de Cursos ===
=== Variables de Entorno ===
APP_ENV: production
...
=== Esperando base de datos ===
✓ Base de datos disponible
...
=== Servidor iniciado ===
Host: 0.0.0.0
Port: 8000
```

### 2. Verificar que el Servidor Responde

En los logs, busca:
- ✅ "Servidor iniciado"
- ✅ "Host: 0.0.0.0"
- ✅ "Port: [número]"

### 3. Probar el Healthcheck

Abre tu dominio de Railway en el navegador:
```
https://tu-dominio.up.railway.app
```

Deberías ver la página de inicio de Laravel.

## ❌ Errores Comunes

### Error 1: "Start Command" configurado
**Síntoma**: Healthcheck falla
**Solución**: Dejar Start Command **VACÍO**

### Error 2: "Target Port" configurado con número fijo
**Síntoma**: Healthcheck falla o conexión rechazada
**Solución**: Dejar Target Port **VACÍO**

### Error 3: APP_KEY no configurado
**Síntoma**: Error 500 o aplicación no inicia
**Solución**: Generar APP_KEY:
```bash
php artisan key:generate --show
```
Y copiarlo a Railway → Variables → APP_KEY

### Error 4: Base de datos no conectada
**Síntoma**: Error de conexión en logs
**Solución**: Verificar que MySQL está agregado y las variables `${{MySQL.*}}` están configuradas

## 🚀 Pasos Finales

1. ✅ Verificar que Start Command está **VACÍO**
2. ✅ Verificar que Target Port está **VACÍO**
3. ✅ Verificar que Healthcheck Path es `/`
4. ✅ Verificar que todas las variables de entorno están configuradas
5. ✅ Hacer nuevo deployment
6. ✅ Revisar logs
7. ✅ Probar la aplicación en el navegador

---

**Nota**: El Dockerfile y start.sh ahora son idénticos a los de `restaurante-laravel` que funciona correctamente.

