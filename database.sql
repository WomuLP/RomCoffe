DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(120) NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','user') NOT NULL DEFAULT 'user',
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `last_login` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_usuarios_username` (`username`),
  UNIQUE KEY `uk_usuarios_email` (`email`),
  KEY `idx_usuarios_active` (`active`),
  KEY `idx_usuarios_role` (`role`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;


-- 5) Tabla: pedidos
--    Registra pedidos realizados por usuarios.
--    Campos: id_pedido, id_usuario (FK -> usuarios.id), tipo_pedido, productos (JSON/TEXT), total, estado, fecha_pedido
--    Motor/charset: InnoDB / utf8mb4
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id_pedido` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_usuario` INT UNSIGNED NOT NULL,
  `tipo_pedido` ENUM('local','retirar') NOT NULL,
  `productos` TEXT NOT NULL,
  `total` DECIMAL(10,2) NOT NULL,
  `estado` ENUM('Pendiente','En preparación','Listo','Entregado') NOT NULL DEFAULT 'Pendiente',
  `fecha_pedido` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pedido`),
  KEY `idx_pedidos_usuario` (`id_usuario`),
  CONSTRAINT `fk_pedidos_usuarios`
    FOREIGN KEY (`id_usuario`) REFERENCES `usuarios`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;


INSERT INTO `usuarios` (`username`, `email`, `password`, `role`, `active`, `created_at`)
VALUES (
  'admin',
  'admin@admin.com',
  '$2y$10$KQbP5iP7cRrU8wO0tqHj5u8q0y0wBq4wC4mYQ8P8m6bO1F0kY7dB2', -- hash de 'admin123'
  'admin',
  1,
  NOW()
);


CREATE TABLE IF NOT EXISTS `productos` (
  `id_producto` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(200) NOT NULL,
  `descripcion` TEXT NULL,
  `precio` DECIMAL(10,2) NOT NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `imagen` VARCHAR(255) NULL,
  `categoria` VARCHAR(100) NULL,
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_usuario` INT UNSIGNED NULL,
  PRIMARY KEY (`id_producto`),
  KEY `idx_productos_categoria` (`categoria`),
  KEY `idx_productos_id_usuario` (`id_usuario`),
  CONSTRAINT `fk_productos_usuarios`
    FOREIGN KEY (`id_usuario`) REFERENCES `usuarios`(`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;



