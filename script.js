// ========================================
// PRODUCTS DATA
// ========================================
const products = [
  {
    image: 'https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?w=1200&q=80&auto=format&fit=crop',
    name: 'Café Latte',
    price: 3900,
    description: 'Espresso suave con leche vaporizada y una capa de espuma.',
    ingredients: ['Espresso', 'Leche', 'Espuma de leche'],
    category: 'cafe'
  },
  {
    image: 'https://images.unsplash.com/photo-1498804103079-a6351b050096?w=1200&q=80&auto=format&fit=crop',
    name: 'Cold Brew',
    price: 4500,
    description: 'Café infusionado en frío durante 16 horas. Refrescante y suave.',
    ingredients: ['Café molido', 'Agua filtrada', 'Hielo'],
    category: 'bebidas-frias'
  },
  {
    image: 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=1200&q=80&auto=format&fit=crop',
    name: 'Sandwich Club',
    price: 6900,
    description: 'Pan tostado con pollo, tocino, vegetales y salsa especial.',
    ingredients: ['Pan', 'Pollo', 'Tocino', 'Lechuga', 'Tomate', 'Salsa'],
    category: 'almuerzo'
  },
  {
    image: 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1200&q=80&auto=format&fit=crop',
    name: 'Ensalada César',
    price: 5500,
    description: 'Clásica ensalada con aderezo césar y crutones.',
    ingredients: ['Lechuga', 'Pollo', 'Parmesano', 'Crutones', 'Aderezo césar'],
    category: 'almuerzo'
  }
];

// ========================================
// PRODUCTS MANAGEMENT
// ========================================
function generateProductCards(productsToShow = products) {
  const productGrid = document.getElementById('productGrid');
  productGrid.innerHTML = '';

  productsToShow.forEach(product => {
    const productCard = document.createElement('article');
    productCard.className = 'card';
    productCard.innerHTML = `
      <div class="card-media">
        <img src="${product.image}" alt="${product.name}" />
      </div>
      <div class="card-body">
        <div class="card-head">
          <h3 class="card-title">${product.name}</h3>
          <span class="price">$${product.price.toLocaleString('es-AR')}</span>
        </div>
        <p class="card-desc">${product.description}</p>
        <ul class="ingredients">
          ${product.ingredients.map(ingredient => `<li>${ingredient}</li>`).join('')}
        </ul>
        <button class="btn primary" onclick="addToCart('${product.name}', ${product.price}, '${product.image}')">Agregar al carrito</button>
      </div>
    `;
    productGrid.appendChild(productCard);
  });
}


function filterProducts(category) {
  if (category === 'all') {
    generateProductCards();
  } else {
    const filteredProducts = products.filter(product => product.category === category);
    generateProductCards(filteredProducts);
  }
}

function initCategoryFilters() {
  const categoryButtons = document.querySelectorAll('.category-menu .chip');

  categoryButtons.forEach(button => {
    button.addEventListener('click', function() {
      categoryButtons.forEach(btn => btn.classList.remove('active'));
      this.classList.add('active');

      const category = this.getAttribute('data-category');
      filterProducts(category);
    });
  });
}

// ========================================
// SHOPPING CART
// ========================================
let cart = [];

// Create cart elements
const cartPanel = document.createElement('div');
cartPanel.className = 'cart-panel';
cartPanel.innerHTML = `
  <div class="cart-header">
    <h4 class="cart-title">TU PEDIDO</h4>
    <button class="cart-remove" id="cartClear">Quitar</button>
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
  <span class="fab-icon">🛒</span>
  <span class="badge" id="cartCount">0</span>`;
document.body.appendChild(cartFab);

function saveCart() { 
  localStorage.setItem('cart', JSON.stringify(cart)); 
}

function loadCart() {
  try { 
    cart = JSON.parse(localStorage.getItem('cart')) || []; 
  }
  catch(e) { 
    cart = [];
  }
}

