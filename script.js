
document.addEventListener('DOMContentLoaded', () => {
    // Detecta qué juego está cargado
    const juego1 = document.getElementById('juego1');
    const juego2 = document.getElementById('juego2');
    const juego3 = document.getElementById('juego3');

    // Lógica para Juego 1
    if (juego1) {
        console.log('Cargando Juego 1');
        const contenedor = document.querySelector(".container");
        contenedor.innerHTML = ""; 
        for (let i = 0; i < nombresImagenes.length; i++) {
            const img = document.createElement("img");
            img.src = nombresImagenes[i];  
            img.id = "img" + i;  
            img.alt = "Imagen " + (i + 1);
        
            // ✅ Asignar el nombre correcto basado en `palabrasCorrectas`
            img.setAttribute('data-nombre', nombresImagenes[i].split('/').pop().split('.')[0]);

            
            img.draggable = true;
            img.ondragstart = drag;
        
            contenedor.appendChild(img);
        
            console.log(`Imagen ID: img${i}, Nombre Asignado: ${img.getAttribute("data-nombre")}`);
        }
        window.arreglo = ["", "", "", "", "", ""]; // Arreglo inicial para las posiciones

        // Variables globales con valores de PHP
        
        

        function allowDrop(ev) {
            ev.preventDefault();
        }
        
        function drag(ev) {
            ev.dataTransfer.setData("text", ev.target.id);
        }
        
        function drop(ev) {
            ev.preventDefault();
            const data = ev.dataTransfer.getData("text"); // ID de la imagen arrastrada
            const targetId = ev.target.id; // ID del contenedor donde se suelta
        
            if (window.arreglo[parseInt(targetId)] === "") {
                const img = document.getElementById(data); // Imagen movida
                const nombreImagen = img.getAttribute("data-nombre"); // Obtener su nombre correcto
        
                window.arreglo[parseInt(targetId)] = { id: data, nombre: nombreImagen }; // Guardar ambos en el arreglo
                ev.target.appendChild(img);
        
                console.log("Estado actual del arreglo:", window.arreglo); // 🔍 Verificar estructura
            }
        }

        
        function comprobar() {
            const mensaje = document.getElementById("mensaje2");
            let esCorrecto = true;
        
            for (let i = 0; i < window.arreglo.length; i++) {
                const elemento = window.arreglo[i]; // Ahora es un objeto { id, nombre }
                
                if (elemento) {
                    const nombreImagen = elemento.nombre.toLowerCase(); // Obtener nombre desde el objeto
                    const palabraCorrecta = palabrasCorrectas[i].toLowerCase(); // Palabra esperada
                    
                    console.log(`Posición: ${i}, Imagen: ${nombreImagen}, Palabra Correcta: ${palabraCorrecta}`);
        
                    if (nombreImagen !== palabraCorrecta) {
                        esCorrecto = false;
                        break;
                    }
                }
            }
        
            mensaje.style.color = esCorrecto ? "green" : "#ff5722";
            mensaje.innerHTML = esCorrecto
                ? "¡Muy bien! Has colocado todas las imágenes correctamente."
                : "¡Intenta de nuevo! Las imágenes no están en el lugar correcto.";
        
            console.log(window.arreglo);
            console.log(palabrasCorrectas);
        }

        function resetear() {
            window.arreglo = ["", "", "", "", "", ""];

            const cajas = document.querySelectorAll(".box");
            cajas.forEach(caja => caja.innerHTML = "");
        
            const contenedor = document.querySelector(".container");
            contenedor.innerHTML = ""; 
        
            // Asegurar que las imágenes se agreguen en el mismo orden
            for (let i = 0; i < nombresImagenes.length; i++) {
                const img = document.createElement("img");
                img.src = nombresImagenes[i];  // Asignar la imagen correcta
                img.id = "img" + i;  // Darle un ID único
                img.alt = "Imagen " + (i + 1);
                
                // ✅ Asegurar que el nombre correcto se asigna
                img.setAttribute('data-nombre', nombresImagenes[i].split('/').pop().split('.')[0]); 
                
                img.draggable = true;
                img.ondragstart = drag;
        
                contenedor.appendChild(img);
        
                // Verificar en consola
                console.log(`Imagen ID: img${i}, Nombre Asignado: ${img.getAttribute("data-nombre")}`);
            }
        
            document.getElementById("mensaje2").innerHTML = "";
        }

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
