<?php
include "conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '' || $email === '') {
        echo "Completa todos los campos.";
        exit;
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO usuarios (username, password, email, role, active) VALUES (?, ?, ?, 'user', 1)");
    $stmt->bind_param("sss", $username, $hash, $email);

    if ($stmt->execute()) {
        header("Location: form_login.html?registro=ok");
        exit;
    } else {
        echo "Error: ". $stmt->error;
    }
}
?>
