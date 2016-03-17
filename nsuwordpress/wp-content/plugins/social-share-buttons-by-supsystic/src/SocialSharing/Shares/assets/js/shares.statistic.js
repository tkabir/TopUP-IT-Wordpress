(function ($, app) {

    var getTotalSharesByDays = function (days) {
        return app.request({
            module: 'shares',
            action: 'getTotalSharesByDays'
        }, {
            project_id: app.getParameterByName('id'),
            days: parseInt(days)
        });
    };

    var totalShares = function () {
        var canvas = $('#totalStatistic'),
            ctx = canvas.length ? canvas.get(0).getContext('2d') : null,
            data = [];

        app.request({
            module: 'shares',
            action: 'getTotalShares'
        }, {project_id: app.getParameterByName('id')}).done(function (response) {
            if (!response.stats.length) {
                canvas.after('Not enough data.');
                canvas.remove();
            }

            $.each(response.stats, function (index, network) {
                data.push({
                    value: network.shares,
                    color: network.color,
                    label: network.name
                });
            });

            return new Chart(ctx).Doughnut(data);
        }).fail(function (error) {
            canvas.after('Failed to retrieve information: ' + error);
            canvas.remove();
        });

        return new Chart(ctx).Doughnut(data);
    };

    var totalViews = function () {
        var canvas = $('#totalViews'),
            ctx = canvas.length ? canvas.get(0).getContext('2d') : null;

        app.request({
            module: 'shares',
            action: 'getTotalViews'
        }, {project_id: app.getParameterByName('id')}).done(function (response) {
            if (!response.stats) {
                canvas.after('Not enough data.');
                canvas.remove();
            }

            var data = {
                labels: ['project ' + app.getParameterByName('id')],
                datasets: [{
                    label: 'project ' + app.getParameterByName('id'),
                    data: [response.stats]
                }]
            };

            return new Chart(ctx).Bar(data);

        }).fail(function (error) {
            canvas.after('Failed to retrieve information: ' + error);
            canvas.remove();
        });

        //return new Chart(ctx).Bar(data);
    };

    var last30 = function () {
        var canvas = $('#last30Statistic'),
            ctx = canvas.get(0).getContext('2d'),
            data = {
                labels: [],
                datasets: []
            };

        data.datasets.push({
            label: "Last 30 days",
            fillColor: "rgba(220,220,220,0.2)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: []
        });

        getTotalSharesByDays(30).done(function (response) {
            if (!response.stats.length) {
                canvas.after('Not enough data.');
                canvas.remove();
            }

            $.each(response.stats, function (index, period) {
                data.labels.push(period.date);
                data.datasets[0].data.push(period.shares);
            });

            return new Chart(ctx).Line(data);
        }).fail(function (error) {
            canvas.after('Failed to retrieve information: ' + error);
            canvas.remove();
        });
    };

    var popular5Pages = function () {
        var table = $('#popularPages'),
            request = app.request({module:'shares',action:'getPopularPagesByDays'}, {
                project_id: app.getParameterByName('id'),
                days: 30
            });

        request.done(function (response) {

            if (!response.stats.length) {
                table.find('tbody').append(
                    $('<tr/>').append(
                        $('<td/>', {colspan:4}).text('Not enough data to determine popular pages')
                    )
                );
            }

            $.each(response.stats, function (index, data) {
                var row = $('<tr/>');

                if (data.post === null) {
                    row.append($('<td/>').text('-'));
                    row.append($('<td/>').html(
                        $('<a/>', { href: window.location.origin, target: '_blank' }).text('Index page')
                    ));
                    row.append($('<td/>').text('-'));
                } else {
                    row.append($('<td/>').text(data.post_id));
                    row.append($('<td/>').html(
                        $('<a/>', { href: data.post.guid, target: '_blank' }).text(data.post.post_title)
                    ));
                    row.append($('<td/>').text(data.post.post_type));
                }

                row.append($('<td/>').text(data.shares));
                table.find('tbody').append(row);
            });
        }).fail(function (error) {

        });
    };

    var popular5PagesViews = function () {
        var table = $('#popularPagesViews'),
            request = app.request({module:'shares',action:'getPopularPagesByDaysViews'}, {
                project_id: app.getParameterByName('id'),
                days: 30
            });

        request.done(function (response) {

            if (!response.stats.length) {
                table.find('tbody').append(
                    $('<tr/>').append(
                        $('<td/>', {colspan:4}).text('Not enough data to determine popular pages')
                    )
                );
            }

            $.each(response.stats, function (index, data) {
                var row = $('<tr/>');

                if (data.post === null) {
                    row.append($('<td/>').text('-'));
                    row.append($('<td/>').html(
                        $('<a/>', { href: window.location.origin, target: '_blank' }).text('Index page')
                    ));
                    row.append($('<td/>').text('-'));
                } else {
                    row.append($('<td/>').text(data.post_id));
                    row.append($('<td/>').html(
                        $('<a/>', { href: data.post.guid, target: '_blank' }).text(data.post.post_title)
                    ));
                    row.append($('<td/>').text(data.post.post_type));
                }

                row.append($('<td/>').text(data.views));
                table.find('tbody').append(row);
            });
        }).fail(function (error) {

        });
    };

    var ssbInitNoticeDialog = function() {
        $('#reviewNotice').dialog({
            modal:    true,
            width:    600,
            autoOpen: true
        });
    };

    var ssbShowReviewNotice = function() {

        $.post(window.ajaxurl,
            {
                action: 'social-sharing',
                route: {
                    module: 'shares',
                    action: 'checkReviewNotice'
                }
            })
            .success(function (response) {

                if(response.show) {
                    ssbInitNoticeDialog();

                    $('#reviewNotice [data-statistic-code]').on('click', function() {
                        var code = $(this).data('statistic-code');

                        $.post(window.ajaxurl,
                            {
                                buttonCode: code,
                                action: 'social-sharing',
                                route: {
                                    module: 'shares',
                                    action: 'checkNoticeButton'
                                }
                            })
                            .success(function(response) {

                                $('#reviewNotice').dialog('close');
                            });
                    });
                }
            });
    };

    $(document).ready(function () {

        $('[data-block="statistic"]').on('click', function() {

            if(!$(this).data('shown')) {
                totalShares();
                last30();
                popular5Pages();

                totalViews();
                popular5PagesViews();
            }

            $(this).attr('data-shown', true);
        });

        ssbShowReviewNotice();
    });

}(jQuery, window.supsystic.SocialSharing));