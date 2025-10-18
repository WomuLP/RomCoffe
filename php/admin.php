<?php
/**
 * Panel de administración de productos
 * Solo accesible para usuarios con rol 'admin'
 * 
 * Funcionalidades:
 * - Ver lista de productos
 * - Agregar nuevos productos
 * - Editar productos existentes
 * - Eliminar productos
 * - Subir imágenes de productos
 */

// Iniciar sesión
session_start();

// Verificar que el usuario esté logueado y sea admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo '<div class="error-message"><h3>Acceso Denegado</h3><p>No tienes permisos para acceder a esta sección.</p></div>';
    exit;
}

// Incluir conexión a la base de datos
require_once 'conexion.php';

// Procesar acciones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'add_product':
                echo json_encode(addProduct($conn));
                break;
            case 'edit_product':
                echo json_encode(editProduct($conn));
                break;
            case 'delete_product':
                echo json_encode(deleteProduct($conn));
                break;
            case 'get_product':
                echo json_encode(getProduct($conn));
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        }
    } catch (Exception $e) {
        error_log("Error en admin.php: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
    }
    exit;
}

/**
 * Agregar nuevo producto
 */
function addProduct($conn) {
    // Validar datos de entrada
    $nombre = trim($_POST['nombre'] ?? '');
    $precio = floatval($_POST['precio'] ?? 0);
    $descripcion = trim($_POST['descripcion'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $ingredientes = trim($_POST['ingredientes'] ?? '');
    
    // Validaciones
    if (empty($nombre)) {
        return ['success' => false, 'message' => 'El nombre es obligatorio'];
    }
    
    if ($precio <= 0) {
        return ['success' => false, 'message' => 'El precio debe ser mayor a 0'];
    }
    
    if (empty($descripcion)) {
        return ['success' => false, 'message' => 'La descripción es obligatoria'];
    }
    
    if (empty($categoria)) {
        return ['success' => false, 'message' => 'La categoría es obligatoria'];
    }
    
    // Procesar imagen si se subió
    $imagen_url = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen_url = uploadImage($_FILES['imagen']);
        if (!$imagen_url) {
            return ['success' => false, 'message' => 'Error al subir la imagen'];
        }
    } else {
        // Usar imagen por defecto si no se subió ninguna
        $imagen_url = 'https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?w=1200&q=80&auto=format&fit=crop';
    }
    
    // Insertar producto en la base de datos
    $sql = "INSERT INTO productos (nombre, precio, descripcion, imagen, categoria, ingredientes, activo, fecha_creacion) 
            VALUES (?, ?, ?, ?, ?, ?, 1, NOW())";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return ['success' => false, 'message' => 'Error en la consulta: ' . $conn->error];
    }
    
    $stmt->bind_param("sdssss", $nombre, $precio, $descripcion, $imagen_url, $categoria, $ingredientes);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Producto agregado correctamente', 'id' => $conn->insert_id];
    } else {
        return ['success' => false, 'message' => 'Error al agregar el producto: ' . $stmt->error];
    }
}

/**
 * Editar producto existente
 */
function editProduct($conn) {
    $id = intval($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $precio = floatval($_POST['precio'] ?? 0);
    $descripcion = trim($_POST['descripcion'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $ingredientes = trim($_POST['ingredientes'] ?? '');
    
    // Validaciones
    if ($id <= 0) {
        return ['success' => false, 'message' => 'ID de producto inválido'];
    }
    
    if (empty($nombre)) {
        return ['success' => false, 'message' => 'El nombre es obligatorio'];
    }
    
    if ($precio <= 0) {
        return ['success' => false, 'message' => 'El precio debe ser mayor a 0'];
    }
    
    if (empty($descripcion)) {
        return ['success' => false, 'message' => 'La descripción es obligatoria'];
    }
    
    if (empty($categoria)) {
        return ['success' => false, 'message' => 'La categoría es obligatoria'];
    }
    
    // Procesar imagen si se subió una nueva
    $imagen_url = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen_url = uploadImage($_FILES['imagen']);
        if (!$imagen_url) {
            return ['success' => false, 'message' => 'Error al subir la imagen'];
        }
    }
    
    // Construir consulta SQL
    if (!empty($imagen_url)) {
        $sql = "UPDATE productos SET nombre=?, precio=?, descripcion=?, imagen=?, categoria=?, ingredientes=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdssssi", $nombre, $precio, $descripcion, $imagen_url, $categoria, $ingredientes, $id);
    } else {
        $sql = "UPDATE productos SET nombre=?, precio=?, descripcion=?, categoria=?, ingredientes=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsssi", $nombre, $precio, $descripcion, $categoria, $ingredientes, $id);
    }
    
    if (!$stmt) {
        return ['success' => false, 'message' => 'Error en la consulta: ' . $conn->error];
    }
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Producto actualizado correctamente'];
    } else {
        return ['success' => false, 'message' => 'Error al actualizar el producto: ' . $stmt->error];
    }
}

