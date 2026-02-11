# 🔧 Solución: Variables de Entorno No Se Están Leyendo

## ❌ Problema

Todas las variables muestran "no configurado":
```
DB_HOST: no configurado
DB_DATABASE: no configurado
DB_CONNECTION: no configurado
```

Laravel está usando valores por defecto (`127.0.0.1`, `laravel`) porque las variables no están disponibles.

## 🔍 Causa

Railway tiene las variables en el **servicio MySQL**, pero **NO las está compartiendo automáticamente** con el **servicio web**.

## ✅ Solución: Compartir Variables o Configurarlas Manualmente

### Opción 1: Compartir Variables desde MySQL (Recomendado)

1. En Railway → Tu proyecto
2. Click en tu **servicio MySQL**
3. Click en **"Variables"**
4. Busca cada variable y haz click en el **ícono de compartir** (🔗) o **"Share"**
5. Selecciona tu **servicio web** (Laravel)
6. Repite para todas las variables necesarias:
   - `MYSQLHOST` → compartir como `DB_HOST`
   - `MYSQLPORT` → compartir como `DB_PORT`
   - `MYSQLDATABASE` → compartir como `DB_DATABASE`
   - `MYSQLUSER` → compartir como `DB_USERNAME`
   - `MYSQLPASSWORD` o `MYSQL_ROOT_PASSWORD` → compartir como `DB_PASSWORD`

### Opción 2: Configurar Variables Directamente en el Servicio Web

1. En Railway → Tu proyecto
2. Click en tu **servicio web** (Laravel)
3. Click en **"Variables"**
4. Click en **"New Variable"**
5. Agrega estas variables **UNA POR UNA** con valores REALES:

```env
DB_CONNECTION=mysql
DB_HOST=[valor de MYSQLHOST desde MySQL]
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=[valor de MYSQL_ROOT_PASSWORD desde MySQL]
```

**Para obtener los valores reales:**
1. Ve a Railway → Tu servicio MySQL → Variables
2. Copia el valor REAL de cada variable (no la referencia `${{}}`)
3. Pégalo en las variables del servicio web

### Opción 3: Usar DATABASE_URL (Más Simple)

Railway puede proporcionar `MYSQL_URL`. Úsala así:

1. En Railway → Tu servicio MySQL → Variables
2. Busca `MYSQL_URL` o `MYSQL_PRIVATE_URL`
3. Copia el valor completo (ejemplo: `mysql://root:password@host:3306/railway`)
4. En tu servicio web → Variables, agrega:

```env
DB_CONNECTION=mysql
DATABASE_URL=[pegar el valor completo de MYSQL_URL]
```

Laravel leerá automáticamente `DATABASE_URL` y configurará todo.

## 📋 Variables Mínimas Requeridas

En tu **servicio web** (Laravel), asegúrate de tener:

```env
APP_NAME="Sistema de Cursos"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:TU_CLAVE_AQUI
APP_URL=https://sistemacursos-production.up.railway.app

DB_CONNECTION=mysql
DB_HOST=[valor real]
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=[valor real]

SESSION_DRIVER=database
CACHE_STORE=database
```

## ⚠️ Importante

1. **Las variables deben estar en el SERVICIO WEB**, no solo en MySQL
2. **Usa valores REALES**, no referencias `${{}}` si no funcionan
3. **NO uses comillas dobles** excepto en `APP_NAME`
4. **Después de agregar variables**, Railway hará un nuevo deployment automáticamente

## 🔍 Verificación

Después de configurar, revisa los logs. Deberías ver:

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

## 🚨 Si Sigue Sin Funcionar

1. Verifica que las variables estén en el **servicio web**, no solo en MySQL
2. Verifica que los valores sean **reales**, no referencias
3. Verifica que **NO haya comillas dobles** en los valores (excepto APP_NAME)
4. Haz un **redeploy manual** después de agregar las variables

