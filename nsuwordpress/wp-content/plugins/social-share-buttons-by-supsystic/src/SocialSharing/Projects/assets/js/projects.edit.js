(function ($, app) {

    $(document).ready(function () {
        //After page loaded selecting preview button example by selected design
        function selectExamplePreviewButtonDesign() {
            var $selectedDesignExample = $($($buttonSetsTable.find('.iradio_minimal.checked')).parent().find('a.social-sharing-button')[0]).clone().addClass('animation-preview');
            $selectedDesignExample.find('i.fa').addClass('icon-animation-preview');
            $('#button-preview-example-wrapper').html($selectedDesignExample);
        }

        var scroll,
            $networksList = $('.networks'),
            $networksDialog,
            $networksDialogOnCreateProject,
            $networksDialogTrigger,
            $buttonsDesignDialog,
            $wtsList = $('.where-to-show'),
            $wtsExtras = $('.wts-extra'),
            $showEverywhere = $('input[name="settings[show_at]"][value="everywhere"]'),
            $showOnlyOnHome = $('input[name="settings[show_at]"][value="homepage"]'),
            $hideOnHome = $('input[name="settings[hide_in_home]"]'),
            $hideOnMobile = $('input[name="settings[hide_on_mobile]"]'),
            $showOnlyOnMobile = $('input[name="settings[show_only_on_mobile]"]'),
            $pages = $('.chosen'),
            $displayTotalShares = $('input[name="settings[display_total_shares]"]'),
            $counterStyles = $('select[name="settings[shares_style]"]'),
            $sharesRadios = $('input[name="settings[shares]"]'),
            $shortNumbers = $('input[name="settings[short_numbers]"]'),
            $gradientCheckBox = $('input[name="settings[grad]"]'),
            $overlayShadowCheckBox = $('input[name="settings[overlay_with_shadow]"]'),
            $previewButtons = $('.pricon'),
            $preview = $('.supsystic-social-sharing'),
            $design = $('input[name="settings[design]"]'),
            $animation = $('#ba-button-animation'),
            $iconAnimation = $('#ba-icons-animation'),
            $adminNavButtons = $('.admin-nav-button'),
            buttonWidth = $('.sharer-flat').width(),
            sharePostLinkInList = $('label[for="wts-share-post-link-in-list"]');

        // Rename
        $('h2[contenteditable]').on('keydown', function (e) {
            if ('keyCode' in e && e.keyCode === 13) {
                var $title, text, request;

                $title = $(this);
                text = $title.text();

                $title.removeAttr('contenteditable');
                $title.html($('<i/>', { class: 'fa fa-fw fa-spin fa-circle-o-notch' }));

                request = app.request({ module: 'projects', action: 'rename' }, {
                    title: text,
                    id: app.getParameterByName('id')
                });

                request.done(function (response) {
                    $title.text(text);
                    $title.attr('contenteditable', true);

                    if (!response.success) {
                        $title.text($title.data('original'));
                    }
                });

                e.preventDefault();
            }
        });

        // cb
        var onRemoveNetwork = (function onRemoveNetwork(e) {
            e.preventDefault();

            var element = e.data ? e.data.element : $(e.currentTarget).parents('.network'),
                checkbox = e.data ? e.data.checkbox : $('#' + element.attr('id') + '-checkbox');

            element.remove();
            checkbox.removeAttr('checked');

            app.request({
                module: 'projects',
                action: 'removeNetwork'
            }, {
                network_id: element.find('input[type="hidden"]').val(),
                project_id: app.getParameterByName('id')
            }).done(function () {
                $('body').trigger('networksChanged');
            }).fail(function (reason) {
                alert('Failed to delete selected network: ' + reason);
            });
        });

        // Animation
        $animation.on('change', function () {
            var $preview = $('.animation-preview'),
                current = $preview.attr('data-animation');

            //$preview.removeClass(current);
            $preview.addClass($animation.val());
            $preview.attr('data-animation', $animation.val());
            $preview.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $(this).removeClass($animation.val() + ' animated');
            })
        });

        $iconAnimation.on('change', function () {
            var $preview = $('.icon-animation-preview'),
                current = $preview.attr('data-animation');

            //$preview.removeClass(current + ' animated');
            $preview.addClass($iconAnimation.val() + ' animated');
            $preview.attr('data-animation', $iconAnimation.val());
            $preview.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $(this).removeClass($iconAnimation.val() + ' animated');
            })
        });

        $('body').on('hover', 'a.animation-preview', function() {
            var $preview = $(this),
                current = $animation.val();

            $preview.addClass($animation.val() + ' animated');
            $preview.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $(this).removeClass(current + ' animated');
            })
        });

        $('body').on('hover', 'a.animation-preview', function() {
            var $preview = $('.icon-animation-preview'),
                current = $iconAnimation.val();

            $preview.addClass($iconAnimation.val() + ' animated');
            $preview.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $(this).removeClass(current + ' animated');
            })
        });

        // Design and animation
        $design.on('click', function () {
            var type = $(this).val(),
                $preview = $('#button-preview-example-wrapper'),
                $exampleHtml = $($(this).closest('th').find('a.social-sharing-button')[0]).clone().addClass('animation-preview');

            $exampleHtml.find('i.fa').addClass('icon-animation-preview');
            $preview.html($exampleHtml);
        });

        // Chosen
        $pages.chosen();

        // Allow to save settings for non-HTML5 browsers.
        $('button#save').bind('click', function (e) {
            var oldHtml = $(e.currentTarget).html();
            var btnSave = e.currentTarget;

            $(btnSave).html($('<i/>', { class: 'fa fa-circle-o-notch fa-spin' }));

            var stdstr = 'action=social-sharing&route%5Bmodule%5D=networks&route%5Baction%5D=addToProject&project_id=1';

            if ($('form#networks').serialize() === stdstr) {
                $('#nonetworks').show();
            } else {
                $('#nonetworks').hide();
            }

            var networksPositions = [];

            $networksList.find('.network').each(function(index) {
                networksPositions.push({
                    'network': $(this).find('[name="networks[]"]').val(),
                    'position': index
                });
            });

            $.post(
                $('form#networks').attr('action'),
                $('form#networks').serialize()
            ).done(function () {
                    $.post($('form#networks').attr('action'), {
                        'action': 'social-sharing',
                        'route': {
                            'module': 'networks',
                            'action': 'updateSorting'
                        },
                        'project_id': parseInt($('#networks [name="project_id"]').val()),
                        'positions': networksPositions
                    }).done(function(response) {
                        $.post($('form#settings').attr('action'), $('form#settings').serialize()).done(function (r) {
                            $(btnSave).html(oldHtml);
                            if ('popup_id' in r) $('input[name="settings[popup_id]"]').val(r.popup_id);
                        })
                    });
                });
        });

        // Extra "Where to show" fields
        $wtsList.find('input[type="radio"]').on('click', function () {
            $wtsExtras.hide();

            var hasExtra = $(this).parents('li').has('ul.wts-extra').length;
            var selectFirstItemInExtra = $(this).closest('ul.not-select-first').length;
            if (hasExtra) {
                $(this).parents('li')
                    .find('.wts-extra')
                    .show();

                if (!selectFirstItemInExtra) {
                    $(this).parents('li')
                        .find('.wts-extra:not(.not-select-first)')
                        .find('li:first input')
                        .attr('checked', 'checked');
                }
            }

            $('#wts-shortcode').hide();
            if (this.value == 'code') {
                $('#wts-shortcode').show();
            }
            window.ppsCheckUpdateArea($(this).closest('ul'));
        });

        $wtsExtras.find('input').bind('click', function (e) {
            $(e.currentTarget).attr('checked', 'checked');
            window.ppsCheckUpdateArea($(this).closest('ul'));
        });

        // Initialize horizontal scroll
        //scroll = new app.ScrollController('.scrollable-content', {blockWidth: 380});
        //scroll.init();

        // Networks list
        $networksList.find('.network > .delete').bind('click', onRemoveNetwork);
        $networksList.sortable({
            sort: function (e, ui) {
                ui.item.css({
                    backgroundColor: '#eee'
                });
            },
            update: function (e, ui) {

                if (ui && ui.item) {
                    ui.item.css({
                        backgroundColor: '#fff'
                    });
                }

                $('#savingNetworksSorting').show();

                var positions = [];

                $networksList.find('.network').each(function (i, el) {
                    var id = parseInt(el.id.slice(7));

                    positions.push({
                        network: id,
                        position: i
                    });
                });

                app.request({
                    module: 'networks',
                    action: 'updateSorting'
                }, {
                    project_id: app.getParameterByName('id'),
                    positions: positions
                }).always(function () {
                    $('#savingNetworksSorting').hide();
                }).fail(function (error) {
                    alert('Failed to save sort order: ' + error);
                });
            }
        });

        // Initialize networks dialog
        $networksDialogTrigger = $('#addNetwork');
        $networksDialogTrigger.bind('click', (function networksDialogTriggerClicked() {
            $networksDialog.dialog('open');
        }));

        var Networks = {
            isAdded : function (networkId) {
                var networksExists = $networksList.find('.network');
                var isAdded = false;

                $.each(networksExists, function each(index, network) {
                    var $network = $(network);
                    var id = $network.find('[name="networks[]"]').val();

                    if (id == networkId) {
                        isAdded = true;
                        return false;
                    }
                });

                return isAdded;
            }
        };

        $networksDialog = $('#networks-dialog');
        $networksDialog.dialog({
            autoOpen: false,
            modal: true,
            width: 500,
            appendTo: '#wpwrap',
            open: function() {
                var networksExists = $networksList.find('.network'),
                    $dialog = $(this);

                $.each($dialog.find('input[name="networks"]'), function each(index, network) {
                    var $network = $(network);
                    $network.parent('.icheckbox_minimal')
                            .removeClass('checked');
                    $network.removeAttr( 'checked' );
                });
                
                $.each(networksExists, function each(index, network) {
                    var $network = $(network);
                    var networkId = $network.find('[name="networks[]"]').val();
                    var $checkboxWrapper = $dialog.find('[for="network' + networkId + '-checkbox"]');

                    $checkboxWrapper.find('.icheckbox_minimal')
                                    .addClass('checked');
                    $checkboxWrapper.find('[name="networks"]')
                                    .attr( 'checked', true );
                });
            },
            buttons: {
                Save: (function btnSelect() {
                    var checked = $(this).find(':checked'),
                        form = $('form#settings');

                    if (!checked.length) {
                        return;
                    }

                    $.each(checked, function each(index, checkbox) {
                        var $checkbox = $(checkbox),
                            network = $.parseJSON($checkbox.val()),
                            $networkContainer = $('<div/>', {
                                class: 'network',
                                id: 'network' + network.id
                            });

                        if (Networks.isAdded(network.id)) return;

                        var isTwitter = network.class == 'twitter';

                        $networkContainer.append(
                            $('<a/>', { class: 'delete', href: '#' })
                                .append($('<i/>', { class: 'fa fa-fw fa-times' }))
                                .bind('click', { element: $networkContainer, checkbox: $checkbox }, onRemoveNetwork)
                        ).append(
                            $('<span/>', { class: 'title' })
                                .text(network.name)
                        ).append(
                            $('<input>', { type: 'hidden', name: 'networks[]' })
                                .val(network.id)
                        ).append(
                            $('<nav/>', {class: 'network-navigation'})
                        ).append(
                            $('<div/>', {class: 'information-container'})
                        );

                        $.each(['title', 'name', 'tooltip', 'text_format'], function (index, value) {
                            if (value == 'text_format' && network.url.indexOf('{title}') == -1) return;

                            if (value == 'text_format')
                                $networkContainer.find('nav').append(
                                    '&nbsp; | &nbsp;'
                                );

                            $networkContainer.find('nav').append(
                                $('<span/>', { class: 'network-nav-item admin-nav-text ' + (!index ? 'active' : '') , data: { type: value } })
                                    .text((value == 'text_format' ? 'Default message' : value[0].toUpperCase() + value.slice(1)))
                            );


                            if(value != 'text_format' && index < 2) {
                                $networkContainer.find('nav').append(
                                    '&nbsp; | &nbsp;'
                                );
                            }
                        });

                        if (isTwitter)
                            $networkContainer.find('nav').append(
                                $('<span/>', { class: 'network-nav-item admin-nav-text', data: { type: 'use_short_url' } })
                                    .html('&nbsp; | &nbsp; Short url')
                            );

                        $.each(['title', 'name', 'tooltip', 'text_format'], function (index, value) {
                            if (value === 'text_format' && network.url.indexOf('{title}') == -1) return;
                            var $line = null;
                            $networkContainer.find('div').append(
                                $line = $('<input/>', { class: 'network-' + value , name: (value == 'tooltip' ? 'networkTooltip' : '' ), data: { id: network.id }, hidden: 'hidden', value: value === 'name' ? 'Share' : network.name, type: 'text' })
                                    .text(value[0].toUpperCase() + value.slice(1))
                            );

                            if (value === 'text_format')
                               $line.val('[page_title]');

                            if(value == 'title') {
                                $line.show();
                            }
                        });

                        if (isTwitter)
                            $networkContainer.find('div').append(
                                '<div class="field" data-param="use_short_url">' +
                                    '<label>Use short url</label>' +
                                    '<input type="checkbox" data-id="' + network.id + '" class="network-use_short_url" name="networkShortUrl" hidden/>' +
                                '</div>'
                            );

                        if (!$networksList.has('#network' + network.id).length) {
                            $networksList.append($networkContainer);
                            $networkContainer.find('.information-container input').bind('focusout', function() {

                                switch ($(this).attr('class').split('network-')[1]) {
                                    case 'title' : {
                                        return saveTitle($(this));
                                    } break;
                                    case 'name' : {
                                        return saveName($(this));
                                    } break;
                                    case 'text_format' : {
                                        return saveTextFormat($(this));
                                    } break;
                                    case 'tooltip' : {
                                        return saveTooltip($(this));
                                    } break;
                                }
                            });

                            if (isTwitter)
                                $networkContainer.find('.information-container .network-use_short_url').click(function () {
                                    var $this = $(this)
                                    ,   isChecked = 0;

                                    isChecked = $this.is(':checked') ? 1 : 0;

                                    saveUseShortUrl($this, isChecked);
                                });

                            networkNavigation();
                        }
                    });

                    $networksDialog.dialog('close');
                    $('body').trigger('networksChanged');
                }),
                Close: (function btnClose() {
                    $networksDialog.dialog('close');
                })
            }
        });

        // Initialize choose buttons template dialog
        $buttonsDesignDialog = $('#select-design-dialog');
        $('#choose-buttons-template').on('click', function() {
            $buttonsDesignDialog.dialog('open');
            $buttonsDesignDialog.find('.button').blur();
        });
        $buttonsDesignDialog.dialog({
            position: { my: "top", at: "center", of: window },
            autoOpen: false,
            modal: true,
            width: '80%',
            appendTo: '#wpwrap',
            buttons: {
                Close: (function btnClose() {
                    $buttonsDesignDialog.dialog('close');
                })
            }
        });

        // Select buttons design
        $('#select-design-dialog .button-design-preset').on('click', function () {
            if(!$(this).hasClass('not-pro')) {
                var currentSet = $(this).data('design'),
                    $rows = $buttonSetsTable.find('tr'),
                    $radioButtons = $buttonSetsTable.find('input[name="settings[design]"]'),
                    $radioButtonsWrapper = $buttonSetsTable.find('div.iradio_minimal'),
                    $currentRows;

                $(this).parent().find('.button-select').show();
                $(this).parent().find('.selected-button-design-preset').hide();
                $(this).find('.button-select').hide();
                $(this).find('.selected-button-design-preset').show();

                $rows.hide();
                $currentRows = $rows.filter('[data-builder="' + currentSet + '"]');
                $radioButtonsWrapper.removeClass('checked');
                $radioButtons.removeAttr('checked');

                if (currentSet === 'flat') {
                    $('#overlay-with-shadow-row').show();
                    $('#gradient-mode-row').show();
                } else {
                    $('#overlay-with-shadow-row').hide();
                    $('#gradient-mode-row').hide();
                }

                if ($currentRows.length) {
                    $currentRows.show();
                    $($currentRows[0]).find('.iradio_minimal:first').addClass('checked');
                    var a = $($currentRows[0]).find('input[name="settings[design]"]').attr('checked', 'checked');
                } else {
                    alert('Failed to load selected set');
                }
                $buttonsDesignDialog.dialog('close');

                selectExamplePreviewButtonDesign();
            }
        });


        // Autosave
        $('body').on('networksChanged', function () {
            $('button#save').click();
            $networksList.sortable('option', 'update')(null);
        });

        // Checkboxes
        $pages.change(function () {
            if ($pages.val() !== null) {
                //$showEverywhere.removeAttr('checked');
                $showEverywhere.iCheck('uncheck');
                $showOnlyOnHome.iCheck('uncheck');
            }
        });

        $showEverywhere.bind('click', function () {
            if (this.checked) {
                $pages.find(':selected').removeAttr('selected');
                $pages.trigger('chosen:updated');
                $showOnlyOnHome.iCheck('uncheck');
            } else {
                $showEverywhere.attr('checked', 'checked');
            }
        });

        $showOnlyOnHome.bind('click', function () {
            if (this.checked) {
                $pages.find(':selected').removeAttr('selected');
                $pages.trigger('chosen:updated');
                $showEverywhere.iCheck('uncheck');
                $hideOnHome.iCheck('uncheck');
            }
        });

        $hideOnHome.bind('click', function () {
            if (this.checked) {
                $pages.find(':selected').removeAttr('selected');
                $pages.trigger('chosen:updated');
                $showOnlyOnHome.iCheck('uncheck');
            }
        });

        $displayTotalShares.bind('click', function () {
            if (this.checked) {
                $previewButtons.removeClass('without-counter');
                $shortNumbers.removeAttr('disabled');
                $sharesRadios.removeAttr('disabled');
                $counterStyles.parents('tr').show();
            } else {
                $previewButtons.addClass('without-counter');
                $previewButtons.find('.counter').text('5731');
                $shortNumbers.removeAttr('checked');
                $shortNumbers.attr('disabled', 'disabled');
                $sharesRadios.attr('disabled', 'disabled');
                $counterStyles.parents('tr').hide();
            }
        });

        $gradientCheckBox.bind('click', function () {
            if (this.checked) {
                $overlayShadowCheckBox.removeClass('checked');
                $overlayShadowCheckBox.parent().removeClass('checked');
                $overlayShadowCheckBox.parent().addClass('disabled');
                $overlayShadowCheckBox.attr('disabled', 'disabled');
            } else {
                $overlayShadowCheckBox.removeAttr('disabled');
                $overlayShadowCheckBox.removeAttr('disabled');
                $overlayShadowCheckBox.parent().removeClass('disabled');
            }
        });

        if ($gradientCheckBox.attr('checked')) {
            $overlayShadowCheckBox.parent().removeClass('checked');
            $overlayShadowCheckBox.parent().addClass('disabled');
        }

        $overlayShadowCheckBox.bind('click', function () {
            if (this.checked) {
                $gradientCheckBox.removeClass('checked');
                $gradientCheckBox.parent().removeClass('checked');
                $gradientCheckBox.parent().addClass('disabled');
                $gradientCheckBox.attr('disabled', 'disabled');
            } else {
                $gradientCheckBox.removeAttr('disabled');
                $gradientCheckBox.removeAttr('disabled');
                $gradientCheckBox.parent().removeClass('disabled');
            }
        });

        if ($overlayShadowCheckBox.attr('checked')) {
            $gradientCheckBox.parent().removeClass('checked');
            $gradientCheckBox.parent().addClass('disabled');
        }

        $shortNumbers.bind('click', function () {
            if (this.checked) {
                $previewButtons.find('.counter').text('5.7k');
            } else {
                $previewButtons.find('.counter').text('5731');
            }
        });

        // Delete
        $('.button.delete').bind('click', function (e) {
            var linkToProject = $('.supsystic-navigation [data-menu-item-title="Projects"] a').attr('href');

            e.preventDefault();

            if (confirm('Are you sure want to remove this Project?')) {
                $(this).html($('<i/>', { class: 'fa fa-fw fa-circle-o-notch fa-spin' }));
                $.post(this.href).done(function () {
                    window.location.href = linkToProject;
                });
            }
        });

        $('.select-all').on('click', function() {
            var $icon = $(this).find('i'),
                $networkCheckboxes = $('[name="networks"]');

            if($icon.hasClass('fa-check')) {
                $networkCheckboxes.attr('checked', true)
                    .iCheck('update');
                $icon.removeClass('fa-check').addClass('fa-remove');
            } else {
                $networkCheckboxes.attr('checked', false)
                    .iCheck('update');
                $icon.removeClass('fa-remove').addClass('fa-check');
            }
        });

        $adminNavButtons.on('click', function() {
            var $sections = $('.scroll');

            if(!$(this).hasClass('network-nav-item')) {
                $adminNavButtons.removeClass('active');
                $(this).addClass('active');

                $sections.hide()
                    .filter('[data-navigation="' + $(this).data('block') + '"]').show();
            }
        });

        $('[name="settings[overlay_with_shadow]"]').on('click', function() {
            var $container = $('.supsystic-social-sharing');

            if($(this).is(':checked')) {
                $container.attr('data-overlay', 'on');
            } else {
                $container.attr('data-overlay', '');
            }
        });

        $('div.supsystic-social-sharing .sharer-flat').on('mouseover', function() {
            if($('[name="settings[change_size]"]').is(':checked')) {
                $(this).css('width', buttonWidth - buttonWidth/4);
            }
        }).on('mouseleave', function() {
            if($('[name="settings[change_size]"]').is(':checked')) {
                $(this).css('width', '');
            }
        });

        $('[name="settings[buttons_size]"]').on('change', function() {
            $('.supsystic-social-sharing a').css('font-size', $(this).val() + 'em');
        }).trigger('change');

        var $spacing = $('[name="settings[spacing]"]').on('change', function() {
            if (this.checked) {
                $('.supsystic-social-sharing a').css('margin-left', '20px');
            } else {
                $('.supsystic-social-sharing a').css('margin-left', '0');
            }
        }).trigger('change');

        $spacing.on('ifChecked', function () {
            $('.supsystic-social-sharing a').css('margin-left', '20px');
        });

        $spacing.on('ifUnchecked', function () {
            $('.supsystic-social-sharing a').css('margin-left', '0');
        });

        $('[data-navigation="design"] .sharer-flat').on('click', function() {
            $(this).parent().find('[type="radio"]').attr('checked', true)
                .trigger('click');
            window.ppsCheckUpdateArea($('.supsystic-social-sharing'));
        });

        $('.location-tooltip').tooltipster({
            animation: 'slide',
            position: 'right',
            theme: 'tooltipster-shadow',
            contentAsHTML: true,
            maxWidth: '320',
            interactive: true,
        });

        $('.choose-effect-buttons').on('mouseover', function() {
            $(this).addClass('animated ' + $(this).data('animation'));
        });

        $('.choose-effect-buttons').bind("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd", function() {
            $(this).removeClass('animated ' + $(this).data('animation'));
        });

        $('.choose-effect-icons').on('mouseover', function() {
            $(this).find('i').addClass('animated ' + $(this).data('animation'));
        });

        $('.choose-effect-icons').bind("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd", function() {
            $(this).find('i').removeClass('animated ' + $(this).data('animation'));
        });

        $('[name="settings[where_to_show]"]').on('click', function() {
            if($(this).val() == 'sidebar') {
                $('#wts-sidebar-nav').iCheck('update')
                    .parent().parent().show();
            } else {
                $('#wts-sidebar-nav').iCheck('update')
                    .parent().parent().hide();
            }
            window.ppsCheckUpdateArea($(this).closest('.where-to-show'));
            if ($(this).val() == 'content' || $(this).val() == 'code') {
                sharePostLinkInList.show();
            } else {
                sharePostLinkInList.hide();
            }
        });

        var saveTooltip = function($element) {
            var networkId = $element.data('id'),
                tooltip = $element.val();

            $.post($('form#networks').attr('action'), {
                'action': 'social-sharing',
                'route': {
                    'module': 'networks',
                    'action': 'saveTooltips'
                },
                'project_id': parseInt($('#networks [name="project_id"]').val()),
                'data': { 'id': networkId, 'value': tooltip }
            }).done(function(response) {
                //console.log(response);
            });
        };

        var saveTitle = function($element) {
            var networkId = $element.data('id'),
                title = $element.val();

            $.post($('form#networks').attr('action'), {
                'action': 'social-sharing',
                'route': {
                    'module': 'networks',
                    'action': 'saveTitles'
                },
                'project_id': parseInt($('#networks [name="project_id"]').val()),
                'data': { 'id': networkId, 'value': title }
            }).done(function(response) {
                //console.log(response);
            });
        };

        var saveTextFormat = function($element) {
            var networkId = $element.data('id'),
                format = $element.val();

            $.post($('form#networks').attr('action'), {
                'action': 'social-sharing',
                'route': {
                    'module': 'networks',
                    'action': 'saveTextFormat'
                },
                'project_id': parseInt($('#networks [name="project_id"]').val()),
                'data': { 'id': networkId, 'value': format }
            }).done(function(response) {
                //console.log(response);
            });
        };

        var saveUseShortUrl = function($element, isChecked) {
            var networkId = $element.data('id');

            $.post($('form#networks').attr('action'), {
                'action': 'social-sharing',
                'route': {
                    'module': 'networks',
                    'action': 'saveUseShortUrl'
                },
                'project_id': parseInt($('#networks [name="project_id"]').val()),
                'data': { 'id': networkId, 'value': isChecked }
            });
        };

        var saveName = function($element) {
            var networkId = $element.data('id'),
                name = $element.val();

            $.post($('form#networks').attr('action'), {
                'action': 'social-sharing',
                'route': {
                    'module': 'networks',
                    'action': 'saveNames'
                },
                'project_id': parseInt($('#networks [name="project_id"]').val()),
                'data': { 'id': networkId, 'value': name }
            }).done(function(response) {
                //console.log(response);
            });
        };

        $('[name="networkTooltip"]').on('focusout', function() {
            saveTooltip($(this));
        });

        $('.network-title').on('focusout', function() {
            saveTitle($(this));
        });

        $('.network-text_format').on('focusout', function() {
            saveTextFormat($(this));
        });

        $('.field[data-param="use_short_url"] .iCheck-helper').on('click', function () {
            var $this = $(this)
            ,   $field = $this.parents('.field[data-param="use_short_url"]')
            ,   isChecked = false;

            isChecked = $this.parents('.icheckbox_minimal').hasClass('checked') ? 1 : 0;

            saveUseShortUrl($field.find('[type="checkbox"]'), isChecked);
        });

        $('.network-name').on('focusout', function() {
            saveName($(this));
        });

        $('[name="settings[display_total_shares]"]').on('change', function() {
            if(this.checked) {
                $preview.find('.counter-wrap').show();
            } else {
                $preview.find('.counter-wrap').hide();
            }
        });

        $('.code').on('click focus', function() {
            $(this).select();
        });

        var networkNavigation = function() {
            var $buttons = $('.network-nav-item');

            $buttons.off('click');
            $buttons.parents('.network-navigation')
                    .parent()
                    .find('.information-container input')
                    .each(function () {
                var $this = $(this);

                if ($this.is('[type="checkbox"]') && $this.attr('hidden'))
                    $this.parents('.field').hide();
            });


            $buttons.on('click', function(e) {
                e.preventDefault();

                //$(this).parent().find('a').removeClass('active');
                //$(this).addClass('active');

                //$('.information-container input').hide()
                //    .filter('.network-' + $(this).data('type')).show();


                var
                    // Current button
                    $button = $(this),
                    // Current network nav container
                    $container = $button.parents('.network-navigation'),
                    // Current buttons group
                    $group = $container.find('span'),
                    // Inputs group
                    $inputs = $container.parent().find('.information-container input');

                $group.removeClass('active');
                $button.addClass('active');

                $inputs.hide();
                $inputs.each(function () {
                    var $this = $(this);

                    if ($this.is('[type="checkbox"]'))
                        $this.parents('.field').hide();                    
                });

                $inputs.filter('.network-' + $button.data('type')).show();

                if ($inputs.filter('.network-' + $button.data('type')).is('[type="checkbox"]'))
                    $inputs.filter('.network-' + $button.data('type')).parents('.field').show();
            });
        };

        networkNavigation();


        $('#bd-shares-style').on('change', function() {
            $preview.filter('.supsystic-social-sharing-preview')
                .find('.counter-wrap').removeClass('standard arrowed white-arrowed')
                    .addClass($(this).val());

            // Remove special classes
            $preview.find('.pricon')
                .removeClass('counter-standard counter-arrowed counter-white-arrowed')
                .addClass('counter-' + $(this).val())
        });

        // Select popup on popup radio
        var $popupDialog = $('#selectPopupDialog').dialog({
            width: 400,
            modal: true,
            autoOpen: false,
            buttons: {
                Select: function () {
                    $('#popupId').val($popupDialog.find('select').val());
                    $(this).dialog('close');
                },
                Cancel: function () {
                    $(this).dialog('close');
                }
            }
        });

        $('#wts-popup').on('click', function () {
            $popupDialog.dialog('open');

            return false;
        });

        // Buttons Set select
        var $buttonSetSelect = $('#buttonSet'),
            $buttonSetsTable = $('#buttonSets'),
            $currentStyle = $buttonSetsTable.find(':checked'),
            currentSet;

        if ($currentStyle.length) {
            currentSet = $currentStyle.parents('tr').data('builder');
        } else {
            currentSet = $buttonSetsTable.find('tr').first().data('builder');
        }

        $buttonSetSelect.val(currentSet);
        $buttonSetsTable.find('tr').each(function () {
            var $row = $(this);

            if ($row.data('builder') === currentSet) {
                $row.show();
            }
        });

        // if current design !== flat - hide gradient and overlay options
        if (currentSet === 'flat') {
            $('#overlay-with-shadow-row').show();
            $('#gradient-mode-row').show();
        } else {
            $('#overlay-with-shadow-row').hide();
            $('#gradient-mode-row').hide();
        }

        $buttonSetSelect.on('change', function () {
            var currentSet = $(this).val(),
                $rows = $buttonSetsTable.find('tr'),
                $currentRows;

            $rows.hide();
            $currentRows = $rows.filter('[data-builder="' + currentSet + '"]');

            if ($currentRows.length) {
                $currentRows.show();
            } else {
                alert('Failed to load selected set');
            }
        });

        // Create new project
        $networksDialogOnCreateProject = $('#networks-dialog-on-create-project');
        $networksDialogOnCreateProject.dialog({
            autoOpen: false,
            modal: true,
            width: 500,
            appendTo: '#wpwrap',
            buttons: {
                Create: (function btnSelect() {
                    var title   = $('#projectTitle').val(),
                        design  = $('#buttonDesign').val(),
                        networksId = {};

                    $('#networks-dialog-on-create-project div.checked input[type="checkbox"]').each(function(index, element) {
                        networksId[index] = $(element).val();
                    });

                    var request = app.request({
                        module: 'projects',
                        action: 'add'
                    }, {
                        title: title,
                        design: design,
                        networks: networksId
                    });

                    request.done(function (data) {
                        window.location.href = data.redirect_url;
                    });
                }),
                Close: (function btnClose() {
                    $networksDialogOnCreateProject.dialog('close');
                })
            }
        });
        $('#createNewSocialButtonProject').on('click', function() {
            $('#projectNameEmpty').hide();
            $('#projectStyleEmpty').hide();
            var title   = $('#projectTitle').val(),
                design  = $('#buttonDesign').val();

            if(title == false) {
                $('#projectNameEmpty').show();
                return;
            }

            if(design == false) {
                $('#projectStyleEmpty').show();
                return;
            }

            $networksDialogOnCreateProject.dialog('open');
        });

        //Select button design on project creating
        $('.button-design-preset').on('click', function() {
            if(!$(this).hasClass('not-pro')) {
                $(this).parent().find('.button-design-preset').removeClass('active');
                $(this).addClass('active');
                var buttonDesign = $(this).data('design');
                $('#buttonDesign').val(buttonDesign);
            }
        });

        //Dependence for checkboxes 'Hide on mobile devices' && 'Show Only on Mobile Devices'
        $hideOnMobile.bind('click', function () {
            if (this.checked) {
                $showOnlyOnMobile.removeClass('checked');
                $showOnlyOnMobile.parent().removeClass('checked');
                $showOnlyOnMobile.parent().addClass('disabled');
                $showOnlyOnMobile.attr('disabled', 'disabled');
            } else {
                $showOnlyOnMobile.removeAttr('disabled');
                $showOnlyOnMobile.removeAttr('disabled');
                $showOnlyOnMobile.parent().removeClass('disabled');
            }
        });

        if ($hideOnMobile.attr('checked')) {
            $showOnlyOnMobile.parent().removeClass('checked');
            $showOnlyOnMobile.parent().addClass('disabled');
        }

        $showOnlyOnMobile.bind('click', function () {
            if (this.checked) {
                $hideOnMobile.removeClass('checked');
                $hideOnMobile.parent().removeClass('checked');
                $hideOnMobile.parent().addClass('disabled');
                $hideOnMobile.attr('disabled', 'disabled');
            } else {
                $hideOnMobile.removeAttr('disabled');
                $hideOnMobile.removeAttr('disabled');
                $hideOnMobile.parent().removeClass('disabled');
            }
        });

        if ($showOnlyOnMobile.attr('checked')) {
            $hideOnMobile.parent().removeClass('checked');
            $hideOnMobile.parent().addClass('disabled');
        }

        // Select design preset on page(create new project) open
        $('.button-design-preview-wrapper.create-project-page').find('.button-design-preset').first().addClass('active');

        // Select design preset on page(progect settings, tab 'Design') open in modal window
        $buttonsDesignDialog.find('div[data-design="'+currentSet+'"] .button-select').hide();
        $buttonsDesignDialog.find('div[data-design="'+currentSet+'"] .selected-button-design-preset').show();
        $buttonsDesignDialog.find('div[data-design="'+currentSet+'"]').addClass('active');

        selectExamplePreviewButtonDesign();

        //link for plugin in sale site
        $('.button-design-preset.not-pro').on('click', function() {
            var selectedProDesign = $(this).data('design');
            var url = '//supsystic.com/plugins/social-share-plugin/?utm_source=plugin&utm_medium=' + selectedProDesign + '&utm_campaign=socialbuttons';
            window.location.href = url;
        });
    });

}(window.jQuery, window.supsystic.SocialSharing));
