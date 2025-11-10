-- ========================================
-- BASE DE DATOS ROOM COFFE
-- Tabla de usuarios con administrador y funciones útiles
-- ========================================

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS romcoffe
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE romcoffe;

-- ========================================
-- TABLA DE USUARIOS
-- ========================================
DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  email VARCHAR(120) NOT NULL,
  password VARCHAR(255) NOT NULL,
  rol ENUM('user', 'admin') DEFAULT 'user' NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uk_usuarios_email (email),
  INDEX idx_usuarios_rol (rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- INSERTAR USUARIO ADMINISTRADOR
-- ========================================
-- Credenciales:
-- Email: admin@romcoffe.com
-- Password: Admin123!
--
-- NOTA: Este hash es para la contraseña "Admin123!"
-- Si necesitas cambiar la contraseña, genera un nuevo hash con PHP:
-- $hash = password_hash('TuNuevaContraseña', PASSWORD_DEFAULT);
-- Luego reemplaza el hash en la siguiente línea

INSERT INTO usuarios (email, password, rol) VALUES 
('admin@romcoffe.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON DUPLICATE KEY UPDATE 
  password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  rol = 'admin';

-- ========================================
-- PROCEDIMIENTOS ALMACENADOS
-- ========================================

-- Procedimiento: Verificar credenciales de usuario
DELIMITER //

DROP PROCEDURE IF EXISTS verificar_usuario //

CREATE PROCEDURE verificar_usuario(
    IN p_email VARCHAR(120),
    OUT p_id INT,
    OUT p_email_out VARCHAR(120),
    OUT p_rol VARCHAR(10),
    OUT p_existe BOOLEAN
)
BEGIN
    DECLARE v_id INT;
    DECLARE v_email VARCHAR(120);
    DECLARE v_rol VARCHAR(10);
    
    SELECT id, email, rol 
    INTO v_id, v_email, v_rol
    FROM usuarios 
    WHERE email = p_email 
    LIMIT 1;
    
    IF v_id IS NOT NULL THEN
        SET p_id = v_id;
        SET p_email_out = v_email;
        SET p_rol = v_rol;
        SET p_existe = TRUE;
    ELSE
        SET p_id = NULL;
        SET p_email_out = NULL;
        SET p_rol = NULL;
        SET p_existe = FALSE;
    END IF;
END //

-- Procedimiento: Obtener información de usuario por ID
DROP PROCEDURE IF EXISTS obtener_usuario_por_id //

CREATE PROCEDURE obtener_usuario_por_id(
    IN p_id INT,
    OUT p_email VARCHAR(120),
    OUT p_rol VARCHAR(10),
    OUT p_existe BOOLEAN
)
BEGIN
    DECLARE v_email VARCHAR(120);
    DECLARE v_rol VARCHAR(10);
    
    SELECT email, rol 
    INTO v_email, v_rol
    FROM usuarios 
    WHERE id = p_id 
    LIMIT 1;
    
    IF v_email IS NOT NULL THEN
        SET p_email = v_email;
        SET p_rol = v_rol;
        SET p_existe = TRUE;
    ELSE
        SET p_email = NULL;
        SET p_rol = NULL;
        SET p_existe = FALSE;
    END IF;
END //

DELIMITER ;

-- ========================================
-- VISTAS ÚTILES
-- ========================================

-- Vista: Usuarios activos (sin contraseñas)
DROP VIEW IF EXISTS v_usuarios_activos;

CREATE VIEW v_usuarios_activos AS
SELECT 
    id,
    email,
    rol,
    '***' AS password_masked
FROM usuarios;

-- ========================================
-- CONSULTAS ÚTILES (COMENTADAS)
-- ========================================

-- Ver todos los usuarios con sus roles
-- SELECT id, email, rol FROM usuarios ORDER BY id;

-- Contar usuarios por rol
-- SELECT rol, COUNT(*) as total FROM usuarios GROUP BY rol;

-- Buscar usuario por email
-- SELECT id, email, rol FROM usuarios WHERE email = 'admin@romcoffe.com';

-- Verificar si un email existe
-- SELECT COUNT(*) > 0 as existe FROM usuarios WHERE email = 'admin@romcoffe.com';

-- Usar el procedimiento verificar_usuario
-- CALL verificar_usuario('admin@romcoffe.com', @id, @email, @rol, @existe);
-- SELECT @id, @email, @rol, @existe;

-- Usar el procedimiento obtener_usuario_por_id
-- CALL obtener_usuario_por_id(1, @email, @rol, @existe);
-- SELECT @email, @rol, @existe;

-- ========================================
-- VERIFICACIÓN FINAL
-- ========================================

-- Verificar que el administrador se insertó correctamente
SELECT id, email, rol, 'Usuario administrador creado' AS estado 
FROM usuarios 
WHERE email = 'admin@romcoffe.com';

-- Mostrar resumen de usuarios
SELECT 
    rol,
    COUNT(*) as total_usuarios
FROM usuarios 
GROUP BY rol;
