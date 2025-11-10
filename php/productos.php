<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/conexion.php';

header('Content-Type: application/json');

// Verificar que el usuario esté logueado y sea admin para operaciones de escritura
$isAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
$method = $_SERVER['REQUEST_METHOD'];

// Operaciones que requieren admin
$adminMethods = ['POST', 'PUT', 'DELETE'];
$requiresAdmin = in_array($method, $adminMethods);

if ($requiresAdmin && !$isAdmin) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'No tienes permisos para realizar esta acción']);
    exit;
}

$conn = get_db_connection();

// GET - Listar productos
if ($method === 'GET') {
    $categoria = $_GET['categoria'] ?? null;
    $activo = isset($_GET['activo']) ? (int)$_GET['activo'] : 1;
    
    $sql = 'SELECT id, nombre, precio, imagen, descripcion, categoria, ingredientes, activo 
            FROM productos 
            WHERE activo = ?';
    $params = [$activo];
    $types = 'i';
    
    if ($categoria && $categoria !== 'all') {
        $sql .= ' AND categoria = ?';
        $params[] = $categoria;
        $types .= 's';
    }
    
    $sql .= ' ORDER BY categoria, nombre';
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'Error preparando la consulta']);
        $conn->close();
        exit;
    }
    
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $productos = [];
    while ($row = $result->fetch_assoc()) {
        $ingredientes = json_decode($row['ingredientes'], true) ?? [];
        $productos[] = [
            'id' => (int)$row['id'],
            'name' => $row['nombre'],
            'price' => (float)$row['precio'],
            'image' => $row['imagen'],
            'description' => $row['descripcion'],
            'category' => $row['categoria'],
            'ingredients' => $ingredientes,
            'activo' => (bool)$row['activo']
        ];
    }
    
    $stmt->close();
    $conn->close();
    
    echo json_encode(['ok' => true, 'productos' => $productos]);
    exit;
}

// POST - Crear producto
if ($method === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $precio = isset($_POST['precio']) ? (float)$_POST['precio'] : 0;
    $imagen = trim($_POST['imagen'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $categoria = $_POST['categoria'] ?? 'otros';
    $ingredientes = isset($_POST['ingredientes']) ? json_encode($_POST['ingredientes']) : '[]';
    
    if (empty($nombre) || $precio <= 0 || empty($imagen)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Faltan campos requeridos']);
        $conn->close();
        exit;
    }
    
    $sql = 'INSERT INTO productos (nombre, precio, imagen, descripcion, categoria, ingredientes) 
            VALUES (?, ?, ?, ?, ?, ?)';
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'Error preparando la consulta']);
        $conn->close();
        exit;
    }
    
    $stmt->bind_param('sdssss', $nombre, $precio, $imagen, $descripcion, $categoria, $ingredientes);
    
    if ($stmt->execute()) {
        $id = $conn->insert_id;
        $stmt->close();
        $conn->close();
        echo json_encode(['ok' => true, 'id' => $id, 'message' => 'Producto creado exitosamente']);
    } else {
        $stmt->close();
        $conn->close();
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'Error al crear el producto']);
    }
    exit;
}

// PUT - Actualizar producto
if ($method === 'PUT') {
    $input = file_get_contents('php://input');
    parse_str($input, $data);
    
    $id = isset($data['id']) ? (int)$data['id'] : 0;
    $nombre = trim($data['nombre'] ?? '');
    $precio = isset($data['precio']) ? (float)$data['precio'] : 0;
    $imagen = trim($data['imagen'] ?? '');
    $descripcion = trim($data['descripcion'] ?? '');
    $categoria = $data['categoria'] ?? 'otros';
    
    // Manejar ingredientes (puede venir como array, string JSON o string serializado)
    if (isset($data['ingredientes'])) {
        $ingredientesRaw = $data['ingredientes'];
        
        // Si es un array, convertir a JSON
        if (is_array($ingredientesRaw)) {
            $ingredientes = json_encode($ingredientesRaw);
        } 
        // Si es un string que parece JSON, validarlo
        else if (is_string($ingredientesRaw)) {
            $decoded = json_decode($ingredientesRaw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // Ya es un JSON válido
                $ingredientes = $ingredientesRaw;
            } else {
                // Intentar crear array desde string
                $ingredientes = json_encode([$ingredientesRaw]);
            }
        } else {
            $ingredientes = '[]';
        }
    } else {
        $ingredientes = '[]';
    }
    
    if ($id <= 0 || empty($nombre) || $precio <= 0 || empty($imagen)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Datos inválidos']);
        $conn->close();
        exit;
    }
    
    $sql = 'UPDATE productos 
            SET nombre = ?, precio = ?, imagen = ?, descripcion = ?, categoria = ?, ingredientes = ?
            WHERE id = ?';
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'Error preparando la consulta']);
        $conn->close();
        exit;
    }
    
    $stmt->bind_param('sdssssi', $nombre, $precio, $imagen, $descripcion, $categoria, $ingredientes, $id);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        echo json_encode(['ok' => true, 'message' => 'Producto actualizado exitosamente']);
    } else {
        $stmt->close();
        $conn->close();
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'Error al actualizar el producto']);
    }
    exit;
}

// DELETE - Eliminar producto (marcar como inactivo)
if ($method === 'DELETE') {
    $input = file_get_contents('php://input');
    parse_str($input, $data);
    $id = isset($data['id']) ? (int)$data['id'] : 0;
    
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'ID inválido']);
        $conn->close();
        exit;
    }
    
    // Marcar como inactivo en lugar de eliminar
    $sql = 'UPDATE productos SET activo = 0 WHERE id = ?';
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'Error preparando la consulta']);
        $conn->close();
        exit;
    }
    
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        echo json_encode(['ok' => true, 'message' => 'Producto eliminado exitosamente']);
    } else {
        $stmt->close();
        $conn->close();
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'Error al eliminar el producto']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
$conn->close();

