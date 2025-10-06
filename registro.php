<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO usuarios (username, password, email, role, active) VALUES (?, ?, ?, 'user', 1)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $passwordHash, $email);

    if ($stmt->execute()) {
        header("Location: form_login.html?registro=ok");
        exit;
    } else {
        echo "❌ Error: " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
?>
