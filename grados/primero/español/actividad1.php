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
    $sql = "SELECT  grado FROM estudiante WHERE tarjeta_identidad = '$documento'";
}
else {
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
    // Recibir los datos del formulario
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $palabra1 = $_POST['palabra1'];
    $palabra2 = $_POST['palabra2'];
    $palabra3 = $_POST['palabra3'];
    $palabra4 = $_POST['palabra4'];
    $palabra5 = $_POST['palabra5'];
    $palabra6 = $_POST['palabra6'];

    // Manejo de imágenes
    $ruta_imagenes = "../../../imagenes/juegos/";
    $imagenes = [];
    for ($i = 1; $i <= 6; $i++) {
        $nombreImagen = "imagen" . $i;
        if (!empty($_FILES[$nombreImagen]['name'])) {
            $ruta = $ruta_imagenes . basename($_FILES[$nombreImagen]['name']);
            if (move_uploaded_file($_FILES[$nombreImagen]['tmp_name'], $ruta)) {
                $imagenes[$i] = $ruta;
            } else {
                $imagenes[$i] = null; // En caso de que no se pueda mover el archivo
                $error_message = "Error al subir la imagen $i.";
            }
        } else {
            $imagenes[$i] = null; // Si no se sube una imagen, dejamos como NULL
        }
    }

    // Si hay algún error en las imágenes, no procesamos la actualización
    if ($error_message == "") {
        // Construir la consulta de actualización
        $sql = "UPDATE juego1 
                SET titulo = ?, descripcion = ?, 
                    imagen1 = ?, imagen2 = ?, imagen3 = ?, 
                    imagen4 = ?, imagen5 = ?, imagen6 = ?, 
                    palabra1 = ?, palabra2 = ?, palabra3 = ?, 
                    palabra4 = ?, palabra5 = ?, palabra6 = ? 
                WHERE id = 'juego1_esp1'";

        $stmt = $conn->prepare($sql);

        // Asegúrate de que las imágenes sean NULL si no fueron cargadas
        $stmt->bind_param(
            "ssssssssssssss",
            $titulo,
            $descripcion,
            $imagenes[1] ?? null,
            $imagenes[2] ?? null,
            $imagenes[3] ?? null,
            $imagenes[4] ?? null,
            $imagenes[5] ?? null,
            $imagenes[6] ?? null,
            $palabra1,
            $palabra2,
            $palabra3,
            $palabra4,
            $palabra5,
            $palabra6
        );

        // Ejecutar la consulta
        if ($stmt->execute()) {
            $error_message = "Juego actualizado correctamente.";
        } else {
            $error_message = "Error al actualizar el juego: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
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
            <img src="../../../imagenes/juegos/loro.webp" alt="" draggable="true" ondragstart="drag(event)" id="loro">
            <img src="../../../imagenes/juegos/gato.webp" alt="" draggable="true" ondragstart="drag(event)" id="gato">
            <img src="../../../imagenes/juegos/perro.webp" alt="" draggable="true" ondragstart="drag(event)" id="perro">
            <img src="../../../imagenes/juegos/conejo.webp" alt="" draggable="true" ondragstart="drag(event)" id="conejo">
            <img src="../../../imagenes/juegos/elefante.webp" alt="" draggable="true" ondragstart="drag(event)" id="elefante">
            <img src="../../../imagenes/juegos/tortuga.webp" alt="" draggable="true" ondragstart="drag(event)" id="tortuga">
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
                <form action="actividad1.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="idJuego" value="juego1_esp1"> <!-- ID del juego a editar -->
                    <div class="field-group2">
                        <label for="titulo">Título del Juego:</label>
                        <input type="text" id="titulo" name="titulo" placeholder="Título del juego" required>
                    </div>
                    <div class="field-group2">
                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" name="descripcion" placeholder="Escribe una descripción del juego" rows="4" required></textarea>
                    </div>
                    <h3>Imágenes y palabras</h3>
                    <div id="contenedorEditarElementos">
                        <!-- Repite este bloque para cada imagen y palabra -->
                        <div class="fila">
                            <label for="imagen1">Imagen 1:</label>
                            <input type="file" id="imagen1" name="imagen1" required>

                            <label for="palabra1">Nombre de la imagen:</label>
                            <input type="text" id="palabra1" name="palabra1" placeholder="Escribe la palabra" required>
                        </div>

                        <div class="fila">
                            <label for="imagen2">Imagen 2:</label>
                            <input type="file" id="imagen2" name="imagen2" required>

                            <label for="palabra2">Nombre de la imagen:</label>
                            <input type="text" id="palabra2" name="palabra2" placeholder="Escribe la palabra" required>
                        </div>

                        <div class="fila">
                            <label for="imagen3">Imagen 3:</label>
                            <input type="file" id="imagen3" name="imagen3" required>

                            <label for="palabra3">Nombre de la imagen:</label>
                            <input type="text" id="palabra3" name="palabra3" placeholder="Escribe la palabra" required>
                        </div>

                        <div class="fila">
                            <label for="imagen4">Imagen 4:</label>
                            <input type="file" id="imagen4" name="imagen4" required>

                            <label for="palabra4">Nombre de la imagen:</label>
                            <input type="text" id="palabra4" name="palabra4" placeholder="Escribe la palabra" required>
                        </div>

                        <div class="fila">
                            <label for="imagen5">Imagen 5:</label>
                            <input type="file" id="imagen5" name="imagen5" required>

                            <label for="palabra5">Nombre de la imagen:</label>
                            <input type="text" id="palabra5" name="palabra5" placeholder="Escribe la palabra" required>
                        </div>

                        <div class="fila">
                            <label for="imagen6">Imagen 6:</label>
                            <input type="file" id="imagen6" name="imagen6" required>

                            <label for="palabra6">Nombre de la imagen:</label>
                            <input type="text" id="palabra6" name="palabra6" placeholder="Escribe la palabra" required>
                        </div>
                    </div>
                    
                </form>
                <div class="botones-formulario">
                    <button type="submit" id="enviar">Guardar Cambios</button>
                    <button id="cancelar" onclick= "noMostrarFormulario()">Cancelar</button>
                </div>
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