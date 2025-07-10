console.log("BASE_URL será configurada en dashFooter.php");

// Función principal para cargar vistas
window.loadView = window.loadView || function(viewName) {
    console.log('loadView llamado desde dashboard de root:', viewName);
    
    // Usar la función loadView global si está disponible
    if (typeof window.loadView === 'function' && window.loadView !== arguments.callee) {
        window.loadView(viewName);
    } else {
        console.error('loadView no está disponible');
        // Fallback: redirigir a la página
        const url = `${window.location.origin}${window.location.pathname}?view=${viewName.replace('/', '&action=')}`;
        window.location.href = url;
    }
}; 