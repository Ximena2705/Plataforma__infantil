document.getElementById('tipo_usuario').addEventListener('change', function() {
    var tipo = this.value;
    var camposDocente = document.getElementById('campos_docente');
    var camposEstudiante = document.getElementById('campos_estudiante');

    if (tipo === 'docente') {
        camposDocente.classList.remove('oculto');
        camposEstudiante.classList.add('oculto');
    } else if (tipo === 'estudiante') {
        camposEstudiante.classList.remove('oculto');
        camposDocente.classList.add('oculto');
    } else {
        camposDocente.classList.add('oculto');
        camposEstudiante.classList.add('oculto');
    }
});
