<?php
/**
 * Archivo de autenticación de usuarios
 * Maneja tanto la visualización del formulario como el procesamiento de login
 */

// Iniciar sesión para manejar variables de sesión
session_start();

// Incluir archivo de conexión a la base de datos
require_once 'conexion.php';

// Si es una petición POST, procesar el login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header('Content-Type: application/json; charset=utf-8');
    
    try {
        // Obtener datos del formulario (puede ser JSON o form-data)
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Si no hay JSON, intentar con $_POST
        if (!$input) {
            $input = $_POST;
        }
        
        // Obtener y limpiar datos de entrada
        $username = trim($input['username'] ?? '');
        $password = $input['password'] ?? '';

        // Validar que los campos no estén vacíos
        if (empty($username) || empty($password)) {
            echo json_encode([
                'success' => false, 
                'message' => 'Por favor, completá todos los campos requeridos.'
            ]);
            exit;
        }

        // Consulta preparada para buscar el usuario
        $sql = "SELECT id, username, password, role FROM usuarios WHERE username = ? AND active = 1 LIMIT 1";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            error_log("Error en la preparación de la consulta: " . $conn->error);
            echo json_encode([
                'success' => false, 
                'message' => 'Error interno del servidor. Inténtalo más tarde.'
            ]);
            exit;
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                // Login exitoso - establecer variables de sesión
                $_SESSION['user_id'] = (int)$row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];
                
                error_log("Login exitoso para usuario: " . $username . " con rol: " . $row['role']);

                echo json_encode([
                    'success' => true, 
                    'message' => 'Inicio de sesión exitoso.',
                    'user' => [
                        'username' => $row['username'],
                        'role' => $row['role']
                    ]
                ]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Credenciales incorrectas. Verificá tu usuario y contraseña.'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Usuario no encontrado o cuenta inactiva.'
            ]);
        }

        $stmt->close();

    } catch (Exception $e) {
        error_log("Error en login.php: " . $e->getMessage());
        echo json_encode([
            'success' => false, 
            'message' => 'Error interno del servidor. Inténtalo más tarde.'
        ]);
    } finally {
        if (isset($conn)) {
            $conn->close();
        }
    }
    exit;
}

// Si no es POST, mostrar el formulario de login con el estilo del login.html
?>

<div class="login-layout">
    <div class="login-card">
        <h1 class="login-title" id="pageTitle">Iniciar Sesión</h1>
        
        <div id="messageContainer"></div>
        
        <!-- Formulario de Login -->
        <form class="login-form" id="loginForm">
            <div class="field">
                <label for="login_username" class="login-label">
                    <span class="ico">👤</span> Nombre de usuario
                </label>
                <input type="text" id="login_username" name="username" class="login-input" required autocomplete="username">
            </div>
            
            <div class="field">
                <label for="login_password" class="login-label">
                    <span class="ico">🔒</span> Contraseña
                </label>
                <div class="password-group">
                    <input type="password" id="login_password" name="password" class="login-input" required autocomplete="current-password">
                    <button type="button" class="toggle-pass" onclick="togglePassword('login_password')">👁️</button>
                </div>
            </div>
            
            <div class="actions">
                <button type="submit" class="btn primary">Iniciar Sesión</button>
                <button type="button" class="btn ghost" onclick="switchMode('register')">Registrarse</button>
            </div>
        </form>
        
        <!-- Formulario de Registro -->
        <form class="login-form" id="registerForm" style="display: none;">
            <div class="field">
                <label for="reg_username" class="login-label">
                    <span class="ico">👤</span> Nombre de usuario
                </label>
                <input type="text" id="reg_username" name="username" class="login-input" required autocomplete="username">
            </div>
            
            <div class="field">
                <label for="reg_password" class="login-label">
                    <span class="ico">🔒</span> Contraseña
                </label>
                <div class="password-group">
                    <input type="password" id="reg_password" name="password" class="login-input" required autocomplete="new-password">
                    <button type="button" class="toggle-pass" onclick="togglePassword('reg_password')">👁️</button>
                </div>
            </div>
            
            <div class="actions">
                <button type="submit" class="btn primary">Registrarse</button>
                <button type="button" class="btn ghost" onclick="switchMode('login')">Ya tengo cuenta</button>
            </div>
        </form>
        
        <div style="text-align: center; margin-top: 20px;">
            <button onclick="loadSection('home')" style="color: #8C031C; text-decoration: none; font-weight: 600; background: none; border: none; cursor: pointer; font-size: 16px;">← Volver al inicio</button>
        </div>
    </div>
</div>

<script>
let currentMode = 'login';

function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const button = input.nextElementSibling;
    
    if (input.type === 'password') {
        input.type = 'text';
        button.textContent = '🙈';
    } else {
        input.type = 'password';
        button.textContent = '👁️';
    }
}

function switchMode(newMode) {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const title = document.getElementById('pageTitle');
    
    currentMode = newMode;
    
    if (newMode === 'login') {
        loginForm.style.display = '';
        registerForm.style.display = 'none';
        title.textContent = 'Iniciar Sesión';
        document.getElementById('reg_username').value = '';
        document.getElementById('reg_password').value = '';
    } else {
        loginForm.style.display = 'none';
        registerForm.style.display = '';
        title.textContent = 'Registrarse';
        document.getElementById('login_username').value = '';
        document.getElementById('login_password').value = '';
    }
    
    clearMessages();
}

function showMessage(message, type) {
    const container = document.getElementById('messageContainer');
    const messageDiv = document.createElement('div');
    
    if (type === 'error') {
        messageDiv.style.cssText = 'background: #ffebee; color: #c62828; padding: 10px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #c62828;';
    } else {
        messageDiv.style.cssText = 'background: #e8f5e8; color: #2e7d32; padding: 10px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #2e7d32;';
    }
    
    messageDiv.textContent = message;
    container.innerHTML = '';
    container.appendChild(messageDiv);
}

function clearMessages() {
    document.getElementById('messageContainer').innerHTML = '';
}

async function handleLogin(username, password) {
    try {
        const response = await fetch('login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ username, password })
        });

        const data = await response.json();
        
        if (data.success) {
            showMessage(data.message, 'success');
            // Usar las funciones globales del script principal
            if (typeof handleLoginSuccess === 'function') {
                handleLoginSuccess(data.user);
            }
        } else {
            showMessage(data.message, 'error');
        }
    } catch (error) {
        showMessage('Error de conexión. Por favor, intentá nuevamente.', 'error');
    }
}

async function handleRegister(username, password) {
    try {
        const response = await fetch('registro.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ username, password })
        });

        const data = await response.json();
        
        if (data.success) {
            showMessage(data.message, 'success');
            setTimeout(() => {
                switchMode('login');
            }, 2000);
        } else {
            showMessage(data.message, 'error');
        }
    } catch (error) {
        showMessage('Error de conexión. Por favor, intentá nuevamente.', 'error');
    }
}

// Event listeners para los formularios
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const username = document.getElementById('login_username').value.trim();
    const password = document.getElementById('login_password').value;
    
    if (!username || !password) {
        showMessage('Por favor, completá todos los campos.', 'error');
        return;
    }
    
    handleLogin(username, password);
});

document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const username = document.getElementById('reg_username').value.trim();
    const password = document.getElementById('reg_password').value;
    
    if (!username || !password) {
        showMessage('Por favor, completá todos los campos.', 'error');
        return;
    }
    
    handleRegister(username, password);
});
</script>
