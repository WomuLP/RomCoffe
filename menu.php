<?php
/**
 * Sección de menú de productos
 * Muestra los productos disponibles organizados por categorías
 * Los productos se cargan dinámicamente desde la base de datos
 */

// Incluir conexión a la base de datos
require_once 'conexion.php';

// Obtener productos de la base de datos
$sql = "SELECT * FROM productos WHERE activo = 1 ORDER BY categoria, nombre";
$result = $conn->query($sql);

$productos = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
}

// Agrupar productos por categoría
$productos_por_categoria = [];
foreach ($productos as $producto) {
    $categoria = $producto['categoria'];
    if (!isset($productos_por_categoria[$categoria])) {
        $productos_por_categoria[$categoria] = [];
    }
    $productos_por_categoria[$categoria][] = $producto;
}

// Mapeo de categorías a nombres e iconos
$categorias_info = [
    'cafe' => ['nombre' => 'Café', 'icono' => '☕'],
    'tes' => ['nombre' => 'Tés', 'icono' => '🍵'],
    'bebidas-frias' => ['nombre' => 'Bebidas Frías', 'icono' => '🧊'],
    'desayuno' => ['nombre' => 'Desayunos', 'icono' => '🥐'],
    'almuerzo' => ['nombre' => 'Almuerzos', 'icono' => '🍽️'],
    'postres' => ['nombre' => 'Postres', 'icono' => '🍰']
];
?>

<div class="menu-section">
    <h2 class="section-title">Nuestro Menú</h2>
    
    <div class="menu-categories">
        <?php if (empty($productos)): ?>
            <div class="no-products">
                <p>No hay productos disponibles en este momento.</p>
            </div>
        <?php else: ?>
            <?php foreach ($categorias_info as $categoria_key => $categoria_data): ?>
                <?php if (isset($productos_por_categoria[$categoria_key])): ?>
                    <div class="category-section">
                        <h3 class="category-title">
                            <?php echo $categoria_data['icono']; ?> 
                            <?php echo $categoria_data['nombre']; ?>
                        </h3>
                        <div class="menu-items">
                            <?php foreach ($productos_por_categoria[$categoria_key] as $producto): ?>
                                <div class="menu-item">
                                    <div class="item-image">
                                        <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" 
                                             alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                                             loading="lazy">
                                    </div>
                                    <div class="item-info">
                                        <h4><?php echo htmlspecialchars($producto['nombre']); ?></h4>
                                        <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                                        <?php if (!empty($producto['ingredientes'])): ?>
                                            <div class="ingredientes">
                                                <strong>Ingredientes:</strong> 
                                                <?php echo htmlspecialchars($producto['ingredientes']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="item-price">
                                        <span class="price">$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></span>
                                        <button class="btn-add-cart" 
                                                onclick="addToCart('<?php echo htmlspecialchars($producto['nombre']); ?>', 
                                                                 <?php echo $producto['precio']; ?>, 
                                                                 '<?php echo htmlspecialchars($producto['imagen']); ?>')">
                                            Agregar
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
.menu-section {
    padding: 2rem;
    max-width: 1000px;
    margin: 0 auto;
    font-family: 'Poppins', 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
}

.menu-categories {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.category-section {
    background: var(--bg-cream);
    padding: 2rem;
    border-radius: 20px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    border: 2px solid var(--bg-light-pink);
}

.category-title {
    color: var(--text-dark);
    margin-bottom: 2rem;
    font-size: 2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-family: 'Dancing Script', cursive;
}

.menu-items {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2rem;
}

.menu-item {
    display: flex;
    flex-direction: column;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 2px solid var(--bg-light-pink);
}

.menu-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    border-color: var(--bg-pink);
}

.item-image {
    width: 100%;
    height: 220px;
    overflow: hidden;
    position: relative;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.menu-item:hover .item-image img {
    transform: scale(1.08);
}

.item-info {
    padding: 2rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.item-info h4 {
    margin: 0 0 0.75rem 0;
    color: var(--text-dark);
    font-size: 1.3rem;
    font-weight: 700;
    font-family: 'Poppins', sans-serif;
}

.item-info p {
    margin: 0 0 1rem 0;
    color: var(--text-muted);
    font-size: 1rem;
    line-height: 1.5;
    flex-grow: 1;
    font-weight: 500;
}

.ingredientes {
    font-size: 0.9rem;
    color: var(--text-muted);
    margin-top: 0.75rem;
    padding: 0.75rem;
    background: var(--bg-cream);
    border-radius: 8px;
    border-left: 4px solid var(--bg-pink);
}

.ingredientes strong {
    color: var(--text-dark);
    font-weight: 700;
}

.item-price {
    padding: 1.5rem 2rem;
    background: var(--bg-light-pink);
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 2px solid var(--bg-pink);
}

.price {
    font-weight: 800;
    color: var(--text-dark);
    font-size: 1.5rem;
    font-family: 'Poppins', sans-serif;
}

.btn-add-cart {
    background: var(--bg-dark);
    color: var(--text-light);
    border: 2px solid var(--bg-dark);
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: 'Poppins', sans-serif;
}

.btn-add-cart:hover {
    background: #a10a24;
    border-color: #a10a24;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.no-products {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--text-muted);
    font-size: 1.2rem;
    font-weight: 500;
    background: var(--bg-cream);
    border-radius: 16px;
    border: 2px solid var(--bg-light-pink);
}

@media (max-width: 768px) {
    .menu-items {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .category-section {
        padding: 1.5rem;
    }
    
    .category-title {
        font-size: 1.75rem;
    }
    
    .item-price {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .btn-add-cart {
        width: 100%;
        text-align: center;
    }
    
    .item-info {
        padding: 1.5rem;
    }
    
    .item-image {
        height: 180px;
    }
}

@media (max-width: 480px) {
    .menu-section {
        padding: 1rem;
    }
    
    .category-section {
        padding: 1rem;
    }
    
    .item-info {
        padding: 1rem;
    }
    
    .item-price {
        padding: 1rem;
    }
}
</style>
