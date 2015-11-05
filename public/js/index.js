$(document).ready(function () {
    $('#side-menu').metisMenu();

    $('.navigation').click(function(e) {
        $('nav').toggleClass('expanded');
        $('#main-page, #footer').toggleClass('compress');
    });
});