console.log("Script cargado");
function loadView(url) {
  fetch('<?php echo url; ?>' + url)
    .then(response => {
      if (!response.ok) {
        throw new Error('Error al cargar la vista');
      }
      return response.text();
    })
    .then(html => {
      document.getElementById('mainContent').innerHTML = html;
      lucide.createIcons(); // Si tu vista tiene íconos
    })
    .catch(error => {
      document.getElementById('mainContent').innerHTML = `<p style="color:red;">${error}</p>`;
    });
}