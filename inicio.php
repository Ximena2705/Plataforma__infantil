<?php
session_start();
include("conexion.php");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php
// Verificar si la sesión está activa
if (!isset($_SESSION['documento'])) {
    header("Location: login.php");
    exit();
}

// Obtener el documento y tipo de persona de la sesión
$documento = $_SESSION['documento'];
$tipo_persona = $_SESSION['tipo_persona'];

// Inicializar las variables para los nombres
$nombre1 = '';
$nombre2 = '';
$apellido1 = '';
$apellido2 = '';

// Dependiendo del tipo de persona, hacer la consulta en la tabla correspondiente
if ($tipo_persona == 'admin') {
    $sql = "SELECT nombre1, nombre2, apellido1, apellido2 FROM admin WHERE cedula = '$documento'";
} elseif ($tipo_persona == 'docente') {
    $sql = "SELECT nombre1, nombre2, apellido1, apellido2 FROM docente WHERE cedula = '$documento'";
} elseif ($tipo_persona == 'estudiante') {
    $sql = "SELECT nombre1, nombre2, apellido1, apellido2 FROM estudiante WHERE tarjeta_identidad = '$documento'";
}

$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
    $nombre1 = $row['nombre1'];
    $nombre2 = $row['nombre2'];
    $apellido1 = $row['apellido1'];
    $apellido2 = $row['apellido2'];
}

// Mostrar el nombre completo
echo "<h3>Bienvenido, $nombre1 $nombre2 $apellido1 $apellido2</h3>";

?>

  <section>
        <a href="registro.php">ir a registro<br></a>
    </section>
    <section>
        <a href="login.php"><br>cerrar sesion</a>
    </section>
    </body>
</html>