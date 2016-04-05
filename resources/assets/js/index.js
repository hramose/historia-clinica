$(document).ready(function () {
    $('#side-menu').metisMenu();

    $('.navigation').click(function (e) {
        if ($('#is_mobile').val() == 1) {
            $('nav').toggleClass('opened');
        } else {
            $('nav').toggleClass('closed');
            $('.wrap-content, #footer').toggleClass('compress');
        }
    });

    /**** Para añadir un indicador si existe una table que tiene el ancho más grande la pantalla ****/
    if ($('#is_mobile') && $('table').length) {
        $('table').each(function(index, element){
            var t = $(element);
            if ($(window).width() < t.find('thead').width()) {
                t.after('<p>Fes lliscar el dit sobre la taula per veure més dades</p>');
            }
        })

    }
});