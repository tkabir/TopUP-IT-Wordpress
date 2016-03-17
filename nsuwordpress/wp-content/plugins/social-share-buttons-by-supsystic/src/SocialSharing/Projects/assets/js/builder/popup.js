(function ($) {

    $(document).ready(function () {
        var $popup        = $('.supsystic-social-sharing-popup'),
            $overlay      = $('<div/>', {class: 'supsystic-social-sharing-popup-overlay'}),
            $container    = $popup.parents('.supsystic-social-sharing'),
            $button       = $popup.parents('.supsystic-social-sharing').find('a.trigger-popup');

        $('body').append($overlay);

        // Open popup
        $button.on('click', function (e) {
            e.preventDefault();
            $overlay.slideDown(function () {
                $popup.css({
                    marginLeft: -$popup.width() / 2,
                    marginTop:  -$popup.height() / 2
                });
                $popup.fadeIn();
            });
        });

        // Listen for clicks to close the popup.
        $({})
            .add($popup)
            .add($popup.find('a'))
            .add($overlay)
            .on('click', function () {
                $popup.fadeOut(function () {
                    $overlay.slideUp();
                    $container.find('a').show();
                });
            });
    });

}(window.jQuery));