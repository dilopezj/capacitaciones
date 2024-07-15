function crearEstudiante() {
    var form = document.getElementById("formularioEstudiante");

    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            var formData = new FormData(form);

            fetch("conexion/guardar_estudiante.php", {
                method: "POST",
                body: formData
            })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Error al enviar el formulario");
                        }
                        return response.text();
                    })
                    .then(data => {
                        console.log(data);
                        alert(data);
                        form.reset(); // Limpiar el formulario después de enviar
                        // Cierra el modal después de completar la operación
                        var Modalcrear = document.getElementById('Modalcrear');
                        $(Modalcrear).modal('hide');
                        menuReload('estudiantes-list.php', 'Listar estudiantes');

                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Ha ocurrido un error al enviar el formulario");
                    });
        });
    } else {
        console.error("El formulario no fue encontrado en el documento.");
    }
}

function crearInstructor() {
    var form = document.getElementById("formularioInstructor");

    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            var formData = new FormData(form);

            fetch("conexion/guardar_instructor.php", {
                method: "POST",
                body: formData
            })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Error al enviar el formulario");
                        }
                        return response.text();
                    })
                    .then(data => {
                        console.log(data);
                        alert("Instructor creado exitosamente");
                        form.reset(); // Limpiar el formulario después de enviar
                        // Cierra el modal después de completar la operación
                        var Modalcrear = document.getElementById('Modalcrear');
                        $(Modalcrear).modal('hide');
                        menuReload('instructor-list.php', 'Listar Instructores');

                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Ha ocurrido un error al enviar el formulario");
                    });
        });
    } else {
        console.error("El formulario no fue encontrado en el documento.");
    }
}

// JavaScript/jQuery para manejar el cambio de departamento
function changeDpto() {
    var departamentoId = $("#regional").val();

    $.ajax({
        url: 'conexion/obtener_municipios.php', // Ruta de tu script PHP que obtiene los cursos asignados al estudiante
        method: 'POST',
        data: {departamento_id: departamentoId},
        success: function (response) {
            if (response.trim() !== '') {
                // Agregar las opciones al select de ciudades
                $('#ciudad').html(response);
            } else {
                // Si la respuesta está vacía, mostrar un mensaje
                $('#ciudad').html('<option value="">No hay ciudades disponibles</option>');
            }
        },
        error: function () {
            alert('Error al cargar los cursos asignados.');
        }
    });
}

function changeDptoE(callback) {
    var departamentoId = $("#regionalE").val();

    $.ajax({
        url: 'conexion/obtener_municipios.php', // Ruta de tu script PHP que obtiene los cursos asignados al estudiante
        method: 'POST',
        data: {departamento_id: departamentoId},
        success: function (response) {
            if (response.trim() !== '') {
                // Agregar las opciones al select de ciudades
                $('#ciudadE').html(response);
                // Llamar al callback si está definido
                if (typeof callback === 'function') {
                    callback();
                }
            } else {
                // Si la respuesta está vacía, mostrar un mensaje
                $('#ciudadE').html('<option value="">No hay ciudades disponibles</option>');
            }
        },
        error: function () {
            alert('Error al cargar los cursos asignados.');
        }
    });
}

function crearUsuarios() {
    var form = document.getElementById("formularioUsuarios");

    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            var formData = new FormData(form);

            fetch("conexion/guardar_usuarios.php", {
                method: "POST",
                body: formData
            })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Error al enviar el formulario");
                        }
                        return response.text();
                    })
                    .then(data => {
                        console.log(data);
                        alert("Usuario creado exitosamente");
                        form.reset(); // Limpiar el formulario después de enviar
                        // Cierra el modal después de completar la operación
                        var Modalcrear = document.getElementById('Modalcrear');
                        $(Modalcrear).modal('hide');
                        menuReload('usuarios-list.php', 'Listar Usuarios');

                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Ha ocurrido un error al enviar el formulario");
                    });
        });
    } else {
        console.error("El formulario no fue encontrado en el documento.");
    }
}

