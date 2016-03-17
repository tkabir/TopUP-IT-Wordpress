(function ($, app) {

    var config = {

        // Inner selector
        innerSelector: '.scrollable-inner',

        // Selector of the scrollable elements
        blockSelector: '.scroll',

        // Width of the one block.
        blockWidth: 500

    };

    app.ScrollController = (function () {
        function ScrollController(container, parameters) {
            if ($.isPlainObject(parameters)) {
                config = $.extend(config, parameters);
            }

            this.container = $(container);
            this.innerContainer = $(config.innerSelector);
            this.blocks = $(config.blockSelector);
        }

        ScrollController.prototype.scroll = (function scroll(distance) {
            this.container.scrollLeft(parseInt(distance, 10));
        });

        ScrollController.prototype.scrollRight = (function scrollRight(distance) {
            this.scroll(distance);
        });

        ScrollController.prototype.scrollLeft = (function scrollLeft(distance) {
            this.scroll(-distance);
        });

        ScrollController.prototype.applyStyles = (function applyStyles() {
            this.container.css({overflowX: 'scroll'});
            this.blocks.css({
                width: parseInt(config.blockWidth, 10),
                float: 'left'
            });
        });

        ScrollController.prototype.calculateWidth = (function calculateWidth() {
            this.innerContainer.css({width: parseInt(config.blockWidth, 10) * this.blocks.length});
        });

        ScrollController.prototype.onScroll = (function onScroll(event) {
            var offset = this.container.scrollLeft(),
                distance = 0,
                delta = event.deltaFactor || 100;

            event.preventDefault();

            if (event.deltaY < 0) {
                distance = offset + delta;
            } else {
                distance = offset - delta;
            }

            this.scroll(distance);
        });

        ScrollController.prototype.init = (function init() {
            this.applyStyles();
            this.calculateWidth();

            this.container.bind('mousewheel', $.proxy(this.onScroll, this));
        });

        ScrollController.prototype.destroy = (function destroy() {
            this.container.unbind('mousewheel', $.proxy(this.onScroll, this));
        });

        return ScrollController;
    })();

}(window.jQuery, window.supsystic.SocialSharing));