document.getElementById('tipo_persona').addEventListener('change', function() {
    var tipoPersona = this.value;
    if (tipoPersona === 'docente') {
        document.getElementById('campos_docente').style.display = 'block';
        document.getElementById('campos_estudiante').style.display = 'none';
    } else if (tipoPersona === 'estudiante') {
        document.getElementById('campos_docente').style.display = 'none';
        document.getElementById('campos_estudiante').style.display = 'block';
    }
});
/*
document.getElementById("subirFotoBtn").addEventListener("click", function() {
    const subirFotoBtn = document.getElementById('subirFotoBtn');
    const fotoForm = document.getElementById('fotoForm');
    
    // Mostrar el formulario y ocultar el botón principal
    subirFotoBtn.style.display = 'none';
    fotoForm.style.display = 'block';
});

document.getElementById("fotoForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Evitar recargar la página

    let formData = new FormData(this); // Crear el FormData con los datos del formulario
    fetch("subir_foto.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json()) // Convertir la respuesta a JSON
    .then(data => {
        alert(data.message); // Mostrar el mensaje al usuario

        if (data.status === "success") {
            // Actualizar la imagen de perfil si la subida fue exitosa
            document.querySelector('.foto-perfil img').src = 'imagenes/usuarios/' + document.getElementById('documentoUsuario').value + ".webp";
        }

        // Resetear el formulario y volver al estado inicial
        this.reset();
        document.getElementById('subirFotoBtn').style.display = 'block';
        document.getElementById('fotoForm').style.display = 'none';
    })
    .catch(error => {
        alert("Ocurrió un error al subir la foto.");
    });
});
*/
