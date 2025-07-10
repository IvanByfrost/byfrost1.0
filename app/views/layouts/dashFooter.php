    <footer>
        <div class="copyright">
            <p>Byfrost &copy; 2026. Todos los derechos reservados.</p>
            <p>Diseñado por Byfrost Software.</p>
        </div>
        </div>
    </footer>
    
    <script>
        // Definir constantes de forma segura si no están definidas
        <?php
        // Asegurar que las constantes estén definidas
        if (!defined('ROOT')) {
            define('ROOT', dirname(dirname(dirname(__DIR__))));
        }
        
        if (!defined('url')) {
            define('url', 'http://localhost:8000/');
        }
        
        if (!defined('app')) {
            define('app', 'app/');
        }
        
        if (!defined('rq')) {
            define('rq', 'resources/');
        }
        
        // Obtener el rol del usuario de forma segura
        if (!isset($userRole)) {
            try {
                require_once ROOT . '/app/library/SessionManager.php';
                $sessionManager = new SessionManager();
                $userRole = $sessionManager->getUserRole();
            } catch (Exception $e) {
                $userRole = 'unknown';
            }
        }
        ?>
        
        const url = '<?php echo url; ?>';
        const BASE_URL = '<?php echo url; ?>';
        console.log('URL base configurada:', BASE_URL);
    </script>
    
    <script>
        window.USER_MANAGEMENT_BASE_URL = '<?php echo url; ?>';
    </script>
    
    <!-- Scripts externos -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Scripts locales -->
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/onlyNumber.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/toggles.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/loadView.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/sessionHandler.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/userSearch.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/roleHistory.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/schoolValidation.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/schoolForm.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/createSchool.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/activityDataTable.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/activityForm.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/activityActions.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/activityDashboard.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/userManagement.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/roleManagement.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/Uploadpicture.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/User.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/Principal.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/app.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/profileSettings.js"></script> 
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/payrollManagement.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/sidebarToggle.js"></script>
    
    <?php 
    // Cargar script específico del director si es necesario
    if ($userRole === 'director'): 
    ?>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/directorMetrics.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/directorCharts.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/directorCommunication.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/directorDashboard.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/dashboard.js"></script>
    <?php endif; ?>

    <script>
        // Inicializar Lucide
        lucide.createIcons();

        // Variable global con la URL base para JavaScript
        window.BYFROST_BASE_URL = '<?php echo url . app ?>';
        console.log('Base URL configurada:', window.BYFROST_BASE_URL);
        
        // Función de respaldo para loadView si no está disponible
        if (typeof loadView === 'undefined') {
            window.loadView = function(viewName) {
                console.log('loadView no disponible, redirigiendo a:', viewName);
                const url = `${BASE_URL}?view=${viewName.replace('/', '&action=')}`;
                window.location.href = url;
            };
        }
    </script>
    </body>
    </html>