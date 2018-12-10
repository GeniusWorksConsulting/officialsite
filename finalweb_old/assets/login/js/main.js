
(function ($) {
    "use strict";
    /*==================================================================
     [ Show pass ]*/
    var showPass = 0;
    $('.btn-show-pass').on('click', function () {
        if (showPass == 0) {
            $(this).next('input').attr('type', 'text');
            $(this).find('i').removeClass('fa fa-eye');
            $(this).find('i').addClass('fa fa-eye-slash');
            showPass = 1;
        } else {
            $(this).next('input').attr('type', 'password');
            $(this).find('i').removeClass('fa fa-eye-slash');
            $(this).find('i').addClass('fa fa-eye');
            showPass = 0;
        }
    });
})(jQuery);