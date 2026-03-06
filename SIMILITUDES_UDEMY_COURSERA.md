# Similitudes del sistema de cursos con Udemy, Coursera y plataformas similares

Revisión previa al deploy para asegurar que el producto está alineado con expectativas de una plataforma de cursos online.

## Resumen ejecutivo

El **sistema de cursos** comparte el modelo de negocio y flujos principales de **Udemy** y **Coursera**: catálogo de cursos de pago, inscripción por compra, contenido en lecciones (videos/PDFs), roles estudiante/instructor/admin y pagos online. No incluye (por diseño actual) certificados, foros, quizzes ni suscripciones.

---

## Comparativa por funcionalidad

| Funcionalidad | Udemy / Coursera | Este sistema | Notas |
|---------------|------------------|--------------|--------|
| **Catálogo de cursos** | Sí | Sí | Cursos con título, descripción, precio, thumbnail |
| **Cursos de pago** | Sí | Sí | Precio por curso, pago único |
| **Lecciones / módulos** | Sí (vídeos, recursos) | Sí | Lecciones con `video` o `pdf`, orden, bloqueo |
| **Inscripción por compra** | Sí | Sí | Compra → acceso al curso |
| **Acceso por compra aprobada** | Automático | Sí (MercadoPago o aprobación manual) | Flujo tipo Udemy |
| **Roles: Admin / Instructor / Estudiante** | Sí | Admin + Estudiante | Sin rol “instructor” explícito; el admin gestiona cursos |
| **Panel estudiante “Mis cursos”** | Sí | Sí | `/student/courses` |
| **Panel admin (cursos, usuarios, pagos)** | Sí | Sí | CRUD cursos, usuarios, aprobar/rechazar compras |
| **Pagos online** | Sí | Sí | Integración MercadoPago |
| **Certificados** | Sí (Coursera/Udemy) | No | Posible extensión futura |
| **Foros / preguntas por curso** | Sí | No | Posible extensión |
| **Quizzes / evaluaciones** | Sí | No | Posible extensión |
| **Suscripción mensual (acceso a todo)** | Algunas plataformas | No | Solo compra por curso |
| **Claves de acceso / cupones** | Sí (cupones) | Parcial | Tabla `access_keys` se elimina en migraciones actuales; se puede reañadir si se desea |

---

## Flujos tipo Udemy/Coursera que sí cubre el sistema

1. **Descubrimiento**: listado de cursos activos en home y en panel estudiante.
2. **Detalle de curso**: vista pública con descripción, precio y lecciones (visibilidad según acceso).
3. **Compra**: estudiante elige curso → pago (MercadoPago o pendiente manual) → compra creada.
4. **Acceso**: al aprobarse la compra (o por webhook), se asocia curso–usuario vía `course_user` y el estudiante ve el curso en “Mis cursos” y puede acceder al contenido.
5. **Contenido**: lecciones con archivo (video/PDF), orden y estado bloqueado/desbloqueado según tenencia del curso.
6. **Administración**: altas/bajas de cursos, lecciones, usuarios y aprobación/rechazo de compras.

Por tanto, el núcleo (catálogo, compra, acceso, lecciones, roles) es **similar a Udemy/Coursera** en un MVP.

---

## Diferencias conscientes (scope actual)

- **Sin certificados**: no se expiden diplomas al completar.
- **Sin foros ni preguntas**: solo contenido unidireccional.
- **Sin evaluaciones**: no hay quizzes ni exámenes.
- **Sin suscripción**: solo compra por curso.
- **Instructor**: el contenido lo gestiona el admin; no hay perfil “instructor” con sus propios cursos en el front.

Estas diferencias son aceptables para un primer deploy; se pueden documentar como roadmap.

---

## Conclusión

El sistema está **alineado con el modelo de plataforma de cursos tipo Udemy/Coursera** en:

- Modelo de negocio (cursos de pago, acceso por compra).
- Flujos de descubrimiento, compra, acceso y consumo de lecciones.
- Roles y paneles (admin vs estudiante).

Es adecuado para subir y desplegar como plataforma de cursos online, asumiendo el scope actual (sin certificados, foros ni quizzes). Para entornos de producción conviene:

- Revisar variables de entorno (DB, `APP_KEY`, MercadoPago).
- Asegurar que las migraciones se ejecutan en orden (ver migraciones y `start.sh`).
- Usar los datos de prueba documentados para validar el flujo completo (admin, estudiante, curso, compra, acceso).
