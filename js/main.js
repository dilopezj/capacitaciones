$(document).ready(function () {

    /*  Show/Hidden Submenus */
    $('.nav-btn-submenu').on('click', function (e) {
        e.preventDefault();
        var SubMenu = $(this).next('ul');
        var iconBtn = $(this).children('.fa-chevron-down');
        if (SubMenu.hasClass('show-nav-lateral-submenu')) {
            $(this).removeClass('active');
            iconBtn.removeClass('fa-rotate-180');
            SubMenu.removeClass('show-nav-lateral-submenu');
        } else {
            $(this).addClass('active');
            iconBtn.addClass('fa-rotate-180');
            SubMenu.addClass('show-nav-lateral-submenu');
        }
    });

    /*  Show/Hidden Nav Lateral */
    $('.show-nav-lateral').on('click', function (e) {
        e.preventDefault();
        var NavLateral = $('.nav-lateral');
        var PageConten = $('.page-content');
        if (NavLateral.hasClass('active')) {
            NavLateral.removeClass('active');
            PageConten.removeClass('active');
        } else {
            NavLateral.addClass('active');
            PageConten.addClass('active');
        }
    });

    /* Exit system button */
    $('.btn-exit-system').on('click', function (e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Estás seguro de cerrar la sesión?',
            text: 'Estás a punto de cerrar la sesión y salir del sistema',
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, salir',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.value) {
                // Redirigir al archivo logout.php para cerrar la sesión
                window.location.href = './conexion/logout.php';
            }
        });
    });

});
(function ($) {    
    $(window).on("load", function () {
        $(".nav-lateral-content").mCustomScrollbar({
            theme: "light-thin",
            scrollbarPosition: "inside",
            autoHideScrollbar: true,
            scrollButtons: {enable: true}
        });
        $(".page-content").mCustomScrollbar({
            theme: "dark-thin",
            scrollbarPosition: "inside",
            autoHideScrollbar: true,
            scrollButtons: {enable: true}
        });        
    });
})(jQuery);

