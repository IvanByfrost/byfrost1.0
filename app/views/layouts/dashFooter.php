    <footer>
        <div class="copyright">
            <p>Byfrost &copy; 2026. Todos los derechos reservados.</p>
            <p>Diseñado por Byfrost Software.</p>
        </div>
        </div>
    </footer>
    <script>
        const url = '<?php echo url; ?>';
        const BASE_URL = '<?php echo url; ?>';
        console.log('URL base configurada:', BASE_URL);
    </script>
    <script>
        window.USER_MANAGEMENT_BASE_URL = '<?php echo url; ?>';
    </script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/onlyNumber.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/toggles.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/loadView.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/sessionHandler.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/userSearch.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/createSchool.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/userManagement.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/roleManagement.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/Uploadpicture.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/User.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/Principal.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/app.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/profileSettings.js"></script> 
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/payrollManagement.js"></script>

    <script>
        // Inicializar Lucide
        lucide.createIcons();

        // Variable global con la URL base para JavaScript
        window.BYFROST_BASE_URL = '<?php echo url . app ?>';
        console.log('Base URL configurada:', window.BYFROST_BASE_URL);
    </script>
    </body>

    </html>