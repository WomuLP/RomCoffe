-- Crear base de datos (ajusta el nombre si es necesario)
CREATE DATABASE IF NOT EXISTS romcoffe
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE romcoffe;

-- Tabla de usuarios mínima: id, email, password
DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  email VARCHAR(120) NOT NULL,
  password VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uk_usuarios_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
