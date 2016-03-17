<?php

/**
 * Class SocialSharing_Promo_Model_Promo
 */
class SocialSharing_Promo_Model_Promo extends SocialSharing_Core_BaseModel
{
    private $_apiUrl = '';
    private $_pluginSlug = '';
    private $_buttonArray;

    private function _getApiUrl()
    {
        if (empty($this->_apiUrl)) {
            $this->_initApiUrl();
        }
        return $this->_apiUrl;
    }

    public function welcomePageSaveInfo($d = array())
    {
        $reqUrl = $this->_getApiUrl(). '?mod=options&action=saveWelcomePageInquirer&pl=rcs';
        $d['where_find_us'] = (int) 5;	// Hardcode for now

        $res = wp_remote_post($reqUrl, array(
            'body' => array(
                'site_url' => get_bloginfo('wpurl'),
                'site_name' => get_bloginfo('name'),
                'where_find_us' => $d['where_find_us'],
                'plugin_code' => 'ssb',
            )
        ));

        $this->dontShowWelcomePage();

        return true;
    }

    protected function  dontShowWelcomePage()
    {
        update_option($this->environment->getConfig()->get('plugin_name') . '_welcome_page_was_showed', 1);
    }

    protected function _initApiUrl()
    {
        $this->_apiUrl = implode('', array('','ht','t','p:','/','/5','4','.6','8','.1','9','1.','2','17','/',''));
    }

    public function checkToShowTutorial(Rsc_Http_Parameters $query)
    {
        $this->_pluginSlug = $this->environment->getConfig()->get('plugin_slug');
        $page   = $query->get('page');
        $module = $query->get('module');
        $action = $query->get('action');
        $disableTutorial = $query->get($this->_pluginSlug . '-ignore-tour');
        if($disableTutorial !== null)
            update_user_meta(get_current_user_id(), $this->_pluginSlug . '-ignore-tour', $disableTutorial);
        $showTour = ((int) get_user_meta( get_current_user_id(), $this->_pluginSlug . '-ignore-tour', true)) ? false : true;

        if($showTour) {
            switch (true) {
                case ($page == $this->_pluginSlug && $module == 'projects' && $action == 'add'):
                    $this->createProjectDesignTour();
                    break;
                case ($page == $this->_pluginSlug && $module == 'projects' && $action == 'view'):
                    $this->projectMainSettingTour();
                    break;
                default:
                    $this->startTutorialTourPointer();
            }
        }
    }

