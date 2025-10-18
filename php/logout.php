<?php
/**
 * Archivo para cerrar sesión del usuario
 * Destruye la sesión y devuelve confirmación
 */

// Iniciar sesión
session_start();

// Configurar header para respuesta JSON
header('Content-Type: application/json; charset=utf-8');

// Verificar que la petición sea POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        'success' => false, 
        'message' => 'Método no permitido. Solo se aceptan peticiones POST.'
    ]);
    exit;
}

try {
    // Destruir todas las variables de sesión
    $_SESSION = array();
    
    // Si se desea destruir la sesión completamente, también borrar la cookie de sesión
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Finalmente, destruir la sesión
    session_destroy();
    
    // Respuesta de éxito
    echo json_encode([
        'success' => true,
        'message' => 'Sesión cerrada correctamente'
    ]);
    
} catch (Exception $e) {
    // Manejo de errores
    error_log("Error en logout.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error al cerrar sesión'
    ]);
}
?>
