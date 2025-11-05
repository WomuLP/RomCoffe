<?php
declare(strict_types=1);

header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
    exit;
}

$email = isset($_POST['email']) ? trim((string)$_POST['email']) : '';
$password   = isset($_POST['password']) ? (string)$_POST['password'] : '';

if ($email === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Faltan credenciales']);
    exit;
}

$conn = get_db_connection();

$sql = 'SELECT id, email, password FROM usuarios WHERE email = ? LIMIT 1';
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Error preparando la consulta']);
    exit;
}
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Usuario no encontrado']);
    $conn->close();
    exit;
}

if (!password_verify($password, (string)$user['password'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Contraseña incorrecta']);
    $conn->close();
    exit;
}

// Inicia sesión
$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['email'] = (string)$user['email'];

$conn->close();

echo json_encode([
    'ok' => true,
    'user' => [
        'id' => (int)$user['id'],
        'email' => (string)$user['email'],
    ],
]);
