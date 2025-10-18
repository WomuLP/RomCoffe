<?php
include "conexion.php";
$username = 'admin';
$email = 'admin@local';
$password = '123456';
$role = 'admin';
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO usuarios (username,password,email,role,active) VALUES (?,?,?,?,1)");
$stmt->bind_param("ssss", $username, $hash, $email, $role);

if ($stmt->execute()) echo "Admin creado";
else echo "Error: " . $stmt->error;

$stmt->close();
$conn->close();
?>