function crearEmpresa() {
    var form = document.getElementById("formularioEmpresas");

    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            var formData = new FormData(form);

            fetch("conexion/guardar_empresas.php", {
                method: "POST",
                body: formData
            })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Error al enviar el formulario");
                        }
                        return response.text();
                    })
                    .then(data => {
                        console.log(data);
                        alert("Empresa creada exitosamente");
                        form.reset(); // Limpiar el formulario después de enviar
                        // Cierra el modal después de completar la operación
                        var Modalcrear = document.getElementById('Modalcrear');
                        $(Modalcrear).modal('hide');
                        menuReload('empresa-list.php', 'Listar Empresas');
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Ha ocurrido un error al enviar el formulario");
                    });
        });
    } else {
        console.error("El formulario no fue encontrado en el documento.");
    }
}

function crearCursos() {
    var form = document.getElementById("formularioCursos");

    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            var formData = new FormData(form);

            fetch("conexion/guardar_cursos.php", {
                method: "POST",
                body: formData
            })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Error al enviar el formulario");
                        }
                        return response.text();
                    })
                    .then(data => {
                        console.log(data);
                        alert("Curso creada exitosamente");
                        form.reset(); // Limpiar el formulario después de enviar
                        // Cierra el modal después de completar la operación
                        var Modalcrear = document.getElementById('Modalcrear');
                        $(Modalcrear).modal('hide');
                        menuReload('modulo-list.php', 'Listar Cursos');
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Ha ocurrido un error al enviar el formulario");
                    });
        });
    } else {
        console.error("El formulario no fue encontrado en el documento.");
    }
}

function crearExamen() {
    var form = document.getElementById("formularioExamen");

    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            var formData = new FormData(form);

            fetch("conexion/guardar_examen.php", {
                method: "POST",
                body: formData
            })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Error al enviar el formulario");
                        }
                        return response.text();
                    })
                    .then(data => {
                        console.log(data);
                        alert("Examen creado exitosamente");
                        form.reset(); // Limpiar el formulario después de enviar
                        // Cierra el modal después de completar la operación
                        var Modalcrear = document.getElementById('Modalcrear');
                        $(Modalcrear).modal('hide');
                        menuReload('examen-list.php', 'Listar Evaluaciones');
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Ha ocurrido un error al enviar el formulario");
                    });
        });
    } else {
        console.error("El formulario no fue encontrado en el documento.");
    }
}

/****CArgue Excel */
function cargarArchivo() {
    // Crea un objeto FormData para enviar el formulario de manera asíncrona
    var formData = new FormData();
    var archivo = document.getElementById('archivo').files[0];
    formData.append('archivo', archivo);

    // Crea una nueva instancia de XMLHttpRequest
    var xhr = new XMLHttpRequest();

    // Define la función de respuesta
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Maneja la respuesta del servidor, por ejemplo, muestra un mensaje de éxito
                alert('Archivo cargado exitosamente');
                // Cierra el modal después de completar la operación
                var ModalArchivo = document.getElementById('ModalArchivo');
                $(ModalArchivo).modal('hide');
                menuReload('usuarios-list.php', 'Listar Usuarios');
            } else {
                // Maneja errores, por ejemplo, muestra un mensaje de error
                alert('Error al cargar el archivo');
            }
        }
    };

    // Abre la conexión y envía el formulario con el archivo
    xhr.open('POST', 'conexion/excelUsuarios.php', true);
    xhr.send(formData);
}

/****CArgue Excel */
function cargarArchivoEmp() {
    // Crea un objeto FormData para enviar el formulario de manera asíncrona
    var formData = new FormData();
    var archivo = document.getElementById('archivo').files[0];
    formData.append('archivo', archivo);

    // Crea una nueva instancia de XMLHttpRequest
    var xhr = new XMLHttpRequest();

    // Define la función de respuesta
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Maneja la respuesta del servidor, por ejemplo, muestra un mensaje de éxito
                alert('Archivo cargado exitosamente');
                // Cierra el modal después de completar la operación
                var ModalArchivo = document.getElementById('ModalArchivo');
                $(ModalArchivo).modal('hide');
                menuReload('empresa-list.php', 'Listar Empresas');
            } else {
                // Maneja errores, por ejemplo, muestra un mensaje de error
                alert('Error al cargar el archivo');
            }
        }
    };

    // Abre la conexión y envía el formulario con el archivo
    xhr.open('POST', 'conexion/excelEmpresa.php', true);
    xhr.send(formData);
}

