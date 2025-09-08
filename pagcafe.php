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
                <button class="btn primary" data-add-name="<?php echo htmlspecialchars($product['name']); ?>" data-add-price="<?php echo number_format($product['price'], 2, '.', ''); ?>" data-add-image="<?php echo htmlspecialchars($product['image']); ?>">Agregar</button>
              </div>
            </article>
          <?php endforeach; ?>
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
      // Carrito de compras (PHP page)
      let cart = [];
      const cartPanel = document.createElement('div');
      cartPanel.className = 'cart-panel';
      cartPanel.innerHTML = `
        <div class="cart-header">
          <h4 class="cart-title">Tu pedido</h4>
          <button class="cart-remove" id="cartClear">Vaciar</button>
        </div>
        <ul class="cart-items" id="cartItems"></ul>
        <div class="cart-footer">
          <span class="cart-total" id="cartTotal">$0.00</span>
          <button class="btn primary" id="cartCheckout">Finalizar</button>
        </div>
      `;
      document.body.appendChild(cartPanel);

      const cartFab = document.createElement('button');
      cartFab.className = 'cart-fab';
      cartFab.setAttribute('aria-label', 'Abrir carrito');
      cartFab.innerHTML = `
        <span class="fab-thumb" id="fabThumb"><img src="" alt="Último producto" style="display:none"></span>
        <span class="fab-info">
          <span class="fab-name" id="fabName">Carrito</span>
          <span class="fab-meta" id="fabMeta">x0 • $0.00</span>
        </span>
        <span class="badge" id="cartCount">0</span>`;
      document.body.appendChild(cartFab);

      function saveCart() { localStorage.setItem('cart', JSON.stringify(cart)); }
      function loadCart() {
        try { cart = JSON.parse(localStorage.getItem('cart')) || []; }
        catch(e) { cart = []; }
      }

      function updateCartUI() {
        const itemsEl = document.getElementById('cartItems');
        const totalEl = document.getElementById('cartTotal');
        const countEl = document.getElementById('cartCount');
        const fabName = document.getElementById('fabName');
        const fabMeta = document.getElementById('fabMeta');
        const fabThumbImg = document.querySelector('#fabThumb img');
        itemsEl.innerHTML = '';
        let total = 0;
        cart.forEach((item, index) => {
          total += item.price * item.qty;
          const li = document.createElement('li');
          li.className = 'cart-item';
          li.innerHTML = `
            <span class="name">${item.name} × ${item.qty}</span>
            <span class="price">$${(item.price * item.qty).toFixed(2)}</span>
            <span class="qty-controls" data-index="${index}">
              <button class="qty-btn" data-action="dec">−</button>
              <span class="qty-value">${item.qty}</span>
              <button class="qty-btn" data-action="inc">+</button>
            </span>
            <button class="cart-remove" data-index="${index}">Quitar</button>
          `;
          itemsEl.appendChild(li);
        });
        totalEl.textContent = `$${total.toFixed(2)}`;
        const totalQty = cart.reduce((a, b) => a + b.qty, 0);
        countEl.textContent = totalQty;
        if (cart.length > 0) {
          const last = cart[cart.length - 1];
          fabName.textContent = last.name;
          fabMeta.textContent = `${totalQty} items • $${total.toFixed(2)}`;
          if (last.image) { fabThumbImg.src = last.image; fabThumbImg.style.display = 'block'; } else { fabThumbImg.style.display = 'none'; }
        } else {
          fabName.textContent = 'Carrito';
          fabMeta.textContent = '0 items • $0.00';
          fabThumbImg.style.display = 'none';
        }
        saveCart();
      }

      function addToCart(productName, price) {
        const existing = cart.find(p => p.name === productName);
        if (existing) {
          existing.qty += 1;
        } else {
          const btn = document.querySelector(`button[data-add-name="${CSS.escape(productName)}"]`);
          const image = btn ? btn.getAttribute('data-add-image') : '';
          cart.push({ name: productName, price: price, qty: 1, image: image });
        }
        updateCartUI();
      }

      document.addEventListener('click', function(e){
        if (e.target && e.target.matches('.cart-remove[data-index]')) {
          const idx = parseInt(e.target.getAttribute('data-index'), 10);
          cart.splice(idx, 1);
          updateCartUI();
        }
        if (e.target && e.target.matches('button[data-add-name]')) {
          const name = e.target.getAttribute('data-add-name');
          const price = parseFloat(e.target.getAttribute('data-add-price'));
          addToCart(name, price);
        }
        if (e.target && e.target.closest('.qty-controls')) {
          const group = e.target.closest('.qty-controls');
          const idx = parseInt(group.getAttribute('data-index'), 10);
          const action = e.target.getAttribute('data-action');
          if (action === 'inc') { cart[idx].qty += 1; }
          if (action === 'dec') { cart[idx].qty = Math.max(1, cart[idx].qty - 1); }
          updateCartUI();
        }
      });

      cartFab.addEventListener('click', function(){
        cartPanel.classList.toggle('open');
      });

      cartPanel.addEventListener('click', function(e){
        if (e.target && e.target.id === 'cartClear') {
          cart.length = 0;
          updateCartUI();
        }
        if (e.target && e.target.id === 'cartCheckout') {
          alert('Gracias por tu pedido!');
          cart.length = 0;
          updateCartUI();
          cartPanel.classList.remove('open');
        }
      });

      document.addEventListener('DOMContentLoaded', function(){
        loadCart();
        updateCartUI();
        // Nuevo carrusel (igual que index)
        const main = document.querySelector('main');
        const section = document.createElement('section');
        section.className = 'nc-section';
        section.innerHTML = `
          <div class="nc-container">
            <input type="radio" name="nc-slider" id="item-1" checked>
            <input type="radio" name="nc-slider" id="item-2">
            <input type="radio" name="nc-slider" id="item-3">
            <div class="nc-cards">
              <label class="nc-card" for="item-1" id="nc-card-1">
                <img src="https://images.unsplash.com/photo-1530651788726-1dbf58eeef1f?w=1200&q=80&auto=format&fit=crop" alt="Slide 1">
              </label>
              <label class="nc-card" for="item-2" id="nc-card-2">
                <img src="https://images.unsplash.com/photo-1559386484-97dfc0e15539?w=1200&q=80&auto=format&fit=crop" alt="Slide 2">
              </label>
              <label class="nc-card" for="item-3" id="nc-card-3">
                <img src="https://images.unsplash.com/photo-1533461502717-83546f485d24?w=1200&q=80&auto=format&fit=crop" alt="Slide 3">
              </label>
            </div>
            <div class="nc-player">
              <div class="nc-info-area">
                <strong class="nc-title">Destacados</strong>
                <span class="nc-subtitle">Disfruta nuestras especialidades</span>
                <span class="nc-time">Hoy</span>
              </div>
            </div>
          </div>`;
        main.appendChild(section);
      });
    </script>
  </body>
</html>


