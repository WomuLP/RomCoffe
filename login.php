<?php
session_start();
include("conexion.php");

// Si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Por favor, completá todos los campos.";
    } else {
        $sql = "SELECT id, username, password, role FROM usuarios WHERE username=? AND active=1 LIMIT 1";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error en la consulta: " . $conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = (int)$row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];

                // Redirigir según el rol
                if ($row['role'] === 'admin') {
                    header("Location: index.html");
                } else {
                    header("Location: index.html");
                }
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado o inactivo.";
        }
    }
}
?>

<div class="login-box">
    <h2>Iniciar sesión</h2>
    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Ingresar</button>
    </form>
</div>
</body>
</html>
