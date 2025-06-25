document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.has-submenu > a').forEach(function (menuLink) {
        menuLink.addEventListener('click', function (e) {
            e.preventDefault(); // evita navegación si href="#"
            const parentLi = this.parentElement;
            parentLi.classList.toggle('active');
        });
    });

});

function toggleUserMenu() {
  const menu = document.querySelector('.user-menu-container');
  if (menu) {
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
  }
}

// Opcional: cerrar al hacer clic fuera
document.addEventListener('click', function(event) {
  const trigger = document.querySelector('.user-menu-trigger');
  const menu = document.querySelector('.user-menu-container');
  if (trigger && menu && !trigger.contains(event.target) && !menu.contains(event.target)) {
    menu.style.display = 'none';
  }
});