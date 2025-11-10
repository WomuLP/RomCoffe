<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/conexion.php';

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.html?error=metodo_no_permitido');
    exit;
}

// Obtener y validar datos
$email = isset($_POST['email']) ? trim((string)$_POST['email']) : '';
$password = isset($_POST['password']) ? (string)$_POST['password'] : '';

if ($email === '' || $password === '') {
    header('Location: ../login.html?error=faltan_credenciales');
    exit;
}

// Validar formato de email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../login.html?error=email_invalido');
    exit;
}

// Conectar a la base de datos
$conn = get_db_connection();

// Buscar usuario en la base de datos
$sql = 'SELECT id, email, password, rol FROM usuarios WHERE email = ? LIMIT 1';
$stmt = $conn->prepare($sql);

if (!$stmt) {
    $conn->close();
    header('Location: ../login.html?error=error_consulta');
    exit;
}

$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Verificar si el usuario existe
if (!$user) {
    $conn->close();
    header('Location: ../login.html?error=usuario_no_encontrado');
    exit;
}

// Verificar contraseña
if (!password_verify($password, (string)$user['password'])) {
    $conn->close();
    header('Location: ../login.html?error=contraseña_incorrecta');
    exit;
}

// Iniciar sesión
$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['email'] = (string)$user['email'];
$_SESSION['rol'] = (string)($user['rol'] ?? 'user');

$conn->close();

// Redirigir según el rol
$rol = (string)($user['rol'] ?? 'user');
if ($rol === 'admin') {
    header('Location: ../admin.html');
} else {
    header('Location: ../index.html');
}
exit;
