const products = [
  /* ======================
     CAFÉ
  ====================== */
  {
    image: 'https://i.pinimg.com/736x/25/b6/29/25b629514436286acc0fd53bcf52f1e2.jpg',
    name: 'Café Latte',
    price: 3900,
    description: 'Espresso suave con leche vaporizada y una capa de espuma.',
    ingredients: ['Espresso', 'Leche', 'Espuma de leche'],
    category: 'cafe'
  },
  {
    image: 'https://i.pinimg.com/736x/22/8b/72/228b72a03cb98c19063193cf0188a6a3.jpg',
    name: 'Mocha',
    price: 4800,
    description: 'Delicioso espresso con chocolate y leche vaporizada, coronado con crema batida.',
    ingredients: ['Espresso', 'Chocolate', 'Leche', 'Crema batida'],
    category: 'cafe'
  },
  {
    image: 'https://i.pinimg.com/1200x/d6/f2/e9/d6f2e9113aa8f9aef8b59a8e28bd7255.jpg',
    name: 'Americano',
    price: 3500,
    description: 'Café espresso diluido con agua caliente, sabor intenso y aromático.',
    ingredients: ['Espresso', 'Agua caliente'],
    category: 'cafe'
  },
  {
    image: 'https://i.pinimg.com/736x/4d/e0/68/4de068124212961d6481e6c631774053.jpg',
    name: 'Ice Mocha',
    price: 3800,
    description: 'Refrescante y con el balance perfecto entre café y chocolate. Ideal para los que aman lo dulce.',
    ingredients: ['Café', 'Chocolate', 'Leche', 'Hielo'],
    category: 'cafe'
  },
  {
    image: 'https://i.pinimg.com/1200x/57/14/b1/5714b11a87991728b48bee9f34855c3a.jpg',
    name: 'Matcha Latte',
    price: 4200,
    description: 'Energía suave y sostenida sin el bajón del café. Sabor herbal y cremoso, ideal con leche de avena o almendra.',
    ingredients: ['Té matcha', 'Leche vegetal', 'Hielo'],
    category: 'cafe'
  },
  /* ======================
     TÉS
  ====================== */
  {
    image: 'https://i.pinimg.com/736x/2e/2b/b0/2e2bb00a641fef8acafa7bec1ac6e2e2.jpg',
    name: 'Té Verde',
    price: 3200,
    description: 'Té verde natural, rico en antioxidantes y de sabor suave.',
    ingredients: ['Hojas de té verde', 'Agua caliente'],
    category: 'tes'
  },
  {
    image: 'https://i.pinimg.com/1200x/c5/f6/23/c5f623cba688460a7311e57d8090fc07.jpg',
    name: 'Chai Latte',
    price: 4100,
    description: 'Té negro especiado con leche y un toque de canela.',
    ingredients: ['Té negro', 'Leche', 'Canela', 'Clavo', 'Jengibre'],
    category: 'tes'
  },
  {
    image: 'https://i.pinimg.com/1200x/f9/ec/e7/f9ece729d7dc016bd9ba391c320a58fb.jpg',
    name: 'Té de Frutos Rojos',
    price: 3700,
    description: 'Infusión de frutas rojas con aroma dulce y refrescante.',
    ingredients: ['Frutilla', 'Arándanos', 'Hibisco'],
    category: 'tes'
  },

  /* ======================
     BEBIDAS FRÍAS
  ====================== */
  {
    image: 'https://i.pinimg.com/1200x/dd/e0/ef/dde0ef2bfc5fdcadca9f1a4d8a5a5101.jpg',
    name: 'Cold Brew',
    price: 4500,
    description: 'Café infusionado en frío durante 16 horas. Refrescante y suave.',
    ingredients: ['Café molido', 'Agua filtrada', 'Hielo'],
    category: 'bebidas-frias'
  },
  {
    image: 'https://i.pinimg.com/1200x/74/5c/e4/745ce426e7e44564117511bbc2dfb16d.jpg',
    name: 'Limonada con Menta',
    price: 3900,
    description: 'Refrescante limonada natural con hojas de menta fresca.',
    ingredients: ['Limón', 'Agua', 'Menta', 'Azúcar'],
    category: 'bebidas-frias'
  },
  {
    image: 'https://i.pinimg.com/1200x/61/17/99/6117996402ac81f5f53bd656fd188eb8.jpg',
    name: 'Smoothie de Frutilla',
    price: 4800,
    description: 'Batido natural de frutilla y yogurt, cremoso y dulce.',
    ingredients: ['Frutilla', 'Yogurt', 'Miel', 'Leche'],
    category: 'bebidas-frias'
  },
  {
    image: 'https://i.pinimg.com/736x/37/22/4e/37224ec272e2c252500a7f5b4351f379.jpg',
    name: 'Ice Latte',
    price: 3500,
    description: 'Suave y fresca. Genial para todos los días.',
    ingredients: ['Café espresso', 'Leche', 'Hielo'],
    category: 'bebidas-frias'
  },
  {
    image: 'https://i.pinimg.com/736x/63/0d/17/630d17a69a9065467344402f21637377.jpg',
    name: 'Zumo de Zanahoria',
    price: 3200,
    description: 'Natural, con sabor dulce y delicado. Rico en betacarotenos y antioxidantes.',
    ingredients: ['Zanahoria', 'Agua', 'Jugo de limón (opcional)'],
    category: 'bebidas-frias'
  },
  {
    image: 'https://i.pinimg.com/736x/14/0d/b5/140db56a88c541aeb133c8035fb369f8.jpg',
    name: 'Jugo de Naranja',
    price: 3000,
    description: 'Suave y fresca. Genial para todos los días, exprimido al momento.',
    ingredients: ['Naranja'],
    category: 'bebidas-frias'
  },
  {
    image: 'https://i.pinimg.com/736x/ff/8a/28/ff8a28a475bc55d91a0e3f27f7e4c3b9.jpg',
    name: 'Jugo de Tomate',
    price: 3200,
    description: 'Fresco y nutritivo, con un toque salado. Excelente fuente de vitaminas y minerales.',
    ingredients: ['Tomate', 'Sal', 'Limón (opcional)'],
    category: 'bebidas-frias'
  },
  {
    image: 'https://i.pinimg.com/736x/dd/49/eb/dd49ebfabde39f0c3494b2e621cbe78d.jpg',
    name: 'Jugo de Frutilla',
    price: 3800,
    description: 'Dulce, suave y con un toque ácido irresistible. Ideal para quienes buscan algo frutal y liviano.',
    ingredients: ['Frutilla', 'Agua', 'Azúcar o miel (opcional)'],
    category: 'bebidas-frias'
  },
  /* ======================
     DESAYUNO
  ====================== */
  {
    image: 'https://i.pinimg.com/1200x/0f/49/ef/0f49ef3dd4008220b7ad04cf7c17c901.jpg',
    name: 'Tostadas con Palta',
    price: 5200,
    description: 'Pan integral con palta, semillas, huevo cocido y un toque de limón.',
    ingredients: ['Pan integral', 'Palta', 'Semillas', 'Limón', 'Huevo cocido'],
    category: 'desayuno'
  },
  {
    image: 'https://i.pinimg.com/1200x/5d/47/7a/5d477a3301aeffe3be9ce404388a708a.jpg',
    name: 'Panqueques con Fruta',
    price: 5900,
    description: 'Panqueques suaves con frutos rojos y miel.',
    ingredients: ['Harina', 'Huevo', 'Leche', 'Frutos rojos', 'Miel'],
    category: 'desayuno'
  },
  {
    image: 'https://i.pinimg.com/736x/6c/04/0d/6c040d14db0513109d14e914b7329ab0.jpg',
    name: 'Croissant de Manteca',
    price: 3300,
    description: 'Croissant artesanal recién horneado, tierno y crujiente.',
    ingredients: ['Harina', 'Manteca', 'Levadura'],
    category: 'desayuno'
  },

  /* ======================
     ALMUERZO
  ====================== */
  {
    image: 'https://i.pinimg.com/1200x/9e/7f/1b/9e7f1b353f6a1e28240c29ec73fde0a7.jpg',
    name: 'Sandwich Club',
    price: 6900,
    description: 'Pan tostado con pollo, tocino, vegetales y salsa especial.',
    ingredients: ['Pan', 'Pollo', 'Tocino', 'Lechuga', 'Tomate', 'Salsa'],
    category: 'almuerzo'
  },
  {
    image: 'https://i.pinimg.com/1200x/04/44/31/044431c8343b5801ff75f4b493fd6a24.jpg',
    name: 'Ensalada César',
    price: 5500,
    description: 'Clásica ensalada con aderezo césar y crutones.',
    ingredients: ['Lechuga', 'Pollo', 'Parmesano', 'Crutones', 'Aderezo césar'],
    category: 'almuerzo'
  },
  {
    image: 'https://i.pinimg.com/1200x/32/10/10/321010569fb22a8abbef01324617ccab.jpg',
    name: 'Wrap de Vegetales',
    price: 5800,
    description: 'Tortilla rellena de vegetales grillados y hummus.',
    ingredients: ['Tortilla', 'Zanahoria', 'Zucchini', 'Hummus'],
    category: 'almuerzo'
  },

  /* ======================
     POSTRES
  ====================== */
  {
    image: 'https://i.pinimg.com/1200x/de/a0/1a/dea01a87b4e0389b06ea43a19e6af30c.jpg',
    name: 'Cheesecake de Frutilla',
    price: 6100,
    description: 'Tarta cremosa con base de galleta y cobertura de frutilla.',
    ingredients: ['Queso crema', 'Galletas', 'Frutilla', 'Azúcar'],
    category: 'postres'
  },
  {
    image: 'https://i.pinimg.com/1200x/04/6f/11/046f1140c8d118c0539c5faadd41a782.jpg',
    name: 'Brownie de Chocolate',
    price: 5700,
    description: 'Brownie húmedo de chocolate con nueces.',
    ingredients: ['Chocolate', 'Harina', 'Nueces', 'Azúcar'],
    category: 'postres'
  },
  {
    image: 'https://i.pinimg.com/1200x/f1/fd/96/f1fd96ca94e19fdd308cf4a7b1553634.jpg',
    name: 'Tiramisú',
    price: 6300,
    description: 'Postre italiano con café, mascarpone y cacao.',
    ingredients: ['Café', 'Mascarpone', 'Cacao', 'Bizcocho'],
    category: 'postres'
  },
  {
    image: 'https://i.pinimg.com/736x/80/12/bf/8012bf1f341fc6518e5acf3312119c4d.jpg',
    name: 'Chocolate Tart',
    price: 4200,
    description: 'Postre intenso y elegante, ideal para cerrar con broche de oro cualquier comida.',
    ingredients: ['Chocolate', 'Crema', 'Masa sablée'],
    category: 'postres'
  },
  {
    image: 'https://i.pinimg.com/736x/ed/f2/37/edf2376cce978ed07547b2cdec9fe2eb.jpg',
    name: 'Mini Chocolate Tarts',
    price: 1200,
    description: 'Pequeños bocados de puro placer chocolatoso, esponjosos y perfectos para acompañar un café o té.',
    ingredients: ['Cacao', 'Harina', 'Manteca', 'Azúcar'],
    category: 'postres'
  },
  {
    image: 'https://i.pinimg.com/736x/0d/ac/e9/0dace93401209e64a66132f0663df13d.jpg',
    name: 'Mini Red Fruits Tarts',
    price: 1400,
    description: 'Frescos y dulces, con un toque ácido natural. Ideal para los que prefieren sabores frutales.',
    ingredients: ['Harina', 'Frutos rojos', 'Crema', 'Azúcar'],
    category: 'postres'
  },
  /* ======================
     PROMOS
  ====================== */
  {
    image: 'https://i.pinimg.com/736x/4b/1f/45/4b1f45aee1344e0dbe7b43006baa55c9.jpg',
    name: 'Promo Nº 1',
    price: 11000,
    description: 'Sándwich artesanal con jamón, queso, palta y huevo revuelto, acompañado de jugo natural a elección (naranja, frutilla, zanahoria o tomate).',
    ingredients: ['Pan', 'Jamón', 'Queso', 'Palta', 'Huevo', 'Jugo natural'],
    category: 'promos'
  },
  {
    image: 'https://i.pinimg.com/736x/45/02/f4/4502f4caf62e629fd2897f0cda9b2932.jpg',
    name: 'Promo Nº 2',
    price: 9000,
    description: 'Elegí tu bebida de café favorita (latte, moka, frappuccino o matcha latte) y acompañala con una porción de torta o cheesecake.',
    ingredients: ['Café', 'Torta o Cheesecake'],
    category: 'promos'
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