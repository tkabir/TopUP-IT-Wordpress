(function ($) {

    $(document).ready(function () {
        var $button   = $('button.social-sharing-navigation-toggle'),
            className = 'toggled';

        $button.on('click', function (e) {
            var $target = $(e.currentTarget),
                $icon = $target.find('i'),
                $container = $target.parents('.supsystic-social-sharing'),
                properties = {show: {}, hide: {}},
                time = 200;

            properties.show[$target.data('pointer')] = -500;
            properties.show.opacity = 0;

            properties.hide[$target.data('pointer')] = 0;
            properties.hide.opacity = 1;

            if (!$target.hasClass(className)) {
                $target.addClass(className);

                $container.find('a').animate(properties.show, time, function () {
                    $icon.removeClass($target.data('replace'));
                    $icon.addClass($target.data('replace-with'));
                });
            } else {
                $target.removeClass(className);

                $container.find('a').animate(properties.hide, time, function () {
                    $icon.removeClass($target.data('replace-with'));
                    $icon.addClass($target.data('replace'));
                });
            }
        });
    });

}(window.jQuery));