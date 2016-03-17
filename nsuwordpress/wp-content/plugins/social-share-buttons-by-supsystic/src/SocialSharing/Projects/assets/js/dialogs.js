(function ($, app) {

    var currentEffectClass = 'current-effect';

    var Controller = function() {
        this.$buttonsDialog = $('.buttons-adnimation-dialog');
        this.$iconsDialog = $('.icons-adnimation-dialog');
        this.$lockDialog = $('#content-lock-dialog-promo');
        this.$promoDialog = $('#pro-ad');
    };

    Controller.prototype.initButtonsDialog = function() {
        var $button = $('#buttons-animation'),
            $effects = $('.choose-effect-buttons'),
            self = this;

        this.$buttonsDialog.dialog({
            autoOpen: false,
            modal:    true,
            width:    600,
            appendTo: '#wpwrap',
            open: function () {
                var animation = $('[name="settings[buttons_animation]"]').val(),
                    $effect = $effects.filter('[data-animation=' + animation + ']');

                if ($effect.length) {
                    $effects.removeClass(currentEffectClass);
                    $effect.addClass(currentEffectClass);
                }
            },
            buttons : {
                Close : function() {
                    self.$buttonsDialog.dialog('close');
                }
            }
        });

        $button.on('click', function(e) {
            e.preventDefault();

            self.$buttonsDialog.dialog('open');
        });

        $effects.on('click', function() {
            $('[name="settings[buttons_animation]"]').val($(this).data('animation'));
            self.$buttonsDialog.dialog('close');
        });
    };

    Controller.prototype.initIconsDialog = function() {
        var $button = $('#icons-animation'),
            $effects = $('.choose-effect-icons'),
            self = this;

        this.$iconsDialog.dialog({
            autoOpen: false,
            modal:    true,
            width:    600,
            appendTo: '#wpwrap',
            open: function () {
                var animation = $('[name="settings[icons_animation]"]').val(),
                    $effect = $effects.filter('[data-animation=' + animation + ']');

                if ($effect.length) {
                    $effects.removeClass(currentEffectClass);
                    $effect.addClass(currentEffectClass);
                }
            },
            buttons : {
                Close : function() {
                    self.$iconsDialog.dialog('close');
                }
            }
        });

        $button.on('click', function(e) {
            e.preventDefault();

            self.$iconsDialog.dialog('open');
        });

        $effects.on('click', function() {
            $('[name="settings[icons_animation]"]').val($(this).data('animation'));
            self.$iconsDialog.dialog('close');
        });
    };

    Controller.prototype.initLockDialog = function() {
        var $button = $('#lock-dialog-promo'),
            self = this;

        this.$lockDialog.dialog({
            autoOpen: false,
            modal:    true,
            width:    600,
            appendTo: '#wpwrap',
            buttons : {
                Close : function() {
                    self.$lockDialog.dialog('close');
                },
            }
        });

        $button.on('click', function(e) {
            e.preventDefault();

            self.$lockDialog.dialog('open');
        });
    };

    Controller.prototype.initPromoDialog = function() {
        var $button = $('.disabled-pro'),
            self = this;

        this.$promoDialog.dialog({
            autoOpen: false,
            modal:    true,
            width:    600,
            appendTo: '#wpwrap',
            buttons : {
                Close : function() {
                    self.$promoDialog.dialog('close');
                },
            }
        });

        $button.on('click', function(e) {

            self.$promoDialog.dialog('open');
        });

        this.$promoDialog.on('dialogclose', function() {

            $button.attr('checked', false).iCheck('update');
        });
    };

    Controller.prototype.init = function() {
        this.initButtonsDialog();
        this.initIconsDialog();
        this.initLockDialog();
        this.initPromoDialog();
    };

    $(document).ready(function() {
        var controller = new Controller();

        controller.init();
    });

}(window.jQuery, window.supsystic.SocialSharing));