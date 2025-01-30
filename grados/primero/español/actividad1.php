<?php

include('../../../header.php');
include('../../../conexion.php');

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
    $sql = "SELECT grado FROM estudiante WHERE tarjeta_identidad = '$documento'";
} else {
    $resultado = null; // O maneja el caso según corresponda
}

$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();

    if ($tipo_persona == 'estudiante') {
        $grado = $row['grado'];
    }
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $id = $_POST['id'] ?? '';

    $rutaImagenes = "../../../imagenes/juegos/";

    // Inicializar arrays
    $imagenes = [];
    $nombres_imagenes = [];
    $palabras = [];

    // Recorrer las imágenes
    for ($i = 1; $i <= 6; $i++) {
        $nombreCampo = "imagen" . $i;
        $nombreImagenCampo = "nombre_imagen" . $i;

        $nombreImagen = $_POST[$nombreImagenCampo] ?? '';
        $nombreArchivo = null;

        if (isset($_FILES[$nombreCampo]) && $_FILES[$nombreCampo]['error'] === UPLOAD_ERR_OK) {
            $nombreArchivo = time() . "_" . basename($_FILES[$nombreCampo]['name']);
            $rutaArchivo = $rutaImagenes . $nombreArchivo;

            if (move_uploaded_file($_FILES[$nombreCampo]['tmp_name'], $rutaArchivo)) {
                echo "Imagen $i guardada correctamente.<br>";
                $imagenes[] = $rutaArchivo; // Guardar ruta de imagen
            } else {
                echo "Error al guardar la imagen $i.<br>";
                $imagenes[] = ''; // Imagen vacía en caso de error
            }
        } else {
            $imagenes[] = ''; // Si no se subió imagen, mantener vacío
        }

        $nombres_imagenes[] = $nombreImagen;
        $palabras[] = $nombreImagen; // Nombre de la imagen = palabra
    }

    // Mezclar las palabras
    shuffle($palabras);

    // SQL para actualizar la tabla
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

    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    // Definir valores seguros para evitar errores de referencia
    $titulo = $titulo ?? '';
    $descripcion = $descripcion ?? '';
    $id = $id ?? '';

    for ($i = 0; $i < 6; $i++) {
        $imagenes[$i] = $imagenes[$i] ?? '';
        $nombres_imagenes[$i] = $nombres_imagenes[$i] ?? '';
        $palabras[$i] = $palabras[$i] ?? '';
    }

    // Asociar los parámetros correctamente
    $stmt->bind_param('sssssssssssssssssssss', 
        $titulo, $descripcion,
        $imagenes[0], $imagenes[1], $imagenes[2], 
        $imagenes[3], $imagenes[4], $imagenes[5], 
        $nombres_imagenes[0], $nombres_imagenes[1], $nombres_imagenes[2], 
        $nombres_imagenes[3], $nombres_imagenes[4], $nombres_imagenes[5], 
        $palabras[0], $palabras[1], $palabras[2], 
        $palabras[3], $palabras[4], $palabras[5],
        $id
    );

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Datos actualizados correctamente.";
    } else {
        echo "Error al actualizar los datos: " . $stmt->error;
    }

    // Cerrar la consulta y conexión
    $stmt->close();
    $conn->close();
}
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
    <!-- Botón en la parte izquierda -->
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
        <h3 class="titulo-actividad" id="titulo" >Actividad: Animales</h3> 
        <h3>Arrastra donde corresponde </h3>
        <div class="container">
        <!-- ondragstart: específico que debe suceder cuando se arrastra el elemento
        draggable: indica que el elemento se podrá arrastrar -->
            
            <br>
            <img src="<?php echo $imagenes[0]; ?>" alt="Imagen 1">
            <img src="<?php echo $imagenes[1]; ?>" alt="Imagen 2">
            <img src="<?php echo $imagenes[2]; ?>" alt="Imagen 3">
            <img src="<?php echo $imagenes[3]; ?>" alt="Imagen 4">
            <img src="<?php echo $imagenes[4]; ?>" alt="Imagen 5">
            <img src="<?php echo $imagenes[5]; ?>" alt="Imagen 6">
        </div>
        <br>
        <div class="container">
            
            <div class="figura">
                <!-- ondrop: específico que sucede cuando se suelta un elemento arrastrado
                ondragover: específico donde se pueden soltar los datos arrastrados -->
                <div class="box" id="0" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
                <h2>Gato</h2>
            </div>

            <div class="figura">
                <!-- ondrop: específico que sucede cuando se suelta un elemento arrastrado
                ondragover: específico donde se pueden soltar los datos arrastrados -->
                <div class="box" id="1" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
                <h2>Perro</h2>
            </div>

            <div class="figura">
                <!-- ondrop: específico que sucede cuando se suelta un elemento arrastrado
                ondragover: específico donde se pueden soltar los datos arrastrados -->
                <div class="box" id="2" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
                <h2>Loro</h2>
            </div>

            <div class="figura">
                <!-- ondrop: específico que sucede cuando se suelta un elemento arrastrado
                ondragover: específico donde se pueden soltar los datos arrastrados -->
                <div class="box" id="3" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
                <h2>Tortuga</h2>
            </div>

            <div class="figura">
                <!-- ondrop: específico que sucede cuando se suelta un elemento arrastrado
                ondragover: específico donde se pueden soltar los datos arrastrados -->
                <div class="box" id="4" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
                <h2>Conejo</h2>
            </div>

            <div class="figura">
                <!-- ondrop: específico que sucede cuando se suelta un elemento arrastrado
                ondragover: específico donde se pueden soltar los datos arrastrados -->
                <div class="box" id="5" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
                <h2>Elefante</h2>
            </div>
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
                    <!--<img id="preview<?php echo $i; ?>" src="#" alt="Vista previa" style="display: none; max-width: 100px;">-->
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

   
</script>

<!-- En español.php -->
<script src="../../../script.js"></script>


</body>
</html>