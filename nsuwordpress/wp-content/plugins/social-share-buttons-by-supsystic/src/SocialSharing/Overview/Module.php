<?php


class SocialSharing_Overview_Module extends Rsc_Mvc_Module
{
    /**
     * {@inheritdoc}
     */
    public function onInit()
    {
        $environment = $this->getEnvironment();
        $config = $environment->getConfig();

        $this->registerMenu();

        // Client ID
        $config->add('post_id', 637);
        $config->add('post_url', 'http://supsystic.com/news/main.html');
        $config->add('mail', 'support@supsystic.team.zendesk.com');

        $prefix = $config->get('hooks_prefix');

        add_action($prefix . 'after_ui_loaded', array(
            $this, 'loadAssets'
        ));
    }

    /**
     * Loads the assets required by the module
     */
    public function loadAssets(SocialSharing_Ui_Module $ui)
    {
        $ui->addAsset(
            $ui->create('script', 'ss-notify-js')
                ->setModuleSource($this, 'js/notify.js')
                ->setHookName('admin_enqueue_scripts')
        );

        $ui->addAsset(
            $ui->create('script', 'ss-overview-js')
                ->setModuleSource($this, 'js/overview-settings.js')
                ->setHookName('admin_enqueue_scripts')
        );

        $ui->addAsset(
            $ui->create('script', 'ss-slimscroll-js')
                ->setModuleSource($this, 'js/jquery.slimscroll.min.js')
                ->setHookName('admin_enqueue_scripts')
        );

        $ui->addAsset(
            $ui->create('style', 'ss-overview-css')
                ->setModuleSource($this, 'css/overview-styles.css')
                ->setHookName('admin_enqueue_scripts')
        );
    }

    public function registerMenu()
    {
        $lang = $this->getEnvironment()->getLang();
        $menu = $this->getEnvironment()->getMenu();
        $submenu = $menu->createSubmenuItem();

        $submenu->setCapability('manage_options')
            ->setMenuSlug('supsystic-social-sharing&module=overview')
            ->setMenuTitle($lang->translate('Overview'))
            ->setPageTitle($lang->translate('Overview'))
            ->setModuleName('overview');

        $menu->addSubmenuItem('ovewrview', $submenu)
            ->register();
    }
} 