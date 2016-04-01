$(document).ready(function () {
    $('#side-menu').metisMenu();

    $('.navigation').click(function (e) {
        $('nav').toggleClass('closed');
        $('.wrap-content, #footer').toggleClass('compress');
    });
});