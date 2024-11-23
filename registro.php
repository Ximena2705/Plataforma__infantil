<?php

include("conexion.php");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$mensaje = ''; // Variable para almacenar mensajes
$mensaje_tipo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger el tipo de persona
    $tipoPersona = $_POST['tipo_persona'] ?? '';

    // Inicializar variables
    $cedula = $doc_nombre1 = $doc_nombre2 = $doc_apellido1 = $doc_apellido2 = $asignatura = '';
    $tarjetaIdentidad = $est_nombre1 = $est_nombre2 = $est_apellido1 = $est_apellido2 = $grado = '';

    // Si es docente, recoge sus datos
    if ($tipoPersona === 'docente') {
        $cedula = mysqli_real_escape_string($conn, $_POST['cedula']);
        $doc_nombre1 = mysqli_real_escape_string($conn, $_POST['doc_nombre1']);
        $doc_nombre2 = mysqli_real_escape_string($conn, $_POST['doc_nombre2']);
        $doc_apellido1 = mysqli_real_escape_string($conn, $_POST['doc_apellido1']);
        $doc_apellido2 = mysqli_real_escape_string($conn, $_POST['doc_apellido2']);
        $asignatura = mysqli_real_escape_string($conn, $_POST['asignatura']);

        // Verificar si la cédula ya existe
        $sqlVerificarCedula = "SELECT COUNT(*) AS conteo FROM docente WHERE cedula = '$cedula'";
        $resultado = $conn->query($sqlVerificarCedula);
        $row = $resultado->fetch_assoc();
        
        if ($row['conteo'] > 0) {
            $mensaje = "Este documento ya existe.";
            $mensaje_tipo = "error"; // Mensaje de error
        }  
        else {
                // Asignar cédula como usuario y contraseña
                $usuario = $cedula;
                $contraseña = $cedula;

                // Insertar en tabla docente
                $sqlDocente = "INSERT INTO docente (cedula, doc_nombre1, doc_nombre2, doc_apellido1, doc_apellido2, asignatura) 
                               VALUES ('$cedula', '$doc_nombre1', '$doc_nombre2', '$doc_apellido1', '$doc_apellido2', '$asignatura')";

                if ($conn->query($sqlDocente) === TRUE) {
                    // Insertar en tabla persona
                    $sqlPersona = "INSERT INTO persona (documento, tipo_persona, usuario, contraseña) 
                                   VALUES ('$cedula', 'docente', '$usuario', '$contraseña')";
                    if ($conn->query($sqlPersona) === TRUE) {
                        $mensaje = "Docente registrado con éxito. Usuario: $usuario";
                        $mensaje_tipo = "exito"; // Mensaje de éxito
                    } else {
                        $mensaje = "Error al registrar en tabla persona: " . $conn->error;
                        $mensaje_tipo = "error"; // Mensaje de error
                    }
                } else {
                    $mensaje = "Error al registrar docente: " . $conn->error;
                    $mensaje_tipo = "error"; // Mensaje de error
                }
            }
        }
     
    // Código de registro para estudiantes
    // Código de registro para estudiantes
