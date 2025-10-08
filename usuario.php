<?php
ob_start();
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        header("Location: form_login.html?error=empty");
        exit;
    }

    $sql = "SELECT id, username, password, role FROM usuarios WHERE username=? AND active=1 LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = (int)$row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            // Redirigir según rol
            if ($row['role'] === 'admin') {
                header("Location: usuarios.php");
            } else {
                header("Location: usuario.php");
            }
            exit;
        } else {
            header("Location: form_login.html?error=wrongpass");
            exit;
        }
    } else {
        header("Location: form_login.html?error=notfound");
        exit;
    }
} else {
    header("Location: form_login.html");
    exit;
}
?>
