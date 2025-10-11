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
// Limpieza: eliminamos estilos/comportamientos no usados. Mantenemos carrito funcional.
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

// HEADER MENU — removed unused code (sin menú desplegable en esta versión)

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
});

// Toggle password visibility
document.addEventListener('click', function(e){
  if (e.target && e.target.classList.contains('toggle-pass')) {
    var id = e.target.getAttribute('data-target');
    var input = document.getElementById(id);
    if (!input) return;
    input.type = input.type === 'password' ? 'text' : 'password';
  }
});

// Abrir/cerrar overlay de autenticación y validaciones simples
document.addEventListener('DOMContentLoaded', function(){
  var loginForm = document.querySelector('#loginCard form');
  var registerForm = document.querySelector('#registerCard form');
  var showReg = document.getElementById('btnShowRegister');
  var backLogin = document.getElementById('btnBackLogin');
  var loginCard = document.getElementById('loginCard');
  var registerCard = document.getElementById('registerCard');
  var overlay = document.getElementById('loginOverlay');
  var openBtn = document.getElementById('openLoginBtn');
  var closeBtn = document.getElementById('closeOverlay');

  if (showReg) {
    showReg.addEventListener('click', function(){
      loginCard.classList.add('hidden');
      registerCard.classList.remove('hidden');
    });
  }

  if (backLogin) {
    backLogin.addEventListener('click', function(){
      registerCard.classList.add('hidden');
      loginCard.classList.remove('hidden');
    });
  }

  if (openBtn && overlay) {
    openBtn.addEventListener('click', function(e){
      e.preventDefault();
      overlay.classList.add('open');
    });
  }

  if (closeBtn && overlay) {
    closeBtn.addEventListener('click', function(){
      overlay.classList.remove('open');
    });
  }

  // Cerrar con Escape
  document.addEventListener('keydown', function(e){
    if (e.key === 'Escape' && overlay) {
      overlay.classList.remove('open');
    }
  });

  function validateNotEmpty(form){
    var inputs = form.querySelectorAll('input[required]');
    var ok = true;
    inputs.forEach(function(el){
      if (!el.value.trim()) {
        el.classList.add('error');
        ok = false;
      } else {
        el.classList.remove('error');
      }
    });
    if (!ok) alert('Por favor completa todos los campos.');
    return ok;
  }

  if (loginForm) {
    loginForm.addEventListener('submit', function(e){
      if (!validateNotEmpty(loginForm)) e.preventDefault();
    });
  }

  if (registerForm) {
    registerForm.addEventListener('submit', function(e){
      if (!validateNotEmpty(registerForm)) e.preventDefault();
    });
  }
});
