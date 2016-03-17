(function ($, app) {

    $(document).ready(function () {
        var onCreateButton = (function onCreateButton() {
            var request = app.request({
                module: 'projects',
                action: 'add'
            }, {
                title: $('#projectTitle').val()
            });

            request.done(function (data) {
                $dialog.dialog('close');

                window.location.href = data.redirect_url;
            });
        });

        $('#projectTitle').keyup(function (e) {
            if (e.keyCode === 13) {
                onCreateButton.call();
            }
        });

        var $dialog = $('#addProjectDialog').dialog({
            autoOpen: false,
            modal: true,
            width: 345,
            buttons: {
                Create: onCreateButton,
                Cancel: (function onCancelButton() {
                    $dialog.dialog('close');
                })
            }
        });

        $('#addProject').bind('click', function (e) {
            e.preventDefault();

            $dialog.dialog('open');
        });

        $('#addProject_modal').bind('click', function () {
            if (window.location.href !== this.href) {
                window.location.href = this.href;
            }

            $dialog.dialog('open');
        });

        if (window.location.hash === '#add') {
            $dialog.dialog('open');
        }

        $('.button.delete').bind('click', function (e) {
            e.preventDefault();

            var tableRow = $(this).parents('tr');

            if (confirm('Are you sure want to remove this Project?')) {
                $(this).html($('<i/>', { class: 'fa fa-fw fa-circle-o-notch fa-spin' }));
                $.post(this.href).done(function () {
                    tableRow.remove();
                });
            }
        });
    });

}(window.jQuery, window.supsystic.SocialSharing));