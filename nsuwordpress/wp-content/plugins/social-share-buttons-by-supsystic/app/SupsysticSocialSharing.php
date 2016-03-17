<?php

/**
 * Class SupsysticSocialSharing
 */
class SupsysticSocialSharing
{
    private $environment;

    public function __construct()
    {
        if (!class_exists('Rsc_Autoloader', false)) {
            require dirname(dirname(__FILE__)) . '/vendor/Rsc/Autoloader.php';
            Rsc_Autoloader::register();
        }

        $pluginPath = dirname(dirname(__FILE__));
        $pluginName = 'sss';
        $pluginTitleName = 'Social Share by Supsystic';
        $pluginSlug = 'supsystic-social-sharing';
        $environment = new Rsc_Environment($pluginName, '1.6.1', $pluginPath);

        /* Configure */
        $environment->configure(
            array(
                'optimizations'    => 1,
                'environment'      => $this->getPluginEnvironment(),
                'default_module'   => 'projects',
                'lang_domain'      => 'social_sharing',
                'lang_path'        => plugin_basename(
                        dirname(__FILE__)
                    ) . '/langs',
                'plugin_title_name' => $pluginTitleName,
                'plugin_slug' => $pluginSlug,
                'plugin_prefix'    => 'SocialSharing',
                'plugin_source'    => $pluginPath . '/src',
                'plugin_menu'      => array(
                    'page_title' => __(
                        $pluginTitleName,
                        $pluginSlug
                    ),
                    'menu_title' => __(
                        $pluginTitleName,
                        $pluginSlug
                    ),
                    'capability' => 'manage_options',
                    'menu_slug'  => $pluginSlug,
                    'icon_url'   => 'dashicons-share',
                    'position'   => '101.8',
                ),
                'shortcode_name'   => 'grid-gallery',
                'db_prefix'        => 'supsystic_ss_',
                'hooks_prefix'     => 'supsystic_ss_',
                'ajax_url'         => admin_url('admin-ajax.php'),
                'admin_url'        => admin_url(),
                'uploads_rw'       => true,
                'jpeg_quality'     => 95,
                'plugin_db_update' => true,
                'revision'         => 288,
                'welcome_page_was_showed' => get_option($pluginName . '_welcome_page_was_showed'),
                'promo_controller' => 'SocialSharing_Promo_Controller'
            )
        );

        $this->environment = $environment;
    }

	public function getEnvironment() {
		return $this->environment;
	}
	
    public function run()
    {
        /*if (isset($_GET['sharing_install_db'])) {
            $this->createSchema();
        }

        if (isset($_GET['sharing_reinstall_db'])) {
            $this->dropSchema();
            $this->createSchema();
        }*/

        $this->environment->run();
    }

    public function activate($bootstrap)
    {
        register_activation_hook($bootstrap, array($this, 'createSchema'));

        if(get_option($this->environment->getPluginName().'_updated') < 289) {    
            register_activation_hook($bootstrap, array($this, 'updateDb'));
            update_option($this->environment->getPluginName().'_updated', $this->environment->getConfig()->get('revision'));
        }
    }

    public function updateDb() {
        global $wpdb;

        $schema = dirname(__FILE__) . '/configs/dbupdate.sql';
        $networks = dirname(__FILE__) . '/configs/update_networks.sql';
        $prefix = $wpdb->prefix . $this->environment
                ->getConfig()
                ->get('db_prefix');
        if (!function_exists('dbDelta')) {
            require_once(ABSPATH.'wp-admin/includes/upgrade.php');
        }

        $checkTable = 'SHOW TABLES LIKE "'. $prefix .'views";';

        if(!$wpdb->get_results($checkTable)) {
            $sql = str_replace('%prefix%', $prefix, file_get_contents($schema));

            dbDelta('SET FOREIGN_KEY_CHECKS=0');
            dbDelta($sql);
            dbDelta('SET FOREIGN_KEY_CHECKS=1');
        }

        $sql = str_replace('%prefix%', $prefix, file_get_contents($networks));

        dbDelta('SET FOREIGN_KEY_CHECKS=0');
        dbDelta($sql);
        dbDelta('SET FOREIGN_KEY_CHECKS=1');
    }

    public function createSchema()
    {
        global $wpdb;

        if (is_file($schema = dirname(__FILE__) . '/configs/dbschema.sql')) {
            $prefix = $wpdb->prefix . $this->environment
                    ->getConfig()
                    ->get('db_prefix');

            $sql = str_replace('%prefix%', $prefix, file_get_contents($schema));

            if (!function_exists('dbDelta')) {
                require_once(ABSPATH.'wp-admin/includes/upgrade.php');
            }

            $queryList = explode(';', $sql);

            $wpdb->show_errors = false;

            foreach ($queryList as $query)
                $wpdb->query($query);

            $wpdb->show_errors = true;

            update_option($this->environment->getPluginName().'_installed', 1);
        }
    }

    public function dropSchema()
    {
        global $wpdb;

        $prefix = $wpdb->prefix . $this->environment
                ->getConfig()
                ->get('db_prefix');

        $tables = $wpdb->get_results('SHOW TABLES LIKE \''.$prefix.'%\'', ARRAY_N);

        if (count($tables) < 1) {
            return;
        }

        $wpdb->query('SET FOREIGN_KEY_CHECKS=0');
        foreach ($tables as $inded => $table) {
            $wpdb->query('DROP TABLE IF EXISTS '.array_pop($table).' CASCADE;');
        }

        $wpdb->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function deactivate($bootstrap)
    {
//        register_deactivation_hook($bootstrap, array($this, 'dropSchema'));
    }

    protected function getPluginEnvironment()
    {
        $environment = Rsc_Environment::ENV_PRODUCTION;

        if ((defined('WP_DEBUG') && WP_DEBUG) || (defined(
                    'SUPSYSTIC_SS_DEBUG'
                ) && SUPSYSTIC_SS_DEBUG)
        ) {
            $environment = Rsc_Environment::ENV_DEVELOPMENT;
        }

        if ($_SERVER['SERVER_NAME'] === 'localhost' && $_SERVER['SERVER_PORT'] === '8001') {
            $environment = Rsc_Environment::ENV_DEVELOPMENT;
        }

        return $environment;
    }
}
