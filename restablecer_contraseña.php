<?php
include("conexion.php");

// Verificar si el token está en la URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verificar si el token es válido
    $sql = "SELECT * FROM persona WHERE token = '$token'";
    $resultado = $conn->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        // Procesar la solicitud POST para restablecer la contraseña
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener los datos del formulario
            $nueva_contraseña = mysqli_real_escape_string($conn, $_POST['nueva_contraseña']);
            $confirmar_contraseña = mysqli_real_escape_string($conn, $_POST['confirmar_contraseña']);

            // Verificar si las contraseñas coinciden
            if ($nueva_contraseña !== $confirmar_contraseña) {
                echo "<script>
                        alert('Las contraseñas no coinciden.');
                        window.history.back();
                      </script>";
                exit();
            }

            // Actualizar la contraseña y limpiar el token
            $sql_actualizar = "UPDATE persona SET contraseña = '$nueva_contraseña', token = NULL WHERE token = '$token'";
            if ($conn->query($sql_actualizar)) {
                echo "<script>
                        alert('Tu contraseña ha sido restablecida con éxito.');
                        window.location = 'login.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Error al restablecer la contraseña. Inténtalo de nuevo.');
                        window.history.back();
                      </script>";
            }
        }
    } else {
        // Token no válido
        echo "<script>
                alert('El enlace de restablecimiento ha expirado o no es válido.');
                window.location = 'login.php';
              </script>";
    }
} else {
    // Redirigir si no hay token en la URL
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña</title>
    <link rel="stylesheet" href="stilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="background-image">
    <div class="button-container2">
        <!-- Botón de regresar -->
        <button class="toggle-door2" onclick="window.location.href='recuperar_contraseña.php'">
            <i class="fa-solid fa-arrow-left"></i><span id="atras">Regresar</span>
        </button>
        <div class="right-buttons2">
            <h1>Colegio Liceo Moderno<h1>
        </div>
    </div>  

    <div class="form-container2">
        <h1>Restablecer contraseña</h1>
        <form action="" method="POST">
            <!-- Token oculto -->
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <!-- Campo de nueva contraseña -->
            <div class="field-group2">
                <label for="nueva_contraseña">Nueva Contraseña:</label>
                <input type="password" id="nueva_contraseña" name="nueva_contraseña" required>
            </div>

            <!-- Campo para confirmar contraseña -->
            <div class="field-group2">
                <label for="confirmar_contraseña">Confirmar Contraseña:</label>
                <input type="password" id="confirmar_contraseña" name="confirmar_contraseña" required>
            </div>

            <!-- Botón para restablecer contraseña -->
            <div class="botones-formulario">
                <button id="enviar" type="submit">Restablecer Contraseña</button>
            </div>
        </form>
        <br>
        <div class="message">
            Asegúrate de que la contraseña sea segura.
        </div>
    </div>
</div>
</body>
</html>
