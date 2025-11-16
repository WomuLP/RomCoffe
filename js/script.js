// ========================================
// SHOPPING CART
// ========================================
let cart = [];

// Crear panel del carrito
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

// Botón flotante del carrito
const cartFab = document.createElement('button');
cartFab.className = 'cart-fab';
cartFab.setAttribute('aria-label', 'Abrir carrito');
cartFab.innerHTML = `
  <img class="fab-icon" id="cartIcon" src="imagen/carrito1.png" alt="Carrito de compras">
  <span class="badge" id="cartCount">0</span>`;
document.body.appendChild(cartFab);

// Guardar carrito
function saveCart() {
  // Sin persistencia local: el carrito vive en memoria
}

// Cargar carrito
function loadCart() {
  cart = [];
}

// Actualizar interfaz del carrito
function updateCartUI() {
  const itemsEl = document.getElementById('cartItems');
  const totalEl = document.getElementById('cartTotal');
  const countEl = document.getElementById('cartCount');
  const cartIcon = document.getElementById('cartIcon');
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

  // Cambiar icono del carrito
  cartIcon.src = cart.length > 0 ? 'imagen/carrito1.png' : 'imagen/carrito2.png';

  saveCart();
  console.log('Carrito actualizado, productos:', cart.length); // Debug
}

// Agregar producto
function addToCart(productName, price, imageUrl) {
  const existing = cart.find(p => p.name === productName);
  if (existing) {
    existing.qty += 1;
  } else {
    cart.push({ name: productName, price: price, qty: 1, image: imageUrl });
  }
  updateCartUI();

  cartFab.classList.add('bounce');
  setTimeout(() => cartFab.classList.remove('bounce'), 600);
}

// Eventos del carrito
cartFab.addEventListener('click', function() {
  cartPanel.classList.toggle('open');
  if (cartPanel.classList.contains('open')) {
    setTimeout(() => {
      cartPanel.style.display = 'block';
    }, 10);
  }
});

cartPanel.addEventListener('click', function(e) {
  console.log('Click en panel:', e.target.id); // Debug
  
  if (e.target && e.target.id === 'cartClear') {
    cart.length = 0;
    updateCartUI();
  }
  
  if (e.target && e.target.id === 'cartCheckout') {
    console.log('Botón Finalizar clickeado'); // Debug
    
    if (cart.length > 0) {
      alert('¡Gracias por tu pedido!');
      confetiCafeteria(); // 🎉 lanza confeti al finalizar
    }
    
    // Limpiar carrito
    cart.length = 0;
    updateCartUI();
    cartPanel.classList.remove('open');
    
    console.log('Carrito limpiado'); // Debug
  }
});

