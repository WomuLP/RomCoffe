<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo "<script>alert('Por favor, completá todos los campos.'); window.history.back();</script>";
        exit;
    }

    $sql = "SELECT id, username, password, role FROM usuarios WHERE username=? AND active=1 LIMIT 1";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en la consulta: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = (int)$row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            echo "<script>
                alert('Bienvenido, {$row['username']}');
                window.location.href = 'menu.php';
            </script>";
            exit;
        } else {
            echo "<script>alert('Contraseña incorrecta.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado o inactivo.'); window.history.back();</script>";
    }
} else {
    header("Location: index.html");
    exit;
}
?>
