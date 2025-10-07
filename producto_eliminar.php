<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: index.html");
  exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) { die('ID inválido'); }

$stmt = $conn->prepare("DELETE FROM productos WHERE id=?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
  header("Location: admin.php");
} else {
  echo "❌ Error: ".$conn->error;
}
?>

