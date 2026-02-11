# 🔍 Verificar Variables de Entorno en Railway

## ⚠️ Problema

Laravel está intentando conectarse a `127.0.0.1` y base de datos `laravel` en lugar de usar las variables de Railway.

## 🔍 Diagnóstico

El error muestra:
```
Host: 127.0.0.1, Port: 3306, Database: laravel
```

Esto significa que las variables de entorno **NO se están leyendo correctamente**.

## ✅ Solución: Verificar Variables en Railway

### Paso 1: Verificar Variables en Railway

1. Ve a Railway → Tu proyecto
2. Click en tu **servicio web** (Laravel)
3. Click en **"Variables"**
4. Verifica que existan estas variables:

```env
DB_CONNECTION=mysql
DB_HOST=[debe tener un valor, NO ${{MySQL.MYSQLHOST}}]
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=[debe tener un valor]
```

### Paso 2: Problema con Referencias `${{}}`

Si ves variables como `${{MySQL.MYSQLHOST}}`, Railway puede no estar resolviendo estas referencias correctamente.

**Solución**: Usa los valores REALES en lugar de referencias:

1. Ve a Railway → Tu servicio MySQL → **"Variables"**
2. Copia los valores REALES de:
   - `MYSQLHOST` o `RAILWAY_PRIVATE_DOMAIN`
   - `MYSQLDATABASE` (debería ser `railway`)
   - `MYSQLUSER` (debería ser `root`)
   - `MYSQL_ROOT_PASSWORD` o `MYSQLPASSWORD`

3. En tu servicio web → Variables, reemplaza las referencias con valores reales:

```env
DB_CONNECTION=mysql
DB_HOST=containers-us-west-xxx.railway.app  # Valor REAL de MYSQLHOST
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=HNUUJdXSOTRxWgcQGYAytJOAuPJysiNf  # Valor REAL de MYSQL_ROOT_PASSWORD
```

### Paso 3: Verificar en los Logs

Después de configurar, revisa los logs del deployment. Deberías ver:

```
=== Variables de Entorno ===
DB_CONNECTION: mysql
DB_HOST: containers-us-west-xxx.railway.app
DB_DATABASE: railway
DB_USERNAME: root
```

**NO deberías ver**:
```
DB_HOST: no configurado
DB_DATABASE: no configurado
```

## 🔧 Alternativa: Usar DATABASE_URL

Si las variables individuales no funcionan, Railway puede proporcionar `MYSQL_URL`. Úsala así:

```env
DB_CONNECTION=mysql
DATABASE_URL=mysql://root:HNUUJdXSOTRxWgcQGYAytJOAuPJysiNf@containers-us-west-xxx.railway.app:3306/railway
```

Laravel leerá automáticamente `DATABASE_URL` y configurará las demás variables.

## ⚠️ Importante

1. **NO uses comillas dobles** en los valores (excepto en `APP_NAME`)
2. **Usa valores REALES**, no referencias `${{}}` si no funcionan
3. **Verifica los logs** después de cada cambio
4. **DB_CONNECTION=mysql** es crítico

## 📝 Checklist

- [ ] Variables configuradas en Railway
- [ ] Valores REALES, no referencias `${{}}`
- [ ] `DB_CONNECTION=mysql` configurado
- [ ] Logs muestran las variables correctamente
- [ ] No más errores de conexión a `127.0.0.1`