/****CArgue Excel */
function cargarArchivoEst() {
    // Crea un objeto FormData para enviar el formulario de manera asíncrona
    var formData = new FormData();
    var archivo = document.getElementById('archivo').files[0];
    formData.append('archivo', archivo);

    // Crea una nueva instancia de XMLHttpRequest
    var xhr = new XMLHttpRequest();

    // Define la función de respuesta
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Maneja la respuesta del servidor, por ejemplo, muestra un mensaje de éxito
                alert('Archivo cargado exitosamente');
                // Cierra el modal después de completar la operación
                var ModalArchivo = document.getElementById('ModalArchivo');
                $(ModalArchivo).modal('hide');
                menuReload('estudiantes-list.php', 'Listar Estudiantes');
            } else {
                // Maneja errores, por ejemplo, muestra un mensaje de error
                alert('Error al cargar el archivo');
            }
        }
    };

    // Abre la conexión y envía el formulario con el archivo
    xhr.open('POST', 'conexion/excelEstudiante.php', true);
    xhr.send(formData);
}

/****CArgue Excel */
function cargarArchivoCursos() {
    // Crea un objeto FormData para enviar el formulario de manera asíncrona
    var formData = new FormData();
    var archivo = document.getElementById('archivo').files[0];
    formData.append('archivo', archivo);

    // Crea una nueva instancia de XMLHttpRequest
    var xhr = new XMLHttpRequest();

    // Define la función de respuesta
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Maneja la respuesta del servidor, por ejemplo, muestra un mensaje de éxito
                alert('Archivo cargado exitosamente');
                // Cierra el modal después de completar la operación
                var ModalArchivo = document.getElementById('ModalArchivo');
                $(ModalArchivo).modal('hide');
                menuReload('modulo-list.php', 'Listar Cursos');
            } else {
                // Maneja errores, por ejemplo, muestra un mensaje de error
                alert('Error al cargar el archivo');
            }
        }
    };

    // Abre la conexión y envía el formulario con el archivo
    xhr.open('POST', 'conexion/excelModulos.php', true);
    xhr.send(formData);
}

/****CArgue Excel */
function cargarArchivoExamen() {
    // Crea un objeto FormData para enviar el formulario de manera asíncrona
    var formData = new FormData();
    var archivo = document.getElementById('archivo').files[0];
    formData.append('archivo', archivo);

    // Crea una nueva instancia de XMLHttpRequest
    var xhr = new XMLHttpRequest();

    // Define la función de respuesta
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Maneja la respuesta del servidor, por ejemplo, muestra un mensaje de éxito
                alert('Archivo cargado exitosamente');
                // Cierra el modal después de completar la operación
                var ModalArchivo = document.getElementById('ModalArchivo');
                $(ModalArchivo).modal('hide');
                menuReload('examen-list.php', 'Listar Evaluaciones');
            } else {
                // Maneja errores, por ejemplo, muestra un mensaje de error
                alert('Error al cargar el archivo');
            }
        }
    };

    // Abre la conexión y envía el formulario con el archivo
    xhr.open('POST', 'conexion/excelExamenes.php', true);
    xhr.send(formData);
}

/****CArgue Excel */
function cargarPreguntas() {
    // Crea un objeto FormData para enviar el formulario de manera asíncrona
    var formData = new FormData();
    var archivo = document.getElementById('archivoPreguntas').files[0];
    formData.append('archivoPreguntas', archivo);

    // Crea una nueva instancia de XMLHttpRequest
    var xhr = new XMLHttpRequest();

    // Define la función de respuesta
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Maneja la respuesta del servidor, por ejemplo, muestra un mensaje de éxito
                alert('Archivo cargado exitosamente');
                // Cierra el modal después de completar la operación               
                menuReload('cargue_preguntas.php', 'Cargue de Datos Excel');
            } else {
                // Maneja errores, por ejemplo, muestra un mensaje de error
                alert('Error al cargar el archivo');
            }
        }
    };

    // Abre la conexión y envía el formulario con el archivo
    xhr.open('POST', 'conexion/preguntas_excel.php', true);
    xhr.send(formData);
}

