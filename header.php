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
} 
elseif ($tipo_persona == 'docente') {
    $sql = "SELECT doc_nombre1, doc_nombre2, doc_apellido1, doc_apellido2 FROM docente WHERE cedula = '$documento'";
} elseif ($tipo_persona == 'estudiante') {
    $sql = "SELECT est_nombre1, est_nombre1, est_apellido1, est_apellido2 FROM estudiante WHERE tarjeta_identidad = '$documento'";
}

$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();

    if ($tipo_persona == 'admin') {
        $nombre1 = $row['nombre1'];
        $nombre2 = isset($row['nombre2']) ? $row['nombre2'] : '';
        $apellido1 = $row['apellido1'];
        $apellido2 = isset($row['apellido2']) ? $row['apellido2'] : '';
    } elseif ($tipo_persona == 'docente') {
        $nombre1 = $row['doc_nombre1'];
        $nombre2 = isset($row['doc_nombre2']) ? $row['doc_nombre2'] : '';
        $apellido1 = $row['doc_apellido1'];
        $apellido2 = isset($row['doc_apellido2']) ? $row['doc_apellido2'] : '';
    } elseif ($tipo_persona == 'estudiante') {
        $nombre1 = $row['est_nombre1'];
        $nombre2 = isset($row['est_nombre2']) ? $row['est_nombre2'] : '';
        $apellido1 = $row['est_apellido1'];
        $apellido2 = isset($row['est_apellido2']) ? $row['est_apellido2'] : '';
    }
}


$nombre=  $nombre1 . ' ' . $apellido1;
$nombre_completo = $nombre1 . ' ' . $nombre2 . ' ' . $apellido1 . ' ' . $apellido2;
?>


