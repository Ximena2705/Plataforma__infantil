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
    <h3 class="titulo-actividad" id="titulo" >Actividad:</h3> 
    <h3>Descripción </h3>
    <div class="grid-container2">
        <div class="juego-container" id="contenedor1">
            <!--<img src="../../../imagenes/juegos/mamá.webp" alt="" id="imagen-pregunta">-->
            <!--<h1>Ma </h1><span data-correcto="true"> </span>-->
            <h1>Ma </h1>
            <br>
            <div class="opciones">
                <div class="opcion" data-correcto="false">pa</div>
                <div class="opcion" data-correcto="false">sa</div>
                <div class="opcion" data-correcto="true">má</div>
            </div>
        </div>

        <div class="juego-container" id="contenedor2">
            <!--<img src="../../../imagenes/juegos/papá.webp" alt="" id="imagen-pregunta">-->
            <!--<h1>Pa </h1><span data-correcto="true"> </span>-->
            <h1>Pa </h1>
            <br>
            <div class="opciones">
                <div class="opcion" data-correcto="false">má</div>
                <div class="opcion" data-correcto="true">pá</div>
                <div class="opcion" data-correcto="false">to</div>
            </div>
        </div>

        <div class="juego-container" id="contenedor3">
            <!--<img src="../../../imagenes/juegos/sapo.webp" alt="" id="imagen-pregunta">-->
            <!--<h1> Sa </h1><span data-correcto="true"> </span>-->
            <h1> Sa </h1>
            <br>
            <div class="opciones">
                <div class="opcion" data-correcto="true">po</div>
                <div class="opcion" data-correcto="false">lón</div>
                <div class="opcion" data-correcto="false">lsa</div>

            </div>
        </div>

        <!--<div class="juego-container" id="contenedor4">
            <img src="../../../imagenes/juegos/mesa.webp" alt="" id="imagen-pregunta">-->
            <!--<h1>Me </h1><span data-correcto="true"> </span>
            <h1>Me </h1>
            <br>
            <div class="opciones">
                <div class="opcion" data-correcto="false">lón</div>
                <div class="opcion" data-correcto="true">sa</div>
                <div class="opcion" data-correcto="false">ta</div>

            </div>
        </div>-->

        <!--<div class="juego-container" id="contenedor5">
            <img src="../../../imagenes/juegos/pato.webp" alt="" id="imagen-pregunta">-->
            <!--<h1> Pa</h1><span data-correcto="true"> </span>
            <h1> Pa</h1>
            <br>
            <div class="opciones">
                <div class="opcion" data-correcto="true">to</div>
                <div class="opcion" data-correcto="false">sa</div>
                <div class="opcion" data-correcto="false">lo</div>
            </div>
        </div>-->

        <!--<div class="juego-container" id="contenedor6">
            <img src="../../../imagenes/juegos/rosa.webp" alt="" id="imagen-pregunta">-->
            <!--<h1> Ro</h1><span data-correcto="true"> </span>
            <h1> Ro</h1>
            <br>
            <div class="opciones">
                <div class="opcion" data-correcto="false">pa</div>
                <div class="opcion" data-correcto="true">sa</div>
                <div class="opcion" data-correcto="false">to</div>
            </div>
        </div>-->
        
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
            <div class="field-group2">
                <label for="tituloJuego">Título del Juego:</label>
                <input type="text" id="tituloJuego" name="tituloJuego" placeholder="Título del juego" required>
            </div>
            <div class="field-group2">
                <label for="descripcionJuego">Descripción:</label>
                <textarea id="descripcionJuego" name="descripcionJuego" placeholder="Escribe una descripción del juego" rows="4" required></textarea>
            </div>
            <!-- Preguntas y respuestas 1-->
            <h3>Pregunta 1</h3>
            <div class="field-group2">
                <label for="descripcionJuego">Pregunta:</label>
                <input type="text" id="preguntaJuego" name="preguntaJuego" placeholder="Escribe la pregunta 1:" rows="4" required></input>
            </div>
            <div class="field-group2">
                <label for="descripcionJuego">Respuesta 1:</label>
                <input type="text" id="respuestaJuego" name="respuestaJuego" placeholder="Escribe una respuesta y señala si es correcta o no:" rows="4" required></input>
                <select id="tipo-respuesta" name="tipo-respuesta"  required >
                    <option value="">¿Correcta o falsa?</option>
                    <option value="correcta">Correcta</option>
                    <option value="falsa">Falsa</option>
                </select>
            </div>
            <div class="field-group2">
                <label for="descripcionJuego">Respuesta 2:</label>
                <input type="text" id="respuestaJuego" name="respuestaJuego" placeholder="Escribe una respuesta y señala si es correcta o no:" rows="4" required></input>
                <select name="tipo-respuesta" id="tipo-respuesta" required >
                    <option value="">¿Correcta o falsa?</option>
                    <option value="correcta">Correcta</option>
                    <option value="falsa">Falsa</option>
                </select>
            </div>
            <div class="field-group2">
                <label for="descripcionJuego">Respuesta 3:</label>
                <input type="text" id="respuestaJuego" name="respuestaJuego" placeholder="Escribe una respuesta y señala si es correcta o no:" rows="4" required></input>
                <select name="tipo-respuesta" id="tipo-respuesta" required >
                    <option value="">¿Correcta o falsa?</option>
                    <option value="correcta">Correcta</option>
                    <option value="falsa">Falsa</option>
                </select>
            </div>
            <br>
            <!-- Preguntas y respuestas 2-->
            <h3>Pregunta 2</h3>
            <div class="field-group2">
                <label for="descripcionJuego">Pregunta:</label>
                <input type="text" id="preguntaJuego" name="preguntaJuego" placeholder="Escribe la pregunta 1:" rows="4" required></input>
            </div>
            <div class="field-group2">
                <label for="descripcionJuego">Respuesta 1:</label>
                <input type="text" id="respuestaJuego" name="respuestaJuego" placeholder="Escribe una respuesta y señala si es correcta o no:" rows="4" required></input>
                <select id="tipo-respuesta" name="tipo-respuesta"  required >
                    <option value="">¿Correcta o falsa?</option>
                    <option value="correcta">Correcta</option>
                    <option value="falsa">Falsa</option>
                </select>
            </div>
            <div class="field-group2">
                <label for="descripcionJuego">Respuesta 2:</label>
                <input type="text" id="respuestaJuego" name="respuestaJuego" placeholder="Escribe una respuesta y señala si es correcta o no:" rows="4" required></input>
                <select name="tipo-respuesta" id="tipo-respuesta" required >
                    <option value="">¿Correcta o falsa?</option>
                    <option value="correcta">Correcta</option>
                    <option value="falsa">Falsa</option>
                </select>
            </div>
            <div class="field-group2">
                <label for="descripcionJuego">Respuesta 3:</label>
                <input type="text" id="respuestaJuego" name="respuestaJuego" placeholder="Escribe una respuesta y señala si es correcta o no:" rows="4" required></input>
                <select name="tipo-respuesta" id="tipo-respuesta" required >
                    <option value="">¿Correcta o falsa?</option>
                    <option value="correcta">Correcta</option>
                    <option value="falsa">Falsa</option>
                </select>
            </div>
            <br>
            <!-- Preguntas y respuestas 3-->
            <h3>Pregunta 3</h3>
            <div class="field-group2">
                <label for="descripcionJuego">Pregunta:</label>
                <input type="text" id="preguntaJuego" name="preguntaJuego" placeholder="Escribe la pregunta 1:" rows="4" required></input>
            </div>
            <div class="field-group2">
                <label for="descripcionJuego">Respuesta 1:</label>
                <input type="text" id="respuestaJuego" name="respuestaJuego" placeholder="Escribe una respuesta y señala si es correcta o no:" rows="4" required></input>
                <select id="tipo-respuesta" name="tipo-respuesta"  required >
                    <option value="">¿Correcta o falsa?</option>
                    <option value="correcta">Correcta</option>
                    <option value="falsa">Falsa</option>
                </select>
            </div>
            <div class="field-group2">
                <label for="descripcionJuego">Respuesta 2:</label>
                <input type="text" id="respuestaJuego" name="respuestaJuego" placeholder="Escribe una respuesta y señala si es correcta o no:" rows="4" required></input>
                <select name="tipo-respuesta" id="tipo-respuesta" required >
                    <option value="">¿Correcta o falsa?</option>
                    <option value="correcta">Correcta</option>
                    <option value="falsa">Falsa</option>
                </select>
            </div>
            <div class="field-group2">
                <label for="descripcionJuego">Respuesta 3:</label>
                <input type="text" id="respuestaJuego" name="respuestaJuego" placeholder="Escribe una respuesta y señala si es correcta o no:" rows="4" required></input>
                <select name="tipo-respuesta" id="tipo-respuesta" required >
                    <option value="">¿Correcta o falsa?</option>
                    <option value="correcta">Correcta</option>
                    <option value="falsa">Falsa</option>
                </select>
            </div>
            <br>
            <!-- Preguntas y respuestas 4-->
            <h3>Pregunta 4</h3>
            <div class="field-group2">
                <label for="descripcionJuego">Pregunta:</label>
                <input type="text" id="preguntaJuego" name="preguntaJuego" placeholder="Escribe la pregunta 1:" rows="4" required></input>
            </div>
            <div class="field-group2">
                <label for="descripcionJuego">Respuesta 1:</label>
                <input type="text" id="respuestaJuego" name="respuestaJuego" placeholder="Escribe una respuesta y señala si es correcta o no:" rows="4" required></input>
                <select id="tipo-respuesta" name="tipo-respuesta"  required >
                    <option value="">¿Correcta o falsa?</option>
                    <option value="correcta">Correcta</option>
                    <option value="falsa">Falsa</option>
                </select>
            </div>
            <div class="field-group2">
                <label for="descripcionJuego">Respuesta 2:</label>
                <input type="text" id="respuestaJuego" name="respuestaJuego" placeholder="Escribe una respuesta y señala si es correcta o no:" rows="4" required></input>
                <select name="tipo-respuesta" id="tipo-respuesta" required >
                    <option value="">¿Correcta o falsa?</option>
                    <option value="correcta">Correcta</option>
                    <option value="falsa">Falsa</option>
                </select>
            </div>
            <div class="field-group2">
                <label for="descripcionJuego">Respuesta 3:</label>
                <input type="text" id="respuestaJuego" name="respuestaJuego" placeholder="Escribe una respuesta y señala si es correcta o no:" rows="4" required></input>
                <select name="tipo-respuesta" id="tipo-respuesta" required >
                    <option value="">¿Correcta o falsa?</option>
                    <option value="correcta">Correcta</option>
                    <option value="falsa">Falsa</option>
                </select>
            </div>
            <br>
        </form>

        <div class="botones-formulario">
             <button id="enviar">Guardar Cambios</button>
            <button id="cancelar" onclick= "noMostrarFormulario()">Cancelar</button>
        </div>
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