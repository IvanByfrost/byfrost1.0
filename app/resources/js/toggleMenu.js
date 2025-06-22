document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.has-submenu > a').forEach(function (menuLink) {
        menuLink.addEventListener('click', function (e) {
            e.preventDefault(); // evita navegación si href="#"
            const parentLi = this.parentElement;
            parentLi.classList.toggle('active');
        });
    });

});