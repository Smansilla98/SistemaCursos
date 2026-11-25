# SistemaCursos

[![CI](https://github.com/Smansilla98/SistemaCursos/actions/workflows/ci.yml/badge.svg)](https://github.com/Smansilla98/SistemaCursos/actions/workflows/ci.yml)

Sistema de gestión de cursos construido con **Laravel 12** (PHP 8.2+), **Vite + Tailwind CSS** en el frontend y **spatie/laravel-permission** para roles.

## Requisitos

- PHP 8.2 o superior con extensiones: `mbstring`, `pdo`, `pdo_sqlite` (o `pdo_mysql`), `bcmath`, `intl`, `zip`, `gd`
- Composer 2
- Node.js 20 o superior y npm
- MySQL 8 (producción/desarrollo) o SQLite (tests y pruebas rápidas)

## Puesta en marcha (local)

```bash
composer install
cp .env.example .env
php artisan key:generate
# Configurar DB_* en .env (MySQL) o DB_CONNECTION=sqlite con DB_DATABASE=database/database.sqlite
php artisan migrate --seed    # opcional: datos de ejemplo
npm install
npm run build                 # o `npm run dev` para Vite con recarga
php artisan serve
```

Con Composer también existe `composer setup` (instala todo, genera la clave y compila assets) y `composer dev` (servidor, cola, logs y Vite en paralelo).

### Usuarios de ejemplo

`php artisan db:seed` crea `admin@cursos.com` y `student@cursos.com`.
La contraseña se toma de `SEED_DEFAULT_PASSWORD` del `.env`; en local, si no se define, es `password`.
En producción, si no se define, se genera una aleatoria (usar "olvidé mi contraseña" para ingresar). Nunca dejar contraseñas por defecto en producción.

## Tests y calidad

```bash
composer test                              # php artisan test (usa SQLite en memoria según phpunit.xml)
vendor/bin/pint --test                     # verificación de estilo (Laravel Pint)
npm run build                              # compilación de assets
```

Los tests no requieren MySQL: `phpunit.xml` fuerza `DB_CONNECTION=sqlite` con `:memory:`.

## Integración continua

`.github/workflows/ci.yml` corre en cada push y pull request a `main`: instala dependencias, compila assets con Vite, verifica estilo con Pint y ejecuta los tests.

## Despliegue

Configuración para Railway/Nixpacks en `Procfile`, `railway.json` y `nixpacks.toml`. Las variables de entorno se definen en el servicio; no se versiona ningún `.env`.
Para Docker en desarrollo ver `docker-compose.dev.yml` y `docker-start.sh`.

## Seguridad

- No commitear `.env` ni claves (`.gitignore` ya lo excluye; sólo `.env.example` está versionado).
- `APP_DEBUG` debe ser `false` en producción.