/****CArgue Excel */
function cargarRespuestas() {
    // Crea un objeto FormData para enviar el formulario de manera asíncrona
    var formData = new FormData();
    var archivo = document.getElementById('archivoRespuestas').files[0];
    formData.append('archivoRespuestas', archivo);

    // Crea una nueva instancia de XMLHttpRequest
    var xhr = new XMLHttpRequest();

    // Define la función de respuesta
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Maneja la respuesta del servidor, por ejemplo, muestra un mensaje de éxito
                alert('Archivo cargado exitosamente');
                // Cierra el modal después de completar la operación               
                menuReload('cargue_preguntas.php', 'Cargue de Datos Excel');
            } else {
                // Maneja errores, por ejemplo, muestra un mensaje de error
                alert('Error al cargar el archivo');
            }
        }
    };

    // Abre la conexión y envía el formulario con el archivo
    xhr.open('POST', 'conexion/respuestas_excel.php', true);
    xhr.send(formData);
}

/****CArgue Excel */
function cargarArchivoIns() {
    // Crea un objeto FormData para enviar el formulario de manera asíncrona
    var formData = new FormData();
    var archivo = document.getElementById('archivo').files[0];
    formData.append('archivo', archivo);

    // Crea una nueva instancia de XMLHttpRequest
    var xhr = new XMLHttpRequest();

    // Define la función de respuesta
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Maneja la respuesta del servidor, por ejemplo, muestra un mensaje de éxito
                var respuesta = JSON.parse(xhr.responseText);
                if (respuesta.length > 0) {
                    alert(respuesta.join('\n'));
                } else {
                    alert('Archivo cargado exitosamente');
                    // Cierra el modal después de completar la operación
                    var ModalArchivo = document.getElementById('ModalArchivo');
                    $(ModalArchivo).modal('hide');
                    menuReload('instructor-list.php', 'Listar Instructores');
                }
            } else {
                // Maneja errores, por ejemplo, muestra un mensaje de error
                alert('Error al cargar el archivo');
            }
        }
    };

    // Abre la conexión y envía el formulario con el archivo
    xhr.open('POST', 'conexion/excelInstructor.php', true);
    xhr.send(formData);
}

/** 
 * Función para eliminar un usuario.
 * 
 * @param {number} idUsuario - El ID del usuario que se va a eliminar.
 */
function eliminarUsuario(idUsuario) {
    if (confirm('¿Estás seguro de eliminar este usuario?')) {
        // Crea un objeto FormData para enviar el ID del usuario a eliminar
        var formData = new FormData();
        formData.append('id_usuario', idUsuario);

        // Crea una nueva instancia de XMLHttpRequest
        var xhr = new XMLHttpRequest();

        // Define la función de respuesta
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Maneja la respuesta del servidor
                    alert(xhr.responseText);
                    menuReload('usuarios-list.php', 'Listar Usuarios');
                    // Actualizar la tabla u otra parte de la página si es necesario
                } else {
                    // Muestra un mensaje de error si la solicitud no se realizó correctamente
                    alert('Error al eliminar el usuario');
                }
            }
        };

        // Abre la conexión y envía el ID del usuario a eliminar
        xhr.open('POST', 'conexion/eliminar_usuario.php', true);
        xhr.send(formData);
    }
}

function btnEditarUsuario(datosUsuario) {
    // Comprueba si los datosUsuario son un objeto
    if (typeof datosUsuario === 'object') {
        document.getElementById('ideE').value = datosUsuario.identificacion;
        document.getElementById('usuarioE').value = datosUsuario.nombre_usuario;
        document.getElementById('perfilE').value = datosUsuario.id_perfil;
        document.getElementById('correoE').value = datosUsuario.correo_usuario;
        // Similar para otros campos del formulario

        document.getElementById('idUsuarioE').value = datosUsuario.id_usuario;
    } else {
        console.error("Los datos del usuario no son un objeto.");
    }
}