/**
 * Eliminar producto
 */
function deleteProduct($conn) {
    $id = intval($_POST['id'] ?? 0);
    
    if ($id <= 0) {
        return ['success' => false, 'message' => 'ID de producto inválido'];
    }
    
    // Verificar que el producto existe
    $sql = "SELECT id FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return ['success' => false, 'message' => 'Producto no encontrado'];
    }
    
    // Eliminar producto (soft delete - marcar como inactivo)
    $sql = "UPDATE productos SET activo = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Producto eliminado correctamente'];
    } else {
        return ['success' => false, 'message' => 'Error al eliminar el producto: ' . $stmt->error];
    }
}

/**
 * Obtener datos de un producto
 */
function getProduct($conn) {
    $id = intval($_POST['id'] ?? 0);
    
    if ($id <= 0) {
        return ['success' => false, 'message' => 'ID de producto inválido'];
    }
    
    $sql = "SELECT * FROM productos WHERE id = ? AND activo = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return ['success' => true, 'product' => $row];
    } else {
        return ['success' => false, 'message' => 'Producto no encontrado'];
    }
}

/**
 * Subir imagen del producto
 */
function uploadImage($file) {
    $uploadDir = 'uploads/products/';
    
    // Crear directorio si no existe
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Validar tipo de archivo
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        return false;
    }
    
    // Validar tamaño (máximo 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        return false;
    }
    
    // Generar nombre único
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    // Mover archivo
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return $filepath;
    }
    
    return false;
}

// Obtener lista de productos para mostrar
$sql = "SELECT * FROM productos WHERE activo = 1 ORDER BY fecha_creacion DESC";
$result = $conn->query($sql);
$productos = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
}
?>

<div class="admin-section">
    <h2 class="section-title">Gestión de Productos</h2>
    <p class="admin-welcome">Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    
    <!-- Botón para agregar producto -->
    <div class="admin-actions-bar">
        <button class="btn btn-primary" onclick="showAddProductModal()">
            ➕ Agregar Producto
        </button>
        <button class="btn btn-secondary" onclick="refreshProducts()">
            🔄 Actualizar Lista
        </button>
    </div>
    
    <!-- Lista de productos -->
    <div class="products-table-container">
        <table class="products-table">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="productsTableBody">
                <?php foreach ($productos as $producto): ?>
                <tr data-id="<?php echo $producto['id']; ?>">
                    <td>
                        <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" 
                             alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                             class="product-thumb">
                    </td>
                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                    <td>$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($producto['categoria']); ?></td>
                    <td><?php echo htmlspecialchars(substr($producto['descripcion'], 0, 50)) . '...'; ?></td>
                    <td>
                        <button class="btn btn-sm btn-edit" onclick="editProduct(<?php echo $producto['id']; ?>)">
                            ✏️ Editar
                        </button>
                        <button class="btn btn-sm btn-delete" onclick="deleteProduct(<?php echo $producto['id']; ?>)">
                            🗑️ Eliminar
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para agregar/editar producto -->
<div id="productModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Agregar Producto</h3>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        <form id="productForm" enctype="multipart/form-data">
            <input type="hidden" id="productId" name="id">
            <input type="hidden" name="action" id="formAction" value="add_product">
            
            <div class="form-group">
                <label for="nombre">Nombre del Producto *</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            
            <div class="form-group">
                <label for="precio">Precio *</label>
                <input type="number" id="precio" name="precio" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="categoria">Categoría *</label>
                <select id="categoria" name="categoria" required>
                    <option value="">Seleccionar categoría</option>
                    <option value="cafe">Café</option>
                    <option value="tes">Tés</option>
                    <option value="bebidas-frias">Bebidas Frías</option>
                    <option value="desayuno">Desayuno</option>
                    <option value="almuerzo">Almuerzo</option>
                    <option value="postres">Postres</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="descripcion">Descripción *</label>
                <textarea id="descripcion" name="descripcion" rows="3" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="ingredientes">Ingredientes (separados por comas)</label>
                <input type="text" id="ingredientes" name="ingredientes" 
                       placeholder="Ej: Café, Leche, Azúcar">
            </div>
            
            <div class="form-group">
                <label for="imagen">Imagen del Producto</label>
                <input type="file" id="imagen" name="imagen" accept="image/*">
                <small>Formatos permitidos: JPG, PNG, GIF, WebP. Máximo 5MB</small>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Producto</button>
            </div>
        </form>
    </div>
</div>

<style>
.admin-section {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
    font-family: 'Poppins', 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
}

.admin-welcome {
    color: var(--text-muted);
    margin-bottom: 2rem;
    font-size: 1.1rem;
    font-weight: 500;
}

