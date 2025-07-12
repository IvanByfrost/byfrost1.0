function handleAjaxForm(event, form) {
    event.preventDefault();

    const formData = new FormData(form);
    const url = form.action || window.location.href;

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (data.redirect) {
                if (typeof data.redirect === 'string') {
                    loadView(data.redirect, null, '#mainContent', true, {});
                } else {
                    loadView(
                        data.redirect.view,
                        data.redirect.action || null,
                        '#mainContent',
                        true,
                        data.redirect.params || {}
                    );
                }
            } else if (data.message) {
                if (typeof showSuccessMessage === 'function') {
                    showSuccessMessage(data.message);
                } else {
                    alert(data.message);
                }
            }
        } else {
            if (typeof showErrorMessage === 'function') {
                showErrorMessage(data.message || 'Error en el formulario');
            } else {
                alert(data.message || 'Error en el formulario');
            }
        }
    })
    .catch(error => {
        console.error('Error en formulario AJAX:', error);
        if (typeof showErrorMessage === 'function') {
            showErrorMessage('Error al procesar el formulario');
        } else {
            alert('Error al procesar el formulario');
        }
    });
}
