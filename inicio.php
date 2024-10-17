<<<<<<< HEAD
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

=======
>>>>>>> 4d588053f16c9835501b802b3864124a11fd869f
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
<<<<<<< HEAD
    $sql = "SELECT nombre1, apellido1 FROM docente WHERE cedula = '$documento'";
} elseif ($tipo_persona == 'estudiante') {
    $sql = "SELECT nombre1, apellido1 FROM estudiante WHERE tarjeta_identidad = '$documento'";
=======
    $sql = "SELECT doc_nombre1, doc_nombre2, doc_apellido1, doc_apellido2 FROM docente WHERE cedula = '$documento'";
} elseif ($tipo_persona == 'estudiante') {
    $sql = "SELECT est_nombre1, est_nombre2, est_apellido1, est_apellido2 FROM estudiante WHERE tarjeta_identidad = '$documento'";
>>>>>>> 4d588053f16c9835501b802b3864124a11fd869f
}

$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
<<<<<<< HEAD
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

=======
    // Dependiendo del tipo de persona, asignar los valores correctos
    if ($tipo_persona == 'admin') {
        $nombre1 = $row['nombre1'];
        $nombre2 = $row['nombre2'];
        $apellido1 = $row['apellido1'];
        $apellido2 = $row['apellido2'];
    } elseif ($tipo_persona == 'docente') {
        $nombre1 = $row['doc_nombre1'];
        $nombre2 = $row['doc_nombre2'];
        $apellido1 = $row['doc_apellido1'];
        $apellido2 = $row['doc_apellido2'];
    } elseif ($tipo_persona == 'estudiante') {
        $nombre1 = $row['est_nombre1'];
        $nombre2 = $row['est_nombre2'];
        $apellido1 = $row['est_apellido1'];
        $apellido2 = $row['est_apellido2'];
    }
}

// Mostrar el nombre completo
echo "<h3>Bienvenido, $nombre1 $nombre2 $apellido1 $apellido2</h3>";
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

  <section>
        <a href="registro.php">Ir a registro<br></a>
    </section>
    <section>
        <a href="login.php"><br>Cerrar sesión</a>
    </section>
>>>>>>> 4d588053f16c9835501b802b3864124a11fd869f
</body>
</html>