elseif ($tipoPersona === 'estudiante') {
    // Recuperar los datos del estudiante
    $tarjetaIdentidad = mysqli_real_escape_string($conn, $_POST['tarjeta_identidad']);
    $est_nombre1 = mysqli_real_escape_string($conn, $_POST['est_nombre1']);
    $est_nombre2 = mysqli_real_escape_string($conn, $_POST['est_nombre2']);
    $est_apellido1 = mysqli_real_escape_string($conn, $_POST['est_apellido1']);
    $est_apellido2 = mysqli_real_escape_string($conn, $_POST['est_apellido2']);
    $grado = mysqli_real_escape_string($conn, $_POST['grado']);
    
    // Verificar si la tarjeta de identidad ya existe
    $sqlVerificarTarjeta = "SELECT COUNT(*) AS conteo FROM estudiante WHERE tarjeta_identidad = '$tarjetaIdentidad'";
    $resultado = $conn->query($sqlVerificarTarjeta);
    $row = $resultado->fetch_assoc();

    if ($row['conteo'] > 0) {
        $mensaje = "Este documento ya existe.";
        $mensaje_tipo = "error"; // Mensaje de error
    } else {
        // Crear usuario base
        $usuarioBase = strtolower(substr($est_nombre1, 0, 2) . "." . $est_apellido1);
        
        // Inicializar variables
        $usuario = $usuarioBase;
        $contador = 0; // Contador para modificar el usuario en caso de duplicados

        // Generar nuevo usuario si ya existe en la base de datos
        while (true) {
            // Verificar si el usuario ya existe
            $sqlVerificarUsuario = "SELECT COUNT(*) AS conteo FROM persona WHERE usuario = '$usuario'";
            $resultado = $conn->query($sqlVerificarUsuario);
            $row = $resultado->fetch_assoc();

            if ($row['conteo'] == 0) {
                break; // El usuario no existe, podemos usarlo
            }

            // Si ya existe, generar nuevo usuario
            if (!empty($est_nombre2)) {
                // Si tiene segundo nombre
                $usuario = strtolower(substr($est_nombre1, 0, 2) . "." . substr($est_nombre2, 0, 2) . "." . $est_apellido1);
            } else {
                // Si no tiene segundo nombre, usar el segundo apellido
                $usuario = strtolower(substr($est_nombre1, 0, 2) . "." . $est_apellido1 . "." . substr($est_apellido2, 0, 2));
            }

            $contador++;
        }
        
        // Asignar la contraseña
        $contraseña = 'liceomoderno2025';
    
        // Insertar los datos en la base de datos
        $sqlInsertarEstudiante = "INSERT INTO estudiante (tarjeta_identidad, est_nombre1, est_nombre2, est_apellido1, est_apellido2, est_usuario, grado) 
                                   VALUES ('$tarjetaIdentidad', '$est_nombre1', '$est_nombre2', '$est_apellido1', '$est_apellido2', '$usuario','$grado')";
        
        if ($conn->query($sqlInsertarEstudiante) === TRUE) {
            // Insertar en tabla persona
            $sqlPersonaEstudiante = "INSERT INTO persona (documento, tipo_persona, usuario, contraseña) 
                                     VALUES ('$tarjetaIdentidad', 'estudiante', '$usuario', '$contraseña')";
            if ($conn->query($sqlPersonaEstudiante) === TRUE) {
                $mensaje = "Estudiante registrado exitosamente. Usuario: $usuario";
                $mensaje_tipo = "exito"; // Mensaje de éxito
            } else {
                $mensaje = "Error al registrar en tabla persona: " . $conn->error;
                $mensaje_tipo = "error"; // Mensaje de error
            }
        } else {
            $mensaje = "Error al registrar estudiante: " . $conn->error;
            $mensaje_tipo = "error"; // Mensaje de error
        }
    }
} else {
    $mensaje = "Por favor selecciona un tipo de persona.";
    $mensaje_tipo = "error"; // Mensaje de error
}

}
?>



<?php

