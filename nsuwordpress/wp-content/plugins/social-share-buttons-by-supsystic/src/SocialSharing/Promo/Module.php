<?php

/**
 * Class SocialSharing_Promo_Module
 *
 * Promo module.
 */
class SocialSharing_Promo_Module extends SocialSharing_Core_BaseModule
{
    /**
     * Module initialization.
     */
    public function onInit()
    {
        parent::onInit();
        add_action('admin_enqueue_scripts', array( $this, 'loadTutorial'));
    }

    public function checkToShowTutorial()
    {
        $this->getModelsFactory()->get('promo', 'promo')->checkToShowTutorial($this->getController()->getRequest()->query);
    }

    public function loadTutorial()
    {
        if ( is_admin() && current_user_can('manage_options') ) {
            wp_enqueue_style( 'wp-pointer' );
            wp_enqueue_script( 'jquery-ui' );
            wp_enqueue_script( 'wp-pointer' );
            add_action( 'admin_print_footer_scripts', array( $this, 'checkToShowTutorial' ) );
        }
    }
}