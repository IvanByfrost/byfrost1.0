<?php
// Asegurar constante ROOT
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/library/SessionHelper.php';
require_once ROOT . '/app/library/Validator.php';

// Obtener sesión y datos de usuario
$session = getSession();
$currentUser = $session->getCurrentUser();
$userName = $session->getUserFullName();
$userRole = ucfirst($session->getUserRole() ?: 'Sin rol');

// Debug opcional
error_log("dashHeader.php - Usuario: " . json_encode($currentUser));
error_log("dashHeader.php - Nombre: " . $userName);
error_log("dashHeader.php - Rol: " . $userRole);

// Head global
require_once ROOT . '/app/views/layouts/dashHead.php';
?>

<header>
    <div class="main-header">
        <a href="#">
            <img src="<?= url . app . rq ?>img/horizontal-logo.svg" alt="Byfrost Logo" width="200">
        </a>
    </div>

    <div class="user-menu">
        <div class="user-menu-trigger" onclick="toggleUserMenu(event)">
            <?php
            $profilePhoto = !empty($currentUser['profile_photo']) && file_exists(ROOT . '/app/resources/img/profiles/' . $currentUser['profile_photo'])
                ? url . 'app/resources/img/profiles/' . $currentUser['profile_photo']
                : url . app . rq . 'img/user-photo.png';
            ?>
            <img src="<?= $profilePhoto ?>" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; cursor: pointer;">
        </div>
        <div class="user-menu-container">
            <div style="text-align: center; padding: 10px;">
                <strong><?= htmlspecialchars($userName) ?></strong><br>
                <small><?= $userRole ?></small>
            </div>
            <hr>
            <div class="user-settings-submenu" style="padding: 0 10px 10px 10px;">
                <div style="font-weight: bold; margin-bottom: 5px;">Configuración</div>
                <button type="button" onclick="loadView('user/settingsRoles?section=recuperar'); closeUserMenu();" class="dropdown-btn">Recuperar contraseña</button>
                <button type="button" onclick="loadView('user/profileSettings'); closeUserMenu();" class="dropdown-btn">Mi perfil</button>
            </div>
            <form action="<?= url . app ?>processes/outProcess.php" method="post" style="margin: 0; padding: 10px;">
                <input type="hidden" name="csrf_token" value="<?= Validator::generateCSRFToken() ?>">
                <button type="submit" class="logout-btn">Cerrar sesión</button>
            </form>
        </div>
    </div>
</header>


<style>
.user-menu {
  position: relative;
  display: inline-block;
}
.user-menu-container {
  display: none;
  position: absolute;
  right: 0;
  top: 50px;
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 8px;
  min-width: 220px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.07);
  z-index: 1000;
}
.user-menu-container.active {
  display: block;
}
.dropdown-btn {
  width: 100%;
  background: #0284c7;
  color: #fff;
  border: none;
  padding: 10px 0;
  margin-bottom: 8px;
  border-radius: 5px;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.2s;
  text-align: center;
}
.dropdown-btn:last-child {
  margin-bottom: 0;
}
.dropdown-btn:hover {
  background: #0369a1;
}
.logout-btn {
  width: 100%;
  background: #e3342f;
  color: #fff;
  border: none;
  padding: 10px 0;
  border-radius: 5px;
  font-size: 1rem;
  cursor: pointer;
  font-weight: bold;
  margin-top: 8px;
  transition: background 0.2s;
  text-align: center;
}
.logout-btn:hover {
  background: #cc1f1a;
}
</style>

<script>
function toggleUserMenu(event) {
  event.stopPropagation();
  const menu = document.querySelector('.user-menu-container');
  menu.classList.toggle('active');
}
document.addEventListener('click', function(event) {
  const menu = document.querySelector('.user-menu-container');
  const trigger = document.querySelector('.user-menu-trigger');
  if (!menu.contains(event.target) && !trigger.contains(event.target)) {
    menu.classList.remove('active');
  }
});
function closeUserMenu() {
  document.querySelector('.user-menu-container').classList.remove('active');
}
</script>