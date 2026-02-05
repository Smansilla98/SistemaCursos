# Instrucciones para Subir a GitHub

## ✅ Estado Actual

El repositorio local está configurado y listo para hacer push. Solo necesitas autenticarte.

## 🔐 Opción 1: Usar Token de Acceso Personal (Recomendado)

### 1. Crear un Token en GitHub

1. Ve a GitHub → Settings → Developer settings → Personal access tokens → Tokens (classic)
2. Click en "Generate new token (classic)"
3. Dale un nombre (ej: "SistemaCursos")
4. Selecciona los permisos: `repo` (acceso completo a repositorios)
5. Click en "Generate token"
6. **Copia el token** (solo se muestra una vez)

### 2. Hacer Push con el Token

Ejecuta este comando (reemplaza `TU_TOKEN` con tu token):

```bash
cd /home/santimansilla-bkp/Escritorio/enst/sistema-cursos
git push -u origin main
```

Cuando te pida credenciales:
- **Username**: tu usuario de GitHub (Smansilla98)
- **Password**: pega el token que copiaste

## 🔐 Opción 2: Usar SSH (Alternativa)

### 1. Configurar SSH en GitHub

1. Genera una clave SSH si no tienes una:
```bash
ssh-keygen -t ed25519 -C "tu_email@ejemplo.com"
```

2. Copia la clave pública:
```bash
cat ~/.ssh/id_ed25519.pub
```

3. En GitHub: Settings → SSH and GPG keys → New SSH key
4. Pega tu clave pública y guarda

### 2. Cambiar el Remote a SSH

```bash
cd /home/santimansilla-bkp/Escritorio/enst/sistema-cursos
git remote set-url origin git@github.com:Smansilla98/SistemaCursos.git
git push -u origin main
```

## 🚀 Opción 3: GitHub CLI (Más Fácil)

Si tienes GitHub CLI instalado:

```bash
gh auth login
cd /home/santimansilla-bkp/Escritorio/enst/sistema-cursos
git push -u origin main
```

## ✅ Verificar

Después del push, verifica en:
https://github.com/Smansilla98/SistemaCursos

Deberías ver todos los archivos del proyecto.

## 📝 Nota Importante

El archivo `.env` NO se sube a GitHub (está en `.gitignore`). 
Asegúrate de que `.env.example` tenga las variables necesarias para que otros desarrolladores puedan configurar el proyecto.

