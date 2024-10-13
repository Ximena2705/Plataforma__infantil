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
    // Recuperar el nombre de usuario de la sesión
    if (isset($_SESSION['usuario'])) {
        $usuario = htmlspecialchars($_SESSION['usuario'], ENT_QUOTES, 'UTF-8');
        echo "<h3>Bienvenido, $usuario</h3>";
    } else {
        echo "<h3>Bienvenido</h3>"; // Mensaje predeterminado si no hay usuario
    }
    ?>
    <section>
        <h1>BIENVENIDO</h1>
        <a href="login.php">Cerrar sesión</a>
    </section>
</body>
</html>

