<?php


class SocialSharing_Projects_Model_Tooltips extends SocialSharing_Core_BaseModel
{
    /**
     * Returns tooltips list.
     * @return array
     */
    public function getTooltips()
    {
        $e = $this->environment;

        return array_map(
            array($this, 'prepareTooltips'),
            array(
                'sidebar-tooltip'   => $e->translate('Adds buttons to the selected side of the page.'),
                'content-tooltip'   => $e->translate('Buttons will appear at the post or page content.'),
                'popup-tooltip'     => sprintf($e->translate('You need to install %s to use this feature.'), '<a href="http://supsystic.com/plugins/popup-plugin/" target="_blank">Popup by Supsystic</a>'),
                'widget-tooltip'    => $e->translate('Creates a widget of the current project at Appearance > Widgets and allows you to use project at theme\'s widgets areas.'),
                'shortcode-tooltip' => $e->translate('Allows you to insert project shortcode and show buttons where you want.'),
                'spacing-tooltip'   => sprintf($e->translate('Adds space between the buttons %s'), '<img src="{url}/tooltips-images/distance-between-buttons.jpg"/>'),
                'buttons-size'      => sprintf($e->translate('Choose the size for social buttons. This option allows you to accent the attention for as much as you want %s'), '<img src="{url}/tooltips-images/buttons-size.jpg" />'),
                'display-counters'  => sprintf($e->translate('Displays counters of social shares on buttons %s'), '<img src="{url}/tooltips-images/display-counters.jpg" />'),
                'counter-style'     => sprintf($e->translate('Displays counters styles of social shares on buttons %s'), '<img src="{url}/tooltips-images/wJOddaskBI.jpg" />'),
                'use-short-numbers' => sprintf($e->translate('Rounds up big numbers of counters and displays the short numbers. Available only with enabled \"Display counters\" option %s'), '<img src="{url}/tooltips-images/display-short-numbers.jpg" />'),
                'grad-mode'         => sprintf($e->translate('Gradient mode creates smooth transitions from the one color to another %s'), '<a href="http://supsystic.com/plugins/social-share-plugin/" target="_blank"><img src="{url}/tooltips-images/gradient-mode.jpg" /></a>'),
                'overlay-shadow'    => sprintf($e->translate('Adds overlay with shadow for buttons %s'), '<img src="{url}/tooltips-images/Uc7r303.gif" />'),
                'change-size'       => sprintf($e->translate('Change size for buttons %s'), '<img src="{url}/tooltips-images/wJOdkBI.gif" />'),
                'nav-button'        => $e->translate('Show/hide navigation button for sidebar mode. Allows user to show or hide share buttons'),
                'all-button'        => $e->translate('Allows user to display all networks in popup'),
                'content-lock'      => $e->translate('Allows you to lock content elements by class name before user shares this page with any network'),
                'show-on-pages'     => $e->translate('Choose pages you want to show popup. You can choose "All Pages" or specify posts, pages or URLs'),
                'mobile-devices'    => $e->translate('Choose this option if you don\'t want to display icons on mobile devices'),
                'show-only-on-mobile'=> $e->translate('Choose this option if you want to display icons only on mobile devices'),
                'show-everywhere'   => $e->translate('Show on all pages'),
                'page-load'         => $e->translate('Show Social Buttons when page loads'),
                'user-click'        => $e->translate('Show Social Buttons when user clicks on page'),
                'share-post-link'   => $e->translate('Share post link even if post is in the list'),
            )
        );
    }

    /**
     * Applies the callbacks to the every tooltip.
     *
     * @param string $text Tooltip text
     *
     * @return string
     */
    public function prepareTooltips($text)
    {
        return $this->replaceUrl(
            $this->addParagraph(
                $text
            )
        );
    }

    /**
     * Wraps every tooltip with paragraph.
     *
     * @param string $text Tooltip text
     *
     * @return string
     */
    public function addParagraph($text)
    {
        return '<p>'.$text.'</p>';
    }

    /**
     * Replaces the {url} placeholder with the real url.
     *
     * @param string $text Tooltip text
     *
     * @return string
     */
    public function replaceUrl($text)
    {
        $path = dirname(dirname(dirname(dirname(__FILE__))));
        $url = plugin_dir_url($path) . 'app/assets/img';
        
        return str_replace('{url}', $url, $text);
    }
}