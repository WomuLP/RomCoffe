# 🧪 GUÍA DE PRUEBAS - ROOM COFFE

## 📋 CHECKLIST DE PRUEBAS

### 1. BASE DE DATOS
- [ ] Ejecutar `sql/usuarios.sql` - Crear tabla usuarios y admin
- [ ] Ejecutar `sql/productos.sql` - Crear tabla productos
- [ ] Verificar que el usuario admin existe: `SELECT * FROM usuarios WHERE email = 'admin@romcoffe.com'`
- [ ] Verificar que hay productos: `SELECT COUNT(*) FROM productos`

### 2. CONFIGURACIÓN
- [ ] Verificar credenciales en `php/conexion.php`
- [ ] Probar conexión a la base de datos

### 3. LOGIN Y AUTENTICACIÓN
- [ ] Probar login como usuario normal → Debe redirigir a `index.html`
- [ ] Probar login como admin → Debe redirigir a `admin.html`
- [ ] Probar login con credenciales incorrectas → Debe mostrar error
- [ ] Probar logout → Debe redirigir a `index.html` y cerrar sesión

### 4. PANEL DE ADMINISTRACIÓN
- [ ] Acceder a `admin.html` sin login → Debe redirigir a `login.html`
- [ ] Acceder a `admin.html` como admin → Debe mostrar el panel
- [ ] Verificar que se cargan los productos desde BD
- [ ] Probar agregar producto → Debe guardarse en BD
- [ ] Probar editar producto → Debe actualizarse en BD
- [ ] Probar eliminar producto → Debe marcarse como inactivo
- [ ] Verificar que los cambios se reflejan en `index.html`

### 5. PÁGINA PRINCIPAL
- [ ] Verificar que se cargan productos desde BD
- [ ] Probar filtrado por categoría
- [ ] Verificar que el menú muestra/oculta según sesión
- [ ] Probar agregar productos al carrito
- [ ] Verificar que el carrito funciona

### 6. REDIRECCIONES
- [ ] Login exitoso admin → `admin.html`
- [ ] Login exitoso user → `index.html`
- [ ] Logout → `index.html`
- [ ] Acceso a admin sin sesión → `login.html`
- [ ] Acceso a admin como user → `index.html` (o `login.html`)

## 🔧 COMANDOS ÚTILES PARA VERIFICAR

### Verificar tablas en MySQL
```sql
USE romcoffe;
SHOW TABLES;
DESCRIBE usuarios;
DESCRIBE productos;
```

### Verificar datos
```sql
SELECT * FROM usuarios;
SELECT COUNT(*) as total FROM productos;
SELECT categoria, COUNT(*) FROM productos GROUP BY categoria;
```

### Verificar sesión PHP
```php
// Agregar temporalmente en cualquier archivo PHP
session_start();
var_dump($_SESSION);
```

## ⚠️ PROBLEMAS COMUNES Y SOLUCIONES

### Error: "Error de conexión a la base de datos"
- Verificar credenciales en `php/conexion.php`
- Verificar que el servidor MySQL esté corriendo
- Verificar que la base de datos existe

### Error: "No tienes permisos"
- Verificar que estás logueado como admin
- Verificar que la sesión no expiró
- Cerrar sesión y volver a iniciar

### Productos no se cargan
- Verificar que la tabla `productos` existe
- Verificar que hay productos con `activo = 1`
- Revisar consola del navegador para errores

### Redirecciones incorrectas
- Verificar rutas relativas (`../` vs `./`)
- Verificar que los archivos existen en las rutas especificadas

