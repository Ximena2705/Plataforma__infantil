<?php
include('../../../header.php');
include('../../../conexion.php'); // Incluir conexión

if (!isset($_SESSION['documento'])) {
    header("Location: ../../../login.php");
    exit();
}

// Obtener el documento y tipo de persona de la sesión
$documento = $_SESSION['documento'];
$tipo_persona = $_SESSION['tipo_persona'];

// Inicializar las variables
$grado = '';


if ($tipo_persona == 'estudiante') {
    $sql = "SELECT grado FROM estudiante WHERE tarjeta_identidad = '$documento'";
    $resultado = $conn->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $grado = $row['grado'];
    }
}
// ID de juego
$id2 = "juego2_esp1";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo2 = $_POST['titulo2'] ?? '';
    $descripcion2 = $_POST['descripcion2'] ?? '';

    // Preguntas y respuestas
    $preguntas = [
        "pregunta1" => $_POST['pregunta1'] ?? '',
        "pregunta2" => $_POST['pregunta2'] ?? '',
        "pregunta3" => $_POST['pregunta3'] ?? ''
    ];

    $r_correcta1 = $_POST['respuesta1_1'] ?? '';
    $r_falsa1 = $_POST['respuesta1_2'] ?? '';
    $r_falsa11 = $_POST['respuesta1_3'] ?? '';

    $r_correcta2 = $_POST['respuesta2_1'] ?? '';
    $r_falsa2 = $_POST['respuesta2_2'] ?? '';
    $r_falsa22 = $_POST['respuesta2_3'] ?? '';

    $r_correcta3 = $_POST['respuesta3_1'] ?? '';
    $r_falsa3 = $_POST['respuesta3_2'] ?? '';
    $r_falsa33 = $_POST['respuesta3_3'] ?? '';

    // Query de actualización
    $sql = "UPDATE juego2 SET 
                titulo2 = ?, descripcion2 = ?, 
                pregunta1 = ?, r_correcta1 = ?, r_falsa1 = ?, r_falsa11 = ?, 
                pregunta2 = ?, r_correcta2 = ?, r_falsa2 = ?, r_falsa22 = ?, 
                pregunta3 = ?, r_correcta3 = ?, r_falsa3 = ?, r_falsa33 = ? 
            WHERE id2 = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssssssssss",
        $titulo2, $descripcion2,
        $preguntas['pregunta1'], $r_correcta1, $r_falsa1, $r_falsa11,
        $preguntas['pregunta2'], $r_correcta2, $r_falsa2, $r_falsa22,
        $preguntas['pregunta3'], $r_correcta3, $r_falsa3, $r_falsa33,
        $id2
    );

    if ($stmt->execute()) {
        echo "✅ Datos actualizados correctamente.";
    } else {
        echo "❌ Error al actualizar los datos: " . $stmt->error;
    }
}




