console.log("BASE_URL será configurada en dashFooter.php");

// Función principal para cargar vistas
// No sobrescribir loadView si ya está disponible desde loadView.js
if (typeof window.loadView === 'undefined') {
    window.loadView = function(viewName) {
        console.log('loadView llamado desde dashboard del estudiante:', viewName);
        
        // Fallback: redirigir a la página
        const url = `${window.location.origin}${window.location.pathname}?view=${viewName.replace('/', '&action=')}`;
        window.location.href = url;
    };
} 