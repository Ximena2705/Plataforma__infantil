<?php
// Datos de conexión a la base de datos
$host = "localhost";
$datab = "bdcolegio";
$user = "root";
$pass = "";

// Crear la conexión a la base de datos
$conn = new mysqli($host, $user, $pass, $datab);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar datos del formulario
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    // Consulta SQL preparada para evitar inyección SQL
    $sql = "SELECT * FROM persona WHERE usuario = ? AND contraseña = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    // Enlazar parámetros (ss = string, string)
    $stmt->bind_param("ss", $usuario, $contraseña);
    
    // Ejecutar la consulta
    $stmt->execute();
    
    // Obtener el resultado
    $resultado = $stmt->get_result();

    // Verificar si se encontró un registro
    if ($resultado->num_rows > 0) {
        // Redirigir a la página de bienvenida
        header("Location: inicio.php?user=" . urlencode($usuario));
        exit();
    } else {
        // Si las credenciales no son correctas
        echo "<h3 class='error'>El usuario o la contraseña son incorrectos.</h3>";
    }

    // Cerrar la declaración
    $stmt->close();
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
