function loadView(viewName) {
    console.log("Cargando vista:", viewName);
    fetch("app/scripts/routerView.php?view=" + viewName)
        .then(response => {
            console.log("Respuesta recibida:", response);
            if (!response.ok) throw new Error("Vista no encontrada.");
            return response.text();
        })
        .then(html => {
            console.log("HTML recibido:", html);
            document.getElementById("mainContent").innerHTML = html;
        })
        .catch(err => {
            console.error("Error al cargar la vista:", err);
            if (typeof Swal !== "undefined") {
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo cargar la vista.',
                    icon: 'error',
                    timer: 4000
                });
            } else {
                alert('No se pudo cargar la vista.');
            }
        });
}