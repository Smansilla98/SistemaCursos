# 🔧 Comandos Git para Hacer Push

## ✅ Configuración del Remote

El repositorio está configurado para usar SSH:
```
git@github.com:Smansilla98/SistemaCursos.git
```

## 📝 Comandos para Hacer Push

### 1. Verificar cambios pendientes
```bash
cd sistema-cursos
git status
```

### 2. Agregar todos los cambios
```bash
git add .
```

### 3. Hacer commit
```bash
git commit -m "Fix: Configuración de base de datos y variables de Railway"
```

### 4. Verificar remote (debe ser SSH)
```bash
git remote -v
```

Si muestra HTTPS, cambiarlo a SSH:
```bash
git remote set-url origin git@github.com:Smansilla98/SistemaCursos.git
```

### 5. Hacer push
```bash
git push origin main
```

## 🔐 Si Tienes Problemas con SSH

Si no tienes SSH configurado en GitHub:

1. **Generar clave SSH** (si no tienes):
   ```bash
   ssh-keygen -t ed25519 -C "tu_email@example.com"
   ```

2. **Copiar clave pública**:
   ```bash
   cat ~/.ssh/id_ed25519.pub
   ```

3. **Agregar a GitHub**:
   - Ve a: https://github.com/settings/keys
   - Click en "New SSH key"
   - Pega la clave pública

4. **Probar conexión**:
   ```bash
   ssh -T git@github.com
   ```

## ✅ Verificación

Después del push, deberías ver:
```
Enumerating objects: X, done.
Counting objects: 100% (X/X), done.
Writing objects: 100% (X/X), done.
To github.com:Smansilla98/SistemaCursos.git
   abc1234..def5678  main -> main
```

