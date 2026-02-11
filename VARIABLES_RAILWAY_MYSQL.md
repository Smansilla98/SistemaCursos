# 🔧 Configurar Variables de Entorno en Railway para MySQL

## ⚠️ Problema Actual

Laravel está usando SQLite en lugar de MySQL porque las variables de entorno no están configuradas correctamente.

## ✅ Solución: Configurar Variables en Railway

### Paso 1: Ir a Variables de Entorno

1. En Railway → Tu proyecto
2. Click en tu **servicio web** (Laravel)
3. Click en **"Variables"**

### Paso 2: Agregar Variables de Laravel

Agrega estas variables **SIN comillas dobles** (excepto APP_NAME):

```env
APP_NAME="Sistema de Cursos"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:TU_CLAVE_GENERADA_AQUI
APP_URL=https://sistemacursos-production.up.railway.app

DB_CONNECTION=mysql
```

### Paso 3: Agregar Variables de MySQL

Railway ya tiene estas variables en el servicio MySQL. Necesitas mapearlas a las variables estándar de Laravel.

**Opción A: Usar Referencias de Railway (Recomendado)**

```env
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
```

**Opción B: Usar Valores Reales (Si las referencias no funcionan)**

Basándote en las variables que proporcionaste:

```env
DB_HOST=${{RAILWAY_PRIVATE_DOMAIN}}
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=${{MYSQL_ROOT_PASSWORD}}
```

O si prefieres valores directos (menos seguro):

```env
DB_HOST=TU_RAILWAY_PRIVATE_DOMAIN_AQUI
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=HNUUJdXSOTRxWgcQGYAytJOAuPJysiNf
```

### Paso 4: Otras Variables Necesarias

```env
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

## 📋 Configuración Completa

Copia y pega esto en Railway → Variables (ajusta los valores):

```env
APP_NAME="Sistema de Cursos"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:TU_CLAVE_AQUI
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
```

## ⚠️ Importante

1. **NO uses comillas dobles** excepto en `APP_NAME` si tiene espacios
2. **DB_CONNECTION=mysql** es crítico - sin esto Laravel usa SQLite
3. Si las referencias `${{MySQL.*}}` no funcionan, usa los valores reales
4. **APP_KEY** debe ser generado: `php artisan key:generate --show`

## 🔍 Verificar

Después de configurar, haz un nuevo deployment y revisa los logs. Deberías ver:

```
DB_CONNECTION: mysql
DB_HOST: [tu-host]
DB_DATABASE: railway
```

En lugar de:
```
DB_CONNECTION: no configurado
```

