import { showSuccessMessage, showErrorMessage } from './uiHelpers.js';
import { loadView } from './viewLoader.js';

export function handleAjaxForm(form) {
    const formData = new FormData(form);
    const url = form.action || window.location.href;

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.redirect) {
                loadView(data.redirect.view, data.redirect.action, '#mainContent', true);
            } else if (data.message) {
                showSuccessMessage(data.message);
            }
        } else {
            showErrorMessage(data.message || 'Error en el formulario');
        }
    })
    .catch(error => {
        console.error('Error en formulario AJAX:', error);
        showErrorMessage('Error al procesar el formulario');
    });
}
