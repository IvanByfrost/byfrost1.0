console.log("Script onlyNumber.js cargado");

// Función mejorada que funciona con jQuery y sin jQuery
function onlyNumbers(id, value) {
    var input;
    
    // Intentar usar jQuery si está disponible
    if (typeof $ !== 'undefined' && $.fn && $.fn.jquery) {
        input = $("#" + id);
        if (input.length > 0) {
            input.val(input.val().replace(/[^0-9]/g, ''));
        }
    } else {
        // Fallback sin jQuery
        input = document.getElementById(id);
        if (input) {
            input.value = input.value.replace(/[^0-9]/g, '');
        }
    }
}

// Asegurar que la función esté disponible globalmente
if (typeof window !== 'undefined') {
    window.onlyNumbers = onlyNumbers;
}

console.log("Función onlyNumbers disponible globalmente");