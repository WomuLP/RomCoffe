<?php
declare(strict_types=1);

// Incluye el archivo de conexión (ruta relativa al archivo actual)
require_once __DIR__ . '/conexion.php';

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.html?error=metodo_no_permitido&mode=register');
    exit;
}

// Obtener y validar datos
$email = isset($_POST['email']) ? trim((string)$_POST['email']) : '';
$password = isset($_POST['password']) ? (string)$_POST['password'] : '';

// Validar que los campos no estén vacíos
if ($email === '' || $password === '') {
    header('Location: ../login.html?error=faltan_credenciales&mode=register');
    exit;
}

// Validar formato de email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../login.html?error=email_invalido&mode=register');
    exit;
}

// Validar longitud mínima de contraseña
if (strlen($password) < 6) {
    header('Location: ../login.html?error=contraseña_corta&mode=register');
    exit;
}

// Obtener la conexión usando la función definida en conexion.php
$conn = get_db_connection();

// Verificar si el email ya existe
$checkSql = 'SELECT id FROM usuarios WHERE email = ? LIMIT 1';
$checkStmt = $conn->prepare($checkSql);

if ($checkStmt) {
    $checkStmt->bind_param('s', $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows > 0) {
        // El email ya existe
        $checkStmt->close();
        $conn->close();
        header('Location: ../login.html?error=email_duplicado&mode=register');
        exit;
    }
    $checkStmt->close();
} else {
    $conn->close();
    header('Location: ../login.html?error=error_consulta&mode=register');
    exit;
}

// Hash de la contraseña
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Preparar la consulta SQL para insertar el nuevo usuario
$sql = "INSERT INTO usuarios (email, password, rol) VALUES (?, ?, 'user')";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    $conn->close();
    header('Location: ../login.html?error=error_consulta&mode=register');
    exit;
}

// Vincular los parámetros (2 parámetros => tipos "ss")
$stmt->bind_param('ss', $email, $hashedPassword);

// Ejecutar la consulta
if ($stmt->execute()) {
    // Registro exitoso
    $stmt->close();
    $conn->close();
    header('Location: ../login.html?success=registro_exitoso');
    exit;
} else {
    // Error al ejecutar la consulta
    $error = $stmt->error;
    $stmt->close();
    $conn->close();
    
    // Verificar si es error de email duplicado (por si acaso)
    if (strpos($error, 'Duplicate entry') !== false || strpos($error, 'UNIQUE constraint') !== false) {
        header('Location: ../login.html?error=email_duplicado&mode=register');
    } else {
        header('Location: ../login.html?error=error_registro&mode=register');
    }
    exit;
}
?>