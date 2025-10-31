const products = [
    /* ======================
       CAFÉ
    ====================== */
    {
      image: 'imagen/Cafe Latte.jpg',
      name: 'Café Latte',
      price: 3900,
      description: 'Espresso suave con leche vaporizada y una capa de espuma.',
      ingredients: ['Espresso', 'Leche', 'Espuma de leche'],
      category: 'cafe'
    },
    {
      image: 'imagen/Mocha.jpg',
      name: 'Mocha',
      price: 4800,
      description: 'Delicioso espresso con chocolate y leche vaporizada, coronado con crema batida.',
      ingredients: ['Espresso', 'Chocolate', 'Leche', 'Crema batida'],
      category: 'cafe'
    },
    {
      image: 'imagen/Americano.jpg',
      name: 'Americano',
      price: 3500,
      description: 'Café espresso diluido con agua caliente, sabor intenso y aromático.',
      ingredients: ['Espresso', 'Agua caliente'],
      category: 'cafe'
    },
    {
      image: 'imagen/Ice Mocha.jpg',
      name: 'Ice Mocha',
      price: 3800,
      description: 'Refrescante y con el balance perfecto entre café y chocolate. Ideal para los que aman lo dulce.',
      ingredients: ['Café', 'Chocolate', 'Leche', 'Hielo'],
      category: 'cafe'
    },
    {
      image: 'imagen/Matcha Latte.jpg',
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
      image: 'imagen/Te Verde.jpg',
      name: 'Té Verde',
      price: 3200,
      description: 'Té verde natural, rico en antioxidantes y de sabor suave.',
      ingredients: ['Hojas de té verde', 'Agua caliente'],
      category: 'tes'
    },
    {
      image: 'imagen/Te de Frutos Rojos.jpg',
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
      image: 'imagen/Cold Brew.jpg',
      name: 'Cold Brew',
      price: 4500,
      description: 'Café infusionado en frío durante 16 horas. Refrescante y suave.',
      ingredients: ['Café molido', 'Agua filtrada', 'Hielo'],
      category: 'bebidas-frias'
    },
    {
      image: 'imagen/Ice Latte.jpg',
      name: 'Ice Latte',
      price: 3500,
      description: 'Suave y fresca. Genial para todos los días.',
      ingredients: ['Café espresso', 'Leche', 'Hielo'],
      category: 'bebidas-frias'
    },
    {
      image: 'imagen/Zumo de Zanahoria.jpg',
      name: 'Zumo de Zanahoria',
      price: 3200,
      description: 'Natural, con sabor dulce y delicado. Rico en betacarotenos y antioxidantes.',
      ingredients: ['Zanahoria', 'Agua', 'Jugo de limón (opcional)'],
      category: 'bebidas-frias'
    },
    {
      image: 'imagen/Jugo de Naranja.jpg',
      name: 'Jugo de Naranja',
      price: 3000,
      description: 'Suave y fresca. Genial para todos los días, exprimido al momento.',
      ingredients: ['Naranja'],
      category: 'bebidas-frias'
    },
    {
      image: 'imagen/Jugo de Tomate.jpg',
      name: 'Jugo de Tomate',
      price: 3200,
      description: 'Fresco y nutritivo, con un toque salado. Excelente fuente de vitaminas y minerales.',
      ingredients: ['Tomate', 'Sal', 'Limón (opcional)'],
      category: 'bebidas-frias'
    },
    {
      image: 'imagen/Jugo de Frutilla.jpg',
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
      image: 'imagen/Tostadas.jpg',
      name: 'Tostadas con Palta',
      price: 5200,
      description: 'Pan integral con palta, semillas, huevo cocido y un toque de limón.',
      ingredients: ['Pan integral', 'Palta', 'Semillas', 'Limón', 'Huevo cocido'],
      category: 'desayuno'
    },
    {
      image: 'imagen/Panqueques.jpg',
      name: 'Panqueques con Fruta',
      price: 5900,
      description: 'Panqueques suaves con frutos rojos y miel.',
      ingredients: ['Harina', 'Huevo', 'Leche', 'Frutos rojos', 'Miel'],
      category: 'desayuno'
    },
    /* ======================
       ALMUERZO
    ====================== */
    {
      image: 'imagen/Sandwich.jpg',
      name: 'Sandwich Club',
      price: 6900,
      description: 'Pan tostado con pollo, tocino, vegetales y salsa especial.',
      ingredients: ['Pan', 'Pollo', 'Tocino', 'Lechuga', 'Tomate', 'Salsa'],
      category: 'almuerzo'
    },
    {
      image: 'imagen/Wrap.jpg',
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
      image: 'imagen/Chesscake.jpg',
      name: 'Cheesecake de Frutilla',
      price: 6100,
      description: 'Tarta cremosa con base de galleta y cobertura de frutilla.',
      ingredients: ['Queso crema', 'Galletas', 'Frutilla', 'Azúcar'],
      category: 'postres'
    },
    {
      image: 'imagen/Tart.jpg',
      name: 'Chocolate Tart',
      price: 4200,
      description: 'Postre intenso y elegante, ideal para cerrar con broche de oro cualquier comida.',
      ingredients: ['Chocolate', 'Crema', 'Masa sablée'],
      category: 'postres'
    },
    {
      image: 'image: imagen/Mini.jpg',
      name: 'Mini Chocolate Tarts',
      price: 1200,
      description: 'Pequeños bocados de puro placer chocolatoso, esponjosos y perfectos para acompañar un café o té.',
      ingredients: ['Cacao', 'Harina', 'Manteca', 'Azúcar'],
      category: 'postres'
    },
    {
      image: 'imagen/Minif.jpg',
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
      image: 'imagen/Promo1.jpg',
      name: 'Promo Nº 1',
      price: 11000,
      description: 'Sándwich artesanal con jamón, queso, palta y huevo revuelto, acompañado de jugo natural a elección (naranja, frutilla, zanahoria o tomate).',
      ingredients: ['Pan', 'Jamón', 'Queso', 'Palta', 'Huevo', 'Jugo natural'],
      category: 'promos'
    },
    {
      image: 'imagen/Promo2.jpg',
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
            ${product.ingredients.map(ingredient => `<li>${ingredient}</li>`).join('\n')}
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
  