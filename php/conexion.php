<?php
$servername =  "193.203.175.157";
$username = "u157683007_luciana";
$password = "Romcoffe2025";
$dbname = "u157683007_romcoffe";

// Crear conexión silenciosa y segura
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
	$conn = new mysqli($servername, $username, $password, $dbname);
	$conn->set_charset('utf8mb4');
} catch (Throwable $e) {
	error_log('DB connection failed: ' . $e->getMessage());
	http_response_code(500);
	exit;
}
?>