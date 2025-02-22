<?php
include('../../../header.php');
include('../../../conexion.php');

if (!isset($_SESSION['documento'])) {
    header("Location: ../../../login.php");
    exit();
}

$documento = $_SESSION['documento'];
$tipo_persona = $_SESSION['tipo_persona'];
$grado = '';

if ($tipo_persona == 'estudiante') {
    $sql = "SELECT grado FROM estudiante WHERE tarjeta_identidad = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $documento);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $grado = $row['grado'];
    }
    $stmt->close();
}

// ID del juego (ajústalo si es necesario)
$id = "juego1_esp1";

// **Inicializar variables**
$titulo = "";
$descripcion = "";
$imagenes = array_fill(0, 6, '');
$palabras = array_fill(0, 6, '');

// **Si se envió el formulario, guardar los datos**
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';

    $rutaImagenes = "../../../imagenes/juegos/";

    // 🔹 1️⃣ **Eliminar imágenes antiguas antes de subir nuevas**
    $sql = "SELECT imagen1, imagen2, imagen3, imagen4, imagen5, imagen6 FROM juego1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        for ($i = 1; $i <= 6; $i++) {
            if (!empty($row["imagen$i"])) {
                $rutaArchivoViejo = $row["imagen$i"];
                if (file_exists($rutaArchivoViejo)) {
                    unlink($rutaArchivoViejo); // 🗑️ Borrar archivo viejo
                }
            }
        }
    }
    $stmt->close();

    // 🔹 2️⃣ **Ahora subir nuevas imágenes**
    $nombres_imagenes = [];
    for ($i = 1; $i <= 6; $i++) {
        $nombreCampo = "imagen" . $i;
        $nombreImagenCampo = "nombre_imagen" . $i;

        $nombreImagen = $_POST[$nombreImagenCampo] ?? '';
        $nombreImagen = strtolower(trim($nombreImagen)); // Minúsculas y sin espacios
        $nombreArchivo = $nombreImagen ? $nombreImagen . ".png" : null; // Asigna nombre solo si existe

        if (isset($_FILES[$nombreCampo]) && $_FILES[$nombreCampo]['error'] === UPLOAD_ERR_OK) {
            $rutaArchivo = $rutaImagenes . $nombreArchivo;

            if (move_uploaded_file($_FILES[$nombreCampo]['tmp_name'], $rutaArchivo)) {
                $imagenes[$i - 1] = $rutaArchivo;
            }
        }

        $nombres_imagenes[$i - 1] = $nombreImagen;
        $palabras[$i - 1] = $nombreImagen;
    }

    shuffle($palabras); // Mezclar palabras antes de guardar

    $sql = "UPDATE juego1 SET 
        titulo = ?, 
        descripcion = ?, 
        imagen1 = ?, imagen2 = ?, imagen3 = ?, 
        imagen4 = ?, imagen5 = ?, imagen6 = ?, 
        palabra1 = ?, palabra2 = ?, palabra3 = ?, 
        palabra4 = ?, palabra5 = ?, palabra6 = ?, 
        nombre_imagen1 = ?, nombre_imagen2 = ?, nombre_imagen3 = ?, 
        nombre_imagen4 = ?, nombre_imagen5 = ?, nombre_imagen6 = ? 
        WHERE id = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('sssssssssssssssssssss', 
            $titulo, $descripcion,
            $imagenes[0], $imagenes[1], $imagenes[2], 
            $imagenes[3], $imagenes[4], $imagenes[5], 
            $palabras[0], $palabras[1], $palabras[2], 
            $palabras[3], $palabras[4], $palabras[5], 
            $nombres_imagenes[0], $nombres_imagenes[1], $nombres_imagenes[2], 
            $nombres_imagenes[3], $nombres_imagenes[4], $nombres_imagenes[5], 
            $id
        );
        $stmt->execute();
        $stmt->close();

        var_dump($imagenes); // Ver los nombres exactos de los archivos
    }
}

