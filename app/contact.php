<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Formulario de Contacto</title>
  <title>Byfrost</title>
  <link rel="stylesheet" href="css\header.css">
  <link rel="stylesheet" href="css\features.css">
  <link rel="stylesheet" href="css\footer.css">
  <link rel="stylesheet" href="css\contact.css">
  <link rel="stylesheet" href="css\slider.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,200,0,0">
</head>
<header>
    <div class="main-header">
    <div class="logo-header">
        <a href="index.php">
            <img src="img\horizontal-logo.svg" alt="Byfrost Logo" width="200">
        </a>
    </div>

    <div class="menu-bar">
        <a href="plans.htm" class="btn-menu">Planes</a>
        <a href="contact.php" class="btn-menu">Contáctenos</a>
        <a href="faq.html" class="btn-menu">FAQ</a>
    </div>

    <a href="login.php">
        <div class="login-bttn">
    <img src="img\user-icon.svg" alt="User Icon" width="30"> 
    Iniciar sesión
        </div>
    </a>
</div>
</header>
<body>
  <div class="form-container">
    <h2>Contáctanos</h2>
    <form action="#" method="POST">
      <div class="form-group">
        <label for="nombre">Nombre completo *</label>
        <input type="text" id="nombre" name="nombre" required minlength="3" maxlength="50" placeholder="Tu nombre">
      </div>

      <div class="form-group">
        <label for="email">Correo electrónico *</label>
        <input type="email" id="email" name="email" required placeholder="ejemplo@correo.com">
      </div>

      <div class="form-group">
        <label for="telefono">Teléfono</label>
        <input type="tel" id="telefono" name="telefono" pattern="[0-9]{10}" placeholder="10 dígitos">
      </div>

      <div class="form-group">
        <label for="asunto">Asunto *</label>
        <select id="asunto" name="asunto" required>
          <option value="">Seleccione una opción</option>
          <option value="consulta">Consulta general</option>
          <option value="soporte">Soporte técnico</option>
          <option value="sugerencia">Sugerencia</option>
        </select>
      </div>

      <div class="form-group">
        <label for="mensaje">Mensaje *</label>
        <textarea id="mensaje" name="mensaje" rows="5" required maxlength="500" placeholder="Escribe tu mensaje aquí..."></textarea>
      </div>

      <button type="submit" class="submit-btn">Enviar mensaje</button>
    </form>
  </div>
</body>
<footer>
        <div class="container">
            <div class="footer-content">
                <div class="about">
                    <h2>Nosotros</h2>
                    <p>Nuestra solución tecnológica innovadora propone transformar la gestión administrativa de las instituciones educativas, ofreciendo una plataforma intuitiva y sostenible.</p>
                </div>
        <div class="contact">
                    <h2>Contacto</h2>
                    <p>Cra 7 # 98-25, Bogotá, Colombia</p>
                    <p>(601) 7886590</p>
                    <p>(601) 4567890</p>
                    <a href="www.byfrost.com.co">www.byfrost.com.co</a>
                    <p>info@byfrost.com</p>
                </div>
        <div class="site-map">
                    <p><a href="#">Inicio</a></p>
                    <p><a href="plans.htm">Planes</a></p>
                    <p><a href="contact.htm">Contáctenos</a></p>
                    <p><a href="faq.htm">FAQ</a></p>
                    <p><a href="site-map.htm">Mapa del sitio</a></p>
                </div>
            </div>
    <div class = "copyright">
            <p>Byfrost &copy; 2026. Todos los derechos reservados.</p>
            <p>Diseñado por Byfrost Software.</p>
        </div>
    </div>
    </footer>
</html>