// Consulta SQL para obtener los datos del juego específico
$sql = "SELECT * FROM juego2 WHERE id2 = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id2);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();

    // Asignar valores a variables
    $titulo2 = $row['titulo2'];
    $descripcion2 = $row['descripcion2'];

    // Preguntas y respuestas
    $pregunta1 = $row['pregunta1'];
    $r_correcta1 = $row['r_correcta1'];
    $r_falsa1 = $row['r_falsa1'];
    $r_falsa11 = $row['r_falsa11'];

    $pregunta2 = $row['pregunta2'];
    $r_correcta2 = $row['r_correcta2'];
    $r_falsa2 = $row['r_falsa2'];
    $r_falsa22 = $row['r_falsa22'];

    $pregunta3 = $row['pregunta3'];
    $r_correcta3 = $row['r_correcta3'];
    $r_falsa3 = $row['r_falsa3'];
    $r_falsa33 = $row['r_falsa33'];

    // Aleatorizar opciones de respuesta
    $opciones1 = [
        ["texto" => $r_correcta1, "correcto" => true],
        ["texto" => $r_falsa1, "correcto" => false],
        ["texto" => $r_falsa11, "correcto" => false]
    ];
    shuffle($opciones1); // Mezclar opciones

    $opciones2 = [
        ["texto" => $r_correcta2, "correcto" => true],
        ["texto" => $r_falsa2, "correcto" => false],
        ["texto" => $r_falsa22, "correcto" => false]
    ];
    shuffle($opciones2); // Mezclar opciones

    $opciones3 = [
        ["texto" => $r_correcta3, "correcto" => true],
        ["texto" => $r_falsa3, "correcto" => false],
        ["texto" => $r_falsa33, "correcto" => false]
    ];
    shuffle($opciones3); // Mezclar opciones

} else {
    echo "No se encontró el juego.";
    exit();
}
    $stmt->close();
    $conn->close();
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Español | Actividad 2</title>
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
<div id="juego2"> 
    <h3 class="titulo-actividad" id="titulo" ><?php echo htmlspecialchars($titulo2); ?></h3> 
    <h3><?php echo htmlspecialchars($descripcion2); ?></h3>
    <div class="grid-container2">
        <div class="juego-container" id="contenedor1">
            <!--<img src="../../../imagenes/juegos/mamá.webp" alt="" id="imagen-pregunta">-->
            <!--<h1>Ma </h1><span data-correcto="true"> </span>-->
            <h1><?php echo htmlspecialchars($pregunta1); ?></h1>
            <br>
            <div class="opciones">
                <?php foreach ($opciones1 as $opcion): ?>
                    <div class="opcion" data-correcto="<?php echo $opcion['correcto'] ? 'true' : 'false'; ?>">
                        <?php echo htmlspecialchars($opcion['texto']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="juego-container" id="contenedor2">
            <!--<img src="../../../imagenes/juegos/papá.webp" alt="" id="imagen-pregunta">-->
            <!--<h1>Pa </h1><span data-correcto="true"> </span>-->
            <h1><?php echo htmlspecialchars($pregunta2); ?></h1>
            <br>
            <div class="opciones">
                <?php foreach ($opciones2 as $opcion): ?>
                    <div class="opcion" data-correcto="<?php echo $opcion['correcto'] ? 'true' : 'false'; ?>">
                        <?php echo htmlspecialchars($opcion['texto']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="juego-container" id="contenedor3">
            <!--<img src="../../../imagenes/juegos/sapo.webp" alt="" id="imagen-pregunta">-->
            <!--<h1> Sa </h1><span data-correcto="true"> </span>-->
            <h1><?php echo htmlspecialchars($pregunta3); ?></h1>
            <br>
            <div class="opciones">
                <?php foreach ($opciones3 as $opcion): ?>
                    <div class="opcion" data-correcto="<?php echo $opcion['correcto'] ? 'true' : 'false'; ?>">
                        <?php echo htmlspecialchars($opcion['texto']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
    </div>
    <div class="botones-juego">
        <button id="verificar">Comprobar</button>
        <button id="reiniciar">Intentar de nuevo</button>
    </div>
    <br>
    <p id="resultado"></p>
    <br>
    <br>
</div>

    <div id="form-container4" style="display:none;">
        <h1>Editar actividad</h1>
        <!-- Formulario -->
        <form action="" method="POST">
            <input type="hidden" name="id2" value="juego2_esp1">
            <div class="field-group2">
                <label for="titulo2">Título del Juego:</label>
                <input type="text" id="titulo2" name="titulo2" placeholder="Título del juego" required>
            </div>
            <div class="field-group2">
                <label for="descripcion2">Descripción:</label>
                <textarea id="descripcion2" name="descripcion2" placeholder="Escribe una descripción del juego" rows="4" required></textarea>
            </div>
            <!-- Preguntas y respuestas 1-->
            <h3>Pregunta 1</h3>
            <div class="field-group2">
                <label for="pregunta1">Pregunta:</label>
                <input type="text" id="pregunta1" name="pregunta1" placeholder="Escribe la pregunta 1:" rows="4" required></input>
            </div>
            <div class="field-group2">
                <label for="respuesta1_1">Respuesta 1:</label>
                <input type="text" id="respuesta1_1" name="respuesta1_1" placeholder="Correcta" rows="4" required></input>
            </div>
            <div class="field-group2">
                <label for="respuesta1_2">Respuesta 2:</label>
                <input type="text" id="respuesta1_2" name="respuesta1_2" placeholder="Falsa" rows="4" required></input>
            </div>
            <div class="field-group2">
                <label for="respuesta1_3">Respuesta 3:</label>
                <input type="text" id="respuesta1_3" name="respuesta1_3" placeholder="Falsa" rows="4" required></input>
            </div>
            <br>
            <!-- Preguntas y respuestas 2-->
            <h3>Pregunta 2</h3>
            <div class="field-group2">
                <label for="pregunta2">Pregunta:</label>
                <input type="text" id="pregunta2" name="pregunta2" placeholder="Escribe la pregunta 2:" rows="4" required></input>
            </div>
            <div class="field-group2">
                <label for="respuesta2_1">Respuesta 1:</label>
                <input type="text" id="respuesta2_1" name="respuesta2_1" placeholder="Correcta" rows="4" required></input>
            </div>
            <div class="field-group2">
                <label for="respuesta2_2">Respuesta 2:</label>
                <input type="text" id="respuesta2_2" name="respuesta2_2" placeholder="Falsa" rows="4" required></input>
            </div>
            <div class="field-group2">
                <label for="respuesta2_3">Respuesta 3:</label>
                <input type="text" id="respuesta2_3" name="respuesta2_3" placeholder="Falsa" rows="4" required></input>
            </div>
            <br>
            <!-- Preguntas y respuestas 3-->
            <h3>Pregunta 3</h3>
            <div class="field-group2">
                <label for="pregunta3">Pregunta:</label>
                <input type="text" id="pregunta3" name="pregunta3" placeholder="Escribe la pregunta 3:" rows="4" required></input>
            </div>
            <div class="field-group2">
                <label for="respuesta3_1">Respuesta 1:</label>
                <input type="text" id="respuesta3_1" name="respuesta3_1" placeholder="Correcta" rows="4" required></input>
            </div>
            <div class="field-group2">
                <label for="respuesta3_2">Respuesta 2:</label>
                <input type="text" id="respuesta3_2" name="respuesta3_2" placeholder="Falsa" rows="4" required></input>
            </div>
            <div class="field-group2">
                <label for="respuesta3_3">Respuesta 3:</label>
                <input type="text" id="respuesta3_3" name="respuesta3_3" placeholder="Falsa" rows="4" required></input>
            </div>
            <br>

            <div class="botones-formulario">
                <button id="enviar">Guardar Cambios</button>
                <button id="cancelar" onclick= "noMostrarFormulario()">Cancelar</button>
            </div>

        </form>

        
    </div>
    <br>
    <br>
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