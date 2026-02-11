# 🔧 Solución Error 500 en Railway

## ❌ Problema

```
Database file at path [/var/www/html/database/database.sqlite] does not exist.
```

Laravel está intentando usar SQLite en lugar de MySQL.

## ✅ Soluciones Aplicadas

### 1. Cambiado Default de Base de Datos

**Archivo**: `config/database.php`

**Antes**:
```php
'default' => env('DB_CONNECTION', 'sqlite'),
```

**Ahora**:
```php
'default' => env('DB_CONNECTION', 'mysql'),
```

### 2. Corregida Migración con Sintaxis MySQL

**Archivo**: `database/migrations/2026_02_10_195057_change_modules_to_lessons.php`

La migración tenía sintaxis MySQL específica (`INNER JOIN` con alias) que no funciona en SQLite. Ahora usa Eloquent/Query Builder que es compatible.

## 🔧 Configuración Requerida en Railway

### Variables de Entorno Mínimas

En Railway → Tu servicio web → Variables, agrega:

```env
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
```

**⚠️ CRÍTICO**: `DB_CONNECTION=mysql` debe estar configurado, de lo contrario Laravel usará SQLite.

## 📝 Pasos para Corregir

1. **Agregar Variables en Railway**:
   - Ve a Railway → Tu servicio web → Variables
   - Agrega `DB_CONNECTION=mysql` y las demás variables de MySQL

2. **Hacer Commit de los Cambios**:
   ```bash
   git add config/database.php database/migrations/2026_02_10_195057_change_modules_to_lessons.php
   git commit -m "Fix: Cambiar default a MySQL y corregir migración"
   git push origin main
   ```

3. **Railway hará un nuevo deployment automáticamente**

4. **Verificar Logs**:
   - Deberías ver `DB_CONNECTION: mysql` en los logs
   - El error de SQLite no debería aparecer

## ✅ Verificación

Después de configurar las variables, los logs deberían mostrar:

```
=== Variables de Entorno ===
APP_ENV: production
DB_CONNECTION: mysql
DB_HOST: [tu-host]
DB_DATABASE: railway
DB_USERNAME: root
```

Y el servidor debería iniciar sin errores.

