<?php
/**
 * Archivo para verificar el estado de la sesión del usuario
 * Devuelve información del usuario si está logueado
 */

// Iniciar sesión
session_start();

// Configurar header para respuesta JSON
header('Content-Type: application/json; charset=utf-8');

// Verificar si hay una sesión activa
if (isset($_SESSION['username']) && isset($_SESSION['role'])) {
    // Usuario logueado - devolver información
    echo json_encode([
        'success' => true,
        'user' => [
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role']
        ]
    ]);
} else {
    // No hay sesión activa
    echo json_encode([
        'success' => false,
        'message' => 'No hay sesión activa'
    ]);
}
?>
