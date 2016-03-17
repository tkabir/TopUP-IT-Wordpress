(function($) {

    var Controller = function () {
        this.$newsContainer = $('.supsystic-overview-news');
        this.$mailButton = $('#send-mail');
        this.$faqToggles = $('.faq-title');
    };

    Controller.prototype.initScroll = function() {

        this.$newsContainer.slimScroll({
            height: '500px',
            railVisible: true,
            alwaysVisible: true,
            allowPageScroll: true
        });
    };

    Controller.prototype.checkMail = function() {
        var $userMail = $('[name="mail[email]"]'),
            $userText = $('[name="mail[message]"]'),
            $table = $('.contact-form-table');

        this.$mailButton.on('click', function(e) {
                $table.find('input, textarea').each(function() {
                    if(!$(this).val()) {
                        e.preventDefault();

                        $(this).notify('You need to fill this field');
                    }
                });
        });
    };

    Controller.prototype.initFaqToggles = function() {
        var self = this;

        this.$faqToggles.on('click', function() {
            self.$faqToggles.find('div.description').hide();
            $(this).find('div.description').show();
        });
    };

    Controller.prototype.init = function() {
        this.initScroll();
        this.checkMail();
        this.initFaqToggles();
    };

    $(document).ready(function() {
        var controller = new Controller();

        controller.init();
    });
})(jQuery);