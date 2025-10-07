<?php
session_start();
include("conexion.php");

// Solo admin puede crear/editar
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: index.html");
  exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$name = trim($_POST['name'] ?? '');
$price = (float)($_POST['price'] ?? 0);
$description = trim($_POST['description'] ?? '');
$image = trim($_POST['image'] ?? '');
$category = trim($_POST['category'] ?? '');

if ($name === '' || $price <= 0 || $description === '' || $image === '') {
  die('Datos inválidos');
}

if ($id > 0) {
  $stmt = $conn->prepare("UPDATE productos SET name=?, price=?, description=?, image=?, category=? WHERE id=?");
  $stmt->bind_param("sdsssi", $name, $price, $description, $image, $category, $id);
} else {
  $stmt = $conn->prepare("INSERT INTO productos (name, price, description, image, category) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sdsss", $name, $price, $description, $image, $category);
}

if ($stmt->execute()) {
  header("Location: admin.php");
} else {
  echo "❌ Error: ".$conn->error;
}
?>

