<?php

include('../../../header.php');
include('../../../conexion.php'); // Conectar a la base de datos

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


// Si se envió el formulario, actualizar los datos en la base de datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id3 = $_POST['id3'];
    $titulo3 = $_POST['titulo3'];
    $descripcion3 = $_POST['descripcion3'];
    $tema1 = $_POST['tema1'];
    $palabra1_1 = $_POST['palabra1_1'];
    $palabra2_1 = $_POST['palabra2_1'];
    $palabra3_1 = $_POST['palabra3_1'];
    $tema2 = $_POST['tema2'];
    $palabra1_2 = $_POST['palabra1_2'];
    $palabra2_2 = $_POST['palabra2_2'];
    $palabra3_2 = $_POST['palabra3_2'];

    // Actualizar la fila en la tabla juego3 donde id3 = 'juego3_esp1'
    $sqlUpdate = "UPDATE juego3 SET 
                    titulo3 = '$titulo3',
                    descripcion3 = '$descripcion3',
                    tema1 = '$tema1',
                    palabra1_1 = '$palabra1_1',
                    palabra2_1 = '$palabra2_1',
                    palabra3_1 = '$palabra3_1',
                    tema2 = '$tema2',
                    palabra1_2 = '$palabra1_2',
                    palabra2_2 = '$palabra2_2',
                    palabra3_2 = '$palabra3_2'
                WHERE id3 = '$id3'";

    if ($conn->query($sqlUpdate) === TRUE) {
        echo "<script>alert('Actividad actualizada correctamente');</script>";
        echo "ID3: $id3, Título: $titulo3, Descripción: $descripcion3"; 

    } else {
        echo "<script>alert('Error al actualizar: " . $conn->error . "');</script>";
    }
}

// Obtener datos de la actividad en juego3
$id3 = 'juego3_esp1'; // ID fijo
$sqlJuego = "SELECT * FROM juego3 WHERE id3 = '$id3'";
$resultadoJuego = $conn->query($sqlJuego);

// Inicializar variables con valores vacíos
$titulo3 = $descripcion3 = $tema1 = $palabra1_1 = $palabra2_1 = $palabra3_1 = '';
$tema2 = $palabra1_2 = $palabra2_2 = $palabra3_2 = '';


if ($resultadoJuego && $resultadoJuego->num_rows > 0) {
    $juego = $resultadoJuego->fetch_assoc();
    $titulo3 = $juego['titulo3'];
    $descripcion3 = $juego['descripcion3'];
    $tema1 = $juego['tema1'];
    $palabra1_1 = $juego['palabra1_1'];
    $palabra2_1 = $juego['palabra2_1'];
    $palabra3_1 = $juego['palabra3_1'];
    $tema2 = $juego['tema2'];
    $palabra1_2 = $juego['palabra1_2'];
    $palabra2_2 = $juego['palabra2_2'];
    $palabra3_2 = $juego['palabra3_2'];
}
// Obtener las palabras y su grupo correcto desde la base de datos
$mapaClasificacion = [
    $palabra1_1 => "grupo1",
    $palabra2_1 => "grupo1",
    $palabra3_1 => "grupo1",
    $palabra1_2 => "grupo2",
    $palabra2_2 => "grupo2",
    $palabra3_2 => "grupo2"
];

// Convertir el array de PHP a JSON para enviarlo a JavaScript
$mapaClasificacionJSON = json_encode($mapaClasificacion);


// 🔀 Mezclar las palabras antes de enviarlas al HTML
$palabras = [$palabra1_1, $palabra2_1, $palabra3_1, $palabra1_2, $palabra2_2, $palabra3_2];
shuffle($palabras);

// Reasignar las palabras mezcladas
$palabra1_1 = $palabras[0];
$palabra2_1 = $palabras[1];
$palabra3_1 = $palabras[2];
$palabra1_2 = $palabras[3];
$palabra2_2 = $palabras[4];
$palabra3_2 = $palabras[5];



?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Español | Actividad 3</title>
    <link rel="stylesheet" href="../../../stilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <script>
        // Convertir el JSON de PHP a un objeto en JavaScript
        const mapaClasificacion = <?php echo $mapaClasificacionJSON; ?>;
        //console.log("Mapa de Clasificación generado en PHP:", mapaClasificacion);
    </script>
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
            <button id="btnEditar" onclick="mostrarFormulario()">Editar</button>
        </div>
    <?php endif; ?>
