document.addEventListener('DOMContentLoaded', function () {
    // Función para importar contenido
    function importarContenido(event) {
        console.log(event);
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
