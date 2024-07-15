let currentStep = 1;
const form = document.getElementById('form-wizard');
const resultadoContainer = document.getElementById('resultado');

function showStep(step) {
    const steps = document.querySelectorAll('fieldset');
    steps.forEach(stepElement => stepElement.style.display = 'none');
    document.getElementById(`step-${step}`).style.display = 'block';
}

function nextStep(step) {
    currentStep = step;
    showStep(step);
}

function prevStep(step) {
    currentStep = step;
    showStep(step);
}

/*function submitForm() {
    const form = document.getElementById('form-wizard');
    const formData = new FormData(form);
    let respuestas = '';

    for (let pair of formData.entries()) {
        // Recolecta las respuestas seleccionadas
        respuestas += `Pregunta: ${pair[0]}, Respuesta: ${pair[1]}<br>`;
    }

    // Muestra las respuestas recolectadas en el contenedor
    const resultadoContainer = document.getElementById('resultadoContainer');
    resultadoContainer.innerHTML = `<h2>Respuestas enviadas:</h2>${respuestas}`;
}*/

function submitForm(examenId,moduloId) {
    const form = document.getElementById('form-wizard');
    const formData = new FormData(form);
    let respuestas = [];
    let pregunta = [];

    for (let pair of formData.entries()) {
        // Recolecta las respuestas seleccionadas
        pregunta.push(pair[0]); // Agregar solo el ID de la pregunta
        respuestas.push(`${pair[0]}:${pair[1]}`); // Formato: id_pregunta=respuesta_seleccionada
    }

    // Concatenar las respuestas y preguntas como una cadena URL
    const dataToSend = 'respuestas=' +respuestas.join(',') + '&preguntas=' + pregunta.join(',')+ '&examenId=' + examenId+ '&moduloId=' + moduloId;

    // Enviar las respuestas al archivo PHP usando AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'conexion/guardar_respuestas.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Respuesta del servidor (puede ser un mensaje de éxito o error)
            const resp = xhr.responseText;
            alert(resp); // Mostrar mensaje de respuesta
            
            // Redireccionar al usuario a home.php
            window.location.href = './home.php';
        }
    };
    xhr.send(dataToSend);
}

/**Asignar Examenes**/


function asignarEstudiante(estudiante, nmb_estudiante) {
    document.getElementById("estudiante").innerHTML = '<span class="roboto-medium">ESTUDIANTE:</span> <span>' + estudiante + ' - '+ nmb_estudiante + '</span>';
    document.getElementById("id_estudent").value = estudiante;

    fetch('conexion/obtener_examen.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ estudiante: estudiante }) // Reemplaza valorEstudiante con el valor que deseas enviar
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Hubo un problema al obtener los exámenes.');
        }
        return response.json();
    })
    .then(data => {
        // Limpia la tabla de exámenes
        var examenTableBody = document.getElementById("examen").querySelector('tbody');
        examenTableBody.innerHTML = '';

        // Recorre los datos recibidos y agrega cada examen a la tabla
        data.forEach(function(examen) {
            // Crea una nueva fila de tabla
            var newRow = document.createElement("tr");
            newRow.className = "text-center";

            // Crea celdas de tabla para los datos del examen
            var idAsignacionCell = document.createElement("td");
            idAsignacionCell.textContent = examen.id_asignacion;
            newRow.appendChild(idAsignacionCell);

            var nombreModuloCell = document.createElement("td");
            nombreModuloCell.textContent = examen.nombre_modulo;
            newRow.appendChild(nombreModuloCell);

            var nombreExamenCell = document.createElement("td");
            nombreExamenCell.textContent = examen.nombre_examen;
            newRow.appendChild(nombreExamenCell);

            var descripcionCell = document.createElement("td");
            descripcionCell.textContent = examen.descripcion;
            newRow.appendChild(descripcionCell);

            var fechaAsignacionCell = document.createElement("td");
            fechaAsignacionCell.textContent = examen.fecha_asignacion;
            newRow.appendChild(fechaAsignacionCell);

            var eliminarCell = document.createElement("td");
            var eliminarBtn = document.createElement("button");
            eliminarBtn.type = "button";
            eliminarBtn.className = "btn btn-warning";
            eliminarBtn.innerHTML = '<i class="far fa-trash-alt"></i>';
            eliminarCell.appendChild(eliminarBtn);
            newRow.appendChild(eliminarCell);

            // Agrega la nueva fila a la tabla de exámenes
            examenTableBody.appendChild(newRow);
        });
    })
    .catch(error => {
        console.error('Error al obtener los exámenes:', error);
    })
    .finally(() => {
        // Cierra el modal después de completar la operación
        var modalEstudiante = document.getElementById('ModalEstudiante');
        $(modalEstudiante).modal('hide');
    });
}
   

