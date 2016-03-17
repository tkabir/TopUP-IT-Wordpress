<?php


class SocialSharing_Ui_Module extends SocialSharing_Core_BaseModule
{
    /**
     * @var SocialSharing_Ui_Asset[]
     */
    private $assets;

    public function onInit()
    {
        parent::onInit();

        $uiStyles = new SocialSharing_Ui_Style();
        $uiStyles->setHandle('supsystic-social-sharing-ui-styles')
            ->setHookName('admin_enqueue_scripts')
            ->setLocalSource('css/supsystic-ui.css');

        $uiScripts = new SocialSharing_Ui_Script();
        $uiScripts->setHandle('supsystic-social-sharing-ui-scripts')
            ->setHookName('admin_enqueue_scripts')
            ->setLocalSource('js/supsystic.ui.js');

        $bootstrap = new SocialSharing_Ui_Script();
        $bootstrap->setHandle('supsystic-social-sharing-bootstrap')
            ->setLocalSource('js/libraries/bootstrap/bootstrap.min.js')
            ->setHookName('admin_enqueue_scripts');

        $this->addAsset($uiStyles);
        $this->addAsset($uiScripts);
        $this->addAsset($bootstrap);

        $this->addAsset(
            $this->create('script', 'sss-chosen')
                ->setHookName('admin_enqueue_scripts')
                ->setLocalSource('js/plugins/chosen.jquery.min.js')
                ->setVersion('1.4.2')
        );

        $this->addAsset(
            $this->create('script', 'sss-icheck')
                ->setHookName('admin_enqueue_scripts')
                ->setLocalSource('js/plugins/icheck.min.js')
        );

        $this->addAsset(
            $this->create('style', 'sss-icheck')
                ->setHookName('admin_enqueue_scripts')
                ->setLocalSource('css/libraries/minimal/minimal.css')
        );

        $this->addAsset(
            $this->create('style', 'sss-admin')
                ->setHookName('admin_enqueue_scripts')
                ->setLocalSource('css/admin.css')
        );

        $this->getEnvironment()
            ->getDispatcher()
            ->on('after_modules_loaded', array($this, 'registerAssets'));
    }

    /**
     * Loads all assets.
     */
    public function registerAssets()
    {
        $environment = $this->getEnvironment();
        $config = $environment->getConfig();
        $prefix = $config->get('hooks_prefix');

        foreach ($this->assets as $asset) {
            if ('admin_enqueue_scripts' !== $asset->getHookName()) {
                $asset->setHookName($prefix . 'before_html_build');
                $asset->register();
            } elseif ($environment->isPluginPage() && 'sss-admin' !== $asset->getHandle()) {
                $asset->register();
            }

            if('sss-admin' === $asset->getHandle()) {
                $asset->register();
            }
        }
    }

    /**
     * Adds asset
     * @param SocialSharing_Ui_AssetInterface $asset
     */
    public function addAsset(SocialSharing_Ui_AssetInterface $asset)
    {
        $this->assets[] = $asset;
    }

    /**
     * Returns Assets.
     * @return SocialSharing_Ui_AssetInterface[]
     */
    public function getAssets()
    {
        return $this->assets;
    }

    /**
     * Sets Assets.
     * @param SocialSharing_Ui_Asset[] $assets
     */
    public function setAssets(array $assets)
    {
        $this->assets = $assets;
    }

    /**
     * Creates new asset with specific handle and return it.
     * @param string $type Asset type
     * @param string|null $handle Asset handle
     * @return SocialSharing_Ui_Script|SocialSharing_Ui_Style
     */
    public static function create($type, $handle = null)
    {
        $asset = null;

        switch (strtolower($type)) {
            case 'javascript':
            case 'script':
            case 'js':
                $asset = new SocialSharing_Ui_Script();
                break;
            case 'stylesheet':
            case 'style':
            case 'css':
                $asset = new SocialSharing_Ui_Style();
                break;

            default:
                throw new InvalidArgumentException(
                    sprintf('Invalid asset type: "%s".', $type)
                );
        }

        $asset->setHandle($handle);

        return $asset;
    }
}