<?php
include("conexion.php");

// Registro básico: username + password, rol por defecto 'user'
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        echo "❌ Datos incompletos";
        exit;
    }

    // Encriptar contraseña de forma segura
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Evitar duplicados de username
    $check = $conn->prepare("SELECT id FROM usuarios WHERE username=? LIMIT 1");
    $check->bind_param("s", $username);
    $check->execute();
    $checkRes = $check->get_result();
    if ($checkRes->fetch_assoc()) {
        echo "❌ El nombre de usuario ya existe";
        exit;
    }

    $sql = "INSERT INTO usuarios (username, password, role, active) VALUES (?, ?, 'user', 1)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $passwordHash);

    if ($stmt->execute()) {
        header("Location: index.html");
        exit;
    } else {
        echo "❌ Error: " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
?>
