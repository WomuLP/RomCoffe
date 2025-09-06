<?php
$products = [
  [
    'image' => 'https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?w=1200&q=80&auto=format&fit=crop',
    'name' => 'Café Latte',
    'price' => 3.9,
    'description' => 'Espresso suave con leche vaporizada y una capa de espuma.',
    'ingredients' => ['Espresso', 'Leche', 'Espuma de leche']
  ],
  [
    'image' => 'https://images.unsplash.com/photo-1498804103079-a6351b050096?w=1200&q=80&auto=format&fit=crop',
    'name' => 'Cold Brew',
    'price' => 4.5,
    'description' => 'Café infusionado en frío durante 16 horas. Refrescante y suave.',
    'ingredients' => ['Café molido', 'Agua filtrada', 'Hielo']
  ],
  [
    'image' => 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=1200&q=80&auto=format&fit=crop',
    'name' => 'Sandwich Club',
    'price' => 6.9,
    'description' => 'Pan tostado con pollo, tocino, vegetales y salsa especial.',
    'ingredients' => ['Pan', 'Pollo', 'Tocino', 'Lechuga', 'Tomate', 'Salsa']
  ],
  [
    'image' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1200&q=80&auto=format&fit=crop',
    'name' => 'Ensalada César',
    'price' => 5.5,
    'description' => 'Clásica ensalada con aderezo césar y crutones.',
    'ingredients' => ['Lechuga', 'Pollo', 'Parmesano', 'Crutones', 'Aderezo césar']
  ],
];
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cafetería • Menú</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <header class="site-header">
      <img class="banner" src="https://images.unsplash.com/photo-1461988625982-7e46a099bf4f?w=1600&q=80&auto=format&fit=crop" alt="Banner cafetería" />
      <div class="header-overlay">
        <h1 class="brand">Tu Café Favorito</h1>
        <p class="tag">Buenos momentos, mejor café</p>
      </div>
    </header>

    <nav class="category-menu">
      <button class="chip">café</button>
      <button class="chip">tés</button>
      <button class="chip">bebidas frías</button>
      <button class="chip">desayuno</button>
      <button class="chip">almuerzo</button>
      <button class="chip">postres</button>
    </nav>

    <main>
      <section class="products" id="productos">
        <h2 class="section-title">Productos</h2>
        <div class="product-grid">
          <?php foreach ($products as $product): ?>
            <article class="card">
              <div class="card-media">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
              </div>
              <div class="card-body">
                <div class="card-head">
                  <h3 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                  <span class="price">$<?php echo number_format($product['price'], 2); ?></span>
                </div>
                <p class="card-desc"><?php echo htmlspecialchars($product['description']); ?></p>
                <ul class="ingredients">
                  <?php foreach ($product['ingredients'] as $ingredient): ?>
                    <li><?php echo htmlspecialchars($ingredient); ?></li>
                  <?php endforeach; ?>
                </ul>
                <button class="btn primary">Agregar</button>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </section>

      <section class="carousel-section" aria-label="Destacados">
        <h2 class="section-title">Destacados</h2>
        <div class="carousel">
          <button class="carousel-btn prev" aria-label="Anterior">❮</button>
          <div class="carousel-track" id="carouselTrack">
            <div class="carousel-item">1</div>
            <div class="carousel-item">2</div>
            <div class="carousel-item">3</div>
            <div class="carousel-item">4</div>
            <div class="carousel-item">5</div>
            <div class="carousel-item">6</div>
          </div>
          <button class="carousel-btn next" aria-label="Siguiente">❯</button>
        </div>
      </section>
    </main>

    <footer class="site-footer">
      <div class="footer-inner">
        <div class="footer-col">
          <h3 class="footer-brand">Colorlib</h3>
          <p>Hecho con amor para servir excelente café y buena vibra.</p>
        </div>
        <nav class="footer-col">
          <h4>Info</h4>
          <ul class="footer-links">
            <li><a href="#">Home</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Portfolio</a></li>
            <li><a href="#">Contact</a></li>
          </ul>
        </nav>
        <nav class="footer-col">
          <h4>Legal</h4>
          <ul class="footer-links">
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms &amp; Conditions</a></li>
          </ul>
        </nav>
        <div class="footer-col align-end">
          <a class="btn contact" href="#">Contactar</a>
        </div>
      </div>
      <div class="footer-bottom">© <?php echo date('Y'); ?> Cafetería. Todos los derechos reservados.</div>
    </footer>

    <script>
      (function () {
        const track = document.getElementById('carouselTrack');
        const prev = document.querySelector('.carousel-btn.prev');
        const next = document.querySelector('.carousel-btn.next');
        const scrollAmount = 260;

        prev.addEventListener('click', function () {
          track.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });

        next.addEventListener('click', function () {
          track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });
      })();
    </script>
  </body>
</html>


