<?php
session_start();  // Iniciar la sesión
include("conexion.php");

if (!empty($_POST)) {
    $usuario = mysqli_real_escape_string($conn, $_POST['user']);
    $contraseña = mysqli_real_escape_string($conn, $_POST['pass']);
    
    // Mostrar para depuración
    echo "Usuario: " . $usuario . "<br>";
    echo "Contraseña ingresada: " . $contraseña . "<br>";

    // Consulta para verificar el usuario y la contraseña en la tabla persona
    $sql = "SELECT documento, tipo_persona, contraseña FROM persona WHERE usuario = '$usuario'";
    $resultado = $conn->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $documento = $row['documento'];
        $tipo_persona = $row['tipo_persona'];
        $contraseña_guardada = $row['contraseña'];

        // Mostrar para depuración
        echo "Documento: " . $documento . "<br>";
        echo "Tipo de Persona: " . $tipo_persona . "<br>";
        echo "Contraseña guardada en la base de datos: " . $contraseña_guardada . "<br>";

        // Comparar la contraseña en texto plano
        if ($contraseña == $contraseña_guardada) {
            $_SESSION['documento'] = $documento;
            $_SESSION['tipo_persona'] = $tipo_persona;

            // Redirigir al inicio
            header("Location: inicio.php");
            exit();
        } else {
            // Contraseña incorrecta
            echo "<script>
                    alert('Usuario o contraseña incorrecto');
                    window.location = 'login.php';
                  </script>";
        }
    } else {
        // Usuario no encontrado
        echo "<script>
                alert('Usuario o contraseña incorrecto');
                window.location = 'login.php';
              </script>";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="container">
        <div class="left-side">
            <div class="logo">
                <img src="imagenes/logo.jpg" alt="logo">
            </div>
        </div>

        <div class="right-side">
            <div class="login-form">
                <h2>Iniciar sesión</h2>
                <form action="" method="POST">
                    <div class="input-group">
                        <label for="user">Usuario:</label>
                        <input type="text" name="user" id="user" required>
                    </div>
                    <div class="input-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" name="pass" id="pass" required>
                    </div>
                    <input type="submit" name="enviar" id="enviar" value="Entrar">
                </form>
            </div>
        </div>
    </div>

</body>
</html>

