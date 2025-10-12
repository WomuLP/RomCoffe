<?php
/**
 * Archivo de conexión a la base de datos MySQL/MariaDB
 * Configuración para RomCoffe
 * 
 * Este archivo establece la conexión con la base de datos usando MySQLi
 * e incluye manejo de errores robusto.
 */

// Configuración de la base de datos
$host = "193.203.175.157";                    // Servidor de la base de datos
$usuario = "u157683007_romcoffe";       // Usuario de la base de datos
$contrasena = "Romcoffe2025";          // Contraseña de la base de datos
$base_datos = "u157683007_romcoffe";    // Nombre de la base de datos

// Crear conexión usando MySQLi (orientado a objetos)
$conn = new mysqli($host, $usuario, $contrasena, $base_datos);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    // Log del error para debugging (solo visible en logs del servidor)
    error_log("Error de conexión a la base de datos: " . $conn->connect_error);
    
    // Mensaje de error para el usuario (sin exponer detalles sensibles)
    die("Error: No se pudo conectar a la base de datos. Por favor, inténtelo más tarde.");
}

// Configurar el conjunto de caracteres para soportar UTF-8 completo
// Esto es importante para caracteres especiales y emojis
$conn->set_charset("utf8mb4");

// Configurar el timezone para la base de datos (opcional pero recomendado)
$conn->query("SET time_zone = '+00:00'");

// Configurar modo SQL estricto para mayor seguridad (opcional)
$conn->query("SET sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO'");

// La variable $conn está ahora disponible para otros scripts
// Ejemplo de uso en otros archivos:
// require_once 'conexion.php';
// $resultado = $conn->query("SELECT * FROM tabla");
?>