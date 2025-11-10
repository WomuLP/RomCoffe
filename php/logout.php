<?php
declare(strict_types=1);

session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Si se desea eliminar la cookie de sesión, también se puede hacer:
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruir la sesión
session_destroy();

// Redirigir al index
header('Location: ../index.html');
exit;
