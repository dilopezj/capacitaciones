document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada

            // Obtener datos del formulario
            var UserName = document.getElementById('UserName').value;
            var contrasena = document.getElementById('UserPassword').value;
            
            if(UserName != "" && contrasena !=""){
                // Realizar la solicitud AJAX al backend (PHP)
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'conexion/validar_login.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Respuesta del servidor
                    var respuesta = xhr.responseText;
                     // Mostrar respuesta del servidor (puede ser un mensaje de éxito o error)
                    if(respuesta == 1){
                        // Redireccionar al usuario a home.php
                           window.location.href = './home.php';
                    }else{
                      alert(respuesta);  
                    }
                }
            };
            // Enviar los datos del formulario al backend
            xhr.send('usuario=' + encodeURIComponent(UserName) + '&contrasena=' + encodeURIComponent(contrasena));                
            }else{
                alert("Usuario y Contraseña invalidos", "Inicio de sesión"); 
            }            
        });