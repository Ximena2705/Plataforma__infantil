<?php
include("conexion.php");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Depurar el contenido de $_POST
    //echo "<pre>";
    //print_r($_POST);
    //echo "</pre>";

    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
    $contraseña = mysqli_real_escape_string($conn, $_POST['contraseña']);

    // Comprobar en la tabla persona
    $sql = "SELECT * FROM persona WHERE usuario='$usuario' AND contraseña='$contraseña'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // El usuario existe
        $row = $result->fetch_assoc();
        // Iniciar sesión
        $_SESSION['usuario'] = $row['usuario'];
        echo "Bienvenido " . $row['usuario'];
        // Redirigir a la página de inicio o dashboard
        header("Location: inicio.php");
        exit();
    } else {
        echo "Credenciales incorrectas.";
    }
}
    if (isset($_POST['tipo_persona'])) {
        $tipoPersona = $_POST['tipo_persona'];

        if ($tipoPersona === 'docente') {
            // Recoger datos del docente
            if (isset($_POST['cedula']) && !empty($_POST['cedula'])) {
                $cedula = mysqli_real_escape_string($conn, $_POST['cedula']);
                
                // Verificar si la cédula ya existe
                $sqlVerificar = "SELECT * FROM persona WHERE documento = '$cedula'";
                $resultado = $conn->query($sqlVerificar);
                
                if ($resultado->num_rows > 0) {
                    echo "Error: La cédula ya está registrada.";
                } else {
                    $doc_nombre1 = mysqli_real_escape_string($conn, $_POST['doc_nombre1']);
                    $doc_nombre2 = mysqli_real_escape_string($conn, $_POST['doc_nombre2']);
                    $doc_apellido1 = mysqli_real_escape_string($conn, $_POST['doc_apellido1']);
                    $doc_apellido2 = mysqli_real_escape_string($conn, $_POST['doc_apellido2']);
                    $asignatura = mysqli_real_escape_string($conn, $_POST['asignatura']);
                    
                    if (!preg_match('/^[0-9]+$/', $cedula)) {
                        die("La cédula debe contener solo números.");
                    }
                    if (!preg_match('/^[a-zA-Z]+$/', $doc_nombre1) || !preg_match('/^[a-zA-Z]+$/', $doc_apellido1)) {
                        die("Los nombres y apellidos deben contener solo letras.");
                    }

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
                            echo "Docente registrado con éxito";
                        } else {
                            echo "Error al registrar en tabla persona: " . $conn->error;
                        }
                    } else {
                        echo "Error al registrar docente: " . $conn->error;
                    }
                }
            } else {
                echo "Faltan datos del docente.";
            }
        } elseif ($tipoPersona === 'estudiante') {
            // Recoger datos del estudiante
            $tarjetaIdentidad = mysqli_real_escape_string($conn, $_POST['tarjeta_identidad']);
            $est_nombre1 = mysqli_real_escape_string($conn, $_POST['est_nombre1']);
            $est_nombre2 = mysqli_real_escape_string($conn, $_POST['est_nombre2']);
            $est_apellido1 = mysqli_real_escape_string($conn, $_POST['est_apellido1']);
            $est_apellido2 = mysqli_real_escape_string($conn, $_POST['est_apellido2']);
            $grado = mysqli_real_escape_string($conn, $_POST['grado']);
            
            if (!preg_match('/^[0-9]+$/', $tarjetaIdentidad)) {
                die("La tarjeta de identidad debe contener solo números.");
            }
            if (!preg_match('/^[a-zA-Z]+$/', $est_nombre1) || !preg_match('/^[a-zA-Z]+$/', $est_apellido1)) {
                die("Los nombres y apellidos deben contener solo letras.");
            }
            // Crear usuario base
            $usuarioBase = strtolower(substr($est_nombre1, 0, 2) . "." . $est_apellido1);
            
            // Verificar si el usuario ya existe
            $contador = 1;
            $usuario = $usuarioBase;
        
            // Generar nuevo usuario si ya existe en la base de datos
            while (true) {
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
                    // Si no tiene segundo nombre, usar segundo apellido
                    $usuario = strtolower(substr($est_nombre1, 0, 2) . "." . $est_apellido1 . "." . $est_apellido2);
                }
                
                $contador++;
            }
        
            // Si el usuario se creó con segundo apellido, asegurarnos de usar el apellido completo
            if (empty($est_nombre2)) {
                $usuario = strtolower(substr($est_nombre1, 0, 2) . "." . $est_apellido1 . "." . $est_apellido2);
            }
        
            $contraseña = 'liceomoderno2025'; // Contraseña por defecto
        
            // Insertar en tabla estudiante
            $sqlEstudiante = "INSERT INTO estudiante (tarjeta_identidad, est_nombre1, est_nombre2, est_apellido1, est_apellido2, est_usuario, grado) 
                              VALUES ('$tarjetaIdentidad', '$est_nombre1', '$est_nombre2', '$est_apellido1', '$est_apellido2', '$usuario', '$grado')";
        
            if ($conn->query($sqlEstudiante) === TRUE) {
                // Insertar en tabla persona
                $sqlPersona = "INSERT INTO persona (documento, tipo_persona, usuario, contraseña) 
                               VALUES ('$tarjetaIdentidad', 'estudiante', '$usuario', '$contraseña')";
                if ($conn->query($sqlPersona) === TRUE) {
                    echo "Estudiante registrado con éxito";
                } else {
                    echo "Error al registrar en tabla persona: " . $conn->error;
                }
            } else {
                echo "Error al registrar estudiante: " . $conn->error;
            }
        }
        
        else {
            echo "Tipo de persona inválido";
        }
    } else {
        echo "No se ha seleccionado el tipo de persona";
    }

} else {
    echo "Método HTTP incorrecto";
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
<form action="registro.php" method="POST" onsubmit="return validarFormulario()">
    <h2>Registro de Usuario</h2>
    <label for="tipo_persona">Registrar como:</label>
    <select id="tipo_persona" name="tipo_persona" onchange="mostrarCampos()">
        <option value="" disabled selected>Seleccionar</option>
        <option value="docente">Docente</option>
        <option value="estudiante">Estudiante</option>
    </select>

    <div id="docente_campos" style="display:none;">
        <h3>Datos Docente</h3>
        <input type="text" name="cedula" placeholder="Cédula" required pattern="\d{1,10}" title="Solo se permiten números">
        <input type="text" name="doc_nombre1" placeholder="Primer Nombre" required pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ ]+" title="Solo se permiten letras">
        <input type="text" name="doc_nombre2" placeholder="Segundo Nombre" pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ ]+" title="Solo se permiten letras">
        <input type="text" name="doc_apellido1" placeholder="Primer Apellido" required pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ ]+" title="Solo se permiten letras">
        <input type="text" name="doc_apellido2" placeholder="Segundo Apellido" pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ ]+" title="Solo se permiten letras">
        <input type="text" name="asignatura" placeholder="Asignatura" required pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ ]+" title="Solo se permiten letras">
    </div>

    <div id="estudiante_campos" style="display:none;">
        <h3>Datos Estudiante</h3>
        <input type="text" name="tarjeta_identidad" placeholder="Tarjeta de Identidad" required pattern="\d{1,10}" title="Solo se permiten números">
        <input type="text" name="est_nombre1" placeholder="Primer Nombre" required pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ ]+" title="Solo se permiten letras">
        <input type="text" name="est_nombre2" placeholder="Segundo Nombre" pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ ]+" title="Solo se permiten letras">
        <input type="text" name="est_apellido1" placeholder="Primer Apellido" required pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ ]+" title="Solo se permiten letras">
        <input type="text" name="est_apellido2" placeholder="Segundo Apellido" pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ ]+" title="Solo se permiten letras">
        <select name="grado" required>
            <option value="" disabled selected>Grado</option>
            <option value="Primero">Primero</option>
            <option value="Segundo">Segundo</option>
            <option value="Tercero">Tercero</option>
        </select>
    </div>

    <input type="submit" value="Registrar">
</form>

<script>
    function mostrarCampos() {
        var tipo = document.getElementById("tipo_persona").value;
        document.getElementById("docente_campos").style.display = (tipo === "docente") ? "block" : "none";
        document.getElementById("estudiante_campos").style.display = (tipo === "estudiante") ? "block" : "none";
    }

    function validarFormulario() {
        // Aquí puedes agregar más validaciones si es necesario
        return true; // Si todo está bien
    }
</script>

</body>
</html>