</div>
<div id="juego3">
    <h3 class="titulo-actividad" id="titulo" ><?php echo htmlspecialchars($titulo3); ?></h3> 
    <h3><?php echo htmlspecialchars($descripcion3); ?></h3>

    <div class="contenedorElementos">
        <span id="elemento1" class="elemento" draggable="true" ondragstart="arrastrar(event)" data-palabra="<?php echo $palabra1_1; ?>">
            <?php echo $palabra1_1; ?>
        </span>
        <span id="elemento2" class="elemento" draggable="true" ondragstart="arrastrar(event)" data-palabra="<?php echo $palabra2_1; ?>">
            <?php echo $palabra2_1; ?>
        </span>
        <span id="elemento3" class="elemento" draggable="true" ondragstart="arrastrar(event)" data-palabra="<?php echo $palabra3_1; ?>">
            <?php echo $palabra3_1; ?>
        </span>
        <span id="elemento4" class="elemento" draggable="true" ondragstart="arrastrar(event)" data-palabra="<?php echo $palabra1_2; ?>">
            <?php echo $palabra1_2; ?>
        </span>
        <span id="elemento5" class="elemento" draggable="true" ondragstart="arrastrar(event)" data-palabra="<?php echo $palabra2_2; ?>">
            <?php echo $palabra2_2; ?>
        </span>
        <span id="elemento6" class="elemento" draggable="true" ondragstart="arrastrar(event)" data-palabra="<?php echo $palabra3_2; ?>">
            <?php echo $palabra3_2; ?>
        </span>
    </div>


    <div class="grupos">
    <div class="grupo" id="grupo1" ondrop="soltar(event)" ondragover="permitirSoltar(event)">
        <h2><?php echo $tema1; ?></h2>
        <!-- Aquí se agregarán las imágenes de los animales domésticos -->
    </div>
    <div class="grupo" id="grupo2" ondrop="soltar(event)" ondragover="permitirSoltar(event)">
        <h2><?php echo $tema2; ?></h2>
        <!-- Aquí se agregarán las imágenes de los animales salvajes -->
    </div>
</div>


    <div class="botones-juego">
        <button onclick="verificar()">Comprobar</button>
        <button class="reset" onclick="reiniciar()">Intentar de nuevo</button>
    </div>

    <div class="mensaje3" id="mensajeActividad"></div> <!-- Aquí se mostrará el mensaje global -->
    </div>

    <div id="form-container4" style="display:none;">
        <h1>Editar actividad</h1>
        <!-- Formulario -->
        <form action="" method="POST">
            <input type="hidden" name="id3" value="juego3_esp1">
            <div class="field-group2">
                <label for="titulo3">Título del Juego:</label>
                <input type="text" id="titulo3" name="titulo3" placeholder="Título del juego" required>
            </div>
            <div class="field-group2">
                <label for="descripcion3">Descripción:</label>
                <textarea id="descripcion3" name="descripcion3" placeholder="Escribe una descripción del juego" rows="4" required></textarea>
            </div>
            <br>
            <!-- Preguntas y respuestas 1-->
            <h3>Tema 1</h3>
            <div class="field-group2">
                <label for="tema1">Escribe el tema 1:</label>
                <input type="text" id="tema1" name="tema1" placeholder="Escribe aquí" rows="4" required></input>
            </div>
            <div class="field-group2">
            <label for="palabra1">Escribe la palabra 1:</label>
            <input type="text" id="palabra1_1" name="palabra1_1" placeholder="Palabra 1" required>
            </div>
            <div class="field-group2">
            <label for="palabra1">Escribe la palabra 2:</label>
            <input type="text" id="palabra2_1" name="palabra2_1" placeholder="Palabra 2" required>
            </div>
            <div class="field-group2">
            <label for="palabra1">Escribe la palabra 3:</label>
            <input type="text" id="palabra3_1" name="palabra3_1" placeholder="Palabra 3" required>
            </div>

            <br>
            <h3>Tema 2</h3> 
            <div class="field-group2">
                <label for="tema2">Escribe el tema 2</label>
                <input type="text" id="tema2" name="tema2" placeholder="Escribe aquí" rows="4" required></input>
            </div>
            <div class="field-group2">
            <label for="palabra1">Escribe la palabra 1:</label>
            <input type="text" id="palabra1_2" name="palabra1_2" placeholder="Palabra 1" required>
            </div>
            <div class="field-group2">
            <label for="palabra1">Escribe la palabra 2:</label>
            <input type="text" id="palabra2_2" name="palabra2_2" placeholder="Palabra 2" required>
            </div>
            <div class="field-group2">
            <label for="palabra1">Escribe la palabra 3:</label>
            <input type="text" id="palabra3_2" name="palabra3_2" placeholder="Palabra 3" required>
            </div>

            <div class="botones-formulario">
                <button id="enviar" type="submit">Guardar Cambios</button>
                <button id="cancelar" type="button" onclick="noMostrarFormulario()">Cancelar</button>
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
        var formulario = document.getElementById('form-container4');
        if (formulario.style.display === 'none') {
            formulario.style.display = 'block';
        } else {
            formulario.style.display = 'none';
        }
    }

    function noMostrarFormulario() {
        var formulario = document.getElementById('form-container4' );
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