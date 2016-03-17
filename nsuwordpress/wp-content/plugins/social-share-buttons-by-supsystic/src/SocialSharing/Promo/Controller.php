<?php

/**
 * Class SocialSharing_Promo_Controller
 *
 * Projects controller.
 */
class SocialSharing_Promo_Controller extends SocialSharing_Core_BaseController
{
    public function indexAction(Rsc_Http_Request $request)
    {
        $this->getModelsFactory()->get('promo', 'promo')->welcomePageSaveInfo();

        return $this->response(
            '@promo/promo.twig',
            array(
                'plugin_name' => $this->getEnvironment()->getConfig()->get('plugin_title_name'),
                'plugin_version' => $this->getEnvironment()->getConfig()->get('plugin_version'),
                'start_url' => '?page=supsystic-social-sharing&module=projects&action=add'
            )
        );
    }
}