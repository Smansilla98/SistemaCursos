# 🔧 Corrección: Puerto en Railway

## ❌ Problema Detectado

En Railway → Networking → Public Networking:
- **Target port: 9000** ❌

Esto está **INCORRECTO**. Railway asigna un puerto dinámico a través de la variable `$PORT`, pero estás forzando el puerto 9000.

## ✅ Solución

### Opción 1: Eliminar el Target Port (Recomendado)

1. Ve a Railway → Settings → Networking → Public Networking
2. En "Target port", **elimina el valor 9000** o déjalo **vacío**
3. Railway usará automáticamente la variable `$PORT` que ya está configurada en tu Start Command

### Opción 2: Usar Variable $PORT

Si Railway te obliga a poner un valor, déjalo vacío o elimina esa configuración completamente.

**Railway detectará automáticamente el puerto desde `$PORT` en tu Start Command.**

## 🔍 Verificación

Después de corregir:

1. El Start Command ya está correcto:
   ```bash
   php artisan serve --host=0.0.0.0 --port=$PORT
   ```

2. El Dockerfile también está correcto (usa `$PORT`)

3. Solo falta eliminar el puerto fijo 9000 en Networking

## 📝 Pasos Exactos

1. **Railway Dashboard** → Tu Servicio → **Settings**
2. **Networking** → **Public Networking**
3. En "Target port", **elimina "9000"** o déjalo vacío
4. Click en **"Update"**
5. Railway hará un redeploy automático

## ⚠️ Nota Importante

Railway asigna puertos dinámicamente (ej: 3000, 5000, 8000, etc.). Tu aplicación escucha en `$PORT` (cualquier puerto que Railway asigne), pero si configuras un puerto fijo 9000, Railway intentará conectarse a ese puerto específico y fallará.

**Solución**: No configures un puerto fijo. Deja que Railway use `$PORT` automáticamente.

---

Después de eliminar el puerto 9000, el healthcheck debería pasar correctamente. ✅

