import './bootstrap';
import AOS from 'aos';
import 'aos/dist/aos.css';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Inicializar AOS
document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
        duration: 800, // Duración de la animación en milisegundos 
        once: true, // Animar solo una vez al hacer scroll
    });
});