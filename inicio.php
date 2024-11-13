<?php
session_start();
include("conexion.php");
include("header.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="stilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="background-image">
        <!-- Contenedor superior: botones y nombre -->
        <div class="top-container">
            <div class="button-container">
                <!-- Botón de perfil con solo el icono inicialmente -->
                <button class="toggle-door" onclick="toggleProfile()">
                    <i class="fas fa-user"></i> <span id="userName" style="display: none;"><?php echo $nombre_completo; ?></span>
                </button>
                <div class="right-buttons">
                    <h1><button>Primero</button></h1>
                    <h1><button>Segundo</button></h1>
                    <h1><button>Tercero</button></h1>
                </div>
            </div>
        </div>

        <!-- Contenedor inferior: contenido adicional -->
        <div class="bottom-container">
            <div class="door-content" id="doorContent" style="display: none;">
                <br><br>
                <div class="button-group">
                    <button onclick="showAsignaturas()">Asignaturas</button>
                    <button onclick="showPerfilCompleto()">Mi perfil</button>
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
            <div class="image-container">
                <img src="imagenes/fondo_bienvenida.jpg" alt="Descripción de la imagen" >
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
