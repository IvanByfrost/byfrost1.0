window.loadView = function(viewName) {
    const target = document.getElementById("mainContent");
    if (!target) {
        console.error("Elemento con id 'mainContent' no encontrado.");
        return;
    }

    console.log("Cargando vista:", viewName);
    fetch(`${BASE_URL}app/scripts/routerView.php?view=${viewName}`)
        .then(response => {
            if (!response.ok) throw new Error("Vista no encontrada.");
            return response.text();
        })
        .then(html => {
            target.innerHTML = html;
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
};