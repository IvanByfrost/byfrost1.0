<?php
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
  <link rel="stylesheet" href="<?php echo url . rq ?>css/loading.css">
  <link rel="icon" type="image/x-icon" href="<?php echo url . rq ?>img/favicon.png">
</head>
<script>
  setTimeout(() => {
    window.location.href = "<?php echo url . rq . $_SESSION['ByFrost_redirect']; ?>";
  }, 3000); // Redirige en 3 segundos
</script>

<body>
  <div class="loading-cont">
    <img class="BYFROST" src="<?php echo url . rq ?>img/Byfrost-logo.svg" alt="loading-logo" />
    <h1>BYFROST</h1>
    <p>¡Bienvenido de vuelta, <span id="userName">Iván</span>!</p>
  </div>
</body>

</html>