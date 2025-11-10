// ========================================
// PRODUCTS MANAGEMENT - Cargado desde BD
// ========================================

let products = [];

// Cargar productos desde la base de datos
async function loadProducts() {
  try {
    const response = await fetch('php/productos.php');
    const data = await response.json();
    
    if (data.ok) {
      products = data.productos;
      generateProductCards(products);
    } else {
      console.error('Error al cargar productos:', data.error);
      // Mostrar mensaje de error en el grid
      const productGrid = document.getElementById('productGrid');
      productGrid.innerHTML = '<p style="text-align: center; padding: 40px; color: #8C031C;">Error al cargar productos. Por favor, recarga la página.</p>';
    }
  } catch (error) {
    console.error('Error de conexión:', error);
    const productGrid = document.getElementById('productGrid');
    productGrid.innerHTML = '<p style="text-align: center; padding: 40px; color: #8C031C;">Error de conexión. Por favor, verifica tu conexión a internet.</p>';
  }
}

function generateProductCards(productsToShow = products) {
  const productGrid = document.getElementById('productGrid');
  if (!productGrid) return;
  
  productGrid.innerHTML = '';

  if (productsToShow.length === 0) {
    productGrid.innerHTML = '<p style="text-align: center; padding: 40px; color: #8C031C;">No hay productos disponibles.</p>';
    return;
  }

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
        <p class="card-desc">${product.description || ''}</p>
        <ul class="ingredients">
          ${(product.ingredients || []).map(ingredient => `<li>${ingredient}</li>`).join('\n')}
        </ul>
        <button class="btn primary" onclick="addToCart('${product.name}', ${product.price}, '${product.image}')">Agregar al carrito</button>
      </div>
    `;
    productGrid.appendChild(productCard);
  });
}

function filterProducts(category) {
  if (category === 'all') {
    generateProductCards(products);
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

// Cargar productos al iniciar
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', loadProducts);
} else {
  loadProducts();
}
