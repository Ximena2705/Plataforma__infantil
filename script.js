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
document.addEventListener('DOMContentLoaded', () => {
    // Detecta qué juego está cargado
    const juego1 = document.getElementById('juego1');
    const juego2 = document.getElementById('juego2');
    const juego3 = document.getElementById('juego3');

    // Lógica para Juego 1
    if (juego1) {
        console.log('Cargando Juego 1');
        // Aquí va el código JavaScript específico para Juego 1


        window.arreglo = ["", "", "", "", "", ""]; // Arreglo inicial para las posiciones

        function allowDrop(ev) {
            ev.preventDefault();
        }

        function drag(ev) {
            ev.dataTransfer.setData("text", ev.target.id);
        }

        function drop(ev) {
            ev.preventDefault();
            const data = ev.dataTransfer.getData("text");
            const targetId = ev.target.id;

            // Si la caja está vacía, coloca la imagen
            if (arreglo[parseInt(targetId)] === "") {
                arreglo[parseInt(targetId)] = data;
                ev.target.appendChild(document.getElementById(data));
            }
        }

        function comprobar() {
            const mensaje = document.getElementById("mensaje2");
            if (arreglo[0] === "gato" && arreglo[1] === "perro" && arreglo[2] === "loro" 
                && arreglo[3] === "tortuga" && arreglo[4] === "conejo" && arreglo[5] === "elefante") {
                mensaje.style.color = "green";
                mensaje.innerHTML = "¡Muy bien! Has colocado todas las imágenes correctamente.";
            } else {
                mensaje.style.color = "#ff5722";
                mensaje.innerHTML = "¡Intenta de nuevo! Las imágenes no están en el lugar correcto.";
            }
        }

        function resetear() {
            // Reiniciar el arreglo
            window.arreglo = ["", "", "", "", "", ""];

            // Vaciar todas las cajas
            const cajas = document.querySelectorAll(".box");
            cajas.forEach(caja => caja.innerHTML = ""); // Vaciar el contenido de las cajas

            // Eliminar las imágenes actuales
            const imagenes = document.querySelectorAll(".container img");
            imagenes.forEach(img => img.remove());

            // Crear las imágenes nuevamente
            const contenedor = document.querySelector(".container");  // Obtener el contenedor donde se encuentran las imágenes
            
            // Lista de imágenes iniciales
            const imagenesRestauradas = [
                { src: "../../../imagenes/juegos/loro.webp", id: "loro", alt: "Loro" },
                { src: "../../../imagenes/juegos/gato.webp", id: "gato", alt: "Gato" },
                { src: "../../../imagenes/juegos/perro.webp", id: "perro", alt: "Perro" },
                { src: "../../../imagenes/juegos/conejo.webp", id: "conejo", alt: "Conejo" },
                { src: "../../../imagenes/juegos/elefante.webp", id: "elefante", alt: "Elefante" },
                { src: "../../../imagenes/juegos/tortuga.webp", id: "tortuga", alt: "Tortuga" }
            ];

            // Crear las imágenes y añadirlas al contenedor
            imagenesRestauradas.forEach(imgData => {
                const img = document.createElement("img");
                img.src = imgData.src;
                img.id = imgData.id;
                img.alt = imgData.alt;
                img.draggable = true;
                img.ondragstart = drag; // Asignar el evento de drag

                contenedor.appendChild(img); // Añadir la imagen al contenedor
            });

            // Limpiar el mensaje de éxito o error
            const mensaje = document.getElementById("mensaje2");
            if (mensaje) {
                mensaje.innerHTML = ""; // Limpiar el mensaje
            } else {
                console.error("No se encontró el elemento con id 'mensaje2'.");
            }
        }

        // Asignar las funciones globalmente
        window.allowDrop = allowDrop;
        window.drag = drag;
        window.drop = drop;
        window.comprobar = comprobar;
        window.resetear = resetear;
    }
    

//--------------------------------------------------------------Juego 2----------------------------------------------------------------
    if (juego2) {

        console.log('Cargando Juego 2');

        const botonVerificar = document.getElementById("verificar");
        const botonReiniciar = document.getElementById("reiniciar");
        const resultado = document.getElementById("resultado");

        // Para cada contenedor (pregunta)
        document.querySelectorAll(".juego-container").forEach(contenedor => {
            const opciones = contenedor.querySelectorAll(".opcion"); // Opciones dentro del contenedor de la pregunta

            opciones.forEach(opcion => {
                opcion.addEventListener("click", () => {
                    // Deseleccionar todas las opciones dentro de este contenedor
                    opciones.forEach(opcion => {
                        opcion.classList.remove("seleccionado");
                    });

                    // Seleccionar la opción que se hizo clic
                    opcion.classList.add("seleccionado");
                });
            });
        });

        botonVerificar.addEventListener("click", () => {
            let correctosTotales = 0;
            let seleccionadosTotales = 0;

            document.querySelectorAll(".juego-container").forEach(contenedor => {
                const opciones = contenedor.querySelectorAll(".opcion");
                const h1Pregunta = contenedor.querySelector("h1");
                const seleccionados = [];
                let correcto = false;

                opciones.forEach(opcion => {
                    if (opcion.classList.contains("seleccionado")) {
                        seleccionados.push(opcion);
                        seleccionadosTotales++;

                        if (opcion.dataset.correcto === "true") {
                            correcto = true;
                            correctosTotales++;
                            opcion.classList.add("correcto");
                        } else {
                            opcion.classList.add("incorrecto");
                        }
                    }
                });

                //if (correcto && seleccionados.length === 1) {
                    
                    //const respuestaCorrecta = contenedor.querySelector('.opcion[data-correcto="true"]').textContent.trim();
                    //h1Pregunta.textContent = h1Pregunta.textContent.trim() + respuestaCorrecta; // Concatenar sin espacios
                //}

                opciones.forEach(opcion => opcion.classList.remove("seleccionado")); // Limpiar selección
            });

            if (seleccionadosTotales === 0) {
                resultado.textContent = "Seleccione al menos una opción.";
                resultado.style.color = "red";
            } else if (correctosTotales === seleccionadosTotales) {
                resultado.textContent = "¡Correcto! Todas son correctas.";
                resultado.style.color = "green";
            } else {
                resultado.textContent = "Incorrecto. Intenta de nuevo.";
                resultado.style.color = "red";
            }
        });

        // Evento para reiniciar el juego
        botonReiniciar.addEventListener("click", () => {
            // Limpiar los estilos de las opciones
            document.querySelectorAll(".opcion").forEach(opcion => {
                opcion.classList.remove("correcto", "incorrecto"); // Quitar estilos
            });

            // Restaurar las preguntas a su texto original
            document.querySelectorAll(".juego-container h1").forEach(h1 => {
                const originalText = h1.getAttribute("data-original"); // Obtener el texto original desde el atributo data-original
                h1.textContent = originalText; // Restaurar el texto original
            });

            resultado.textContent = ""; // Limpiar el mensaje de resultado
        });

        // Almacenar el texto original de cada pregunta en su atributo data-original
        document.querySelectorAll(".juego-container h1").forEach(h1 => {
            const originalText = h1.textContent.trim(); // Tomar solo la parte original sin modificación
            h1.setAttribute("data-original", originalText); // Guardarlo en el atributo data-original
        });
    }

/*-------------------------------------------------------------JUEGO 3----------------------------------------------------*/
if (juego3) {
    // Función para iniciar el arrastre de un elemento
function arrastrar(event) {
    event.dataTransfer.setData("text", event.target.id);
}

// Función para permitir que los elementos sean soltados
function permitirSoltar(event) {
    event.preventDefault();
}

// Función para manejar el evento de soltar un elemento
function soltar(event) {
    event.preventDefault();
    const idElemento = event.dataTransfer.getData("text");
    const elemento = document.getElementById(idElemento);

    // Verificar si el destino es un contenedor válido
    if (event.target.classList.contains("grupo")) {
        event.target.appendChild(elemento);
    }
}

// Mapa de clasificación: define qué elemento pertenece a qué grupo
const mapaClasificacion = {
    elemento1: "grupo1", // Gato -> Domésticos
    elemento2: "grupo1", // Perro -> Domésticos
    elemento3: "grupo1", // Conejo -> Domésticos
    elemento4: "grupo2", // Elefante -> Salvajes
    elemento5: "grupo2", // Jirafa -> Salvajes
    elemento6: "grupo2"  // León -> Salvajes
};

// Función para verificar si todos los elementos están en su grupo correcto
function verificar() {
    const mensaje = document.getElementById("mensajeActividad");
    let correcto = true;

    Object.keys(mapaClasificacion).forEach(id => {
        const elemento = document.getElementById(id);
        const contenedorPadre = elemento.parentElement.id;

        if (contenedorPadre !== mapaClasificacion[id]) {
            correcto = false;
        }
    });

    if (correcto) {
        mensaje.style.color = "green";
        mensaje.textContent = "¡Muy bien! Todos los elementos están en el lugar correcto.";
    } else {
        mensaje.style.color = "red";
        mensaje.textContent = "¡Intenta de nuevo! Algunos elementos no están en el lugar correcto.";
    }
}

// Función para reiniciar el juego
function reiniciar() {
    const contenedorInicial = document.querySelector(".contenedorElementos");
    document.querySelectorAll(".grupo .elemento").forEach(elemento => {
        contenedorInicial.appendChild(elemento);
    });
    document.getElementById("mensajeActividad").textContent = "";
}

    // Asignar las funciones globalmente
    window.arrastrar = arrastrar;
    window.permitirSoltar = permitirSoltar;
    window.soltar = soltar;
    window.verificar = verificar;
    window.reiniciar = reiniciar;
    }

    
});