.admin-actions-bar {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: var(--bg-cream);
    border-radius: 16px;
    border: 2px solid var(--bg-light-pink);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.products-table-container {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    border: 2px solid var(--bg-light-pink);
}

.products-table {
    width: 100%;
    border-collapse: collapse;
}

.products-table th,
.products-table td {
    padding: 1.5rem 1rem;
    text-align: left;
    border-bottom: 1px solid var(--bg-light-pink);
}

.products-table th {
    background: var(--bg-light-pink);
    font-weight: 700;
    color: var(--text-dark);
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.products-table td {
    color: var(--text-dark);
    font-weight: 500;
}

.product-thumb {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid var(--bg-light-pink);
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    font-family: 'Poppins', sans-serif;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-primary {
    background: var(--bg-dark);
    color: var(--text-light);
    border: 2px solid var(--bg-dark);
}

.btn-primary:hover {
    background: #a10a24;
    border-color: #a10a24;
}

.btn-secondary {
    background: var(--bg-pink);
    color: var(--text-light);
    border: 2px solid var(--bg-pink);
}

.btn-secondary:hover {
    background: #e67a9a;
    border-color: #e67a9a;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.8rem;
}

.btn-edit {
    background: #ffc107;
    color: var(--text-dark);
    border: 2px solid #ffc107;
    margin-right: 0.5rem;
}

.btn-edit:hover {
    background: #e0a800;
    border-color: #e0a800;
}

.btn-delete {
    background: var(--bg-red);
    color: var(--text-light);
    border: 2px solid var(--bg-red);
}

.btn-delete:hover {
    background: #d63031;
    border-color: #d63031;
}

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 0;
    border-radius: 20px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    border: 3px solid var(--bg-light-pink);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    border-bottom: 2px solid var(--bg-light-pink);
    background: var(--bg-cream);
    border-radius: 20px 20px 0 0;
}

.modal-header h3 {
    margin: 0;
    color: var(--text-dark);
    font-family: 'Dancing Script', cursive;
    font-size: 2rem;
    font-weight: 700;
}

.close {
    font-size: 1.8rem;
    font-weight: bold;
    cursor: pointer;
    color: var(--text-muted);
    transition: color 0.3s ease;
}

.close:hover {
    color: var(--text-dark);
}

#productForm {
    padding: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.75rem;
    font-weight: 700;
    color: var(--text-dark);
    font-size: 0.95rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid var(--bg-light-pink);
    border-radius: 12px;
    font-size: 1rem;
    background: #fdeaea;
    color: var(--text-dark);
    font-family: 'Poppins', sans-serif;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--bg-pink);
    box-shadow: 0 0 0 3px rgba(242,136,164,0.18);
    background: white;
}

.form-group small {
    color: var(--text-muted);
    font-size: 0.8rem;
    margin-top: 0.5rem;
    display: block;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 2px solid var(--bg-light-pink);
}

@media (max-width: 768px) {
    .products-table-container {
        overflow-x: auto;
    }
    
    .products-table {
        min-width: 600px;
    }
    
    .admin-actions-bar {
        flex-direction: column;
    }
    
    .modal-content {
        width: 95%;
        margin: 10% auto;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        text-align: center;
    }
}
</style>

<script>
// Variables globales
let currentProductId = null;

// Mostrar modal para agregar producto
function showAddProductModal() {
    document.getElementById('modalTitle').textContent = 'Agregar Producto';
    document.getElementById('formAction').value = 'add_product';
    document.getElementById('productForm').reset();
    document.getElementById('productId').value = '';
    currentProductId = null;
    document.getElementById('productModal').style.display = 'block';
}

// Cerrar modal
function closeModal() {
    document.getElementById('productModal').style.display = 'none';
}

// Editar producto
async function editProduct(id) {
    try {
        const formData = new FormData();
        formData.append('action', 'get_product');
        formData.append('id', id);
        
        const response = await fetch('admin.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            const product = data.product;
            document.getElementById('modalTitle').textContent = 'Editar Producto';
            document.getElementById('formAction').value = 'edit_product';
            document.getElementById('productId').value = product.id;
            document.getElementById('nombre').value = product.nombre;
            document.getElementById('precio').value = product.precio;
            document.getElementById('categoria').value = product.categoria;
            document.getElementById('descripcion').value = product.descripcion;
            document.getElementById('ingredientes').value = product.ingredientes || '';
            currentProductId = product.id;
            document.getElementById('productModal').style.display = 'block';
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al cargar el producto');
    }
}

// Eliminar producto
async function deleteProduct(id) {
    if (!confirm('¿Estás seguro de que quieres eliminar este producto?')) {
        return;
    }
    
    try {
        const formData = new FormData();
        formData.append('action', 'delete_product');
        formData.append('id', id);
        
        const response = await fetch('admin.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Producto eliminado correctamente');
            refreshProducts();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al eliminar el producto');
    }
}

// Actualizar lista de productos
function refreshProducts() {
    location.reload();
}

// Manejar envío del formulario
document.getElementById('productForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const action = formData.get('action');
    
    try {
        const response = await fetch('admin.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            closeModal();
            refreshProducts();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al guardar el producto');
    }
});

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>