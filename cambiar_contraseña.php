<?php
include("header.php");
?>

<?php

// Procesamiento del formulario al enviar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $documento = $_SESSION['documento']; // Obtenemos el documento del usuario logueado
    $current_password = mysqli_real_escape_string($conn, $_POST['current_password']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Validar que las nuevas contraseñas coincidan
    if ($new_password !== $confirm_password) {
        $error_message = "La nueva contraseña y la confirmación no coinciden.";
    } else {
        // Consultar la contraseña actual
        $sql = "SELECT contraseña FROM persona WHERE documento = '$documento'";
        $resultado = $conn->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            $contraseña_actual = $row['contraseña'];

            // Verificar que la contraseña actual sea correcta
            if ($current_password === $contraseña_actual) {
                // Actualizar con la nueva contraseña
                $sql_update = "UPDATE persona SET contraseña = '$new_password' WHERE documento = '$documento'";
                if ($conn->query($sql_update)) {
                    $success_message = "Contraseña actualizada exitosamente.";
                } else {
                    $error_message = "Hubo un error al actualizar la contraseña. Intenta nuevamente.";
                }
            } else {
                $error_message = "La contraseña actual no es correcta.";
            }
        } else {
            $error_message = "Usuario no encontrado.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil | Cambiar contraseña</title>
    <link rel="stylesheet" href="stilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

</head>
<body>
<div class="background-image">
    <div class="button-container">
        <!-- Botón de perfil con solo el icono inicialmente -->
        <button class="toggle-door" onclick="toggleProfile()">
            <i class="fas fa-bars"></i><span id="userName" style="display: none;"><?php echo $nombre; ?></span>
        </button>
        <div class="right-buttons">
            <h1><button onclick="window.location.href='inicio.php'">Inicio</button></h1>
            <h1><button>Primero</button><h1>
            <h1><button>Segundo</button><h1>
            <h1> <button>Tercero</button><h1>
        </div>
    </div>  

  <!-- Aquí colocamos la imagen dentro de un contenedor -->
  <div class="form-container2">
        <h1>Cambiar contraseña</h1>
        <!-- Mostrar mensajes de error o éxito -->
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php elseif (isset($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <br>
        <!-- Formulario -->
        <form action="" method="POST">
            <div class="field-group2">
                <label for="current_password">Contraseña actual:</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            <div class="field-group2">
                <label for="new_password">Nueva contraseña:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="field-group2">
                <label for="confirm_password">Confirmar nueva contraseña:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="botones-formulario">
                <button id="enviar" type="submit">Guardar</button>
                <button onclick="window.location.href='perfil.php'" id="cancelar" type="button">Regresar</button>
            </div>
        </form>
        <br>
        <div class="message">
            Asegúrate de que la nueva contraseña sea segura.
        </div>
    </div>

    <!-- Contenedor de perfil que se muestra al hacer clic en el botón -->
    <div class="door-content" id="doorContent" style="display: none;">
<br><br>
        <div class="button-group">
            <!--    <button onclick="showAsignaturas()">Asignaturas</button> -->
            <button onclick="window.location.href='perfil.php'">
                <i class="fas fa-user"></i>&nbsp;&nbsp;Mi perfil
            </button>
        </div>

       
        <div class="bottom-buttons">
            <?php if ($tipo_persona == 'admin'): ?>
                <button class="icon-button" onclick="window.location.href='registro.php'">
                    <i class="fas fa-user-plus"></i> Ir a Registro
                </button>
            <?php endif; ?>
            <button class="icon-button" onclick="window.location.href='login.php'">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </button>
        </div>

    </div>
</div>


   <script>
    // Función para mostrar u ocultar el perfil completo
    function toggleProfile() {
        const userName = document.getElementById('userName');
        const doorContent = document.getElementById('doorContent');

        if (userName.style.display === 'none') {
            userName.style.display = 'inline'; // Mostrar el nombre
            doorContent.style.display = 'block'; // Mostrar la información del perfil
        } else {
            userName.style.display = 'none'; // Ocultar el nombre
            doorContent.style.display = 'none'; // Ocultar la información del perfil
        }
    }
            
    function showAsignaturas() {
        alert("Mostrando las asignaturas del usuario...");
    }

    function showPerfilCompleto() {
        alert("Mostrando el perfil completo del usuario...");
    }
</script>

</body>
</html>