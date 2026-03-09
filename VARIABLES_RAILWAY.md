# Variables de entorno para Railway (sistema-cursos)

## Lo que ya tenés (correcto)

| Variable       | Tu valor                          | Nota |
|---------------|-----------------------------------|------|
| APP_ENV       | production                        | OK   |
| APP_DEBUG     | true                              | Ver recomendación abajo |
| APP_URL       | https://web-production-xxx.up.railway.app | OK (tu URL real) |
| APP_KEY       | base64:...                        | OK   |
| DB_CONNECTION | mysql                             | OK   |
| DB_HOST       | ${{MySQL.MYSQLHOST}}              | OK si el servicio MySQL se llama "MySQL" en Railway |
| DB_PORT       | 3306                              | OK   |
| DB_DATABASE   | ${{MySQL.MYSQLDATABASE}}          | OK   |
| DB_USERNAME   | root                              | OK (o usar ${{MySQL.MYSQLUSER}} si lo exponés) |
| DB_PASSWORD   | ${{MySQL.MYSQLPASSWORD}}          | OK   |

---

## Recomendaciones

### 1. APP_DEBUG en producción

En producción conviene **no** mostrar errores al usuario:

```env
APP_DEBUG=false
```

Dejalo en `true` solo para depurar; después pasalo a `false`.

---

### 2. Sesiones sobre HTTPS (importante)

Como tu `APP_URL` es `https://`, las cookies de sesión deberían ir solo por HTTPS:

```env
SESSION_SECURE_COOKIE=true
```

Si no lo ponés, en algunos entornos el login puede fallar o las sesiones no se mantienen bien.

---

### 3. MercadoPago (opcional)

Para que los pagos vayan a MercadoPago y no solo queden “pendiente” para aprobar a mano:

```env
MERCADOPAGO_ACCESS_TOKEN=APP_USR-xxxx...
```

Sin esta variable, las compras se crean en estado “pendiente” y el admin las aprueba/rechaza desde el panel.

---

### 4. Nombre de la app (opcional)

```env
APP_NAME="Sistema de Cursos"
```

Se usa en títulos, correos, etc.

---

### 5. Variables de MySQL en Railway

La sintaxis `${{MySQL.MYSQLHOST}}` usa el **nombre del servicio** (ej. `MySQL`). Comprobá en Railway que:

- El servicio de base de datos se llama exactamente como en la referencia (ej. `MySQL`).
- Las variables que exponés son las que usás: en el addon MySQL de Railway suelen ser `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, `MYSQLPASSWORD` (nombres pueden variar según el addon).

Si Railway inyecta directamente `MYSQLHOST`, `MYSQLDATABASE`, etc., en `config/database.php` ya hay fallback a esas variables, así que podrías no definir `DB_HOST`/`DB_DATABASE` y dejar que use `MYSQLHOST`/`MYSQLDATABASE`. En ese caso no hace falta repetirlas como `DB_*` salvo que quieras sobrescribirlas.

---

### 6. PORT

Railway suele inyectar **PORT** automáticamente. El `start.sh` usa `PORT` (por defecto 8000), así que en general no hace falta que la definas vos.

---

## Listado sugerido para Railway

**Mínimo (sin MercadoPago):**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://web-production-a4f7e.up.railway.app
APP_KEY=base64:4viXVM2uFgX7NKydnBN3ZfO1a+7sEe3YIBdq7rlBZ7w=
SESSION_SECURE_COOKIE=true

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=3306
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=root
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
```

**Si usás MercadoPago:**

```env
MERCADOPAGO_ACCESS_TOKEN=tu_token_aqui
```

**Opcional:**

```env
APP_NAME=Sistema de Cursos
```

---

## Resumen: qué le “faltaba”

1. **SESSION_SECURE_COOKIE=true** — recomendado para HTTPS.
2. **APP_DEBUG=false** — recomendado en producción.
3. **MERCADOPAGO_ACCESS_TOKEN** — solo si querés pagos con MercadoPago.
4. **APP_NAME** — opcional.

El resto de lo que tenés está bien para que la app y la base de datos funcionen en Railway.
