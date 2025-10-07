<?php
// Crea dos usuarios de prueba: admin / user (idempotente)
include("conexion.php");

function ensureUser($conn, $username, $password, $role) {
  $stmt = $conn->prepare("SELECT id FROM usuarios WHERE username=? LIMIT 1");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $res = $stmt->get_result();
  if ($res && $res->fetch_assoc()) {
    return; // ya existe
  }
  $hash = password_hash($password, PASSWORD_BCRYPT);
  $ins = $conn->prepare("INSERT INTO usuarios (username, password, role, active) VALUES (?, ?, ?, 1)");
  $ins->bind_param("sss", $username, $hash, $role);
  $ins->execute();
}

ensureUser($conn, 'admin', 'Admin123!', 'admin');
ensureUser($conn, 'user', 'User123!', 'user');

echo "OK: usuarios de prueba creados (admin/user)";
?>

