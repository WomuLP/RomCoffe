-- Esquema mínimo para usuarios y productos

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  description TEXT NOT NULL,
  image VARCHAR(500) NOT NULL,
  category VARCHAR(100) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Usuario admin de ejemplo (cambiar contraseña en producción)
-- INSERTAR SOLO UNA VEZ si necesitas un admin inicial:
-- INSERT INTO usuarios (username, password, role, active)
-- VALUES ('admin', '$2y$10$z0T4q3m3p0w3r5HaShDeEjEmPlO/8l8M0hB0qU1l0H0g1uT2q', 'admin', 1);

