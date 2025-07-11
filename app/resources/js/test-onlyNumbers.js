// Script de prueba para verificar que onlyNumbers funciona correctamente
document.addEventListener('DOMContentLoaded', function() {
    console.log('Test onlyNumbers - DOM cargado');
    
    // Verificar que la función esté disponible
    if (typeof onlyNumbers === 'function') {
        console.log('✅ Función onlyNumbers está disponible');
    } else {
        console.error('❌ Función onlyNumbers NO está disponible');
    }
    
    // Verificar que jQuery esté disponible
    if (typeof $ !== 'undefined' && $.fn && $.fn.jquery) {
        console.log('✅ jQuery está disponible:', $.fn.jquery);
    } else {
        console.warn('⚠️ jQuery NO está disponible, usando fallback');
    }
    
    // Probar la función en campos existentes
    const testFields = [
        'search_director_query',
        'search_coordinator_query',
        'userDocument',
        'userPhone'
    ];
    
    testFields.forEach(function(fieldId) {
        const field = document.getElementById(fieldId);
        if (field) {
            console.log('✅ Campo encontrado:', fieldId);
            // Agregar evento de prueba
            field.addEventListener('input', function() {
                console.log('Campo', fieldId, 'cambiado a:', this.value);
            });
        } else {
            console.log('ℹ️ Campo no encontrado:', fieldId);
        }
    });
    
    console.log('Test onlyNumbers completado');
}); 