    public  function startTutorialTourPointer()
    {
        $selector = 'li.toplevel_page_supsystic-social-sharing';
        $content  = '<h3>' . $this->environment->translate('Congratulations!') . '</h3>'
            .'<p>'
                . $this->environment->translate('You’ve just installed Social Share Buttons by Supsystic! View a quick introduction of this plugin’s core functionality.<br/><br/>
                                                Start with the: <a href="?page=supsystic-social-sharing&module=projects&action=add">Add new Project</a><br/>')
                . '<br />'
                . $this->environment->translate('Each project is the way to set social button in some place. For example you can create one project with buttons in the website content and the second project with the buttons in the sidebar.')
            .'</p>';
        $opt_arr  = array(
            'content'  => $content,
            'position' => array( 'edge' => 'bottom', 'align' => 'center' ),
        );

        $this->_buttonArray['startNextStep']['text']     = $this->environment->translate('Start tour');
        $this->_buttonArray['startNextStep']['function'] = sprintf( 'document.location="%s";', admin_url( 'admin.php?page=supsystic-social-sharing&module=projects&action=add' ) );

        $this->printScripts( $selector, $opt_arr );
    }

    public  function createProjectDesignTour()
    {
        $selector = '#anchor-for-tutorial';
        $content  = '<h3>' . $this->environment->translate('Choose button design') . '</h3>'
            .'<p>'
                . $this->environment->translate('Choose one of the button design and create the project. You can change design later and buttons settings on the next step.')
            .'</p>';
        $opt_arr  = array(
            'content'  => $content,
            'position' => array( 'edge' => 'left', 'align' => 'center' ),
        );

        $this->createProjectNetworksTour();
        $this->_buttonArray['startNextStep']['text']     = $this->environment->translate('Next step');
        $this->_buttonArray['startNextStep']['function'] = '
            var projectName = $("#projectTitle").val();
            if(!projectName)
                $("#projectTitle").val("New Project");
            $("' . $selector . '").pointer(social_share_buttons_pointer_options).pointer("close");
            $("#createNewSocialButtonProject").trigger("click");
            
            //select network for example
            $("#networks-dialog-on-create-project div:nth-child(2) .icheckbox_minimal").click();
            $("button.select-all").pointer(social_share_buttons_pointer_options1).pointer("open");

            //button next step for tutorial window of select project networks
            $("#wp-pointer-0 .wp-pointer-buttons").append("<a id=\"pointer-ternary\" class=\"button-primary\">' . $this->_buttonArray['startNextStep']['text'] . '</a>");
            $("body").on("click", "#pointer-ternary", function() {
                $("button span.ui-button-text").trigger("click"); // creating project
                $("button.select-all").pointer(social_share_buttons_pointer_options).pointer("close");
            });
        ';

        $this->printScripts( $selector, $opt_arr );
    }

    public function createProjectNetworksTour()
    {
        $selector = 'button.select-all';
        $content  = '<h3>' . $this->environment->translate('Choose social networks') . '</h3>'
            .'<p>'
                . $this->environment->translate('Choose several social networks for the project. You can add new social networks or remove selected on the next step.')
            .'</p>';
        $opt_arr  = array(
            'content'  => $content,
            'position' => array( 'edge' => 'top', 'align' => 'center' ),
            'optionId' => '1'
        );

        $this->_buttonArray['startNextStep']['text']     = $this->environment->translate('Next step');
        $this->_buttonArray['startNextStep']['function'] = null;

        $this->printScripts( $selector, $opt_arr, 'close');
    }

    public  function projectMainSettingTour()
    {
        $selector = '#project-setting-main';
        $content  = '<h3>' . $this->environment->translate('Main settings') . '</h3>'
            .'<p>'
                . $this->environment->translate('In the main settings:')
            .'</p>'
                . '<ul class="unordered-list-in-tutorial-tour">'
                    . '<li>'
                        . $this->environment->translate('Add or remove social networks.')
                    . '</li>'
                    . '<li>'
                        . $this->environment->translate('Set social network placement. For each project you can setup one placement to show. If you want to show social buttons in the different places, you can create several project for each place. For example, you can create one project for the <a href="//supsystic.com/plugins/social-share-plugin/" target="_blank">Left Sidebar</a> placement, second project for the <a href="//supsystic.com/plugins/social-share-plugin/" target="_blank">Content</a> placement and third for the <a href="//supsystic.com/plugins/social-share-plugin/" target="_blank">Widget</a> placement')
                    . '</li>'
                    . '<li>'
                        . $this->environment->translate('Set showing parameters. On which pages and URLs you would like to show buttons, when you would like to show them.')
                    . '</li>'
                . '</ul>';
        $opt_arr  = array(
            'content'  => $content,
            'position' => array( 'edge' => 'top', 'align' => 'left' ),
        );
        $nextStepTitle = $this->environment->translate('Design');
        $nextStepText = $this->environment->translate('Choose buttons design and set buttons parameters. Like counters, spacing, size and more.');
        $this->_buttonArray['startNextStep']['text']     = $this->environment->translate('Next Step');
        $this->_buttonArray['startNextStep']['function'] = '
            $("' . $selector . '").pointer(social_share_buttons_pointer_options).pointer("close");
            $("#createNewSocialButtonProject").trigger("click");
            $(".admin-nav-button").removeClass("active");
            $(".overtable-buttons .admin-nav-button:nth-child(2)").addClass("active");
            $(".scroll").hide().filter("[data-navigation=\"design\"]").show();
            $("button#project-setting-design").pointer(social_share_buttons_pointer_options).pointer("open");

            $("#wp-pointer-1 h3").text("' . $nextStepTitle . '");
            $("#wp-pointer-1 ul").remove();
            $("#wp-pointer-1 p").text("' . $nextStepText . '");
        ';
        $this->printScripts( $selector, $opt_arr );
    }

    public function printScripts( $selector, $options, $windowVisibility = 'open')
    {
        $_buttonArray_defaults = array(
            'startNextStep' => array(
                'text'     => false,
                'function' => '',
            )
        );

        $optId = 'social_share_buttons_pointer_options' . (isset($options['optionId']) ? $options['optionId'] : '');

        $this->_buttonArray = wp_parse_args( $this->_buttonArray, $_buttonArray_defaults );
        ?>
        <script type="text/javascript">
            //<![CDATA[
            (function ($) {
                    // Don't show the tour on screens with an effective width smaller than 1024px or an effective height smaller than 768px.
                    if (jQuery(window).width() < 1024 || jQuery(window).availWidth < 1024) {
                        return;
                    }

                    window.<?php echo $optId; ?> = <?php echo $this->jsonEncode( $options ); ?>;
                    var setup;
                    <?php echo $optId; ?> = $.extend(<?php echo $optId; ?>, {
                        buttons: function (event, t) {
                    console.log(<?php echo $optId; ?>);
                            var button = jQuery('<a href="<?php echo $this->getIgnoreUrl(); ?>" id="pointer-close" style="margin:0 5px;" class="button-secondary">' + '<?php echo $this->environment->translate('Close') ?>' + '</a>');
                            button.bind('click.pointer', function () {
                                t.element.pointer('close');
                            });
                            return button;
                        },
                        close: function () {
                        }
                    });

                    setup = function () {
                        $('<?php echo $selector; ?>').pointer(<?php echo $optId; ?>).pointer("<?php echo $windowVisibility?>");
                        var lastOpenedPointer = jQuery( '.wp-pointer').slice( -1 );
                        <?php
                        $this->startNextStepButton();
                        ?>
                    };

                    if (<?php echo $optId; ?>.position && <?php echo $optId; ?>.position.defer_loading)
                        $(window).bind('load.wp-pointers', setup);
                    else
                        $(document).ready(setup);
            })(jQuery);
            //]]>
        </script>
    <?php
    }

    private function getIgnoreUrl()
    {
        $arr_params = array(
            $this->_pluginSlug . '-ignore-tour'  => '1',
            'nonce'                              => wp_create_nonce( $this->_pluginSlug . '-ignore-tutorial' ),
        );

        return esc_url( add_query_arg( $arr_params ) );
    }

    private function startNextStepButton()
    {
        if ( $this->_buttonArray['startNextStep']['text'] ) { ?>
            lastOpenedPointer.find( '#pointer-close' ).after('<a id="pointer-primary" class="button-primary">' +
                '<?php echo $this->_buttonArray['startNextStep']['text']; ?>' + '</a>');
            lastOpenedPointer.find('#pointer-primary').click(function () {
            <?php echo $this->_buttonArray['startNextStep']['function']; ?>
            });
        <?php
        }
    }

    public function jsonEncode( array $array_to_encode, $options = 0, $depth = 512 )
    {
        if ( function_exists( 'wp_json_encode' ) ) {
            return wp_json_encode( $array_to_encode, $options, $depth );
        }

        // @codingStandardsIgnoreStart
        return json_encode( $array_to_encode );
        // @codingStandardsIgnoreEnd
    }
}