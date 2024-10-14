<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuarios</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function mostrarCampos(tipo) {
            document.getElementById('docenteFields').style.display = tipo === 'docente' ? 'block' : 'none';
            document.getElementById('estudianteFields').style.display = tipo === 'estudiante' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <div class="container-registro">
        <h2>Registro de Usuario</h2>
        <label for="tipoUsuario">Selecciona el tipo de usuario:</label>
        <select id="tipoUsuario" onchange="mostrarCampos(this.value)">
            <option value="">Seleccione...</option>
            <option value="docente">Docente</option>
            <option value="estudiante">Estudiante</option>
        </select>

        <form action="procesar_registro.php" method="POST">
            <!-- Campos para docente -->
            <div id="docenteFields" style="display: none;">
                <h3>Registro de Docente</h3>
                <label for="cedula">Cédula:</label>
                <input type="text" name="cedula" required>

                <label for="nombre1">Primer Nombre:</label>
                <input type="text" name="nombre1" required>

                <label for="nombre2">Segundo Nombre:</label>
                <input type="text" name="nombre2">

                <label for="apellido1">Primer Apellido:</label>
                <input type="text" name="apellido1" required>

                <label for="apellido2">Segundo Apellido:</label>
                <input type="text" name="apellido2" required>

                <label for="asignatura">Asignatura:</label>
                <input type="text" name="asignatura" required>
            </div>

            <!-- Campos para estudiante -->
            <div id="estudianteFields" style="display: none;">
                <h3>Registro de Estudiante</h3>
                <label for="tarjeta">Tarjeta de Identidad:</label>
                <input type="text" name="tarjeta" required>

                <label for="nombre1">Primer Nombre:</label>
                <input type="text" name="nombre1" required>

                <label for="nombre2">Segundo Nombre:</label>
                <input type="text" name="nombre2">

                <label for="apellido1">Primer Apellido:</label>
                <input type="text" name="apellido1" required>

                <label for="apellido2">Segundo Apellido:</label>
                <input type="text" name="apellido2" required>

                <label for="usuario">Usuario:</label>
                <input type="text" name="usuario" required>

                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" name="fecha_nacimiento" required>

                <label for="grado">Grado:</label>
                <select name="grado" required>
                    <option value="primero">Primero</option>
                    <option value="segundo">Segundo</option>
                    <option value="tercero">Tercero</option>
                </select>
            </div>

            <input type="submit" value="Registrar">
        </form>
    </div>
</body>
</html>
