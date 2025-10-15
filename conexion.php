<?php
$servername =  "193.203.175.157";
$username = "u157683007_luciana";
$password = "Romcoffe2025";
$dbname = "u157683007_romcoffe";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>