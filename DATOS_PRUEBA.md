# Datos base para probar la funcionalidad completa

Después de ejecutar `php artisan db:seed` (o al desplegar con seed), podés usar estos datos para probar todos los flujos.

---

## Usuarios de prueba

| Rol      | Email             | Contraseña | Uso |
|----------|-------------------|------------|-----|
| Admin    | `admin@cursos.com`   | `password` | Panel admin: cursos, usuarios, aprobar/rechazar compras. |
| Estudiante | `student@cursos.com` | `password` | Ver catálogo, “Mis cursos”, comprar, ver contenido. |

**Importante:** Cambiá las contraseñas en producción.

---

## Qué se crea con el seed

1. **Roles:** `admin`, `student`.
2. **Usuarios:** Admin y Estudiante (arriba).
3. **Cursos (3):**
   - Curso Completo de Uñas Acrílicas (15.000)
   - Técnicas de Pestañas pelo a pelo (12.000)
   - Estética Facial Profesional (18.000)
4. **Lecciones:** Cada curso tiene 3 lecciones de ejemplo (intro PDF, técnica básica y avanzada en video).
5. **Compras:**
   - Una compra **aprobada** del estudiante al primer curso → el estudiante ya tiene ese curso en “Mis cursos” y puede ver el contenido.
   - Una compra **pendiente** del estudiante a otro curso → para probar en Admin → Compras el flujo de aprobar/rechazar.

---

## Pruebas recomendadas

### 1. Admin

- Iniciar sesión con `admin@cursos.com` / `password`.
- **Dashboard:** Ver resumen.
- **Cursos:** Listar, crear, editar, agregar lecciones, eliminar.
- **Usuarios:** Listar, crear, editar.
- **Compras:** Ver la compra pendiente y probar **Aprobar**; verificar que el estudiante pasa a tener ese curso y contenido desbloqueado.

### 2. Estudiante

- Iniciar sesión con `student@cursos.com` / `password`.
- **Mis cursos:** Debería aparecer al menos un curso (el de la compra aprobada).
- **Catálogo:** Ver todos los cursos; los que no tiene aparecen como disponibles para comprar.
- **Detalle de curso:** En un curso comprado, ver lecciones y contenido; en uno no comprado, ver precio y opción de compra.
- **Compra:** Ir a un curso no comprado → Comprar (si tenés MercadoPago configurado irá a MP; si no, queda compra pendiente para que Admin la apruebe).

### 3. Público (sin login)

- Home: listado de cursos activos.
- Ver detalle de un curso (sin acceso al contenido hasta comprar/iniciar sesión).

### 4. Flujo completo compra manual

1. Estudiante inicia sesión y crea una compra (sin MercadoPago queda pendiente).
2. Admin entra a Compras y aprueba esa compra.
3. Estudiante recarga “Mis cursos” y ve el nuevo curso con contenido desbloqueado.

---

## Ejecutar seed en entorno local / Docker

```bash
# Con Docker
docker-compose exec app php artisan db:seed

# O si usás el Dockerfile unificado (servicio único)
php artisan db:seed

# Fresh (borra todo y recrea tablas + seed)
php artisan migrate:fresh --seed
```

---

## Variables de entorno mínimas para deploy

- `APP_KEY`, `APP_ENV`, `APP_DEBUG`, `APP_URL`
- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- Opcional: `MERCADOPAGO_ACCESS_TOKEN` para pagos online; si no está, las compras quedan pendientes y el admin las aprueba manualmente.

Para cargar datos de prueba en producción (solo si es aceptable):

```bash
php artisan db:seed
```

O solo roles y usuario admin:

```bash
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=AdminUserSeeder
```
