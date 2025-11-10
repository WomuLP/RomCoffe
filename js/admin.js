document.addEventListener('DOMContentLoaded', () => {
    const productList = document.getElementById('productList');
    const btnAdd = document.getElementById('btnAddProduct');
    const footerText = document.getElementById('footerText');
    const btnSaveFooter = document.getElementById('btnSaveFooter');
    const emptyMessage = document.getElementById('emptyMessage');
  
    let products = [];
    let editingProductId = null;
  
    // Cargar productos desde la base de datos
    async function loadProducts() {
      try {
        const response = await fetch('php/productos.php');
        const data = await response.json();
        
        if (data.ok) {
          products = data.productos;
          renderProducts(products);
        } else {
          showNotification('Error al cargar productos: ' + (data.error || 'Error desconocido'), 'error');
        }
      } catch (error) {
        console.error('Error:', error);
        showNotification('Error de conexión al cargar productos', 'error');
      }
    }
  
    // Renderizar productos
    function renderProducts(list) {
      productList.innerHTML = '';
      
      if (list.length === 0) {
        emptyMessage.style.display = 'block';
        return;
      }
      
      emptyMessage.style.display = 'none';
      
      list.forEach((p) => {
        const li = document.createElement('li');
        li.innerHTML = `
          <div class="product-info">
            <img src="${p.image}" alt="${p.name}" class="product-thumbnail">
            <div class="product-details">
              <span class="product-name">${p.name}</span>
              <span class="product-category">${p.category}</span>
              <span class="product-price">$${p.price.toLocaleString('es-AR', { minimumFractionDigits: 2 })}</span>
            </div>
          </div>
          <div class="product-actions">
            <button class="edit" onclick="openEditModal(${p.id})">
              <i class="bi bi-pencil"></i> Editar
            </button>
            <button class="delete" onclick="deleteProduct(${p.id})">
              <i class="bi bi-trash"></i> Eliminar
            </button>
          </div>
        `;
        productList.appendChild(li);
      });
    }
  
    // Abrir modal para agregar/editar producto
    window.openEditModal = function(productId = null) {
      editingProductId = productId;
      const modal = document.getElementById('productModal');
      const form = document.getElementById('productForm');
      const modalTitle = document.getElementById('modalTitle');
      
      if (productId) {
        // Modo edición
        const product = products.find(p => p.id === productId);
        if (!product) return;
        
        modalTitle.textContent = 'Editar Producto';
        document.getElementById('productName').value = product.name;
        document.getElementById('productPrice').value = product.price;
        document.getElementById('productImage').value = product.image;
        document.getElementById('productDescription').value = product.description || '';
        document.getElementById('productCategory').value = product.category;
        
        // Ingredientes
        const ingredientsContainer = document.getElementById('ingredientsContainer');
        ingredientsContainer.innerHTML = '';
        if (product.ingredients && product.ingredients.length > 0) {
          product.ingredients.forEach(ing => {
            addIngredientInput(ing);
          });
        } else {
          addIngredientInput();
        }
      } else {
        // Modo creación
        modalTitle.textContent = 'Agregar Producto';
        form.reset();
        const ingredientsContainer = document.getElementById('ingredientsContainer');
        ingredientsContainer.innerHTML = '';
        addIngredientInput();
      }
      
      modal.style.display = 'flex';
    };
  
    // Cerrar modal
    window.closeProductModal = function() {
      const modal = document.getElementById('productModal');
      modal.style.display = 'none';
      editingProductId = null;
    };
  
    // Agregar campo de ingrediente
    function addIngredientInput(value = '') {
      const container = document.getElementById('ingredientsContainer');
      const div = document.createElement('div');
      div.className = 'ingredient-input-group';
      div.innerHTML = `
        <input type="text" class="ingredient-input" value="${value}" placeholder="Ingrediente">
        <button type="button" class="btn-remove-ingredient" onclick="this.parentElement.remove()">
          <i class="bi bi-x"></i>
        </button>
      `;
      container.appendChild(div);
    }
  
    window.addIngredientInput = addIngredientInput;
  
    // Guardar producto (crear o actualizar)
    window.saveProduct = async function(e) {
      e.preventDefault();
      
      const formData = new FormData(e.target);
      const name = formData.get('name');
      const price = parseFloat(formData.get('price'));
      const image = formData.get('image');
      const description = formData.get('description');
      const category = formData.get('category');
      
      // Recopilar ingredientes
      const ingredientInputs = document.querySelectorAll('.ingredient-input');
      const ingredients = Array.from(ingredientInputs)
        .map(input => input.value.trim())
        .filter(ing => ing !== '');
      
      if (!name || !price || !image || !category) {
        showNotification('Por favor, completa todos los campos requeridos', 'warning');
        return;
      }
      
      if (price <= 0) {
        showNotification('El precio debe ser mayor a 0', 'warning');
        return;
      }
      
      try {
        const data = {
          nombre: name,
          precio: price,
          imagen: image,
          descripcion: description,
          categoria: category,
          ingredientes: ingredients
        };
        
        let response;
        if (editingProductId) {
          // Actualizar
          data.id = editingProductId;
          // Convertir ingredientes a JSON string para URLSearchParams
          const dataToSend = { ...data };
          dataToSend.ingredientes = JSON.stringify(data.ingredientes);
          
          const params = new URLSearchParams();
          Object.keys(dataToSend).forEach(key => {
            const value = dataToSend[key];
            if (value !== null && value !== undefined) {
              params.append(key, value);
            }
          });
          
          response = await fetch('php/productos.php', {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: params.toString()
          });
        } else {
          // Crear
          const formDataToSend = new FormData();
          Object.keys(data).forEach(key => formDataToSend.append(key, data[key]));
          
          response = await fetch('php/productos.php', {
            method: 'POST',
            body: formDataToSend
          });
        }
        
        const result = await response.json();
        
        if (result.ok) {
          showNotification(editingProductId ? 'Producto actualizado con éxito!' : 'Producto creado con éxito!', 'success');
          closeProductModal();
          await loadProducts(); // Recargar lista
        } else {
          showNotification('Error: ' + (result.error || 'Error desconocido'), 'error');
        }
      } catch (error) {
        console.error('Error:', error);
        showNotification('Error de conexión. Intenta nuevamente.', 'error');
      }
    };
  
    // Eliminar producto
    window.deleteProduct = async function(productId) {
      const product = products.find(p => p.id === productId);
      if (!product) return;
      
      if (!confirm(`¿Estás seguro de eliminar "${product.name}"?`)) {
        return;
      }
      
      try {
        const params = new URLSearchParams();
        params.append('id', productId);
        
        const response = await fetch('php/productos.php', {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: params.toString()
        });
        
        const result = await response.json();
        
        if (result.ok) {
          showNotification('Producto eliminado exitosamente', 'info');
          await loadProducts(); // Recargar lista
        } else {
          showNotification('Error: ' + (result.error || 'Error desconocido'), 'error');
        }
      } catch (error) {
        console.error('Error:', error);
        showNotification('Error de conexión. Intenta nuevamente.', 'error');
      }
    };
  
    // Agregar producto
    btnAdd.addEventListener('click', () => {
      openEditModal();
    });
  
    // Guardar footer
    btnSaveFooter.addEventListener('click', () => {
      const text = footerText.value.trim();
      if (text) {
        showNotification('Texto del footer actualizado (no se guarda en el navegador).', 'success');
      } else {
        showNotification('Por favor, ingresa un texto para el footer.', 'warning');
      }
    });
  
    // Mostrar notificación
    function showNotification(message, type = 'info') {
      const notification = document.createElement('div');
      notification.className = `admin-notification admin-notification-${type}`;
      notification.textContent = message;
      
      notification.style.cssText = `
        position: fixed;
        top: 90px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : type === 'warning' ? '#f59e0b' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        padding: 16px 24px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 2000;
        animation: slideIn 0.3s ease;
        max-width: 300px;
        font-weight: 600;
      `;
      
      document.body.appendChild(notification);
      
      setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
          if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
          }
        }, 300);
      }, 3000);
    }
  
    // Agregar animaciones CSS
    const style = document.createElement('style');
    style.textContent = `
      @keyframes slideIn {
        from {
          transform: translateX(100%);
          opacity: 0;
        }
        to {
          transform: translateX(0);
          opacity: 1;
        }
      }
      @keyframes slideOut {
        from {
          transform: translateX(0);
          opacity: 1;
        }
        to {
          transform: translateX(100%);
          opacity: 0;
        }
      }
    `;
    document.head.appendChild(style);
  
    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', function(event) {
      const modal = document.getElementById('productModal');
      if (event.target === modal) {
        closeProductModal();
      }
    });
  
    // Inicializar
    loadProducts();
  });
