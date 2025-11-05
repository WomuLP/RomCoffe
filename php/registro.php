<?php
declare(strict_types=1);

header('Content-Type: application/json');

require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
    exit;
}

$input = [
    'email'    => isset($_POST['email']) ? trim((string)$_POST['email']) : '',
    'password' => isset($_POST['password']) ? (string)$_POST['password'] : '',
];

if ($input['email'] === '' || $input['password'] === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Email y contraseña son obligatorios']);
    exit;
}

if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Email inválido']);
    exit;
}

$conn = get_db_connection();

// Verificar duplicado por email
$sqlCheck = 'SELECT id FROM usuarios WHERE email = ? LIMIT 1';
$stmt = $conn->prepare($sqlCheck);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Error preparando la consulta']);
    exit;
}
$stmt->bind_param('s', $input['email']);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    http_response_code(409);
    echo json_encode(['ok' => false, 'error' => 'El email ya existe']);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

$hashed = password_hash($input['password'], PASSWORD_BCRYPT, ['cost' => 10]);

$sqlInsert = 'INSERT INTO usuarios (email, password) VALUES (?, ?)';
$stmtI = $conn->prepare($sqlInsert);
if (!$stmtI) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Error preparando el registro']);
    exit;
}
$stmtI->bind_param('ss', $input['email'], $hashed);
$ok = $stmtI->execute();

if (!$ok) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'No se pudo registrar el usuario']);
    $stmtI->close();
    $conn->close();
    exit;
}

$userId = $stmtI->insert_id;
$stmtI->close();
$conn->close();

echo json_encode(['ok' => true, 'id' => $userId, 'email' => $input['email']]);
