<?php
/**
 * Sección de contacto
 * Muestra información de contacto y formulario
 */
?>

<div class="contact-section">
    <h2 class="section-title">Contactanos</h2>
    
    <div class="contact-content">
        <div class="contact-info">
            <h3>Información de Contacto</h3>
            
            <div class="contact-item">
                <div class="contact-icon">📍</div>
                <div class="contact-details">
                    <h4>Dirección</h4>
                    <p>Av. Principal 123<br>Ciudad, País</p>
                </div>
            </div>
            
            <div class="contact-item">
                <div class="contact-icon">📞</div>
                <div class="contact-details">
                    <h4>Teléfono</h4>
                    <p>+1 (555) 123-4567</p>
                </div>
            </div>
            
            <div class="contact-item">
                <div class="contact-icon">✉️</div>
                <div class="contact-details">
                    <h4>Email</h4>
                    <p>info@romcoffe.com</p>
                </div>
            </div>
            
            <div class="contact-item">
                <div class="contact-icon">🕒</div>
                <div class="contact-details">
                    <h4>Horarios</h4>
                    <p>Lunes - Viernes: 7:00 - 20:00<br>
                    Sábados: 8:00 - 22:00<br>
                    Domingos: 9:00 - 18:00</p>
                </div>
            </div>
        </div>
        
        <div class="contact-form">
            <h3>Envíanos un mensaje</h3>
            <form id="contactForm">
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="subject">Asunto</label>
                    <input type="text" id="subject" name="subject" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Mensaje</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
            </form>
        </div>
    </div>
</div>

<style>
.contact-section {
    padding: 2rem;
    max-width: 1000px;
    margin: 0 auto;
}

.contact-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    margin-top: 2rem;
}

.contact-info h3,
.contact-form h3 {
    color: #8B4513;
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 2rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.contact-icon {
    font-size: 1.5rem;
    width: 40px;
    text-align: center;
}

.contact-details h4 {
    margin: 0 0 0.5rem 0;
    color: #333;
    font-size: 1.1rem;
}

.contact-details p {
    margin: 0;
    color: #666;
    line-height: 1.5;
}

.contact-form {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #333;
    font-weight: 500;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #8B4513;
}

.btn-primary {
    background: #8B4513;
    color: white;
    padding: 0.75rem 2rem;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-primary:hover {
    background: #6B3410;
}

@media (max-width: 768px) {
    .contact-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
}
</style>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Aquí podrías agregar lógica para enviar el formulario
    alert('¡Gracias por tu mensaje! Te contactaremos pronto.');
    this.reset();
});
</script>
