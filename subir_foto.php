<?php
session_start();
include("conexion.php");

$response = array("status" => "error", "message" => "Error al subir la foto");

if (!isset($_SESSION['documento'])) {
    $response["message"] = "No se ha iniciado sesión.";
    echo json_encode($response);
    exit();
}

$documento = $_SESSION['documento']; // Documento del usuario actual
$targetDir = "imagenes/usuarios/";  // Carpeta de destino para las fotos
$fileName = $documento . ".webp";   // Usar el documento como nombre del archivo
$targetFile = $targetDir . $fileName; // Ruta completa del archivo a guardar

// Verificar si se ha enviado un archivo a través del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES["foto_perfil"]["tmp_name"])) {
        $fileType = mime_content_type($_FILES["foto_perfil"]["tmp_name"]); // Obtener el tipo de archivo

        // Validar que el archivo sea una imagen
        if (in_array($fileType, ['image/jpeg', 'image/png', 'image/webp'])) {
            // Consultar la base de datos para obtener la foto anterior
            $sqlFoto = "SELECT foto_perfil FROM persona WHERE documento = '$documento'";
            $resultadoFoto = $conn->query($sqlFoto);

            if ($resultadoFoto && $resultadoFoto->num_rows > 0) {
                $rowFoto = $resultadoFoto->fetch_assoc();
                $fotoAnterior = $rowFoto['foto_perfil'];

                // Si existe una foto anterior, eliminarla del servidor
                if (!empty($fotoAnterior) && file_exists("imagenes/usuarios/" . $fotoAnterior)) {
                    unlink("imagenes/usuarios/" . $fotoAnterior); // Eliminar la foto anterior del servidor
                }
            }

            // Mover el archivo subido a la carpeta de destino
            if (move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $targetFile)) {
                // Guardar solo el nombre del archivo en la base de datos (sin ruta completa)
                $fileNameInDb = $fileName; 

                // Actualizar la base de datos con el nombre del archivo
                $sqlUpdate = "UPDATE persona SET foto_perfil = '$fileNameInDb' WHERE documento = '$documento'";
                if ($conn->query($sqlUpdate) === TRUE) {
                    $response["status"] = "success";
                    $response["message"] = "Foto subida con éxito.";
                } else {
                    $response["message"] = "Error al actualizar la base de datos.";
                }
            } else {
                $response["message"] = "Error al subir la foto.";
            }
        } else {
            $response["message"] = "Solo se permiten imágenes en formato JPEG, PNG o WEBP.";
        }
    } else {
        $response["message"] = "No se seleccionó ninguna foto.";
    }
}

echo json_encode($response); // Respuesta en formato JSON
?>
