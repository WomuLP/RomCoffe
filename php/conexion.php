<?php
declare(strict_types=1);

// Configuración de base de datos (ajusta según tu entorno)
const DB_HOST = '193.203.175.157';
const DB_USER = 'u157683007_luciana';
const DB_PASS = 'Romcoffe2025';
const DB_NAME = 'u157683007_romcoffe';

/**
 * Retorna una instancia de mysqli con charset utf8mb4.
 */
function get_db_connection(): mysqli {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($mysqli->connect_errno) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'ok' => false,
            'error' => 'Error de conexión a la base de datos',
            'details' => $mysqli->connect_error,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $mysqli->set_charset('utf8mb4');
    return $mysqli;
}