function EditarUsuarios() {
    var form = document.getElementById("formularioUsuariosE");

    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            var formData = new FormData(form);

            fetch("conexion/editar_usuarios.php", {
                method: "POST",
                body: formData
            })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Error al enviar el formulario");
                        }
                        return response.text();
                    })
                    .then(data => {
                        console.log(data);
                        alert("Usuario editado exitosamente");
                        form.reset(); // Limpiar el formulario después de enviar
                        // Cierra el modal después de completar la operación
                        var ModalEditar = document.getElementById('ModalEditar');
                        $(ModalEditar).modal('hide');
                        menuReload('usuarios-list.php', 'Listar Usuarios');

                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Ha ocurrido un error al enviar el formulario");
                    });
        });
    } else {
        console.error("El formulario no fue encontrado en el documento.");
    }
}

function eliminarEmpresa(idEmpresa) {
    if (confirm('¿Estás seguro de eliminar esta empresa?')) {
        // Crea un objeto FormData para enviar el ID del usuario a eliminar
        var formData = new FormData();
        formData.append('idEmpresa', idEmpresa);

        // Crea una nueva instancia de XMLHttpRequest
        var xhr = new XMLHttpRequest();

        // Define la función de respuesta
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Maneja la respuesta del servidor
                    alert(xhr.responseText);
                    menuReload('empresa-list.php', 'Listar Empresas');
                    // Actualizar la tabla u otra parte de la página si es necesario
                } else {
                    // Muestra un mensaje de error si la solicitud no se realizó correctamente
                    alert('Error al eliminar la empresa');
                }
            }
        };

        // Abre la conexión y envía el ID del empresa a eliminar
        xhr.open('POST', 'conexion/eliminar_empresa.php', true);
        xhr.send(formData);
    }
}

function btnEditarEmpresa(datos) {
    // Comprueba si los datos son un objeto
    if (typeof datos === 'object') {
        document.getElementById('regionalE').value = datos.regional;
        changeDptoE(function () {
            // Este callback se ejecuta después de que changeDptoE() termine
            document.getElementById('ciudadE').value = datos.ciudadEM;
        });
        document.getElementById('nitE').value = datos.nit;
        document.getElementById('nmb_empresaE').value = datos.empresa;
        document.getElementById('direccionE').value = datos.direccion;
        document.getElementById('telefonoE').value = datos.telefono;
        document.getElementById('correoE').value = datos.correo_contacto;
        document.getElementById('nmb_contactoE').value = datos.nmb_contacto;
        document.getElementById('apl_contactoE').value = datos.apl_contacto;

        // Similar para otros campos del formulario

        document.getElementById('idEmpresaE').value = datos.nit;
    } else {
        console.error("Los datos de la empresa no son un objeto.");
    }
}

function EditarEmpresas() {
    var form = document.getElementById("formularioEmpresasE");

    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            var formData = new FormData(form);

            fetch("conexion/editar_empresa.php", {
                method: "POST",
                body: formData
            })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Error al enviar el formulario");
                        }
                        return response.text();
                    })
                    .then(data => {
                        console.log(data);
                        alert("Empresa editada exitosamente");
                        form.reset(); // Limpiar el formulario después de enviar
                        // Cierra el modal después de completar la operación
                        var ModalEditar = document.getElementById('ModalEditar');
                        $(ModalEditar).modal('hide');
                        menuReload('empresa-list.php', 'Listar Empresas');

                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Ha ocurrido un error al enviar el formulario");
                    });
        });
    } else {
        console.error("El formulario no fue encontrado en el documento.");
    }
}

// Función para eliminar empresas seleccionadas
function eliminarEmpresasSeleccionadas() {
    var seleccionados = document.querySelectorAll('.checkEliminar:checked');
    var idsEliminar = [];

    seleccionados.forEach(function (check) {
        idsEliminar.push(check.value);
    });

    if (idsEliminar.length === 0) {
        alert('Por favor, selecciona al menos una empresa para eliminar.');
        return;
    }

    // Confirmar eliminación
    if (!confirm('¿Estás seguro que deseas eliminar las empresas seleccionadas?')) {
        return;
    }

    // Petición AJAX para eliminar
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'conexion/eliminar_empresas_seleccionadas.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            // Recargar la página o actualizar la tabla de empresas
            menuReload('empresa-list.php', 'Listar Empresas');
        } else {
            alert('Hubo un problema al eliminar las empresas. Inténtalo de nuevo.');
        }
    };

    // Enviar los NITs a eliminar como parámetros
    var params = 'ids=' + encodeURIComponent(idsEliminar.join(','));
    xhr.send(params);
}

