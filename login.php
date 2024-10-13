<?php
session_start();  // Iniciar la sesión
include("conexion.php");

if (!empty($_POST)) {
    $usuario = mysqli_real_escape_string($conn, $_POST['user']);
    $contraseña = mysqli_real_escape_string($conn, $_POST['pass']);
    $contraseña_encriptada = sha1($contraseña);  // Encriptar la contraseña

    // Mostrar para depurar
    echo "Usuario: " . $usuario . "<br>";
    echo "Contraseña encriptada: " . $contraseña_encriptada . "<br>";

    $sql = "SELECT id FROM persona WHERE usuario = '$usuario' AND contraseña = '$contraseña_encriptada'";
    $resultado = $conn->query($sql);

    // Depuración de la consulta SQL
    if ($resultado) {
        echo "Consulta SQL exitosa.<br>";
        echo "Número de filas encontradas: " . $resultado->num_rows . "<br>";
        
        if ($resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            $_SESSION['id_persona'] = $row['id'];
            $_SESSION['usuario'] = $usuario;  // Almacenar el nombre de usuario en la sesión
            header("Location: inicio.php");
            exit();
        } else {
            echo "<script>
                    alert('Usuario o contraseña incorrecto');
                    window.location = 'login.php';
                  </script>";
        }
    } else {
        echo "Error en la consulta SQL: " . $conn->error; // Muestra cualquier error de la consulta
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

