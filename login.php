<?php
session_start();  // Iniciar la sesión
include("conexion.php");

if (!empty($_POST)) {
    $usuario = mysqli_real_escape_string($conn, $_POST['user']);
    $contraseña = mysqli_real_escape_string($conn, $_POST['pass']);

    // Consulta para obtener la contraseña guardada en la base de datos en texto plano
    $sql = "SELECT id, nombre1, nombre2, apellido1, apellido2, contraseña FROM persona WHERE usuario = '$usuario'";

    $resultado = $conn->query($sql);

    if ($resultado) {
        // Verificar si se encontró un usuario con el nombre ingresado
        if ($resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            $contraseña_guardada = $row['contraseña']; // Contraseña guardada en la base de datos (en texto plano)

            // Comparar la contraseña ingresada con la almacenada (en texto plano)
            if ($contraseña === $contraseña_guardada) {
                // Si la contraseña es correcta, guardar la sesión
                $_SESSION['id_persona'] = $row['id'];
                $_SESSION['usuario'] = $usuario;  // Guardar el nombre de usuario en la sesión
                $_SESSION['nombre1'] = $row['nombre1'];
                $_SESSION['nombre2'] = $row['nombre2'];
                $_SESSION['apellido1'] = $row['apellido1'];
                $_SESSION['apellido2'] = $row['apellido2'];
                
                header("Location: inicio.php");
                exit(); // Terminar la ejecución para evitar seguir ejecutando código
            } else {
                // Si la contraseña es incorrecta
                echo "<script>
                        alert('Usuario o contraseña incorrecto');
                        window.location = 'login.php';
                      </script>";
            }
        } else {
            // Si no se encontró ningún usuario con ese nombre
            echo "<script>
                    alert('Usuario o contraseña incorrecto');
                    window.location = 'login.php';
                  </script>";
        }
    } else {
        // En caso de que haya un error en la consulta SQL
        echo "Error en la consulta SQL: " . $conn->error . "<br>";
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

