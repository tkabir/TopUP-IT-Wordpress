(function ($, app) {

    app.NetworksController = (function () {
        function NetworksController(selector) {
            this.container = $(selector);
        }

        NetworksController.query = (function query() {
            return app.request({module: 'networks', action: 'all'});
        });

        return NetworksController;
    })();

}(window.jQuery, window.supsystic.SocialSharing));