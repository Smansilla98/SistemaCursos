# 🚀 Inicio Rápido con Docker

## ⚡ Inicio en 3 Pasos

### 1. Copiar archivo de entorno
```bash
cp .env.docker.example .env
```

### 2. Ejecutar script de inicio
```bash
./docker-start.sh
```

### 3. Acceder a la aplicación
Abre tu navegador en: **http://localhost:8000**

## 👤 Credenciales

**Aplicación:**
- Email: `admin@cursos.com`
- Contraseña: `password`

**MySQL:**
- Host: `localhost:3306`
- Usuario: `laravel_user`
- Contraseña: `laravel_password`
- Base de datos: `sistema_cursos`
- Root password: `root_password`

## 🛑 Detener

```bash
./docker-stop.sh
```

O manualmente:
```bash
docker-compose down
```

## 📖 Más Información

Para más detalles, consulta [DOCKER.md](DOCKER.md)

