(function ($) {

    $(document).ready(function () {

        var selector = '.supsystic-social-sharing a.social-sharing-button',
            $buttons = $(selector);

        $buttons.on('click', function (e) {
            var $button = $(this),
                projectId = parseInt($button.data('pid')),
                networkId = parseInt($button.data('nid')),
                postId = parseInt($button.data('post-id')),
                data = {},
                url = $button.data('url');

            if ($button.hasClass('trigger-popup')) {
                return;
            }

            data.action = 'social-sharing-share';
            data.project_id = projectId;
            data.network_id = networkId;
            data.post_id = isNaN(postId) ? null : postId;

            $.post(url, data).done(function () {
                $button.find('.counter').text(function (index, text) {
                    if (isNaN(text)) {
                        return text;
                    }

                    return parseInt(text) + 1;
                });
            });

            e.preventDefault();
        });
    });
}(jQuery));