// Función para seleccionar/deseleccionar todos los checkboxes
document.getElementById('checkTodos').addEventListener('change', function () {
    var checkboxes = document.querySelectorAll('.checkEliminar');
    checkboxes.forEach(function (checkbox) {
        checkbox.checked = this.checked;
    }, this);
});

function eliminarInstructor(idInstructor) {
    if (confirm('¿Estás seguro de eliminar este instructor?')) {
        // Crea un objeto FormData para enviar el ID del usuario a eliminar
        var formData = new FormData();
        formData.append('idInstructor', idInstructor);

        // Crea una nueva instancia de XMLHttpRequest
        var xhr = new XMLHttpRequest();

        // Define la función de respuesta
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Maneja la respuesta del servidor
                    alert(xhr.responseText);
                    menuReload('instructor-list.php', 'Listar Instructores');
                    // Actualizar la tabla u otra parte de la página si es necesario
                } else {
                    // Muestra un mensaje de error si la solicitud no se realizó correctamente
                    alert('Error al eliminar Instructor');
                }
            }
        };

        // Abre la conexión y envía el ID del empresa a eliminar
        xhr.open('POST', 'conexion/eliminar_instructor.php', true);
        xhr.send(formData);
    }
}

// Función para eliminar empresas seleccionadas
function eliminarInstructoresSeleccionados() {
    var seleccionados = document.querySelectorAll('.checkEliminar:checked');
    var idsEliminar = [];

    seleccionados.forEach(function (check) {
        idsEliminar.push(check.value);
    });

    if (idsEliminar.length === 0) {
        alert('Por favor, selecciona al menos un instructor para eliminar.');
        return;
    }

    // Confirmar eliminación
    if (!confirm('¿Estás seguro que deseas eliminar los instructor seleccionados?')) {
        return;
    }

    // Petición AJAX para eliminar
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'conexion/eliminar_instructores_seleccionados.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            // Recargar la página o actualizar la tabla de empresas
            menuReload('instructor-list.php', 'Listar Instructores');
        } else {
            alert('Hubo un problema al eliminar los instructores. Inténtalo de nuevo.');
        }
    };

    // Enviar los NITs a eliminar como parámetros
    var params = 'ids=' + encodeURIComponent(idsEliminar.join(','));
    xhr.send(params);
}

// Función para seleccionar/deseleccionar todos los checkboxes
document.getElementById('checkTodos').addEventListener('change', function () {
    var checkboxes = document.querySelectorAll('.checkEliminar');
    checkboxes.forEach(function (checkbox) {
        checkbox.checked = this.checked;
    }, this);
});

function btnEditarInstructor(datos) {
    // Comprueba si los datos son un objeto
    if (typeof datos === 'object') {
        document.getElementById('regionalE').value = datos.regional;
        changeDptoE(function () {
            // Este callback se ejecuta después de que changeDptoE() termine
            document.getElementById('ciudadE').value = datos.ciudadEM;
        });
        document.getElementById('tipo_documentoE').value = datos.tipo_identificacion;
        document.getElementById('documentoE').value = datos.identificacion;
        document.getElementById('nombresE').value = datos.nombres;
        document.getElementById('apellidosE').value = datos.apellidos;

        // Similar para otros campos del formulario

        document.getElementById('idInstructorE').value = datos.identificacion;
    } else {
        console.error("Los datos de los instructores no son un objeto.");
    }
}

function EditarInstructors() {
    var form = document.getElementById("formularioInstructorsE");

    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            var formData = new FormData(form);

            fetch("conexion/editar_instructor.php", {
                method: "POST",
                body: formData
            })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Error al enviar el formulario");
                        }
                        return response.text();
                    })
                    .then(data => {
                        console.log(data);
                        alert("Instructor editado exitosamente");
                        form.reset(); // Limpiar el formulario después de enviar
                        // Cierra el modal después de completar la operación
                        var ModalEditar = document.getElementById('ModalEditar');
                        $(ModalEditar).modal('hide');
                        menuReload('instructor-list.php', 'Listar Instructores');

                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Ha ocurrido un error al enviar el formulario");
                    });
        });
    } else {
        console.error("El formulario no fue encontrado en el documento.");
    }
}


