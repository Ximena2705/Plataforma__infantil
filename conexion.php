<?php
include("configuracion.php");

// Crear la conexión
$conn = new mysqli($host, $user, $pass, $dbname, $port);

// Verificar la conexión
if ($conn->connect_error) {
    die("La conexión falló: " . $conn->connect_error);
}

/*else{
    echo "Conectado";
}
    */
?>
