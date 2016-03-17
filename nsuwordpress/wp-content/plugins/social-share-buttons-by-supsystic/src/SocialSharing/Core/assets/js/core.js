(function (vendor, $, window) {

    var appName = 'SocialSharing';

    if (!(appName in vendor)) {
        vendor[appName] = {};

        vendor[appName].getAppName = (function getAppName() {
            return appName;
        });

        vendor[appName].request = (function request(route, data) {
            if (!$.isPlainObject(route) || !('module' in route) || !('action' in route)) {
                throw new Error('Request route is not specified.');
            }

            if (!$.isPlainObject(data)) {
                data = {};
            }

            if ('action' in data) {
                throw new Error('Reserved field "action" used.');
            }

            data.action = 'social-sharing';

            var request = $.post(window.ajaxurl, $.extend({}, { route: route }, data)),
                deferred = $.Deferred();

            request.done(function (response, textStatus, jqXHR) {
                if (typeof response.success !== 'undefined' && response.success) {
                    deferred.resolve(response, textStatus, jqXHR);
                } else {
                    var message = 'There are errors during the request.';

                    if (typeof response.message !== 'undefined') {
                        message = response.message;
                    }

                    deferred.reject(message, textStatus, jqXHR);
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                deferred.reject(errorThrown, textStatus, jqXHR);
            });

            return deferred.promise();
        });

        vendor[appName].getParameterByName = (function getParameterByName(name) {
            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");

            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);

            return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
        });
    }

}(window.supsystic = window.supsystic || {}, window.jQuery, window));