function eliminarEstudiante(idEstudiante) {
    if (confirm('¿Estás seguro de eliminar este estudiante?')) {
        // Crea un objeto FormData para enviar el ID del usuario a eliminar
        var formData = new FormData();
        formData.append('idEstudiante', idEstudiante);

        // Crea una nueva instancia de XMLHttpRequest
        var xhr = new XMLHttpRequest();

        // Define la función de respuesta
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Maneja la respuesta del servidor
                    alert(xhr.responseText);
                    menuReload('estudiantes-list.php', 'Listar Estudiantes');
                    // Actualizar la tabla u otra parte de la página si es necesario
                } else {
                    // Muestra un mensaje de error si la solicitud no se realizó correctamente
                    alert('Error al eliminar Estudiante');
                }
            }
        };

        // Abre la conexión y envía el ID del empresa a eliminar
        xhr.open('POST', 'conexion/eliminar_estudiante.php', true);
        xhr.send(formData);
    }
}

// Función para eliminar empresas seleccionadas
function eliminarEstudiantesSeleccionados() {
    var seleccionados = document.querySelectorAll('.checkEliminar:checked');
    var idsEliminar = [];

    seleccionados.forEach(function (check) {
        idsEliminar.push(check.value);
    });

    if (idsEliminar.length === 0) {
        alert('Por favor, selecciona al menos un estudiante para eliminar.');
        return;
    }

    // Confirmar eliminación
    if (!confirm('¿Estás seguro que deseas eliminar los estudiantes seleccionados?')) {
        return;
    }

    // Petición AJAX para eliminar
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'conexion/eliminar_estudiantes_seleccionados.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            // Recargar la página o actualizar la tabla de empresasmenuReload('instructor
            menuReload('estudiantes-list.php', 'Listar Estudiantes');
        } else {
            alert('Hubo un problema al eliminar los estudantes. Inténtalo de nuevo.');
        }
    };

    // Enviar los NITs a eliminar como parámetros
    var params = 'ids=' + encodeURIComponent(idsEliminar.join(','));
    xhr.send(params);
}

// Función para seleccionar/deseleccionar todos los checkboxes
document.getElementById('checkTodos').addEventListener('change', function () {
    var checkboxes = document.querySelectorAll('.checkEliminar');
    checkboxes.forEach(function (checkbox) {
        checkbox.checked = this.checked;
    }, this);
});

function btnEditarEstudiante(datos) {
    // Comprueba si los datos son un objeto
    if (typeof datos === 'object') {
        document.getElementById('empresaE').value = datos.nit;        
        document.getElementById('tipo_documentoE').value = datos.tipo_identificacion;
        document.getElementById('documentoE').value = datos.id_estudiante;
        document.getElementById('nombresE').value = datos.nombre;
        document.getElementById('apellidosE').value = datos.apellido;        
        document.getElementById('generoE').value = datos.genedi_id;
        document.getElementById('cargoE').value = datos.cargo;
        document.getElementById('celularE').value = datos.celular;        
        document.getElementById('correoE').value = datos.correo;

        // Similar para otros campos del formulario

        document.getElementById('idEstudianteE').value = datos.id_estudiante;
    } else {
        console.error("Los datos de los estudiantes no son un objeto.");
    }
}

function EditarEstudiantes() {
    var form = document.getElementById("formularioestudiantesE");

    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            var formData = new FormData(form);

            fetch("conexion/editar_estudiante.php", {
                method: "POST",
                body: formData
            })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Error al enviar el formulario");
                        }
                        return response.text();
                    })
                    .then(data => {
                        console.log(data);
                        alert("Estudiante editado exitosamente");
                        form.reset(); // Limpiar el formulario después de enviar
                        // Cierra el modal después de completar la operación
                        var ModalEditar = document.getElementById('ModalEditar');
                        $(ModalEditar).modal('hide');
                        menuReload('estudiantes-list.php', 'Listar Estudiantes');

                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Ha ocurrido un error al enviar el formulario");
                    });
        });
    } else {
        console.error("El formulario no fue encontrado en el documento.");
    }
}