function asignarExamen(examenId) {
    var estudiante = document.getElementById("id_estudent").value;

    if(estudiante !== '0' && estudiante !== null){

    fetch('conexion/asignar_examen.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ estudiante: estudiante, examenId: examenId })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Hubo un problema al asignar el examen.');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Parsea la cadena JSON en un array de JavaScript
            var examenes = JSON.parse(data.examenes);
    
            // Si la asignación fue exitosa, actualiza la tabla de exámenes
            actualizarTablaExamanes(examenes);
        } else {
            // Si hubo un error, muestra un mensaje de error
            console.error('Error al asignar el examen:', data.error);
        }
    })
    .catch(error => {
        console.error('Error al asignar el examen:', error);
    })
    .finally(() => {
        // Cierra el modal después de completar la operación
        var ModalExamen = document.getElementById('ModalExamen');
        $(ModalExamen).modal('hide');
    });

}else{
    alert("Debe seleccionar un estudiante");
    limpiar();
}
}

// Función para actualizar la tabla de exámenes con los nuevos datos
function actualizarTablaExamanes(examenes) {
    var examenTableBody = document.getElementById("examen").querySelector('tbody');
    examenTableBody.innerHTML = '';

    // Recorre los datos recibidos y agrega cada examen a la tabla
    examenes.forEach(function(examen) {
        // Crea una nueva fila de tabla
        var newRow = document.createElement("tr");
        newRow.className = "text-center";

        // Crea celdas de tabla para los datos del examen
        var idAsignacionCell = document.createElement("td");
        idAsignacionCell.textContent = examen.id_asignacion;
        newRow.appendChild(idAsignacionCell);

        var nombreModuloCell = document.createElement("td");
        nombreModuloCell.textContent = examen.nombre_modulo;
        newRow.appendChild(nombreModuloCell);

        var nombreExamenCell = document.createElement("td");
        nombreExamenCell.textContent = examen.nombre_examen;
        newRow.appendChild(nombreExamenCell);

        var descripcionCell = document.createElement("td");
        descripcionCell.textContent = examen.descripcion;
        newRow.appendChild(descripcionCell);

        var fechaAsignacionCell = document.createElement("td");
        fechaAsignacionCell.textContent = examen.fecha_asignacion;
        newRow.appendChild(fechaAsignacionCell);

        var eliminarCell = document.createElement("td");
        var eliminarBtn = document.createElement("button");
        eliminarBtn.type = "button";
        eliminarBtn.className = "btn btn-warning";
        eliminarBtn.innerHTML = '<i class="far fa-trash-alt"></i>';
        eliminarCell.appendChild(eliminarBtn);
        newRow.appendChild(eliminarCell);

        // Agrega la nueva fila a la tabla de exámenes
        examenTableBody.appendChild(newRow);
    });
}

function limpiar(){
    document.getElementById('examen').querySelector('tbody').innerHTML = '';

    document.getElementById("estudiante").innerHTML = '<span class="roboto-medium">ESTUDIANTE:</span><span class="text-danger">&nbsp; <i class="fas fa-exclamation-triangle"></i> Seleccione un estudiante</span>';
    document.getElementById("id_estudent").value = 0;
}

function submitForm02(estudianteId,moduloId) {
    const form = document.getElementById('form-wizard');
    const formData = new FormData(form);
    let respuestas = [];
    let pregunta = [];

    for (let pair of formData.entries()) {
        // Recolecta las respuestas seleccionadas
        pregunta.push(pair[0]); // Agregar solo el ID de la pregunta
        respuestas.push(`${pair[0]}:${pair[1]}`); // Formato: id_pregunta=respuesta_seleccionada
    }

    // Concatenar las respuestas y preguntas como una cadena URL
    const dataToSend = 'respuestas=' +respuestas.join(',') + '&preguntas=' + pregunta.join(',')+ '&estudianteId=' + estudianteId+ '&moduloId=' + moduloId;

    // Enviar las respuestas al archivo PHP usando AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'conexion/guardar_respuestasEva.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Respuesta del servidor (puede ser un mensaje de éxito o error)
            const resp = xhr.responseText;
            alert(resp); // Mostrar mensaje de respuesta
            
            // Redireccionar al usuario a home.php
            window.location.href = './home.php';
        }
    };
    xhr.send(dataToSend);
}

