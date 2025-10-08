<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8"><title>Login</title></head>
<body>
  <h2>Iniciar Sesión</h2>
  <form method="POST" action="login.php">
    <input type="text" name="username" placeholder="Usuario" required><br>
    <input type="password" name="password" placeholder="Contraseña" required><br>
    <button type="submit">Ingresar</button>
  </form>
  <p>¿No tenés cuenta? <a href="registro.php">Registrate</a></p>
</body>
</html>
