<?php
session_start();
if (!defined('ROOT')) {
  define('ROOT', dirname(__DIR__, 3));
}
require_once ROOT . '/config.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Byfrost - Cargando...</title>
  <link rel="stylesheet" href="<?php echo url . app . rq ?>css/loading.css">
  <link rel="icon" type="image/x-icon" href="<?php echo url . app . rq ?>img/favicon.png">
</head>
<script>
  setTimeout(() => {
    window.location.href = "<?php echo url . '?view=' . $_SESSION['ByFrost_redirect']; ?>";
  }, 3000); // Redirige en 3 segundos
</script>

<body>
  <div class="loading-cont">
    <img class="BYFROST" src="<?php echo url . app . rq ?>img/Byfrost-logo.svg" alt="loading-logo" />
    <h1>BYFROST</h1>
    <p>¡Hola de vuelta, <span id="userName"><?php echo htmlspecialchars($_SESSION["ByFrost_userName"] ?? 'Usuario'); ?></span>! ! Que tengas un buen día 😊</p>


  </div>
</body>

</html>