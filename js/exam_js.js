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

function submitForm(examenId) {
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
    const dataToSend = 'respuestas=' +respuestas.join(',') + '&preguntas=' + pregunta.join(',')+ '&examenId=' + examenId;

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

