document.addEventListener('DOMContentLoaded', () => {
    const productList = document.getElementById('productList');
    const btnAdd = document.getElementById('btnAddProduct');
    const footerText = document.getElementById('footerText');
    const btnSaveFooter = document.getElementById('btnSaveFooter');
  
    // Mostrar productos actuales
    if (localStorage.getItem('products')) {
      const saved = JSON.parse(localStorage.getItem('products'));
      renderProducts(saved);
    }
  
    btnAdd.addEventListener('click', () => {
      const name = prompt('Nombre del producto:');
      const price = parseFloat(prompt('Precio:'));
      const image = prompt('URL de la imagen:');
      if (name && !isNaN(price)) {
        const products = JSON.parse(localStorage.getItem('products')) || [];
        products.push({ name, price, image, description: '', ingredients: [], category: 'otros' });
        localStorage.setItem('products', JSON.stringify(products));
        renderProducts(products);
        alert('Producto agregado con éxito.');
      }
    });
  
    btnSaveFooter.addEventListener('click', () => {
      localStorage.setItem('footerText', footerText.value);
      alert('Texto del footer guardado.');
    });
  
    function renderProducts(list) {
      productList.innerHTML = '';
      list.forEach(p => {
        const li = document.createElement('li');
        li.textContent = `${p.name} - $${p.price}`;
        productList.appendChild(li);
      });
    }
  
    // Cargar texto guardado del footer
    const savedFooter = localStorage.getItem('footerText');
    if (savedFooter) footerText.value = savedFooter;
  });
  