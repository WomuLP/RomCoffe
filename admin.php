<?php
session_start();
include("conexion.php");

// Control de acceso: solo admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: index.html");
  exit;
}

// Manejo simple de creación/edición desde el mismo formulario
// Los archivos (imágenes) aquí se reciben como URL para simplificar; se podría ampliar a upload real.

function sanitize($v) { return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }

// Cargar lista de productos
$productos = [];
$res = $conn->query("SELECT id, name, price, description, image, category FROM productos ORDER BY id DESC");
if ($res) { while ($r = $res->fetch_assoc()) { $productos[] = $r; } }
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panel Admin • Productos</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <header class="main-header">
    <div class="header-content">
      <strong>Admin:</strong> <?php echo sanitize($_SESSION['username']); ?>
      <a href="index.html" class="instagram-btn">Inicio</a>
      <a href="logout.php" class="instagram-btn">Cerrar sesión</a>
    </div>
  </header>

  <main style="padding:20px; max-width:1000px; margin:0 auto;">
    <h1>Administrar productos</h1>
    <section style="margin:20px 0;">
      <h2>Agregar / Editar</h2>
      <form action="producto_guardar.php" method="post">
        <input type="hidden" name="id" id="form_id" />
        <div class="field"><label>Nombre</label><input type="text" name="name" id="form_name" required></div>
        <div class="field"><label>Precio</label><input type="number" step="0.01" name="price" id="form_price" required></div>
        <div class="field"><label>Descripción</label><textarea name="description" id="form_description" required></textarea></div>
        <div class="field"><label>Imagen (URL)</label><input type="url" name="image" id="form_image" required></div>
        <div class="field"><label>Categoría</label><input type="text" name="category" id="form_category" placeholder="cafe, desayunos, etc."></div>
        <div class="actions"><button class="btn primary" type="submit">Guardar</button></div>
      </form>
    </section>

    <section>
      <h2>Listado</h2>
      <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>ID</th><th>Nombre</th><th>Precio</th><th>Imagen</th><th>Categoría</th><th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($productos as $p): ?>
            <tr>
              <td><?php echo (int)$p['id']; ?></td>
              <td><?php echo sanitize($p['name']); ?></td>
              <td>$<?php echo number_format((float)$p['price'], 2, ',', '.'); ?></td>
              <td><img src="<?php echo sanitize($p['image']); ?>" alt="img" style="width:60px;height:40px;object-fit:cover"></td>
              <td><?php echo sanitize($p['category']); ?></td>
              <td>
                <form action="producto_eliminar.php" method="post" style="display:inline" onsubmit="return confirm('¿Eliminar producto?');">
                  <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
                  <button type="submit">Eliminar</button>
                </form>
                <button type="button" onclick='editar(<?php echo (int)$p['id']; ?>, <?php echo json_encode($p, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT); ?>)'>Editar</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
  </main>

  <script>
    function editar(id, p){
      document.getElementById('form_id').value = id;
      document.getElementById('form_name').value = p.name || '';
      document.getElementById('form_price').value = p.price || '';
      document.getElementById('form_description').value = p.description || '';
      document.getElementById('form_image').value = p.image || '';
      document.getElementById('form_category').value = p.category || '';
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  </script>
</body>
</html>