function checkAsistencia(checkbox) {
    var studentId = checkbox.getAttribute('data-student-id'); // Obtener el ID del estudiante
    var testId = checkbox.getAttribute('data-test-id'); // Obtener el ID del test
    var attendance = checkbox.checked ? 1 : 0; // Verificar si el checkbox está marcado
    
    // Aquí puedes hacer lo que necesites con el ID del estudiante y el estado de asistencia
    console.log('Estudiante ' + studentId + ' está ' + attendance);
    
    // Por ejemplo, puedes enviar estos datos al servidor para guardarlos en la base de datos
    $.ajax({
        url: 'conexion/guardar_asistencia.php',
        method: 'POST',
        data: { studentId: studentId, testId: testId, attendance: attendance },
        success: function(response) {
            alert(response);
            console.log('Asistencia guardada exitosamente');
        },
        error: function(xhr, status, error) {
            console.error('Error al guardar la asistencia:', error);
        }
    });
}

// Función para mostrar los cursos asignados al estudiante
function mostrarCursos(idEstudiante,nmb_estudiante) {
    $('#nmb_estudiante').html(nmb_estudiante)
    $('#id_nmbEstud').val(idEstudiante)
    // Realizar una solicitud AJAX para obtener los cursos asignados al estudiante
    // Supongamos que tienes una función llamada obtenerCursosAsignados(idEstudiante) que devuelve los cursos asignados al estudiante con el ID proporcionado
    $.ajax({
      url: 'conexion/obtener_cursos_asignados.php', // Ruta de tu script PHP que obtiene los cursos asignados al estudiante
      method: 'POST',
      data: { idEstudiante: idEstudiante },
      success: function(response) {
        // Si la respuesta contiene cursos, los mostramos en la tabla
        // Si la respuesta está vacía, mostramos un mensaje
        if (response) {          
          $('#listaCursosEstudiante').html(response);
        } else {
          $('#listaCursosEstudiante').html('<tr><td colspan="2">No tiene cursos asignados.</td></tr>');
        }
      },
      error: function() {
        alert('Error al cargar los cursos asignados.');
      }
    });
  }

  // Manejar la presentación de cursos al cargar el modal
  $('#cursosModal').on('shown.bs.modal', function() {
    var idEstudiante = $(this).data('id-estudiante');
    var nmb_estudiante = $('#nmb_estudiante').html(); 
    mostrarCursos(idEstudiante,nmb_estudiante);
  });

  // Manejar la asignación de más cursos
function formAsignarCursos() {
    event.preventDefault();
    var idEstudiante = $('#id_nmbEstud').val();
    var idCurso = $('#selectCursosModal').val();
    var nmb_estudiante = $('#nmb_estudiante').html(); 
    // Realizar una solicitud AJAX para asignar el curso al estudiante
    // Supongamos que tienes una función llamada asignarCurso(idEstudiante, idCurso) que asigna el curso con el ID proporcionado al estudiante con el ID proporcionado
    $.ajax({
      url: 'conexion/asignar_curso.php', // Ruta de tu script PHP que asigna el curso al estudiante
      method: 'POST',
      data: { idEstudiante: idEstudiante, idCurso: idCurso },
      success: function(response) {
        alert(response);
        // Recargar la lista de cursos después de asignar un nuevo curso
        mostrarCursos(idEstudiante,nmb_estudiante);
      },
      error: function() {
        alert('Error al asignar el curso.');
      }
    });
  };

function selectInstructor(cont, estudiante, modulo) {
    var selectedInstructor = document.getElementById('instructor' + cont).value;
    var dateValue = document.getElementById('fecha' + cont).value;
    var selectSalon = document.getElementById('salon' + cont).value;
    var nmb_estudiante = $('#nmb_estudiante').html(); 
    $.ajax({
        url: 'conexion/asignarComplemento.php', // Ruta de tu script PHP que asigna el curso al estudiante
        method: 'POST',
        data: { idEstudiante: estudiante, idCurso: modulo, salon :selectSalon , instructor: selectedInstructor, fecha: dateValue },
        success: function(response) {
          alert(response);
          // Recargar la lista de cursos después de asignar un nuevo curso
          mostrarCursos(estudiante,nmb_estudiante);
        },
        error: function() {
          alert('Error al asignar el curso.');
        }
      });
}

