-- ========================================
-- TABLA DE PRODUCTOS
-- Base de datos Room Coffe
-- ========================================

USE romcoffe;

-- Crear tabla de productos
DROP TABLE IF EXISTS productos;
CREATE TABLE productos (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(120) NOT NULL,
  precio DECIMAL(10, 2) NOT NULL,
  imagen VARCHAR(255) NOT NULL,
  descripcion TEXT,
  categoria ENUM('cafe', 'tes', 'bebidas-frias', 'desayuno', 'almuerzo', 'postres', 'promos', 'otros') DEFAULT 'otros' NOT NULL,
  ingredientes TEXT, -- JSON array como texto
  activo TINYINT(1) DEFAULT 1 NOT NULL,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  INDEX idx_productos_categoria (categoria),
  INDEX idx_productos_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar productos iniciales desde productos.js
INSERT INTO productos (nombre, precio, imagen, descripcion, categoria, ingredientes) VALUES
-- CAFÉ
('Café Latte', 3900.00, 'imagen/Cafe Latte.webp', 'Espresso suave con leche vaporizada y una capa de espuma.', 'cafe', '["Espresso", "Leche", "Espuma de leche"]'),
('Mocha', 4800.00, 'imagen/Mocha.webp', 'Delicioso espresso con chocolate y leche vaporizada, coronado con crema batida.', 'cafe', '["Espresso", "Chocolate", "Leche", "Crema batida"]'),
('Americano', 3500.00, 'imagen/Americano.webp', 'Café espresso diluido con agua caliente, sabor intenso y aromático.', 'cafe', '["Espresso", "Agua caliente"]'),
('Ice Mocha', 3800.00, 'imagen/Ice mocha.webp', 'Refrescante y con el balance perfecto entre café y chocolate. Ideal para los que aman lo dulce.', 'cafe', '["Café", "Chocolate", "Leche", "Hielo"]'),
('Matcha Latte', 4200.00, 'imagen/Matcha latte.webp', 'Energía suave y sostenida sin el bajón del café. Sabor herbal y cremoso, ideal con leche de avena o almendra.', 'cafe', '["Té matcha", "Leche vegetal", "Hielo"]'),

-- TÉS
('Té Verde', 3200.00, 'imagen/TV.webp', 'Té verde natural, rico en antioxidantes y de sabor suave.', 'tes', '["Hojas de té verde", "Agua caliente"]'),
('Té de Frutos Rojos', 3700.00, 'imagen/TFR.webp', 'Infusión de frutas rojas con aroma dulce y refrescante.', 'tes', '["Frutilla", "Arándanos", "Hibisco"]'),

-- BEBIDAS FRÍAS
('Cold Brew', 4500.00, 'imagen/Cold brew.webp', 'Café infusionado en frío durante 16 horas. Refrescante y suave.', 'bebidas-frias', '["Café molido", "Agua filtrada", "Hielo"]'),
('Ice Latte', 3500.00, 'imagen/Ice Latte.webp', 'Suave y fresca. Genial para todos los días.', 'bebidas-frias', '["Café espresso", "Leche", "Hielo"]'),
('Zumo de Zanahoria', 3200.00, 'imagen/Zumo de Zanahoria.webp', 'Natural, con sabor dulce y delicado. Rico en betacarotenos y antioxidantes.', 'bebidas-frias', '["Zanahoria", "Agua", "Jugo de limón (opcional)"]'),
('Jugo de Naranja', 3000.00, 'imagen/Jugo de naranja.webp', 'Suave y fresca. Genial para todos los días, exprimido al momento.', 'bebidas-frias', '["Naranja"]'),
('Jugo de Tomate', 3200.00, 'imagen/Jugo de tomate.webp', 'Fresco y nutritivo, con un toque salado. Excelente fuente de vitaminas y minerales.', 'bebidas-frias', '["Tomate", "Sal", "Limón (opcional)"]'),
('Jugo de Frutilla', 3800.00, 'imagen/Jugo de frutilla.webp', 'Dulce, suave y con un toque ácido irresistible. Ideal para quienes buscan algo frutal y liviano.', 'bebidas-frias', '["Frutilla", "Agua", "Azúcar o miel (opcional)"]'),

-- DESAYUNO
('Tostadas con Palta', 5200.00, 'imagen/Tostadas.webp', 'Pan integral con palta, semillas, huevo cocido y un toque de limón.', 'desayuno', '["Pan integral", "Palta", "Semillas", "Limón", "Huevo cocido"]'),
('Panqueques con Fruta', 5900.00, 'imagen/Panqueques.webp', 'Panqueques suaves con frutos rojos y miel.', 'desayuno', '["Harina", "Huevo", "Leche", "Frutos rojos", "Miel"]'),

-- ALMUERZO
('Sandwich Club', 6900.00, 'imagen/Sandwich.webp', 'Pan tostado con pollo, tocino, vegetales y salsa especial.', 'almuerzo', '["Pan", "Pollo", "Tocino", "Lechuga", "Tomate", "Salsa"]'),
('Wrap de Vegetales', 5800.00, 'imagen/Wrap.webp', 'Tortilla rellena de vegetales grillados y hummus.', 'almuerzo', '["Tortilla", "Zanahoria", "Zucchini", "Hummus"]'),

-- POSTRES
('Cheesecake de Frutilla', 6100.00, 'imagen/Chesscake.webp', 'Tarta cremosa con base de galleta y cobertura de frutilla.', 'postres', '["Queso crema", "Galletas", "Frutilla", "Azúcar"]'),
('Chocolate Tart', 4200.00, 'imagen/Tart.webp', 'Postre intenso y elegante, ideal para cerrar con broche de oro cualquier comida.', 'postres', '["Chocolate", "Crema", "Masa sablée"]'),
('Mini Chocolate Tarts', 1200.00, 'imagen/Mini.webp', 'Pequeños bocados de puro placer chocolatoso, esponjosos y perfectos para acompañar un café o té.', 'postres', '["Cacao", "Harina", "Manteca", "Azúcar"]'),
('Mini Red Fruits Tarts', 1400.00, 'imagen/Minif.webp', 'Frescos y dulces, con un toque ácido natural. Ideal para los que prefieren sabores frutales.', 'postres', '["Harina", "Frutos rojos", "Crema", "Azúcar"]'),

-- PROMOS
('Promo Nº 1', 11000.00, 'imagen/Promo1.webp', 'Sándwich artesanal con jamón, queso, palta y huevo revuelto, acompañado de jugo natural a elección (naranja, frutilla, zanahoria o tomate).', 'promos', '["Pan", "Jamón", "Queso", "Palta", "Huevo", "Jugo natural"]'),
('Promo Nº 2', 9000.00, 'imagen/Promo2.webp', 'Elegí tu bebida de café favorita (latte, moka, frappuccino o matcha latte) y acompañala con una porción de torta o cheesecake.', 'promos', '["Café", "Torta o Cheesecake"]');

-- Verificar productos insertados
SELECT COUNT(*) as total_productos FROM productos;
SELECT categoria, COUNT(*) as total FROM productos GROUP BY categoria;

