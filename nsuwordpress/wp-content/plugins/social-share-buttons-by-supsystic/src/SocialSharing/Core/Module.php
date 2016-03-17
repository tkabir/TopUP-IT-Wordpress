<?php

/**
 * Class SocialSharing_Core_Module
 */
class SocialSharing_Core_Module extends SocialSharing_Core_BaseModule
{
    /**
     * {@inheritdoc}
     */
    public function onInit()
    {
        parent::onInit();

        $dispatcher = $this->getEnvironment()->getDispatcher();
        $dispatcher->on('after_ui_loaded', array($this, 'loadScripts'), -5);
        $dispatcher->on('after_modules_loaded', array($this, 'disablePromo'), -5);

        // Improve admin_notices action
        add_action('admin_notices', array($this, 'doPluginNotices'));

        $this->registerTwigFunctions();
    }

    /**
     * Loads plugin core js.
     * @param SocialSharing_Ui_Module $ui
     */
    public function loadScripts(SocialSharing_Ui_Module $ui)
    {
        $core = new SocialSharing_Ui_Script();
        $core->setHandle('social-sharing-core-js')
            ->setModuleSource($this, 'js/core.js')
            ->setHookName('admin_enqueue_scripts');

        $ui->addAsset($core);
    }

    /**
     * Disable build-in promo module.
     */
    public function disablePromo()
    {
        $pluginName = $this->getEnvironment()->getPluginName();
        update_option($pluginName.'_promo_shown', 1);
    }

    public function doPluginNotices()
    {
        if (!$this->getEnvironment()->isPluginPage()) {
            return;
        }

        $dispatcher = $this->getEnvironment()->getDispatcher();
        $dispatcher->dispatch('notices');
    }

    public function getPluginDirectoryUrl($path)
    {
        return plugin_dir_url($this->getEnvironment()->getPluginPath() . '/index.php') . '/' . $path;
    }

    private function registerTwigFunctions()
    {
        $twig = $this->getEnvironment()->getTwig();

        $twig->addFunction(
            new Twig_SimpleFunction(
                'plugin_directory_url',
                array($this, 'getPluginDirectoryUrl')
            )
        );
    }
}