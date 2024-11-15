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

// Inicializar las variables para los nombres, grado y asignatura
$nombre1 = '';
$nombre2 = '';
$apellido1 = '';
$apellido2 = '';
$grado = '';
$asignatura = '';
$numero_documento = '';
$rutaFoto = '';

// Dependiendo del tipo de persona, hacer la consulta en la tabla correspondiente
if ($tipo_persona == 'admin') {
    $sql = "SELECT nombre1, nombre2, apellido1, apellido2, cedula FROM admin WHERE cedula = '$documento'";
} 
elseif ($tipo_persona == 'docente') {
    $sql = "SELECT doc_nombre1, doc_nombre2, doc_apellido1, doc_apellido2, asignatura, cedula FROM docente WHERE cedula = '$documento'";
} elseif ($tipo_persona == 'estudiante') {
    $sql = "SELECT est_nombre1, est_nombre2, est_apellido1, est_apellido2, grado, tarjeta_identidad FROM estudiante WHERE tarjeta_identidad = '$documento'";
}

$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();

    if ($tipo_persona == 'admin') {
        $nombre1 = $row['nombre1'];
        $nombre2 = isset($row['nombre2']) ? $row['nombre2'] : '';
        $apellido1 = $row['apellido1'];
        $apellido2 = isset($row['apellido2']) ? $row['apellido2'] : '';
        $numero_documento = $row['cedula'];
    } elseif ($tipo_persona == 'docente') {
        $nombre1 = $row['doc_nombre1'];
        $nombre2 = isset($row['doc_nombre2']) ? $row['doc_nombre2'] : '';
        $apellido1 = $row['doc_apellido1'];
        $apellido2 = isset($row['doc_apellido2']) ? $row['doc_apellido2'] : '';
        $asignatura = $row['asignatura'];
        $numero_documento = $row['cedula'];
    } elseif ($tipo_persona == 'estudiante') {
        $nombre1 = $row['est_nombre1'];
        $nombre2 = isset($row['est_nombre2']) ? $row['est_nombre2'] : '';
        $apellido1 = $row['est_apellido1'];
        $apellido2 = isset($row['est_apellido2']) ? $row['est_apellido2'] : '';
        $grado = $row['grado'];
        $numero_documento = $row['tarjeta_identidad'];
    }
}

// Obtener la ruta de la foto desde la base de datos
$sqlFoto = "SELECT foto_perfil FROM persona WHERE documento = '$documento'";
$resultadoFoto = $conn->query($sqlFoto);

if ($resultadoFoto && $resultadoFoto->num_rows > 0) {
    $rowFoto = $resultadoFoto->fetch_assoc();
    // Si la foto existe, usar la ruta completa para mostrarla
    $rutaFoto = !empty($rowFoto['foto_perfil']) 
        ? 'imagenes/usuarios/' . $rowFoto['foto_perfil'] 
        : "imagenes/default.webp";
}

// Concatenar el nombre completo
$nombre_completo = $nombre1 . ' ' . $nombre2 . ' ' . $apellido1 . ' ' . $apellido2;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="stilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="background-image">
    <div class="button-container">
        <!-- Botón de perfil con solo el icono inicialmente -->
        <button class="toggle-door" onclick="toggleProfile()">
            <i class="fas fa-user"></i> <span id="userName" style="display: none;"><?php echo $nombre_completo; ?></span>
        </button>
        <div class="right-buttons">
            <h1><button onclick="window.location.href='inicio.php'">Inicio</button></h1>
            <h1><button>Primero</button></h1>
            <h1><button>Segundo</button></h1>
            <h1><button>Tercero</button></h1>
        </div>
    </div>  

    <div class="contenedor-perfil">
            <div class="perfil">
                <div class="foto-perfil">
                    <!-- Imagen de perfil, puedes usar una imagen del usuario -->
                    <img id="fotoPerfil" src="<?php echo $rutaFoto; ?>" alt="Foto de perfil">
                </div>
                <div class="informacion-perfil">
                    <h2><?php echo $nombre_completo; ?></h2>
                    <hr>
                    <ul>
                        <p><strong>Número de documento:</strong> <?php echo $numero_documento; ?></p>
                        <?php if ($tipo_persona == 'estudiante'): ?>
                        <p><strong>Grado:</strong> <?php echo $grado; ?></p>
                            <?php elseif ($tipo_persona == 'docente'): ?>
                        <p><strong>Asignatura:</strong> <?php echo $asignatura; ?></p>
                            <?php endif; ?>
                    </ul>
                    <form id="fotoForm" action="subir_foto.php" method="POST" enctype="multipart/form-data">
                        <input type="file" name="foto_perfil" accept="image/*" required>
                        <button type="submit">Subir Foto</button>
                    </form>
                </div>
            </div>

            <!-- Espacio para otros trabajos u opciones -->
            <div class="trabajos-subidos">
                <h2>Documentos Subidos</h2>
                <div class="trabajo">
                    <i class="far fa-file-pdf"></i>
                    <a href="#">Ejemplo de Documento</a>
                </div>
            </div>
        </div>
    <!-- Aquí colocamos el contenedor del perfil -->
    <div class="door-content" id="doorContent" style="display: none;">
        <br><br>
        <div class="button-group">
            <button onclick="showAsignaturas()">Asignaturas</button>
            <button onclick="window.location.href='perfil.php'">Mi perfil</button>
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

document.getElementById("fotoForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Evitar que se recargue la página

    let formData = new FormData(this); // Crear un FormData con los datos del formulario
    fetch("subir_foto.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json()) // Obtener la respuesta en formato JSON
    .then(data => {
        alert(data.message); // Mostrar el mensaje con alert()
        
        // Si la foto se sube con éxito, actualizar la imagen de perfil en la página
        if (data.status === "success") {
            document.querySelector('.foto-perfil img').src = 'imagenes/usuarios/' + '<?php echo $documento; ?>' + ".webp";
        }
    })
    .catch(error => {
        alert("Ocurrió un error al subir la foto.");
    });
});

</script>
</body>
</html>