function updateCartUI() {
  const itemsEl = document.getElementById('cartItems');
  const totalEl = document.getElementById('cartTotal');
  const countEl = document.getElementById('cartCount');
  itemsEl.innerHTML = '';
  let total = 0;

  cart.forEach((item, index) => {
    total += item.price * item.qty;
    const li = document.createElement('li');
    li.className = 'cart-item';
    li.innerHTML = `
      <img src="${item.image}" alt="${item.name}" class="cart-item-image">
      <span class="name">${item.name}</span>
      <span class="price">$${(item.price * item.qty).toLocaleString('es-AR')}</span>
      <span class="qty-controls" data-index="${index}">
        <button class="qty-btn" data-action="dec">−</button>
        <span class="qty-value">${item.qty}</span>
        <button class="qty-btn" data-action="inc">+</button>
      </span>
    `;
    itemsEl.appendChild(li);
  });

  totalEl.textContent = `$${total.toLocaleString('es-AR')}`;
  const totalQty = cart.reduce((a, b) => a + b.qty, 0);
  countEl.textContent = totalQty;
  saveCart();
}

function addToCart(productName, price, imageUrl) {
  const existing = cart.find(p => p.name === productName);
  if (existing) {
    existing.qty += 1;
  } else {
    cart.push({ name: productName, price: price, qty: 1, image: imageUrl });
  }
  updateCartUI();

  cartFab.classList.add('bounce');
  setTimeout(() => {
    cartFab.classList.remove('bounce');
  }, 600);
}