function selectInstructorCampo(cont, estudiante, modulo) {
    var selectedInstructor = document.getElementById('instructor' + cont).value;
    var dateValue = document.getElementById('fecha' + cont).value;
    var nmb_estudiante = $('#nmb_estudiante').html(); 
    $.ajax({
        url: 'conexion/asignarComplementoCampo.php', // Ruta de tu script PHP que asigna el curso al estudiante
        method: 'POST',
        data: { idEstudiante: estudiante, idCurso: modulo, instructor: selectedInstructor, fecha: dateValue },
        success: function(response) {
          alert(response);
          // Recargar la lista de cursos después de asignar un nuevo curso
          mostrarCursos(estudiante,nmb_estudiante);
        },
        error: function() {
          alert('Error al asignar el curso.');
        }
      });
}

function validarCapacidadSalon(salon) {
    // Obtener el valor seleccionado del menú desplegable
    var salonSeleccionado = document.getElementById(salon).value;

    // Realizar una solicitud AJAX para obtener la capacidad del salón
    $.ajax({
        url: 'conexion/obtener_capacidad_salon.php', // Ruta al script PHP que obtiene la capacidad del salón
        type: 'POST',
        data: { salon_id: salonSeleccionado }, // Enviar el ID del salón seleccionado
        dataType: 'json',
        success: function(response) {
                if(response.msn == true){
                    // Mostrar el mensaje de capacidad en el elemento HTML
                    alert("La capacidad del salón es de " + response.capacidad + " estudiantes. Pase al siguiente salon");
                    document.getElementById(salon).value = "";
                }  
                
        },
        error: function(xhr, status, error) {
            // Manejar errores de la solicitud AJAX
            console.error('Error al obtener la capacidad del salón:', error);
            alert("Error al obtener la capacidad del salón. Por favor, intenta de nuevo más tarde.");
        }
    });
}

function deleteInstructor(cont, estudiante, modulo) {   
    var nmb_estudiante = $('#nmb_estudiante').html(); 
    $.ajax({
        url: 'conexion/eliminarComplemento.php', // Ruta de tu script PHP que asigna el curso al estudiante
        method: 'POST',
        data: { idEstudiante: estudiante, idCurso: modulo},
        success: function(response) {
          alert(response);
          // Recargar la lista de cursos después de asignar un nuevo curso
          mostrarCursos(estudiante,nmb_estudiante);
        },
        error: function() {
          alert('Error al asignar el curso.');
        }
      });
}

function validateDate(selecteDateX, estudiante, modulo) {
    const selectedDate = document.getElementById(selecteDateX).value; 
    const studentId = estudiante; 
    const moduleId = modulo; 

    if (!selectedDate) {
        alert('Por favor selecciona una fecha válida.');
        return;
    }

    $.ajax({
        url: 'conexion/check_date_conflict.php',
        method: 'POST',
        data: { date: selectedDate, studentId: studentId, moduleId: moduleId},
        success: function(response) {
           const data = typeof response === 'string' ? JSON.parse(response) : response;
            if (data.conflict) { // Check the conflict property 
                alert('La fecha seleccionada ya tiene un examen asignado para otro módulo.');
                document.getElementById(selecteDateX).value = ''; 
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}


function descargarReporte01() {
    fetch('conexion/export-Reporte01.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.blob(); // Convertir la respuesta a un Blob
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob); // Crear un URL temporal
            const a = document.createElement('a'); // Crear un enlace
            a.href = url;
            a.download = 'reporte01.xlsx'; // Nombre del archivo
            document.body.appendChild(a);
            a.click(); // Simular clic para descargar
            a.remove(); // Limpiar el enlace
        })
        .catch(error => {
            console.error('Error al descargar el archivo:', error);
        });
}

function descargarReporte02() {
    fetch('conexion/export-Reporte02.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.blob(); // Convertir la respuesta a un Blob
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob); // Crear un URL temporal
            const a = document.createElement('a'); // Crear un enlace
            a.href = url;
            a.download = 'reporte02.xlsx'; // Nombre del archivo
            document.body.appendChild(a);
            a.click(); // Simular clic para descargar
            a.remove(); // Limpiar el enlace
        })
        .catch(error => {
            console.error('Error al descargar el archivo:', error);
        });
}

