<?php
declare(strict_types=1);

session_start();

// Verificar si hay una sesión activa consultando la base de datos
require_once __DIR__ . '/conexion.php';

$response = ['logged_in' => false];

if (isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
    // Verificar en la base de datos que el usuario aún existe y tiene el mismo rol
    $conn = get_db_connection();
    $user_id = (int)$_SESSION['user_id'];
    
    $sql = 'SELECT id, email, rol FROM usuarios WHERE id = ? LIMIT 1';
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        if ($user) {
            // Actualizar sesión con datos actuales de la base de datos
            $_SESSION['email'] = (string)$user['email'];
            $_SESSION['rol'] = (string)($user['rol'] ?? 'user');
            
            $response = [
                'logged_in' => true,
                'user' => [
                    'id' => (int)$user['id'],
                    'email' => (string)$user['email'],
                    'rol' => (string)($user['rol'] ?? 'user'),
                ],
            ];
        } else {
            // Usuario eliminado de la base de datos, destruir sesión
            session_destroy();
        }
        
        $conn->close();
    }
}

// Si se solicita como JSON (para AJAX)
if (isset($_GET['format']) && $_GET['format'] === 'json') {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Si se solicita como redirección (para protección de páginas)
if (isset($_GET['redirect'])) {
    if (!$response['logged_in']) {
        header('Location: ../login.html');
        exit;
    }
    
    // Verificar rol si se especifica
    if (isset($_GET['rol']) && $response['logged_in']) {
        $requiredRol = $_GET['rol'];
        if ($response['user']['rol'] !== $requiredRol) {
            header('Location: ../index.html');
            exit;
        }
    }
    
    // Si todo está bien, no hacer nada (la página puede continuar cargando)
    exit;
}

// Por defecto, devolver JSON para compatibilidad con código existente
header('Content-Type: application/json');
echo json_encode($response);
exit;

