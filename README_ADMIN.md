# Panel de Administración - RomCoffe

## Configuración de la Base de Datos

### 1. Crear la tabla de productos

Ejecuta el siguiente script SQL en HeidiSQL o tu cliente MySQL:

```sql
-- Crear tabla de productos
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen VARCHAR(500) DEFAULT NULL,
    categoria VARCHAR(50) NOT NULL,
    ingredientes TEXT DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_categoria (categoria),
    INDEX idx_activo (activo),
    INDEX idx_fecha_creacion (fecha_creacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 2. Insertar productos de ejemplo

```sql
INSERT INTO productos (nombre, precio, descripcion, imagen, categoria, ingredientes) VALUES
('Café Latte', 3900, 'Espresso suave con leche vaporizada y una capa de espuma.', 'https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?w=1200&q=80&auto=format&fit=crop', 'cafe', 'Espresso, Leche, Espuma de leche'),
('Cold Brew', 4500, 'Café infusionado en frío durante 16 horas. Refrescante y suave.', 'https://images.unsplash.com/photo-1498804103079-a6351b050096?w=1200&q=80&auto=format&fit=crop', 'bebidas-frias', 'Café molido, Agua filtrada, Hielo'),
('Sandwich Club', 6900, 'Pan tostado con pollo, tocino, vegetales y salsa especial.', 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=1200&q=80&auto=format&fit=crop', 'almuerzo', 'Pan, Pollo, Tocino, Lechuga, Tomate, Salsa'),
('Ensalada César', 5500, 'Clásica ensalada con aderezo césar y crutones.', 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1200&q=80&auto=format&fit=crop', 'almuerzo', 'Lechuga, Pollo, Parmesano, Crutones, Aderezo césar'),
('Té Verde', 2000, 'Refrescante y antioxidante', 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=1200&q=80&auto=format&fit=crop', 'tes', 'Té verde, Agua caliente'),
('Croissant', 3000, 'Panadería francesa tradicional', 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=1200&q=80&auto=format&fit=crop', 'desayuno', 'Harina, Mantequilla, Levadura, Sal');
```

## Funcionalidades del Panel de Administración

### Acceso
- Solo usuarios con `role = 'admin'` pueden acceder
- Se verifica automáticamente al cargar la página

### Gestión de Productos

#### 1. **Agregar Producto**
- Formulario completo con validaciones
- Campos: nombre, precio, descripción, categoría, ingredientes, imagen
- Subida de imágenes (JPG, PNG, GIF, WebP, máximo 5MB)
- Validaciones del lado servidor y cliente

#### 2. **Editar Producto**
- Carga datos existentes en el formulario
- Permite modificar todos los campos
- Opción de cambiar imagen o mantener la actual

#### 3. **Eliminar Producto**
- Soft delete (marca como inactivo)
- Confirmación antes de eliminar
- No se pierden datos históricos

#### 4. **Lista de Productos**
- Tabla responsive con todos los productos
- Muestra imagen, nombre, precio, categoría, descripción
- Botones de acción para editar/eliminar

### Categorías Disponibles
- `cafe` - Café
- `tes` - Tés  
- `bebidas-frias` - Bebidas Frías
- `desayuno` - Desayunos
- `almuerzo` - Almuerzos
- `postres` - Postres

### Validaciones Implementadas

#### Servidor (PHP)
- Campos obligatorios: nombre, precio, descripción, categoría
- Precio debe ser mayor a 0
- Validación de tipo y tamaño de imagen
- Sanitización de datos de entrada
- Protección contra inyección SQL (consultas preparadas)

#### Cliente (JavaScript)
- Validación en tiempo real
- Confirmación antes de eliminar
- Feedback visual de acciones

### Seguridad
- Verificación de sesión y rol de admin
- Sanitización de datos de entrada
- Consultas preparadas para prevenir SQL injection
- Validación de tipos de archivo
- Límites de tamaño de archivo

## Estructura de Archivos

```
admin.php              - Panel de administración principal
menu.php              - Menú dinámico (muestra productos de BD)
conexion.php          - Conexión a base de datos
create_products_table.sql - Script para crear tabla
uploads/products/     - Directorio para imágenes (se crea automáticamente)
```

## Uso

1. **Acceder al panel**: Inicia sesión como admin y haz clic en "Admin"
2. **Agregar producto**: Clic en "➕ Agregar Producto"
3. **Editar producto**: Clic en "✏️ Editar" en la tabla
4. **Eliminar producto**: Clic en "🗑️ Eliminar" en la tabla
5. **Ver cambios**: Los productos se actualizan automáticamente en el menú

## Notas Técnicas

- Las imágenes se suben a `uploads/products/` con nombres únicos
- Los productos eliminados se marcan como `activo = 0` (soft delete)
- El menú se actualiza dinámicamente desde la base de datos
- Interfaz responsive para móviles y desktop
- Compatible con el carrito de compras existente
