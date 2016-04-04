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
});