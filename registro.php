<?php
include("conexion.php");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoPersona = $_POST['tipo_persona'];

    if ($tipoPersona === 'docente') {
        // Recoger datos del docente
        $cedula = mysqli_real_escape_string($conn, $_POST['cedula']);
        $doc_nombre1 = mysqli_real_escape_string($conn, $_POST['doc_nombre1']);
        $doc_nombre2 = mysqli_real_escape_string($conn, $_POST['doc_nombre2']);
        $doc_apellido1 = mysqli_real_escape_string($conn, $_POST['doc_apellido1']);
        $doc_apellido2 = mysqli_real_escape_string($conn, $_POST['doc_apellido2']);
        $asignatura = mysqli_real_escape_string($conn, $_POST['asignatura']);

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
    } elseif ($tipoPersona === 'estudiante') {
        // Recoger datos del estudiante
        $tarjetaIdentidad = mysqli_real_escape_string($conn, $_POST['tarjeta_identidad']);
        $est_nombre1 = mysqli_real_escape_string($conn, $_POST['est_nombre1']);
        $est_nombre2 = mysqli_real_escape_string($conn, $_POST['est_nombre2']);
        $est_apellido1 = mysqli_real_escape_string($conn, $_POST['est_apellido1']);
        $est_apellido2 = mysqli_real_escape_string($conn, $_POST['est_apellido2']);
        $grado = mysqli_real_escape_string($conn, $_POST['grado']);

        // Crear usuario según las reglas definidas
        $usuario = strtolower(substr($est_nombre1, 0, 2) . "." . $est_apellido1);
        $contraseña = 'liceomoderno2025'; // Contraseña por defecto

        // Insertar en tabla estudiante
        $sqlEstudiante = "INSERT INTO estudiante (tarjeta_identidad, est_nombre1, est_nombre2, est_apellido1, est_apellido2, est_usuario, grado) 
                          VALUES ('$tarjetaIdentidad', '$est_nombre1', '$est_nombre2', '$est_apellido1', '$est_apellido2', '$usuario', '$grado')";

        if ($conn->query($sqlEstudiante) === TRUE) {
            // Insertar en tabla persona
            $sqlPersona = "INSERT INTO persona (documento, tipo_persona, usuario, contraseña) 
                           VALUES ('$tarjetaIdentidad','estudiante', '$usuario', '$contraseña')";
            if ($conn->query($sqlPersona) === TRUE) {
                echo "Estudiante registrado con éxito";
            } else {
                echo "Error al registrar en tabla persona: " . $conn->error;
            }
        } else {
            echo "Error al registrar estudiante: " . $conn->error;
        }
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
    <h2>Registro de Usuario</h2>
    <form method="POST" action="registro.php">

        <label for="tipo_persona">Registrar como:</label>
        <select id="tipo_persona" name="tipo_persona" onchange="mostrarCampos()">
            <option value="" disabled selected>Seleccionar</option>
            <option value="docente">Docente</option>
            <option value="estudiante">Estudiante</option>
        </select>

        <div id="docente_campos" style="display:none;">
            <h3>Datos Docente</h3>
            <input type="text" name="cedula" placeholder="Cédula" required>
            <input type="text" name="nombre1" placeholder="Primer Nombre" required>
            <input type="text" name="nombre2" placeholder="Segundo Nombre">
            <input type="text" name="apellido1" placeholder="Primer Apellido" required>
            <input type="text" name="apellido2" placeholder="Segundo Apellido" required>
            <input type="text" name="asignatura" placeholder="Asignatura" required>
        </div>

        <div id="estudiante_campos" style="display:none;">
            <h3>Datos Estudiante</h3>
            <input type="text" name="tarjeta_identidad" placeholder="Tarjeta de Identidad" required>
            <input type="text" name="nombre1" placeholder="Primer Nombre" required>
            <input type="text" name="nombre2" placeholder="Segundo Nombre">
            <input type="text" name="apellido1" placeholder="Primer Apellido" required>
            <input type="text" name="apellido2" placeholder="Segundo Apellido" required>
            <select name="grado" required>
                <option value="" disabled selected>Grado</option>
                <option value="Primero">Primero</option>
                <option value="Segundo">Segundo</option>
                <option value="Tercero">Tercero</option>
            </select>
        </div>

        <button type="submit">Registrar</button>

    </form>

    <script>
        function mostrarCampos() {
            var tipo = document.getElementById("tipo_persona").value;
            document.getElementById("docente_campos").style.display = (tipo === "docente") ? "block" : "none";
            document.getElementById("estudiante_campos").style.display = (tipo === "estudiante") ? "block" : "none";
        }
    </script>
</body>
</html>

