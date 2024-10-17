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

<?php
session_start();
include("conexion.php");

// Verificar si la sesión está activa
if (!isset($_SESSION['documento'])) {
    header("Location: login.php");
    exit();
}

// Obtener el documento y tipo de persona de la sesión
$documento = $_SESSION['documento'];
$tipo_persona = $_SESSION['tipo_persona'];

// Inicializar las variables para los nombres
$nombre1 = '';
$apellido1 = '';

// Dependiendo del tipo de persona, hacer la consulta en la tabla correspondiente
if ($tipo_persona == 'admin') {
    $sql = "SELECT nombre1, apellido1 FROM admin WHERE cedula = '$documento'";
} elseif ($tipo_persona == 'docente') {
    $sql = "SELECT nombre1, apellido1 FROM docente WHERE cedula = '$documento'";
} elseif ($tipo_persona == 'estudiante') {
    $sql = "SELECT nombre1, apellido1 FROM estudiante WHERE tarjeta_identidad = '$documento'";
}

$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
    $nombre1 = $row['nombre1'];
    $apellido1 = $row['apellido1'];
}

$nombre_completo = "$nombre1 $apellido1";
?>

<div class="background-image">
    <div class="button-container">
        <!-- Botón de perfil con solo el icono inicialmente -->
        <button class="toggle-door" onclick="toggleProfile()">
            <i class="fas fa-user"></i> <span id="userName" style="display: none;"><?php echo $nombre_completo; ?></span>
        </button>
        <div class="right-buttons">
            <h1><button>Primero</button><h1>
            <h1><button>Segundo</button><h1>
            <h1> <button>Tercero</button><h1>
        </div>
    </div>

    <!-- Contenedor de perfil que se muestra al hacer clic en el botón -->
    <div class="door-content" id="doorContent" style="display: none;">
<br><br>
        <div class="button-group">
            <button onclick="showAsignaturas()">Asignaturas</button>
            <button onclick="showPerfilCompleto()">Registrar</button>
        </div>

       
        <div class="bottom-buttons">
            <button class="icon-button" onclick="window.location.href='registro.php'">
                <i class="fas fa-user-plus"></i> Ir a Registro
            </button>
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
