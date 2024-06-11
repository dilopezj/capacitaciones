document.addEventListener('DOMContentLoaded', function () {
    // Función para importar contenido
    function importarContenido(event) {
        ///console.log(event);
        event.preventDefault(); // Prevenir comportamiento por defecto del enlace

        // Obtener el nombre del archivo a importar desde el data-file del enlace
        const archivo = event.target.dataset.file;
        const url = archivo; // URL del archivo a importar

        // Obtener el contenido del archivo
        fetch(url)
                .then(response => response.text())
                .then(data => {
                    // Insertar el contenido en el contenedor
                    document.getElementById('contenido-importado').innerHTML = data;
                    document.getElementById('title_page').innerHTML = event.target.innerText;

                    // Verificar si la tabla ya tiene DataTable aplicado
                    if (!$.fn.DataTable.isDataTable('#example')) {
                        // Si no tiene DataTable aplicado, entonces aplicar DataTable
                        $('#example').DataTable();
                    }
                })
                .catch(error => console.error('Error al importar contenido:', error));
    }

    // Agregar evento de clic a todos los elementos del menú
    const menuItems = document.querySelectorAll('.tile_menu');
    menuItems.forEach(item => {
        item.addEventListener('click', importarContenido);
    });
});

function realizarExamen(examenId, title) {
    // Obtener el contenido del examen desde el servidor
    fetch(`examen_a_realizar.php?id=${examenId}`)
            .then(response => response.text())
            .then(data => {
                // Insertar el contenido del examen en el div contenido-importado
                document.getElementById('contenido-importado').innerHTML = data;
                document.getElementById('title_page').innerHTML = title;
            })
            .catch(error => console.error('Error al obtener el examen:', error));
}

function realizarExamen_evaluador(id_estudiante,id_modulo){
    var modalApre = document.getElementById('ModalEvaApreciacion');
    $(modalApre).modal('hide');
     // Obtener el contenido del examen desde el servidor
     fetch(`examen_realizarEvaluador.php?id=${id_estudiante}&modulo=${id_modulo}`)
     .then(response => response.text())
     .then(data => {
         // Insertar el contenido del examen en el div contenido-importado
         document.getElementById('contenido-importado').innerHTML = data;
         document.getElementById('title_page').innerHTML = "Evaluación de Campo";
     })
     .catch(error => console.error('Error al obtener el examen:', error));

}

function menuModulos(modulo, title) {
    // Obtener el contenido del examen desde el servidor
    fetch(`examen-pending.php?m=${modulo}`)
            .then(response => response.text())
            .then(data => {
                // Insertar el contenido del examen en el div contenido-importado
                document.getElementById('contenido-importado').innerHTML = data;
                document.getElementById('title_page').innerHTML = title;

                // Verificar si la tabla ya tiene DataTable aplicado
                if (!$.fn.DataTable.isDataTable('#example')) {
                    // Si no tiene DataTable aplicado, entonces aplicar DataTable
                    $('#example').DataTable({
                        scrollX: true,
                        scrollY: 300,
                        searching: true // Habilitar el filtro por campos
                       /* language: {
                            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                        } */
                    });
                }
            })
            .catch(error => console.error('Error al obtener el examen:', error));
}

function menuReload(url, title) {
    // Obtener el contenido del examen desde el servidor
    fetch(url)
            .then(response => response.text())
            .then(data => {
                // Insertar el contenido del examen en el div contenido-importado
                document.getElementById('contenido-importado').innerHTML = data;
                document.getElementById('title_page').innerHTML = title;

                // Verificar si la tabla ya tiene DataTable aplicado
                if (!$.fn.DataTable.isDataTable('#example')) {
                    // Si no tiene DataTable aplicado, entonces aplicar DataTable
                    $('#example').DataTable({
                        scrollX: true,
                         scrollY: 300,
                         searching: true // Habilitar el filtro por campos
                         /* language: {
                            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                        } */
                    });
                }
            })
            .catch(error => console.error('Error al obtener el examen:', error));
}
