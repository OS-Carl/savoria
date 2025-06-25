document.addEventListener('DOMContentLoaded', function() {
    const slideshowInner = document.querySelector('.slideshow-background-inner');
    const images = slideshowInner.querySelectorAll('img');
    const totalImages = images.length;
    let currentImageIndex = 0; // Para el modo de cambio automático (si lo implementas)

    // Opción 1: Cambio de imagen en base al scroll (sencillo)
    // Esto es un ejemplo. Un slideshow que 'cambia' con el scroll
    // a menudo significa que las imágenes se desplazan o se hacen fade in/out
    // a diferentes puntos de scroll.

    // Para un efecto simple de paralaje o movimiento con el scroll:
    window.addEventListener('scroll', function() {
        const scrollY = window.scrollY; // Cuánto ha bajado el usuario
        
        // Efecto de paralaje: mueve el contenedor del slideshow en la dirección opuesta al scroll
        // Ajusta el '0.3' para controlar la velocidad del paralaje
        slideshowInner.style.transform = `translateY(${scrollY * 0}px)`;

        // --- Lógica para CAMBIAR IMAGEN en base al scroll (ejemplo más avanzado) ---
        // Esto es más complejo y depende de cómo quieras que "cambien" las imágenes.
        // Podrías dividir la altura total del documento en secciones y cambiar la imagen
        // cuando se entra en una nueva sección.

        // Ejemplo: Cambiar imagen cada 1000px de scroll (muy básico)
        // const scrollSectionHeight = 1000;
        // const newImageIndex = Math.floor(scrollY / scrollSectionHeight) % totalImages;
        
        // if (newImageIndex !== currentImageIndex) {
        //     images[currentImageIndex].classList.remove('active');
        //     images[newImageIndex].classList.add('active');
        //     currentImageIndex = newImageIndex;
        // }
        // ------------------------------------------------------------------------
    });

    // Opción 2: Autoplay de slideshow (si también lo quieres)
    // Esto haría que las imágenes cambien automáticamente después de un tiempo,
    // independientemente del scroll. Puedes usarlo en combinación o por separado.

    const transitionInterval = 4000; // Cambiar cada 5 segundos (5000 ms)

    function showNextImage() {
        // Quita la clase 'active' de la imagen actual
        images[currentImageIndex].classList.remove('active');

        // Calcula el índice de la siguiente imagen
        currentImageIndex = (currentImageIndex + 1) % totalImages;

        // Añade la clase 'active' a la nueva imagen
        images[currentImageIndex].classList.add('active');
    }

    // Inicializa el slideshow mostrando la primera imagen
    if (images.length > 0) {
        images[0].classList.add('active');
        // Si quieres autoplay, descomenta la siguiente línea:
         setInterval(showNextImage, transitionInterval);
    }
});