include("header.php");


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="stilos.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <script>
        function mostrarCampos() {
            var tipoPersona = document.querySelector('input[name="tipo_persona"]:checked').value;
            if (tipoPersona === 'docente') {
                document.getElementById('campos_docente').style.display = 'block';
                document.getElementById('campos_estudiante').style.display = 'none';
                // Limpiar campos de estudiante
                document.getElementById('tarjeta_identidad').value = '';
                document.getElementById('est_nombre1').value = '';
                document.getElementById('est_nombre2').value = '';
                document.getElementById('est_apellido1').value = '';
                document.getElementById('est_apellido2').value = '';
                document.getElementById('grado').value = '';
            } else if (tipoPersona === 'estudiante') {
                document.getElementById('campos_docente').style.display = 'none';
                document.getElementById('campos_estudiante').style.display = 'block';
                // Limpiar campos de docente
                document.getElementById('cedula').value = '';
                document.getElementById('doc_nombre1').value = '';
                document.getElementById('doc_nombre2').value = '';
                document.getElementById('doc_apellido1').value = '';
                document.getElementById('doc_apellido2').value = '';
                document.getElementById('asignatura').value = '';
            }

            // Mostrar el botón de registrar
            botonRegistrar.style.display = "block";

        }

        function validarFormulario() {
            const tipoPersona = document.querySelector('input[name="tipo_persona"]:checked');
            if (!tipoPersona) {
                alert("Por favor, selecciona un tipo de persona.");
                return false;
            }

            // Expresión regular para validar solo letras y espacios
            const soloLetras = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/;

            if (tipoPersona.value === 'docente') {
                const cedula = document.getElementById('cedula').value.trim();
                const nombre1 = document.getElementById('doc_nombre1').value.trim();
                const nombre2 = document.getElementById('doc_nombre2').value.trim();
                const apellido1 = document.getElementById('doc_apellido1').value.trim();
                const apellido2 = document.getElementById('doc_apellido2').value.trim();
                const asignatura = document.getElementById('asignatura').value.trim();

                if (!cedula) {
                    alert("La cédula es obligatoria.");
                    return false;
                }
                if (!/^[0-9]+$/.test(cedula)) {
                    alert("La cédula debe contener solo números.");
                    return false;
                }
                if (!nombre1) {
                    alert("El primer nombre es obligatorio.");
                    return false;
                }
                if (!soloLetras.test(nombre1)) {
                    alert("El primer nombre solo debe contener letras.");
                    return false;
                }
                if (nombre2 && !soloLetras.test(nombre2)) {
                    alert("El segundo nombre, si se proporciona, solo debe contener letras.");
                    return false;
                }
                if (!apellido1) {
                    alert("El primer apellido es obligatorio.");
                    return false;
                }
                if (!soloLetras.test(apellido1)) {
                    alert("El primer apellido solo debe contener letras.");
                    return false;
                }
                if (!apellido2) {
                    alert("El segundo apellido es obligatorio.");
                    return false;
                }
                if (!soloLetras.test(apellido2)) {
                    alert("El segundo apellido solo debe contener letras.");
                    return false;
                }
                if (!asignatura) {
                    alert("La asignatura es obligatoria.");
                    return false;
                }
                if (!soloLetras.test(asignatura)) {
                    alert("La asignatura solo debe contener letras.");
                    return false;
                }
            } else if (tipoPersona.value === 'estudiante') {
                const tarjetaIdentidad = document.getElementById('tarjeta_identidad').value.trim();
                const nombre1 = document.getElementById('est_nombre1').value.trim();
                const nombre2 = document.getElementById('est_nombre2').value.trim();
                const apellido1 = document.getElementById('est_apellido1').value.trim();
                const apellido2 = document.getElementById('est_apellido2').value.trim();
                const grado = document.getElementById('grado').value;

                if (!tarjetaIdentidad) {
                    alert("La tarjeta de identidad es obligatoria.");
                    return false;
                }
                if (!/^[0-9]+$/.test(tarjetaIdentidad)) {
                    alert("La tarjeta de identidad debe contener solo números.");
                    return false;
                }
                if (!nombre1) {
                    alert("El primer nombre es obligatorio.");
                    return false;
                }
                if (!soloLetras.test(nombre1)) {
                    alert("El primer nombre solo debe contener letras.");
                    return false;
                }
                if (nombre2 && !soloLetras.test(nombre2)) {
                    alert("El segundo nombre, si se proporciona, solo debe contener letras.");
                    return false;
                }
                if (!apellido1) {
                    alert("El primer apellido es obligatorio.");
                    return false;
                }
                if (!soloLetras.test(apellido1)) {
                    alert("El primer apellido solo debe contener letras.");
                    return false;
                }
                if (!apellido2) {
                    alert("El segundo apellido es obligatorio.");
                    return false;
                }
                if (!soloLetras.test(apellido2)) {
                    alert("El segundo apellido solo debe contener letras.");
                    return false;
                }
                if (!grado) {
                    alert("Por favor, selecciona un grado.");
                    return false;
                }
            }

            // Si todas las validaciones pasan
            return true;
        }

        function limpiarMensaje() {
            const mensajeDiv = document.getElementById('mensaje');
            if (mensajeDiv) {
                mensajeDiv.innerHTML = ''; // Limpiar el contenido del mensaje
            }
        }

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
            <h1><button onclick="window.location.href='inicio.php'">Inicio</button></h1>
            <h1><button>Primero</button><h1>
            <h1><button>Segundo</button><h1>
            <h1> <button>Tercero</button><h1>
        </div>
    </div>

    <!-- Contenedor de perfil que se muestra al hacer clic en el botón -->
    <div class="door-content" id="doorContent" style="display: none;">
        <br><br>
        <div class="button-group">
            <!--    <button onclick="showAsignaturas()">Asignaturas</button> -->
            <button onclick="window.location.href='perfil.php'">
                <i class="fas fa-user"></i>&nbsp;&nbsp;Mi perfil
            </button>
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

    <!-- Contenedor para el mensaje de bienvenida -->
    <div class="form-container">
        <h1>Registro</h1>
        <form method="post" action="" onsubmit="return validarFormulario()">
            <!-- Mensaje de error -->
            <div id="mensaje" class="<?php echo (!empty($mensaje_tipo) && $mensaje_tipo === 'exito') ? 'mensaje-exito' : 'mensaje-error'; ?>">
                <?php if (!empty($mensaje)) echo $mensaje; ?>
            </div>

            <!-- Radios para seleccionar el tipo de persona -->
            <div class="radio-options">
                <label><input type="radio" name="tipo_persona" value="docente" onclick="mostrarCampos(); limpiarMensaje()"> Docente</label>
                <label><input type="radio" name="tipo_persona" value="estudiante" onclick="mostrarCampos(); limpiarMensaje()"> Estudiante</label>
            </div>

            <!-- Campos para docente -->
            <div id="campos_docente" style="display:none;">
                <h2>Datos del docente</h2>
                <div class="field-group">
                    <label>Cédula: <input type="text" name="cedula" id="cedula"></label>
                    <label>Primer nombre: <input type="text" name="doc_nombre1" id="doc_nombre1" ></label>
                    <label>Segundo nombre: <input type="text" name="doc_nombre2" id="doc_nombre2"></label>
                    <label>Primer apellido: <input type="text" name="doc_apellido1" id="doc_apellido1" ></label>
                    <label>Segundo apellido: <input type="text" name="doc_apellido2" id="doc_apellido2" ></label>
                    <label>Asignatura: <input type="text" name="asignatura" id="asignatura" ></label>
                </div>
            </div>
        <!-- Campos para estudiante -->
        <div id="campos_estudiante" style="display:none;">
            <h2>Datos del estudiante</h2>
            <div class="field-group">
                <label>Tarjeta de Identidad: <input type="text" name="tarjeta_identidad" id="tarjeta_identidad" ></label>
                <label>Primer nombre: <input type="text" name="est_nombre1" id="est_nombre1" ></label>
                <label>Segundo nombre: <input type="text" name="est_nombre2" id="est_nombre2"></label>
                <label>Primer apellido: <input type="text" name="est_apellido1" id="est_apellido1" ></label>
                <label>Segundo apellido: <input type="text" name="est_apellido2" id="est_apellido2" ></label>
                <label>Grado: 
                    <select name="grado" id="grado" >
                        <option value="">Selecciona un grado</option>
                        <option value="primero">Primero</option>
                        <option value="segundo">Segundo</option>
                        <option value="tercero">Tercero</option>
                    </select>
                </label>
            </div>
        </div>

            <br>
            <div class="boton-registrar" style="display: none;" id="botonRegistrar">
                <button type="submit">Registrar</button>
            </div>
        </form>
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
