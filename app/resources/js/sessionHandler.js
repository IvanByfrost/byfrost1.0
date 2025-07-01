/**
 * SessionHandler.js - Maneja las respuestas de sesión expirada y errores de sesión
 * Muestra alertas de SweetAlert2 en lugar de mensajes simples
 */

// Interceptar todas las respuestas AJAX para manejar sesión expirada
$(document).ajaxComplete(function(event, xhr, settings) {
    try {
        // Verificar si la respuesta es JSON
        const contentType = xhr.getResponseHeader('Content-Type');
        if (contentType && contentType.includes('application/json')) {
            const response = JSON.parse(xhr.responseText);
            
            // Verificar si la respuesta indica que debe mostrar SweetAlert
            if (response.showSwal && response.swalConfig) {
                showSessionAlert(response.swalConfig, response.redirect);
                return;
            }
            
            // Verificar si es una respuesta de sesión expirada (compatibilidad con respuestas existentes)
            if (response.success === false && 
                (response.message && response.message.includes('Sesión expirada')) ||
                (response.message && response.message.includes('Error de sesión'))) {
                
                const swalConfig = {
                    icon: 'warning',
                    title: '¡Sesión Expirada!',
                    text: response.message || 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#3085d6'
                };
                
                showSessionAlert(swalConfig, response.redirect);
                return;
            }
        }
    } catch (error) {
        console.error('Error al procesar respuesta AJAX:', error);
    }
});

/**
 * Muestra una alerta de SweetAlert2 para sesión expirada
 * @param {Object} config - Configuración de SweetAlert2
 * @param {string} redirectUrl - URL de redirección después de cerrar la alerta
 */
function showSessionAlert(config, redirectUrl) {
    // Configuración por defecto
    const defaultConfig = {
        icon: 'warning',
        title: '¡Sesión Expirada!',
        text: 'Tu sesión ha expirado por inactividad. Por favor, inicia sesión nuevamente.',
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#3085d6',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showCloseButton: false
    };
    
    // Combinar configuración por defecto con la proporcionada
    const finalConfig = { ...defaultConfig, ...config };
    
    // Agregar callback para redirección
    finalConfig.confirmButtonText = finalConfig.confirmButtonText || 'Entendido';
    
    Swal.fire(finalConfig).then((result) => {
        if (result.isConfirmed) {
            // Redirigir a la página de login
            if (redirectUrl) {
                window.location.href = redirectUrl;
            } else {
                window.location.href = '/?view=index&action=login';
            }
        }
    });
}

/**
 * Función global para manejar errores de sesión desde cualquier parte del código
 * @param {Object} response - Respuesta del servidor
 */
function handleSessionResponse(response) {
    if (response && response.showSwal && response.swalConfig) {
        showSessionAlert(response.swalConfig, response.redirect);
        return true;
    }
    
    if (response && response.success === false && 
        response.message && 
        (response.message.includes('Sesión expirada') || response.message.includes('Error de sesión'))) {
        
        const swalConfig = {
            icon: 'warning',
            title: '¡Sesión Expirada!',
            text: response.message,
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#3085d6'
        };
        
        showSessionAlert(swalConfig, response.redirect);
        return true;
    }
    
    return false;
}

// Exportar funciones para uso global
window.handleSessionResponse = handleSessionResponse;
window.showSessionAlert = showSessionAlert; 