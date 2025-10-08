<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo "<script>alert('Por favor, completá todos los campos.'); window.history.back();</script>";
        exit;
    }

    // Verificar si el usuario ya existe
    $check = $conn->prepare("SELECT id FROM usuarios WHERE username=? LIMIT 1");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('El nombre de usuario ya existe.'); window.history.back();</script>";
        exit;
    }

    // Guardar nuevo usuario
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuarios (username, password, role, active) VALUES (?, ?, 'user', 1)");
    $stmt->bind_param("ss", $username, $hash);

    if ($stmt->execute()) {
        echo "<script>alert('Usuario registrado correctamente. Ahora podés iniciar sesión.'); window.location.href='index.html';</script>";
    } else {
        echo "<script>alert('Error al registrar usuario.'); window.history.back();</script>";
    }
}
?>
