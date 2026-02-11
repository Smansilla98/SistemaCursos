# 🔧 Solución: railway.toml causando errores

## ❌ Problema

El archivo `railway.toml` estaba configurado para usar **Nixpacks**, pero el proyecto usa **Dockerfile**. Esto causaba conflictos.

## ✅ Solución Aplicada

He actualizado el `railway.toml` para que use **Dockerfile** en lugar de Nixpacks.

### Cambios Realizados

**Antes:**
```toml
[build]
builder = "nixpacks"
buildCommand = "..."
startCommand = "..."  # ❌ Esto causaba conflictos
```

**Ahora:**
```toml
[build]
builder = "dockerfile"
dockerfilePath = "Dockerfile"

[deploy]
healthcheckPath = "/"
healthcheckTimeout = 100
restartPolicyType = "on_failure"
restartPolicyMaxRetries = 10
```

### ⚠️ Puntos Importantes

1. **NO hay `startCommand`** - El Dockerfile ya tiene `CMD ["/var/www/html/start.sh"]`
2. **NO hay `buildCommand`** - Docker maneja el build automáticamente
3. **Builder es `dockerfile`** - Para usar el Dockerfile

## 🔄 Alternativa: Eliminar railway.toml

Si prefieres que Railway detecte automáticamente el Dockerfile (como en `restaurante-laravel`), puedes **eliminar** el archivo `railway.toml` completamente:

```bash
rm railway.toml
```

Railway detectará automáticamente:
- ✅ El Dockerfile en la raíz
- ✅ La configuración necesaria

## ✅ Verificación

Después de actualizar o eliminar `railway.toml`:

1. Haz commit y push:
   ```bash
   git add railway.toml
   git commit -m "Fix: railway.toml configurado para Dockerfile"
   git push origin main
   ```

2. Railway hará un nuevo deployment automáticamente

3. Verifica los logs para asegurarte de que:
   - ✅ Usa el Dockerfile
   - ✅ No hay conflictos con startCommand
   - ✅ El servidor inicia correctamente

## 📝 Nota

El archivo `railway.toml` es **opcional**. Si no existe, Railway detecta automáticamente:
- Dockerfile → Usa Docker
- package.json → Usa Nixpacks
- Otros archivos → Detección automática

Como `restaurante-laravel` no tiene `railway.toml` y funciona perfectamente, puedes eliminarlo si prefieres la detección automática.

