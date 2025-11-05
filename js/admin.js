document.addEventListener('DOMContentLoaded', () => {
    const productList = document.getElementById('productList');
    const btnAdd = document.getElementById('btnAddProduct');
    const footerText = document.getElementById('footerText');
    const btnSaveFooter = document.getElementById('btnSaveFooter');
  
    // Productos en memoria (sin almacenamiento local)
    let products = [];
  
    btnAdd.addEventListener('click', () => {
      const name = prompt('Nombre del producto:');
      const price = parseFloat(prompt('Precio:'));
      const image = prompt('URL de la imagen:');
      if (name && !isNaN(price)) {
        products.push({ name, price, image, description: '', ingredients: [], category: 'otros' });
        renderProducts(products);
        alert('Producto agregado con éxito.');
      }
    });
  
    btnSaveFooter.addEventListener('click', () => {
      alert('Texto del footer actualizado (no se guarda en el navegador).');
    });
  
    function renderProducts(list) {
      productList.innerHTML = '';
      list.forEach(p => {
        const li = document.createElement('li');
        li.textContent = `${p.name} - $${p.price}`;
        productList.appendChild(li);
      });
    }
  
    // Sin carga de footer desde almacenamiento local
  });
  