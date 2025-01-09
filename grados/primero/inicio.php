<?php

include('../../header.php');

if (!isset($_SESSION['documento'])) {
    header("Location: ../../login.php");
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
    <title>Primero | Inicio</title>
    <link rel="stylesheet" href="../../stilos.css">
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
        <h1><button onclick="window.location.href='../../inicio.php'">Inicio</button></h1>
        <?php if ($tipo_persona == 'admin' || $tipo_persona == 'docente'): ?>
            <h1><button onclick="window.location.href='../../grados/primero/inicio.php'">Primero</button></h1>
            <h1><button onclick="window.location.href='../../grados/segundo/inicio.php'">Segundo</button></h1>
            <h1><button onclick="window.location.href='../../grados/tercero/inicio.php'">Tercero</button></h1>
        <?php endif; ?>

        <?php if ($tipo_persona == 'estudiante'): ?>
            <?php 
                // Determinar la página según el grado
                $paginaGrado = '';
                if ($grado == 'primero' || $grado == 'Primero') {
                    $paginaGrado = 'grados/primero/inicio.php';
                } elseif ($grado == 'segundo' || $grado == 'Segundo') {
                    $paginaGrado = 'grados/segundo/inicio.php';
                } elseif ($grado == 'tercero' || $grado == 'Tercero') {
                    $paginaGrado = 'grados/tercero/inicio.php';
                }
            ?>
            <h1><button onclick="window.location.href='<?php echo $paginaGrado; ?>'">Asignaturas</button></h1>
        <?php endif; ?>
    </div>
    </div>  
    <h3 class="titulo-grado" id="titulo" >Grado: Primero</h3>  
    
     
  <!-- Aquí colocamos la imagen dentro de un contenedor -->
  <div class="grid-container">
        <!-- Tarjeta de ejemplo -->
        <div class="card">
            <a href="../primero/español/español.php" style="text-decoration: none; color: inherit;">
                <div class="card-header" ></div>
                <div class="card-body">
                    <img src="../../imagenes/asignaturas/primero_español.webp" alt="Descripción de la imagen">
                    <h3>ESPAÑOL</h3>
                    <p>GRADO: PRIMERO</p>
                    <!-- <span>3% completado</span>-->
                </div>
            </a>
        </div>

        <div class="card">
            <a href="../primero/matematicas/matematicas.php" style="text-decoration: none; color: inherit;">
                <div class="card-header" ></div>
                <div class="card-body">
                    <img src="../../imagenes/asignaturas/primero_mate.webp" alt="Descripción de la imagen">
                    <h3>MATEMÁTICAS</h3>
                    <p>GRADO: PRIMERO</p>
                <!-- <span>3% completado</span>-->
                </div>
            </a>
        </div>
        <!-- Agrega más tarjetas aquí -->
        <div class="card">
            <a href="../primero/matematicas/matematicas.php" style="text-decoration: none; color: inherit;">
                <div class="card-header" ></div>
                <div class="card-body">
                <img src="../../imagenes/asignaturas/primero_ingles.webp" alt="Descripción de la imagen">    
                <h3>INGLÉS</h3>
                    <p>GRADO: PRIMERO</p>
                <!-- <span>3% completado</span>-->
                </div>
            </a>
        </div>
        <div class="card">
            <a href="../primero/matematicas/matematicas.php" style="text-decoration: none; color: inherit;">
                <div class="card-header" ></div>
                <div class="card-body">
                <img src="../../imagenes/asignaturas/primero_naturales.webp" alt="Descripción de la imagen">
                    <h3>CIENCIAS NATURALES</h3>
                    <p>GRADO: PRIMERO</p>
                <!-- <span>3% completado</span>-->
                </div>
            </a>
        </div>
        <div class="card">
            <a href="../primero/matematicas/matematicas.php" style="text-decoration: none; color: inherit;">
                <div class="card-header" ></div>
                <div class="card-body">
                <img src="../../imagenes/asignaturas/primero_sociales.webp" alt="Descripción de la imagen">
                    <h3>CIENCIAS SOCIALES</h3>
                    <p>GRADO: PRIMERO</p>
                <!-- <span>3% completado</span>-->
                </div>
            </a>
        </div>
        <div class="card">
            <a href="../primero/matematicas/matematicas.php" style="text-decoration: none; color: inherit;">
                <div class="card-header" ></div>
                <div class="card-body">
                <img src="../../imagenes/asignaturas/primero_info.webp" alt="Descripción de la imagen">
                    <h3>INFORMATICA</h3>
                    <p>GRADO: PRIMERO</p>
                <!-- <span>3% completado</span>-->
                </div>
            </a>
        </div>
    </div>

    <!-- Contenedor de perfil que se muestra al hacer clic en el botón -->
    <div class="door-content" id="doorContent" style="display: none;">
<br><br>
        <div class="button-group">
            <!--    <button onclick="showAsignaturas()">Asignaturas</button> -->
            <button onclick="window.location.href='../../perfil.php'">
                <i class="fas fa-user"></i>&nbsp;&nbsp;Mi perfil
            </button>
            
        </div>

       
        <div class="bottom-buttons">
            <?php if ($tipo_persona == 'admin'): ?>
                <button class="icon-button" onclick="window.location.href='../../registro.php'">
                    <i class="fas fa-user-plus"></i> Ir a Registro
                </button>
            <?php endif; ?>
            <button class="icon-button" onclick="window.location.href='../../login.php'">
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

</body>
</html>