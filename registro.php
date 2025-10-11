<?php
include("conexion.php");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = json_decode(file_get_contents('php://input'), true);
    $username = trim($input['username'] ?? '');
    $password = $input['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Por favor, completá todos los campos.']);
        exit;
    }

    // Verificar si el usuario ya existe
    $check = $conn->prepare("SELECT id FROM usuarios WHERE username=? LIMIT 1");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'El nombre de usuario ya existe.']);
        exit;
    }

    // Guardar nuevo usuario
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuarios (username, password, role, active) VALUES (?, ?, 'user', 1)");
    $stmt->bind_param("ss", $username, $hash);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Usuario registrado correctamente. Ahora podés iniciar sesión.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar usuario.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
