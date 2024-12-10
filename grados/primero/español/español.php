<?php

include('../../../header.php');

if (!isset($_SESSION['documento'])) {
    header("Location: ../../../login.php");
    exit();
}

// Obtener el documento y tipo de persona de la sesión
$documento = $_SESSION['documento'];
$tipo_persona = $_SESSION['tipo_persona'];

// Inicializar las variables para los nombres, grado y asignatura

$grado = '';


// Dependiendo del tipo de persona, hacer la consulta en la tabla correspondiente

    if ($tipo_persona == 'estudiante') {
    $sql = "SELECT  grado FROM estudiante WHERE tarjeta_identidad = '$documento'";
}

$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();

    if ($tipo_persona == 'estudiante') {
        $grado = $row['grado'];
    }
}


?>

<?php

// Verificar si se ha subido un archivo
if ($_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
    $nombreArchivo = $_FILES['imagen']['name'];
    $rutaTemporal = $_FILES['imagen']['tmp_name'];
    $directorioDestino = 'imagenes/juegos'; // Asegúrate de que esta carpeta exista y tenga permisos de escritura

    // Generar un nombre único para el archivo para evitar conflictos
    $nombreArchivoDestino = uniqid() . '_' . $nombreArchivo;
    $rutaFinal = $directorioDestino . $nombreArchivoDestino;

    // Mover el archivo desde la carpeta temporal al destino
    if (move_uploaded_file($rutaTemporal, $rutaFinal)) {
        echo "Archivo subido con éxito: " . $nombreArchivoDestino;
    } else {
        echo "Error al subir la imagen.";
    }
} else {
    echo "No se ha subido ninguna imagen o hubo un error en la carga.";
}

// Recibir los demás datos del formulario
$nombre_juego = $_POST['nombre_juego'];
$descripcion = $_POST['descripcion'];
$url = $_POST['url']; // Esta será 'actividad1.php'
$id_juego = $_POST['id_juego']; // Asegúrate de que este valor se pase correctamente

// Insertar los datos en la base de datos
$sql = "UPDATE juegos_primero SET nombre_juego=?, descripcion=?, url=?, imagen=? WHERE id_juego=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssssi', $nombre_juego, $descripcion, $url, $nombreArchivoDestino, $id_juego);

if ($stmt->execute()) {
    echo "Actividad actualizada con éxito";
} else {
    echo "Error al actualizar la actividad";
}

