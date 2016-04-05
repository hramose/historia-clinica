$(document).ready(function () {
    $('#side-menu').metisMenu();

    $('.navigation').click(function (e) {
        if ($('#is_mobile').val() == 1) {
            $('nav').toggleClass(function () {
                if ($(this).hasClass('opened')) {
                    $(this).removeClass('opened');
                    return "closed";
                } else {
                    $(this).removeClass('closed');
                    return "opened";
                }
            });
        } else {
            $('nav').toggleClass('closed');
            $('.wrap-content, #footer').toggleClass('compress');
        }
    });
});