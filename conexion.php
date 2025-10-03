<?php
$host = "193.203.175.157";
$user = "u157683007_luciana";
$pass = "Romcoffe2025";
$db   = "Romcoffe";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    error_log("Error de conexión: " . $conn->connect_error); 
    die("No se pudo conectar con la base de datos.");
}

$conn->set_charset("utf8mb4");
?>