// Cart event listeners
cartFab.addEventListener('click', function(){
  cartPanel.classList.toggle('open');

  if (cartPanel.classList.contains('open')) {
    setTimeout(() => {
      cartPanel.style.display = 'block';
    }, 10);
  }
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

// Quantity controls
document.addEventListener('click', function(e){
  if (e.target && e.target.closest('.qty-controls')) {
    const group = e.target.closest('.qty-controls');
    const idx = parseInt(group.getAttribute('data-index'), 10);
    const action = e.target.getAttribute('data-action');
    if (action === 'inc') { cart[idx].qty += 1; }
    if (action === 'dec') { cart[idx].qty = Math.max(1, cart[idx].qty - 1); }
    updateCartUI();
  }
});

// ========================================
// HEADER MENU
// ========================================
function initHeaderMenu() {
  const menuToggle = document.getElementById('menuToggle');
  const dropdownMenu = document.getElementById('dropdownMenu');
  const menuOverlay = document.getElementById('menuOverlay');

  if (!menuToggle || !dropdownMenu) return;
  console.log('[menu] initHeaderMenu() running', { menuToggle: !!menuToggle, dropdownMenu: !!dropdownMenu });

  // Helper to detect mobile layout
  const isMobile = () => window.matchMedia('(max-width: 767px)').matches;

  // prepare mobile panel class when needed
  function applyLayout() {
    if (isMobile()) {
      dropdownMenu.classList.add('mobile-panel');
      menuToggle.setAttribute('aria-expanded', 'false');
      menuOverlay.setAttribute('aria-hidden', 'true');
      console.log('[menu] applyLayout -> mobile layout applied');
    } else {
      dropdownMenu.classList.remove('mobile-panel', 'active');
      menuToggle.classList.remove('active');
      menuOverlay.classList.remove('active');
      menuToggle.setAttribute('aria-expanded', 'false');
      menuOverlay.setAttribute('aria-hidden', 'true');
      // close all submenu states
      document.querySelectorAll('.menu-item.has-submenu, .submenu-item.has-submenu').forEach(el => el.classList.remove('active'));
      console.log('[menu] applyLayout -> desktop layout applied');
    }
  }

  applyLayout();

  // Toggle main menu
  menuToggle.addEventListener('click', function(e) {
    e.stopPropagation();
    if (isMobile()) {
      // open sliding panel
      const opening = !dropdownMenu.classList.contains('active');
      dropdownMenu.classList.toggle('active');
  // do not insert a back button; keep overlay for click but do not darken
  // (do not toggle overlay active to avoid darkening)
      menuToggle.classList.toggle('active', opening);
      // change icon (hamburger -> X)
      const iconEl = menuToggle.querySelector('.menu-icon');
      if (iconEl) iconEl.textContent = opening ? '\u2715' : '\u2630';
      console.log('[menu] menuToggle clicked (mobile). opening=', opening);
      menuToggle.setAttribute('aria-expanded', opening ? 'true' : 'false');
      menuOverlay.setAttribute('aria-hidden', opening ? 'false' : 'true');
    } else {
      // on desktop just toggle visual state for accessibility
      const opening = !dropdownMenu.classList.contains('active');
      dropdownMenu.classList.toggle('active');
      menuToggle.classList.toggle('active', opening);
      // change icon on desktop toggle as well
      const iconEl = menuToggle.querySelector('.menu-icon');
      if (iconEl) iconEl.textContent = opening ? '\u2715' : '\u2630';
      console.log('[menu] menuToggle clicked (desktop). opening=', opening);
    }
  });

  // Close when clicking overlay
  if (menuOverlay) {
    menuOverlay.addEventListener('click', function() {
      dropdownMenu.classList.remove('active');
      menuOverlay.classList.remove('active');
      menuToggle.classList.remove('active');
      // reset icon to hamburger
      const iconEl = menuToggle.querySelector('.menu-icon');
      if (iconEl) iconEl.textContent = '\u2630';
      console.log('[menu] overlay clicked — menu closed');
      menuToggle.setAttribute('aria-expanded', 'false');
      menuOverlay.setAttribute('aria-hidden', 'true');
    });
  }

  // Close menu when clicking outside (desktop behavior)
  document.addEventListener('click', function(e) {
    if (!dropdownMenu.contains(e.target) && !menuToggle.contains(e.target) && isMobile() === false) {
      dropdownMenu.classList.remove('active');
      menuToggle.classList.remove('active');
      const iconEl = menuToggle.querySelector('.menu-icon');
      if (iconEl) iconEl.textContent = '\u2630';
    }
  });

  // Submenu toggles (works for both mobile and desktop)
  const mainSubmenuItems = document.querySelectorAll('.menu-item.has-submenu');
  mainSubmenuItems.forEach(item => {
    const link = item.querySelector('.menu-link');
    link.addEventListener('click', function(e) {
      // On desktop open/close children; on mobile expand the block
      e.preventDefault();
      e.stopPropagation();
      const other = Array.from(mainSubmenuItems).filter(i => i !== item);
      other.forEach(o => o.classList.remove('active'));
      item.classList.toggle('active');
      // if the clicked main menu is 'Productos', show product section
      try {
        const txt = (link.textContent || '').trim().toLowerCase();
        if (txt === 'productos') {
          // show all products and scroll to productos section
          filterProducts('all');
          const sec = document.getElementById('productos');
          if (sec) sec.scrollIntoView({ behavior: 'smooth', block: 'start' });
          // on mobile, close the panel after selection
          if (isMobile()) {
            dropdownMenu.classList.remove('active');
            menuToggle.classList.remove('active');
            const iconEl = menuToggle.querySelector('.menu-icon');
            if (iconEl) iconEl.textContent = '\u2630';
          }
        }
      } catch (err) {
        console.warn('[menu] error handling Productos click', err);
      }
    });
  });

  const submenuItems = document.querySelectorAll('.submenu-item.has-submenu');
  submenuItems.forEach(item => {
    const link = item.querySelector('.submenu-link');
    link.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      // On desktop behave as before. On mobile we'll toggle an accordion inside the right panel
      if (!isMobile()) {
        const other = Array.from(submenuItems).filter(i => i !== item);
        other.forEach(o => o.classList.remove('active'));
        item.classList.toggle('active');
        return;
      }

      // Mobile accordion behaviour for sub-submenu
      const sub = item.querySelector('.sub-submenu');
      if (!sub) return;

      const isOpen = sub.classList.contains('open');
      // close other open sub-submenus
      document.querySelectorAll('.dropdown-menu.mobile-panel .sub-submenu.open').forEach(s => s.classList.remove('open'));
      if (!isOpen) sub.classList.add('open');
    });
  });

  // back button functionality removed by request

  // Prevent clicks inside the menu from closing it on mobile
  dropdownMenu.addEventListener('click', function(e) { e.stopPropagation(); });

  // Close on escape
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      dropdownMenu.classList.remove('active');
      menuOverlay.classList.remove('active');
      menuToggle.classList.remove('active');
      const iconEl = menuToggle.querySelector('.menu-icon');
      if (iconEl) iconEl.textContent = '\u2630';
      menuToggle.setAttribute('aria-expanded', 'false');
      menuOverlay.setAttribute('aria-hidden', 'true');
    }
  });

  // Re-apply layout on resize
  let resizeTimer;
  window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => applyLayout(), 120);
  });
}

// ========================================
// UTILITIES
// ========================================
function updateFooterYear() {
  const currentYear = new Date().getFullYear();
  document.getElementById('currentYear').textContent = currentYear;
}

// ========================================
// INITIALIZATION
// ========================================
document.addEventListener('DOMContentLoaded', function() {
  loadCart();
  generateProductCards();
  updateCartUI();
  initCategoryFilters();
  updateFooterYear();
  initHeaderMenu();
});
