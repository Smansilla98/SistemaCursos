# 🔧 Configuración de Variables en Railway - Guía Completa

## ⚠️ Problema Actual

Las variables de entorno no se están leyendo. Todas muestran "no configurado".

## ✅ Solución Paso a Paso

### Paso 1: Ir al Servicio Web

1. Railway → Tu proyecto
2. Click en tu **servicio web** (Laravel) - NO el servicio MySQL
3. Click en **"Variables"**

### Paso 2: Obtener Valores desde MySQL

1. Railway → Tu proyecto
2. Click en tu **servicio MySQL**
3. Click en **"Variables"**
4. Anota estos valores REALES (copia los valores, NO las referencias):

```
MYSQLHOST = containers-us-west-xxx.railway.app
MYSQLPORT = 3306
MYSQLDATABASE = railway
MYSQLUSER = root
MYSQL_ROOT_PASSWORD = HNUUJdXSOTRxWgcQGYAytJOAuPJysiNf
```

### Paso 3: Agregar Variables en el Servicio Web

Vuelve a tu **servicio web** → **"Variables"** → **"New Variable"**

Agrega estas variables **UNA POR UNA**:

#### Variable 1:
```
Name: DB_CONNECTION
Value: mysql
```

#### Variable 2:
```
Name: DB_HOST
Value: [pegar valor de MYSQLHOST]
Ejemplo: containers-us-west-xxx.railway.app
```

#### Variable 3:
```
Name: DB_PORT
Value: 3306
```

#### Variable 4:
```
Name: DB_DATABASE
Value: railway
```

#### Variable 5:
```
Name: DB_USERNAME
Value: root
```

#### Variable 6:
```
Name: DB_PASSWORD
Value: [pegar valor de MYSQL_ROOT_PASSWORD]
Ejemplo: HNUUJdXSOTRxWgcQGYAytJOAuPJysiNf
```

### Paso 4: Otras Variables Necesarias

También agrega:

```
APP_NAME = Sistema de Cursos
APP_ENV = production
APP_DEBUG = false
APP_KEY = [generar con: php artisan key:generate --show]
APP_URL = https://sistemacursos-production.up.railway.app

SESSION_DRIVER = database
CACHE_STORE = database
```

## 🔍 Verificación

Después de agregar las variables:

1. Railway hará un nuevo deployment automáticamente
2. Ve a **Deployments** → Último deployment → **View Logs**
3. Deberías ver:

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
```

## ⚠️ Errores Comunes

### Error 1: Variables en el servicio MySQL pero no en el web
**Solución**: Las variables deben estar en el **servicio web**, no solo en MySQL

### Error 2: Usando referencias `${{MySQL.*}}`
**Solución**: Usa valores REALES, no referencias

### Error 3: Comillas dobles en los valores
**Solución**: NO uses comillas excepto en `APP_NAME` si tiene espacios

### Error 4: Variables con espacios o caracteres especiales
**Solución**: Asegúrate de que los valores no tengan espacios al inicio o final

## 📝 Checklist Final

- [ ] Variables agregadas en el **servicio web** (Laravel)
- [ ] Valores REALES, no referencias `${{}}`
- [ ] `DB_CONNECTION=mysql` configurado
- [ ] Todas las variables DB_* configuradas
- [ ] Logs muestran las variables correctamente
- [ ] No más errores de "no configurado"

