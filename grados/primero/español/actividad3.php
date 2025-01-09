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



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Español | Actividad 3</title>
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
            <button id="btnEditar" onclick="mostrarFormulario()">Editar</button>
        </div>
    <?php endif; ?>
</div>
<div id="juego3">
    <h3 class="titulo-actividad" id="titulo" >Actividad: </h3> 
    <h3>Arrastra donde corresponde </h3>

    <div class="contenedorElementos">
        <span id="elemento1" class="elemento" draggable="true" ondragstart="arrastrar(event)">Gato</span>
        <span id="elemento2" class="elemento" draggable="true" ondragstart="arrastrar(event)">Perro</span>
        <span id="elemento3" class="elemento" draggable="true" ondragstart="arrastrar(event)">Conejo</span>
        <span id="elemento4" class="elemento" draggable="true" ondragstart="arrastrar(event)">Elefante</span>
        <span id="elemento5" class="elemento" draggable="true" ondragstart="arrastrar(event)">Jirafa</span>
        <span id="elemento6" class="elemento" draggable="true" ondragstart="arrastrar(event)">León</span>
    </div>


    <div class="grupos">
    <div class="grupo" id="grupo1" ondrop="soltar(event)" ondragover="permitirSoltar(event)">
        <h2>Domésticos</h2>
        <!-- Aquí se agregarán las imágenes de los animales domésticos -->
    </div>
    <div class="grupo" id="grupo2" ondrop="soltar(event)" ondragover="permitirSoltar(event)">
        <h2>Salvajes</h2>
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
            <div class="field-group2">
                <label for="tituloJuego">Título del Juego:</label>
                <input type="text" id="tituloJuego" name="tituloJuego" placeholder="Título del juego" required>
            </div>
            <div class="field-group2">
                <label for="descripcionJuego">Descripción:</label>
                <textarea id="descripcionJuego" name="descripcionJuego" placeholder="Escribe una descripción del juego" rows="4" required></textarea>
            </div>
            <br>
            <!-- Preguntas y respuestas 1-->
            <div class="field-group2">
                <label for="descripcionJuego">Tipo 1:</label>
                <input type="text" id="preguntaJuego" name="preguntaJuego" placeholder="Escribe el tipo 1" rows="4" required></input>
            </div>
            <div class="field-group2">
                <label for="descripcionJuego">Tipo 2:</label>
                <input type="text" id="preguntaJuego" name="preguntaJuego" placeholder="Escribe el tipo 2" rows="4" required></input>
            </div>
            <br>
            <h3>Palabra 1</h3>
            <div class="field-group2">
            <label for="palabra1">Escribe la palabra:</label>
            <input type="text" id="palabra1" name="palabra1" placeholder="Escribe la palabra" required>
                <select id="tipo-respuesta" name="tipo-respuesta"  required >
                    <option value="">¿A qué tipo pertenece?</option>
                    <option value="correcta">Tipo 1</option>
                    <option value="falsa">Tipo 2</option>
                </select>
            </div>
            
            <br>
            <h3>Palabra 2</h3>
            <div class="field-group2">
            <label for="palabra1">Escribe la palabra:</label>
            <input type="text" id="palabra2" name="palabra2" placeholder="Escribe la palabra" required>
                <select id="tipo-respuesta" name="tipo-respuesta"  required >
                    <option value="">¿A qué tipo pertenece?</option>
                    <option value="correcta">Tipo 1</option>
                    <option value="falsa">Tipo 2</option>
                </select>
            </div>
            
            <br>
            <h3>Palabra 3</h3>
            <div class="field-group2">
            <label for="palabra1">Escribe la palabra:</label>
            <input type="text" id="palabra3" name="palabra3" placeholder="Escribe la palabra" required>
                <select id="tipo-respuesta" name="tipo-respuesta"  required >
                    <option value="">¿A qué tipo pertenece?</option>
                    <option value="correcta">Tipo 1</option>
                    <option value="falsa">Tipo 2</option>
                </select>
            </div>

            <br>
            <h3>Palabra 4</h3>
            <div class="field-group2">
            <label for="palabra1">Escribe la palabra:</label>
            <input type="text" id="palabra4" name="palabra4" placeholder="Escribe la palabra" required>
                <select id="tipo-respuesta" name="tipo-respuesta"  required >
                    <option value="">¿A qué tipo pertenece?</option>
                    <option value="correcta">Tipo 1</option>
                    <option value="falsa">Tipo 2</option>
                </select>
            </div>

            <br>
            <h3>Palabra 5</h3>
            <div class="field-group2">
            <label for="palabra1">Escribe la palabra:</label>
            <input type="text" id="palabra5" name="palabra5" placeholder="Escribe la palabra" required>
                <select id="tipo-respuesta" name="tipo-respuesta"  required >
                    <option value="">¿A qué tipo pertenece?</option>
                    <option value="correcta">Tipo 1</option>
                    <option value="falsa">Tipo 2</option>
                </select>
            </div>

            <br>
            <h3>Palabra 6</h3>
            <div class="field-group2">
            <label for="palabra1">Escribe la palabra:</label>
            <input type="text" id="palabra6" name="palabra6" placeholder="Escribe la palabra" required>
                <select id="tipo-respuesta" name="tipo-respuesta"  required >
                    <option value="">¿A qué tipo pertenece?</option>
                    <option value="correcta">Tipo 1</option>
                    <option value="falsa">Tipo 2</option>
                </select>
            </div>
        </form>

        <div class="botones-formulario">
             <button id="enviar">Guardar Cambios</button>
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