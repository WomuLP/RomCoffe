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
  
  // Inicializar navegación dinámica
  initDynamicNavigation();
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

// ========================================
// DYNAMIC CONTENT LOADING
// ========================================

// Estado de la aplicación
let currentUser = null;
let currentSection = 'home';

// Elementos del DOM
let contenido, loginBtn, adminBtn, userInfo, userName, logoutBtn;

// Inicializar elementos del DOM cuando estén disponibles
function initDynamicElements() {
  contenido = document.getElementById('contenido');
  loginBtn = document.getElementById('loginBtn');
  adminBtn = document.getElementById('adminBtn');
  userInfo = document.getElementById('userInfo');
  userName = document.getElementById('userName');
  logoutBtn = document.getElementById('logoutBtn');
}

// Verificar sesión activa
async function checkSession() {
  try {
    const response = await fetch('check_session.php');
    const data = await response.json();
    
    if (data.success && data.user) {
      currentUser = data.user;
      updateUI();
    }
  } catch (error) {
    console.log('No hay sesión activa');
  }
}

// Actualizar interfaz según el estado del usuario
function updateUI() {
  if (!loginBtn || !adminBtn || !userInfo || !userName) return;
  
  if (currentUser) {
    // Usuario logueado
    loginBtn.style.display = 'none';
    userInfo.style.display = 'block';
    userName.textContent = currentUser.username;
    
    // Mostrar botón admin si es admin
    if (currentUser.role === 'admin') {
      adminBtn.style.display = 'inline-block';
    }
  } else {
    // Usuario no logueado
    loginBtn.style.display = 'inline-block';
    userInfo.style.display = 'none';
    adminBtn.style.display = 'none';
  }
}

// Cargar sección dinámicamente
async function loadSection(section) {
  if (!contenido) return;
  
  currentSection = section;
  
  // Actualizar botones activos
  document.querySelectorAll('.nav-btn').forEach(btn => {
    btn.classList.remove('active');
    if (btn.getAttribute('data-section') === section) {
      btn.classList.add('active');
    }
  });

  try {
    // Mostrar loading
    contenido.innerHTML = '<div class="loading">Cargando...</div>';
    
    let url = '';
    switch(section) {
      case 'menu':
        url = 'menu.php';
        break;
      case 'contacto':
        url = 'contacto.php';
        break;
      case 'login':
        url = 'login.php';
        break;
      case 'admin':
        if (currentUser && currentUser.role === 'admin') {
          url = 'admin.php';
        } else {
          throw new Error('Acceso denegado');
        }
        break;
      default:
        // Cargar contenido por defecto (productos)
        loadDefaultContent();
        return;
    }

    const response = await fetch(url);
    
    if (!response.ok) {
      throw new Error(`Error ${response.status}: ${response.statusText}`);
    }
    
    const html = await response.text();
    contenido.innerHTML = html;
    
    // Ejecutar scripts si los hay en el contenido cargado
    const scripts = contenido.querySelectorAll('script');
    scripts.forEach(script => {
      const newScript = document.createElement('script');
      newScript.textContent = script.textContent;
      document.head.appendChild(newScript);
      document.head.removeChild(newScript);
    });
    
  } catch (error) {
    console.error('Error cargando sección:', error);
    contenido.innerHTML = `
      <div class="error-message">
        <h3>Error al cargar el contenido</h3>
        <p>${error.message}</p>
        <button onclick="loadSection('home')" class="btn">Volver al inicio</button>
      </div>
    `;
  }
}

// Cargar contenido por defecto (productos)
function loadDefaultContent() {
  if (!contenido) return;
  
  contenido.innerHTML = `
    <section class="products" id="productos">
      <h2 class="section-title">Productos</h2>
      <div class="product-grid" id="productGrid">
        <!-- Products will be generated dynamically -->
      </div>
    </section>
  `;
  
  // Reinicializar la funcionalidad de productos
  generateProductCards();
  initCategoryFilters();
}

// Función de logout
async function logout() {
  try {
    const response = await fetch('logout.php', {
      method: 'POST'
    });
    
    const data = await response.json();
    
    if (data.success) {
      currentUser = null;
      updateUI();
      loadSection('home');
      alert('Sesión cerrada correctamente');
    } else {
      alert('Error al cerrar sesión');
    }
  } catch (error) {
    console.error('Error en logout:', error);
    alert('Error al cerrar sesión');
  }
}

// Función para manejar login exitoso (llamada desde login.php)
function handleLoginSuccess(user) {
  currentUser = user;
  updateUI();
  loadSection('home');
}

// Función para manejar errores de login (llamada desde login.php)
function handleLoginError(message) {
  alert('Error de login: ' + message);
}

// Función de inicialización para navegación dinámica
function initDynamicNavigation() {
  // Inicializar elementos del DOM
  initDynamicElements();
  
  // Verificar si hay una sesión activa
  checkSession();
  
  // Agregar event listeners a los botones de navegación
  document.querySelectorAll('.nav-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const section = this.getAttribute('data-section');
      if (section) {
        loadSection(section);
      }
    });
  });

  // Event listener para cerrar sesión
  if (logoutBtn) {
    logoutBtn.addEventListener('click', logout);
  }
}

// Función para inicializar productos (compatible con el código existente)
function initProducts() {
  generateProductCards();
  initCategoryFilters();
}