$stmt->close();
$conn->close();
?>
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Primero | Español</title>
    <link rel="stylesheet" href="../../../stilos.css">
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
        <h1><button onclick="window.location.href='../../../inicio.php'">Inicio</button></h1>
        <?php if ($tipo_persona == 'admin' || $tipo_persona == 'docente'): ?>
            <h1><button onclick="window.location.href='../../../grados/primero/inicio.php'">Primero</button></h1>
            <h1><button onclick="window.location.href='../../../grados/segundo/inicio.php'">Segundo</button></h1>
            <h1><button onclick="window.location.href='../../../grados/tercero/inicio.php'">Tercero</button></h1>
        <?php endif; ?>

        <?php if ($tipo_persona == 'estudiante'): ?>
            <?php 
                // Determinar la página según el grado
                $paginaGrado = '';
                if ($grado == 'primero' || $grado == 'Primero') {
                    $paginaGrado = '../inicio.php';
                } elseif ($grado == 'segundo' || $grado == 'Segundo') {
                    $paginaGrado = '../../segundo/inicio.php';
                } elseif ($grado == 'tercero' || $grado == 'Tercero') {
                    $paginaGrado = '../../grados/tercero/inicio.php';
                }
            ?>
            <h1><button onclick="window.location.href='<?php echo $paginaGrado; ?>'">Asignaturas</button></h1>
        <?php endif; ?>
    </div>
    </div>  
    <h3 class="titulo-grado" id="titulo" >Español</h3>  
    
     
  <!-- Aquí colocamos la imagen dentro de un contenedor -->
  <div class="games-container">
        <!-- Aquí irán las tarjetas dinámicas generadas desde PHP -->
        <div class="game-card">
            <a href="../../primero/español/actividad1.php" style="text-decoration: none; color: inherit;">
            <img src="../../../imagenes/juegos/foto_juegos.webp" alt="Juego 1">
            <div class="game-info">
                <h3>Nombre del Juego</h3>
                <p>Descripción breve del juego.</p>
                <a href="#" class="play-button">Jugar</a>
            </div>
            <?php if ($tipo_persona == 'admin' || $tipo_persona == 'docente'): ?>
                <div class="game-actions">
                    <!-- Botón para visualizar -->
                    <button class="action-btn view-btn" title="Permitir visualización">
                        👁️
                    </button>
                    <!-- Botón para editar -->
                    <button onclick="mostrarFormulario(1)" class="action-btn edit-btn" title="Editar">
                        ✏️ Editar
                    </button>
                    <!-- Botón para eliminar 
                    <button class="action-btn delete-btn" title="Eliminar">
                        ❌ Eliminar
                    </button>-->
                </div>
            <?php endif; ?>
        </div>
        <!-- Repetir estructura para otros juegos -->

        <div class="game-card">
            <img src="../../../imagenes/juegos/foto_juegos.webp" alt="Juego 2">
            <div class="game-info">
                <h3>Nombre del Juego</h3>
                <p>Descripción breve del juego.</p>
                <a href="#" class="play-button">Jugar</a>
            </div>
            <?php if ($tipo_persona == 'admin' || $tipo_persona == 'docente'): ?>
                <div class="game-actions">
                    <!-- Botón para visualizar -->
                    <button class="action-btn view-btn" title="Permitir visualización">
                        👁️
                    </button>
                    <!-- Botón para editar -->
                    <button class="action-btn edit-btn" title="Editar">
                        ✏️ Editar
                    </button>
                    <!-- Botón para eliminar 
                    <button class="action-btn delete-btn" title="Eliminar">
                        ❌ Eliminar
                    </button>-->
                </div>
            <?php endif; ?>
        </div>

        <div class="game-card">
            <img src="../../../imagenes/juegos/foto_juegos.webp" alt="Juego 3">
            <div class="game-info">
                <h3>Nombre del Juego</h3>
                <p>Descripción breve del juego.</p>
                <a href="#" class="play-button">Jugar</a>
            </div>
            <?php if ($tipo_persona == 'admin' || $tipo_persona == 'docente'): ?>
                <div class="game-actions">
                    <!-- Botón para visualizar -->
                    <button class="action-btn view-btn" title="Permitir visualización">
                        👁️
                    </button>
                    <!-- Botón para editar -->
                    <button class="action-btn edit-btn" title="Editar">
                        ✏️ Editar
                    </button>
                    <!-- Botón para eliminar 
                    <button class="action-btn delete-btn" title="Eliminar">
                        ❌ Eliminar
                    </button>-->
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Formulario de edición con campo para subir una imagen -->
<div id="formularioEditar1" style="display:none;">
    <form id="editarActividadForm" enctype="multipart/form-data">
        <label for="nombre_juego">Nombre del juego:</label>
        <input type="text" id="nombre_juego" name="nombre_juego"><br>
        
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion"></textarea><br>

        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" name="imagen"><br>

        <!-- Campo oculto con la URL predefinida -->
        <input type="hidden" id="url" name="url" value="actividad1.php">

        <input type="submit" value="Actualizar">
    </form>
</div>

    <!-- Contenedor de perfil que se muestra al hacer clic en el botón -->
    <div class="door-content" id="doorContent" style="display: none;">
<br><br>
        <div class="button-group">
            <!--    <button onclick="showAsignaturas()">Asignaturas</button> -->
            <button onclick="window.location.href='../../../perfil.php'">
                <i class="fas fa-user"></i>&nbsp;&nbsp;Mi perfil
            </button>
            
        </div>

       
        <div class="bottom-buttons">
            <?php if ($tipo_persona == 'admin'): ?>
                <button class="icon-button" onclick="window.location.href='../../../registro.php'">
                    <i class="fas fa-user-plus"></i> Ir a Registro
                </button>
            <?php endif; ?>
            <button class="icon-button" onclick="window.location.href='../../../login.php'">
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

    function mostrarFormulario(id) {
        var formulario = document.getElementById('formularioEditar' + id);
        if (formulario.style.display === 'none') {
            formulario.style.display = 'block';
        } else {
            formulario.style.display = 'none';
        }
    }
</script>

</script>
<!-- En español.php -->
<script src="../../../script.js"></script>

</body>
</html>