// Controlar cantidad
document.addEventListener('click', function(e) {
  if (e.target && e.target.closest('.qty-controls')) {
    const group = e.target.closest('.qty-controls');
    const idx = parseInt(group.getAttribute('data-index'), 10);
    const action = e.target.getAttribute('data-action');
    if (action === 'inc') cart[idx].qty += 1;
    if (action === 'dec') cart[idx].qty = Math.max(1, cart[idx].qty - 1);
    updateCartUI();
  }
});
// Eliminar producto del carrito
document.addEventListener('click', function(e) {
  if (e.target && e.target.closest('.cart-item-delete')) {
    const deleteBtn = e.target.closest('.cart-item-delete');
    const idx = parseInt(deleteBtn.getAttribute('data-index'), 10);
    if (idx >= 0 && idx < cart.length) {
      cart.splice(idx, 1);
      updateCartUI();
    }
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

  // Mostrar texto del footer personalizado (si el admin lo cambió)
  // Sin footer persistido en almacenamiento local

  // Inicializar navegación dinámica
  initDynamicNavigation();
});

// ========================================
// USER SESSION MANAGEMENT
// ========================================
let currentUser = null;

async function checkSession() {
  try {
    // Solicitar formato JSON para el frontend
    const response = await fetch('php/check_session.php?format=json');
    const data = await response.json();
    
    if (data.logged_in) {
      currentUser = data.user;
    } else {
      currentUser = null;
    }
    
    updateUI();
  } catch (error) {
    console.error('Error verificando sesión:', error);
    currentUser = null;
    updateUI();
  }
}

function updateUI() {
  const loginNavItem = document.getElementById('loginNavItem');
  const userNavItem = document.getElementById('userNavItem');
  const adminNavItem = document.getElementById('adminNavItem');
  const logoutNavItem = document.getElementById('logoutNavItem');
  const userEmailNav = document.getElementById('userEmailNav');
  
  if (currentUser) {
    // Usuario logueado
    if (loginNavItem) loginNavItem.style.display = 'none';
    if (userNavItem) {
      userNavItem.style.display = 'block';
      if (userEmailNav) userEmailNav.textContent = currentUser.email;
    }
    if (logoutNavItem) logoutNavItem.style.display = 'block';
    
    // Mostrar enlace a admin si es administrador
    if (currentUser.rol === 'admin' && adminNavItem) {
      adminNavItem.style.display = 'block';
    } else if (adminNavItem) {
      adminNavItem.style.display = 'none';
    }
  } else {
    // Usuario no logueado
    if (loginNavItem) loginNavItem.style.display = 'block';
    if (userNavItem) userNavItem.style.display = 'none';
    if (adminNavItem) adminNavItem.style.display = 'none';
    if (logoutNavItem) logoutNavItem.style.display = 'none';
  }
}

// Función logout disponible globalmente
window.logout = function() {
  if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
    window.location.href = 'php/logout.php';
  }
};

function initDynamicNavigation() {
  checkSession();
}

// ========================================
// CONFETI CAFETERÍA ☕🎉
// ========================================
function confetiCafeteria() {
  // Crear elementos de confeti personalizados
  const confetiContainer = document.createElement('div');
  confetiContainer.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 9999;
  `;
  
  document.body.appendChild(confetiContainer);
  
  // Crear partículas de confeti
  const emojis = ['☕', '🎉', '🍰', '🥐', '☕', '🎊', '🍩', '🥤'];
  const colors = ['#F288A4', '#8C031C', '#F4C9CA', '#F2F2F2', '#F24141'];
  
  for (let i = 0; i < 50; i++) {
    setTimeout(() => {
      const confeti = document.createElement('div');
      const isEmoji = Math.random() > 0.5;
      
      if (isEmoji) {
        confeti.textContent = emojis[Math.floor(Math.random() * emojis.length)];
        confeti.style.fontSize = '20px';
      } else {
        confeti.style.width = '10px';
        confeti.style.height = '10px';
        confeti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
      }
      
      confeti.style.position = 'absolute';
      confeti.style.left = Math.random() * 100 + '%';
      confeti.style.top = '-20px';
      confeti.style.animation = `confetiFall ${2 + Math.random() * 3}s linear forwards`;
      
      confetiContainer.appendChild(confeti);
      
      // Remover después de la animación
      setTimeout(() => {
        if (confeti.parentNode) {
          confeti.parentNode.removeChild(confeti);
        }
      }, 5000);
    }, i * 50);
  }
  
  // Remover el contenedor después de 6 segundos
  setTimeout(() => {
    if (confetiContainer.parentNode) {
      confetiContainer.parentNode.removeChild(confetiContainer);
    }
  }, 6000);
}

// Agregar CSS para la animación de confeti
const style = document.createElement('style');
style.textContent = `
  @keyframes confetiFall {
    to {
      transform: translateY(100vh) rotate(720deg);
      opacity: 0;
    }
  }
`;
document.head.appendChild(style);

// ========================================
// SIMPLE CAROUSEL FIX
// (Movido a index.html para mejor inicialización)
// ========================================
