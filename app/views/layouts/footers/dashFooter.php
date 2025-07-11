<footer>
    <div class="copyright">
        <p>Byfrost &copy; 2026. Todos los derechos reservados.</p>
        <p>Diseñado por Byfrost Software.</p>
    </div>
</footer>

<script>
    window.BASE_URL = '<?= htmlspecialchars(url) ?>';
    console.log('BASE_URL configurada:', window.BASE_URL);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<script>
    if (window.lucide && typeof lucide.createIcons === 'function') {
        lucide.createIcons();
    } else {
        console.warn('Lucide no se cargó correctamente.');
    }
</script>

<script type="module">
    import { loadView } from '<?= htmlspecialchars(url . app . rq) ?>js/loadView.js';

    document.addEventListener('DOMContentLoaded', () => {
        loadView('dashboard');
    });
</script>

</body>
</html>
