<?php
// Incluye el archivo de conexión (ruta relativa al archivo actual)
require_once __DIR__ . '/conexion.php'; // ahora usa la función get_db_connection()

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    // *** MEJORA DE SEGURIDAD: Validar formato de email ***
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Formato de email inválido.";
        exit;
    }
    
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash de la contraseña

    // Preparar la consulta SQL. *** CORREGIDO: ahora usa 'usuario' ***
    // Obtener la conexión usando la función definida en conexion.php
    $conn = get_db_connection();

    $sql = "INSERT INTO usuarios (email, password, rol) VALUES (?, ?, 'user')";

    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
    // Vincular los parámetros (2 parámetros => tipos "ss")
    $stmt->bind_param("ss", $email, $password);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Usamos un pequeño script de JavaScript para mostrar un mensaje y luego redirigir.
            echo "<script>alert('Se registró correctamente. Serás redirigido al login.'); window.location.href = 'login.html';</script>";
            exit();
        } else {
            // Error, puede ser que el email ya exista si tienes una restricción UNIQUE en la base de datos
            echo "Error al registrar. Puede que el email ya esté en uso o haya un problema: " . $stmt->error;
        }

        // Cerrar la sentencia
        $stmt->close();
    } else {
        echo "Error en la consulta: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
}
?>