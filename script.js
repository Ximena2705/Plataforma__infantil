/*
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

document.getElementById('editarActividadForm').onsubmit = function(event) {
    event.preventDefault(); // Evita el envío normal del formulario

    // Captura los valores de los campos
    var nombreJuego = document.getElementById('nombre_juego').value;
    var descripcion = document.getElementById('descripcion').value;
    var url = document.getElementById('url').value;
    var imagen = document.getElementById('imagen').value;

    // Crea la solicitud AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'actualizar_actividad.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Envía los datos al archivo PHP
    xhr.send('nombre_juego=' + encodeURIComponent(nombreJuego) +
             '&descripcion=' + encodeURIComponent(descripcion) +
             '&url=' + encodeURIComponent(url) +
             '&imagen=' + encodeURIComponent(imagen));

    // Maneja la respuesta
    xhr.onload = function() {
        if (xhr.status === 200) {
            alert('Actividad actualizada con éxito');
            location.reload(); // Recarga la página para reflejar los cambios
        } else {
            alert('Hubo un problema al actualizar la actividad');
        }
    };
};
*/
//-----------------------------------------------------------------------------------------------------------------------
// Arreglo para saber cuáles divs ya están ocupados
let arreglo = ["", "", ""];

// Función que evita que se abra como enlace al soltar un elemento
function allowDrop(ev) {
    ev.preventDefault();
}

// Lo que sucede cuando se arrastra un elemento
function drag(ev) {
    // Método que establece el tipo de datos y el valor del dato arrastrado
    // En este caso el dato es texto y el valor es el id del elemento arrastrado
    // Por ejemplo
    ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
    // Mediante ev.target.id tomo el nombre del id del div que puede ser 0, 1 o 2
    // Si el arreglo en dicha posición está vacío, significa que no tiene nada, o sea puedo
    // soltar ahí. Caso contrario, ya tiene un elemento
    if (arreglo[parseInt(ev.target.id)] == "") {
        // Obtengo los datos arrastrados con el método dataTransfer.getData().
        // Este método devolverá cualquier dato que se haya establecido en el mismo tipo en setData().
        // En este caso, el id quedará con gato, perro o loro
        var data = ev.dataTransfer.getData("text");
        
        // Agrego al arreglo el nombre del id
        arreglo[parseInt(ev.target.id)] = data;

        // Agrego el elemento arrastrado al elemento soltado
        ev.target.appendChild(document.getElementById(data));
    

    // Mostrar mensaje de retroalimentación global
    if (arreglo[0] === "gato" && arreglo[1] === "perro" && arreglo[2] === "loro") {
        document.getElementById("mensaje").innerHTML = "¡Muy bien! Has completado el juego.";
    } else {
        document.getElementById("mensaje").innerHTML = "¡Intenta de nuevo!";
    }
}
}
