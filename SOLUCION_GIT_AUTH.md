# 🔐 Solución: Error de Autenticación Git

## ❌ Error

```
Missing or invalid credentials.
fatal: Autenticación falló para 'https://github.com/Smansilla98/SistemaCursos.git/'
```

## ✅ Soluciones

### Opción 1: Usar Token de Acceso Personal (Recomendado)

1. **Crear Token en GitHub**:
   - Ve a GitHub → Settings → Developer settings → Personal access tokens → Tokens (classic)
   - Click en "Generate new token (classic)"
   - Selecciona permisos: `repo` (acceso completo a repositorios)
   - Genera el token y **cópialo** (solo se muestra una vez)

2. **Configurar Git para usar el token**:
   ```bash
   cd sistema-cursos
   git remote set-url origin https://TU_TOKEN@github.com/Smansilla98/SistemaCursos.git
   ```
   
   O mejor, usar el usuario:
   ```bash
   git remote set-url origin https://Smansilla98@github.com/Smansilla98/SistemaCursos.git
   ```
   
   Cuando Git pida la contraseña, usa el **token** en lugar de tu contraseña.

3. **Hacer push**:
   ```bash
   git push origin main
   ```

### Opción 2: Configurar Credential Helper

```bash
cd sistema-cursos

# Configurar credential helper para almacenar credenciales
git config --global credential.helper store

# O usar cache (temporal)
git config --global credential.helper cache
git config --global credential.helper 'cache --timeout=3600'
```

Luego intenta hacer push y cuando pida credenciales:
- Username: `Smansilla98`
- Password: **Tu token de acceso personal** (no tu contraseña de GitHub)

### Opción 3: Cambiar a SSH (Si tienes SSH configurado)

```bash
cd sistema-cursos

# Cambiar remote a SSH
git remote set-url origin git@github.com:Smansilla98/SistemaCursos.git

# Verificar
git remote -v

# Hacer push
git push origin main
```

### Opción 4: Usar GitHub CLI (gh)

Si tienes GitHub CLI instalado:

```bash
gh auth login
```

Luego:
```bash
git push origin main
```

## 🚀 Solución Rápida

1. **Crear token en GitHub** (ver Opción 1)
2. **Hacer push con token**:
   ```bash
   cd sistema-cursos
   git push https://TU_TOKEN@github.com/Smansilla98/SistemaCursos.git main
   ```

## 📝 Nota Importante

GitHub ya **NO acepta contraseñas** para autenticación HTTPS. Debes usar:
- ✅ **Token de acceso personal** (Personal Access Token)
- ✅ **SSH keys**
- ✅ **GitHub CLI**

## 🔗 Enlaces Útiles

- Crear token: https://github.com/settings/tokens
- Documentación: https://docs.github.com/en/authentication

