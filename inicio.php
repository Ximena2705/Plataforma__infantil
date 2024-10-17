<?php
session_start();
include("conexion.php");

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
    $sql = "SELECT doc_nombre1, doc_nombre2, doc_apellido1, doc_apellido2 FROM docente WHERE cedula = '$documento'";
} elseif ($tipo_persona == 'estudiante') {
    $sql = "SELECT est_nombre1, est_nombre2, est_apellido1, est_apellido2 FROM estudiante WHERE tarjeta_identidad = '$documento'";
}

$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
    // Dependiendo del tipo de persona, asignar los valores correctos
    if ($tipo_persona == 'admin') {
        $nombre1 = $row['nombre1'];
        $nombre2 = $row['nombre2'];
        $apellido1 = $row['apellido1'];
        $apellido2 = $row['apellido2'];
    } elseif ($tipo_persona == 'docente') {
        $nombre1 = $row['doc_nombre1'];
        $nombre2 = $row['doc_nombre2'];
        $apellido1 = $row['doc_apellido1'];
        $apellido2 = $row['doc_apellido2'];
    } elseif ($tipo_persona == 'estudiante') {
        $nombre1 = $row['est_nombre1'];
        $nombre2 = $row['est_nombre2'];
        $apellido1 = $row['est_apellido1'];
        $apellido2 = $row['est_apellido2'];
    }
}

// Mostrar el nombre completo
echo "<h3>Bienvenido, $nombre1 $nombre2 $apellido1 $apellido2</h3>";
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

  <section>
        <a href="registro.php">Ir a registro<br></a>
    </section>
    <section>
        <a href="login.php"><br>Cerrar sesión</a>
    </section>
</body>
</html>
