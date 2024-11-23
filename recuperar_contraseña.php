<?php
include("conexion.php"); // Asegúrate de incluir la conexión a tu base de datos
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Cargar el autoload generado por Composer

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $documento = mysqli_real_escape_string($conn, $_POST['documento']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $confirmar_correo = mysqli_real_escape_string($conn, $_POST['confirmar_correo']);

    // Validar que los correos coincidan
    if ($correo !== $confirmar_correo) {
        echo "<script>
                alert('Los correos electrónicos no coinciden. Por favor, verifica.');
                window.history.back();
              </script>";
        exit();
    }

    // Verificar si el documento existe en la base de datos
    $sql = "SELECT * FROM persona WHERE documento = '$documento' AND correo = '$correo'";
    $resultado = $conn->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        // El documento y correo existen, procesar el correo
        $row = $resultado->fetch_assoc();

        // Crear un token único
        $token = md5($documento . time());

        // Crear el enlace para restablecer la contraseña
        $url = "http://localhost/Plataforma__infantil/restablecer_contraseña.php?token=" . $token;

        // Actualizar el token en la base de datos (opcional, dependiendo de tu implementación)
        $sql_actualizar_token = "UPDATE persona SET token = '$token' WHERE documento = '$documento'";
        $conn->query($sql_actualizar_token);

        // Usar PHPMailer para enviar el correo
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Para Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'tommyrojas2013@gmail.com'; // Tu correo
            $mail->Password = 'sjev dxfx zbbr ynso'; // La contraseña de la aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Destinatarios
            $mail->setFrom('tommyrojas2013@gmail.com', 'Ximena Sarmiento');
            $mail->addAddress($correo, 'Destinatario');

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de contraseña';
            $mail->Body    = "Hola, hemos recibido una solicitud para recuperar tu contraseña. Si no solicitaste este cambio, ignora este mensaje. De lo contrario, haz clic en el siguiente enlace para restablecer tu contraseña:<br><br>";
            $mail->Body   .= "<a href='$url'>$url</a><br><br>";
            $mail->Body   .= "Este enlace es válido por 24 horas.";

            // Enviar el correo
            $mail->send();
            echo "<script>
                    alert('Hemos enviado un enlace de recuperación al correo proporcionado.');
                    window.location = 'login.php';
                  </script>";
        } catch (Exception $e) {
            echo "El mensaje no pudo ser enviado. Error de correo: {$mail->ErrorInfo}";
        }
    } else {
        // Documento o correo no encontrado
        echo "<script>
                alert('El número de documento o correo no están registrados.');
                window.history.back();
              </script>";
    }
}
?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="stilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

</head>
<body>
<div class="background-image">
    <div class="button-container2">
        <!-- Botón de perfil con solo el icono inicialmente -->
        <button class="toggle-door2" onclick="window.location.href='login.php'">
            <i class="fa-solid fa-arrow-left"></i><span id="atras">Regresar</span>
        </button>
        <div class="right-buttons2">
            <h1>Colegio Liceo Moderno<h1>
        </div>
    </div>  

  <!-- Aquí colocamos la imagen dentro de un contenedor -->

  <div class="form-container2">
        <h1>¿Has olvidado tu contraseña?</h1>
        <br>
        <!-- Formulario -->
        <form id="recuperarForm" action="recuperar_contraseña.php" method="POST" onsubmit="return validarCorreos()">
            <div class="field-group2">
                <label for="documento">Número de documento:</label>
                <input type="text" id="documento" name="documento" required>
            </div>
            <div class="field-group2">
                <label for="correo">Correo electrónico:</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            <div class="field-group2">
                <label for="confirmar_correo">Confirmar correo electrónico:</label>
                <input type="email" id="confirmar_correo" name="confirmar_correo" required>
            </div>
            <div class="botones-formulario">
                <button id="enviar" type="submit">Enviar mensaje al correo</button>
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

       


    </div>
</div>

<script>
    function validarCorreos() {
        const correo = document.getElementById('correo').value;
        const confirmarCorreo = document.getElementById('confirmar_correo').value;

        if (correo !== confirmarCorreo) {
            alert('Los correos electrónicos no coinciden. Por favor, verifica.');
            return false; // Evita el envío del formulario
        }
        return true; // Permite el envío del formulario
    }
</script>

</body>
</html>