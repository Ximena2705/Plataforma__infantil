<?php
session_start();  // Iniciar la sesión

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
    // Recuperar la información del usuario de la sesión
    if (isset($_SESSION['nombre1'])) {
        $nombre1 = htmlspecialchars($_SESSION['nombre1'], ENT_QUOTES, 'UTF-8');
        $nombre2 = htmlspecialchars($_SESSION['nombre2'], ENT_QUOTES, 'UTF-8');
        $apellido1 = htmlspecialchars($_SESSION['apellido1'], ENT_QUOTES, 'UTF-8');
        $apellido2 = htmlspecialchars($_SESSION['apellido2'], ENT_QUOTES, 'UTF-8');
        echo "<h3>Bienvenido, $nombre1 $nombre2 $apellido1 $apellido2</h3>";
    } else {
        echo "<h3>.</h3>"; // Mensaje predeterminado si no hay información del usuario
    }
    ?>
    <section>
        <a href="login.php">Cerrar sesión</a>
    </section>
</body>
</html>

