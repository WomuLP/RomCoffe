
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

  // ✅ Mostrar texto del footer personalizado (si el admin lo cambió)
  const savedFooter = localStorage.getItem('footerText');
  if (savedFooter) {
    const footerParagraph = document.querySelector('.footer-brand + p');
    if (footerParagraph) {
      footerParagraph.textContent = savedFooter;
    }
  }

  // Inicializar navegación dinámica
  initDynamicNavigation();
});



// ========================================
// USER SESSION MANAGEMENT
// ========================================

// Estado de la aplicación
let currentUser = null;

// Elementos del DOM
let loginBtn, adminBtn, userInfo, userName, logoutBtn;

// Inicializar elementos del DOM cuando estén disponibles
function initDynamicElements() {
  loginBtn = document.getElementById('loginBtn');
  adminBtn = document.getElementById('adminBtn');
  userInfo = document.getElementById('userInfo');
  userName = document.getElementById('userName');
  logoutBtn = document.getElementById('logoutBtn');
}

// Verificar sesión activa desde localStorage
function checkSession() {
  const usuarioActual = localStorage.getItem('usuarioActual');
  if (usuarioActual) {
    currentUser = { username: usuarioActual, role: 'user' };
    updateUI();
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
    if (currentUser.username === 'admin') {
      adminBtn.style.display = 'inline-block';
    }
  } else {
    // Usuario no logueado
    loginBtn.style.display = 'inline-block';
    userInfo.style.display = 'none';
    adminBtn.style.display = 'none';
  }
}

// Función de logout
function logout() {
  localStorage.removeItem('usuarioActual');
  currentUser = null;
  updateUI();
  alert('Sesión cerrada correctamente');
}

// Función de inicialización para navegación dinámica
function initDynamicNavigation() {
  // Inicializar elementos del DOM
  initDynamicElements();
  
  // Verificar si hay una sesión activa
  checkSession();
  
  // Event listener para cerrar sesión
  if (logoutBtn) {
    logoutBtn.addEventListener('click', logout);
  }
  
  // Event listener para botón admin
  if (adminBtn) {
    adminBtn.addEventListener('click', () => {
      window.location.href = 'admin.html';
    });
  }
}
