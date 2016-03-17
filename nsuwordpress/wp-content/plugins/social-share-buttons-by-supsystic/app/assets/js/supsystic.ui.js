/*
 * Main UI file.
 *
 * Here we activate and configure all scripts or
 * jQuery plugins required for UI.
 *
 */
(function ($, window, vendor, undefined) {

    $(document).ready(function () {
        /* Bootstrap Tooltips */

        /*$('input').not('[name="settings[design]"]').iCheck({
            checkboxClass: 'icheckbox_minimal',
            radioClass: 'iradio_minimal',
            increaseArea: '20%'
        });*/

        ppsInitCustomCheckRadio();
        removeDuplicateSubmenu();

        //$('[data-toggle="tooltip"]').tooltip();

        var activeMenuTitle = $($('.supsystic-navigation').find('li.active')[0]).data('menu-item-title');
        $('#toplevel_page_supsystic-social-sharing .wp-submenu li').each(function (index, element){
            var adminSubMenuText = $(element).text();
            if(adminSubMenuText === activeMenuTitle) {
                $(element).addClass('current');
            }
        });
    });

    function ppsInitCustomCheckRadio(selector) {
        if(!selector)
            selector = document;
        jQuery(selector).find('input').iCheck('destroy').iCheck({
            checkboxClass: 'icheckbox_minimal'
            ,	radioClass: 'iradio_minimal'
        }).on('ifChanged', function(e){
            // for checkboxHiddenVal type, see class htmlPps
            //jQuery(this).trigger('change');
            /*if(jQuery(this).attr('name') == 'settings[where_to_show]') {
                console.log(jQuery(this));
                var parentRow = jQuery(this).parents('.jqgrow:first');
                if(parentRow && parentRow.size()) {
                    jQuery(this).parents('td:first').trigger('click');
                } else {
                    var checkId = jQuery(this).attr('id');
                    if(checkId && checkId != '' && strpos(checkId, 'cb_') === 0) {
                        var parentTblId = str_replace(checkId, 'cb_', '');
                        if(parentTblId && parentTblId != '' && jQuery('#'+ parentTblId).size()) {
                            jQuery('#'+ parentTblId).find('input[type=checkbox]').iCheck('update');
                        }
                    }
                }
            }*/
        }).on('ifClicked', function(e){
            jQuery(this).trigger('click');
        });
    }
    function ppsCheckUpdate(checkbox) {
        jQuery(checkbox).iCheck('update');
    }
    window.ppsCheckUpdateArea = function ppsCheckUpdateArea(selector) {
        jQuery(selector).find('input[type=radio]').iCheck('update');
    };

    function removeDuplicateSubmenu() {
        var $menu = $('#toplevel_page_supsystic-social-sharing .wp-submenu');

        $menu.find('.wp-first-item').remove();
    };

}(jQuery, window, 'supsystic'));