// **Recuperar datos almacenados en la base de datos**
$sql = "SELECT * FROM juego1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
    $titulo = $row['titulo'];
    $descripcion = $row['descripcion'];

    for ($i = 1; $i <= 6; $i++) {
        $imagenes[$i - 1] = $row["imagen$i"] ?? '';
        $palabras[$i - 1] = $row["palabra$i"] ?? '';
    }

    // Asegúrate de que ya tienes las variables $imagenes y $palabras definidas en PHP
    $imagenesJuego = array_map(function($imagen) {
        return [
            'src' => $imagen, // Ruta de la imagen
            'id' => basename($imagen, ".png"), // ID basado en el nombre del archivo sin la extensión
            'alt' => basename($imagen, ".png") // Texto alternativo basado en el nombre del archivo
        ];
    }, $imagenes);

    $palabrasCorrectas = $palabras; // Las palabras ya están mezcladas en $palabras
    $nombresImagenes = array_map(function($nombre) {
        return strtolower(trim($nombre)); // Nombre de la imagen en minúsculas
    }, $palabras);
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Español | Actividad 1</title>
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
      
  <!-- Aquí colocamos la imagen dentro de un contenedor -->
    <div class="top-bar">
    
        <button class="back-button" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i>
        </button>
        <?php if ($tipo_persona != 'estudiante' ): ?>
        <!-- Botones en la parte derecha -->
            <div class="right-buttons">
                <!--<button class="action-button" onclick="window.location.href='../../crear_actividad.php'">Crear actividad</button>-->
                <button id="btnEditar" onclick= "mostrarFormulario()">Editar</button>
            </div>
        <?php endif; ?>
    </div>

    <div id="juego1">    
        <h3 class="titulo-actividad" id="titulo"><?php echo htmlspecialchars($titulo); ?></h3> 
        <h3><?php echo htmlspecialchars($descripcion); ?></h3>
        <div class="container">
            <?php for ($i = 0; $i < 6; $i++): ?>
                <?php if (!empty($imagenes[$i])): ?>
                    <img src="<?php echo htmlspecialchars($imagenes[$i]); ?>" 
                    id="img<?php echo $i; ?>" 
                    draggable="true" 
                    ondragstart="drag(event)" 
                    data-nombre="<?php echo htmlspecialchars($palabras[$i]); ?>" 
                    alt="Imagen <?php echo $i + 1; ?>">
                <?php endif; ?>
            <?php endfor; ?>
        </div>

        <br>    
        <div class="container">
            <?php for ($i = 0; $i < 6; $i++): ?>
                <div class="figura">
                    <div class="box" id="<?php echo $i; ?>" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
                    <h2><?php echo htmlspecialchars($palabras[$i]); ?></h2>
                </div>
            <?php endfor; ?>
        </div>

        
        <div class="botones-juego">
            <button onclick="comprobar()">Comprobar</button>
            <button class="reset" onclick="resetear()">Intentar de nuevo</button>
        </div>

        <div class="mensaje" id="mensaje2"></div> <!-- Aquí se mostrará el mensaje global -->
    </div>
    <br>
    <br>
    <div id="form-container3" style="display:none;">
                <h1>Editar actividad</h1>

                <!-- Formulario -->
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="juego1_esp1">

                    <div class="field-group2">
                        <label for="titulo">Título del Juego:</label>
                        <input type="text" id="titulo" name="titulo" placeholder="Título del juego" required>
                    </div>
                    <div class="field-group2">
                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" name="descripcion" placeholder="Escribe una descripción del juego" rows="4" required></textarea>
                    </div>
                    <h3>Imágenes y palabras</h3>
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                        <div class="field-group2">
                            <label for="imagen<?php echo $i; ?>">Imagen <?php echo $i; ?>:</label>
                            <input type="file" id="imagen<?php echo $i; ?>" name="imagen<?php echo $i; ?>">
                            <label for="nombre_imagen<?php echo $i; ?>">Nombre de la imagen:</label>
                            <input type="text" id="nombre_imagen<?php echo $i; ?>" name="nombre_imagen<?php echo $i; ?>" placeholder="Escribe el nombre de la imagen">
                            <br>
                        </div>
                    <?php endfor; ?>
                    
                <div class="botones-formulario">
                    <button type="submit" id="enviar">Guardar Cambios</button>
                    <button id="cancelar" onclick= "noMostrarFormulario()">Cancelar</button>
                </div>    
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



   <script>
    // Función para mostrar u ocultar el perfil completo
    
    
            
    function showAsignaturas() {
        alert("Mostrando las asignaturas del usuario...");
    }

    function showPerfilCompleto() {
        alert("Mostrando el perfil completo del usuario...");
    }

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
   function mostrarFormulario() {
        var formulario = document.getElementById('form-container3' );
        if (formulario.style.display === 'none') {
            formulario.style.display = 'block';
        } else {
            formulario.style.display = 'none';
        }
    }
    
    function noMostrarFormulario() {
        var formulario = document.getElementById('form-container3' );
        if (formulario.style.display === 'block') {
            formulario.style.display = 'none';
        } else {
            formulario.style.display = 'block';
        }
    }
   
    // Pasar las variables de PHP a JavaScript
    window.palabrasCorrectas = <?php echo json_encode($palabras); ?>;
    window.nombresImagenes = <?php echo json_encode($imagenes); ?>;
    
</script>

<!-- En español.php -->
<script src="../../../script.js"></script>


</body>
</html>