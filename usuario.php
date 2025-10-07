<?php
ob_start(); // Limpieza: buffering para asegurar redirecciones sin salida previa
session_start();
include("conexion.php");

// Gestión de usuarios y roles
// - Al iniciar sesión se guarda en $_SESSION['username'] y $_SESSION['role'] (admin|user)
// - Las páginas restringidas (por ejemplo, admin.php) deben verificar el rol antes de permitir acceso

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Buscar usuario activo por nombre
    $sql = "SELECT id, username, password, role FROM usuarios WHERE username=? AND active=1 LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Verificar contraseña encriptada (password_hash/password_verify)
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = (int)$row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            // Limpieza: este script no muestra vistas; redirige al inicio tras login
            header("Location: index.html");
            exit;
        } else {
            echo "❌ Contraseña incorrecta";
            exit;
        }
    } else {
        echo "❌ Usuario no encontrado o inactivo";
        exit;
    }
}

// En accesos directos (GET) redirigir siempre al inicio
header("Location: index.html");
exit;
?>
