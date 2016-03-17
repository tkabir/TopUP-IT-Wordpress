<?php
/*
  Plugin Name: Google Map
  Plugin URI: http://www.srmilon.com
  Description: The plugin will help to embed google map in post and pages also in sidebar.
  Author: SRMILON
  Author URI: http://www.srmilon.com
  Version: 1.2.5
 */

if (!defined('ABSPATH')) {
    exit("Your don't have required permission.");
}

if (!class_exists('gmap_main')):

    class gmap_main {

        private $plugin_name = 'Google Map';

        /**
         * constructor function
         */
        function __construct() {
            add_action('wp_enqueue_scripts', array($this, 'gmap_enqueue_scripts'));
            add_action('admin_menu', array($this, 'gmap_create_menu'));
            add_action('admin_init', array($this, 'gmap_register_fields'));
        }

        /**
         * To enqueue scripts for front-end
         */
        public function gmap_enqueue_scripts() {
            //including map library            
            wp_enqueue_script('gmap_api', 'http://maps.googleapis.com/maps/api/js', array('jquery'));
        }

        /**
         * To create menu in admin panel
         */
        public function gmap_create_menu() {

            //create new top-level menu
            add_menu_page($this->plugin_name.' Settings', $this->plugin_name, 'administrator', 'gmap-settings', array($this, 'gmap_settings_page'), 'dashicons-admin-generic');

            // add submenu under Google Map
            add_submenu_page('gmap-settings', $this->plugin_name . ' Help', $this->plugin_name . ' Help', 'administrator', 'gmap-help', array($this, 'gmap_help_page'));
        }

        public function gmap_register_fields() {
            //register fields
            register_setting('gmap_settings_group', 'gmap_title');
            register_setting('gmap_settings_group', 'gmap_lat');
            register_setting('gmap_settings_group', 'gmap_long');
            register_setting('gmap_settings_group', 'gmap_width');
            register_setting('gmap_settings_group', 'gmap_height');
            register_setting('gmap_settings_group', 'gmap_zoom');
            register_setting('gmap_settings_group', 'gmap_type');
        }

        public function gmap_settings_page() {
            ?>
            <div class="wrap">
                <?php
                if (isset($_GET['settings-updated'])) {
                    echo '<div class="updated bellow-h2 notice notice-success is-dismissible"><p>Global Settings updated.</p></div>';
                }
                ?>
                <h2><?php echo $this->plugin_name; ?> Global Settings</h2>

                <form method="post" action="options.php">
                    <?php settings_fields('gmap_settings_group'); ?>
                    <?php do_settings_sections('gmap_settings_group'); ?>
                    <p>You may update the global settings for <?php echo $this->plugin_name; ?> plugin</p>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">Map Title</th>
                            <td><input type="text" name="gmap_title" class="regular-text" value="<?php echo esc_attr(get_option('gmap_title')); ?>" /></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Latitude</th>
                            <td><input type="text" name="gmap_lat" value="<?php echo esc_attr(get_option('gmap_lat')); ?>" /></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Longitude</th>
                            <td><input type="text" name="gmap_long" value="<?php echo esc_attr(get_option('gmap_long')); ?>" /></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Width</th>
                            <td><input type="text" name="gmap_width" value="<?php echo esc_attr(get_option('gmap_width')); ?>" /> %</td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Height</th>
                            <td><input type="text" name="gmap_height" value="<?php echo esc_attr(get_option('gmap_height')); ?>" /> px</td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Zoom</th>
                            <td><input type="text" name="gmap_zoom" value="<?php echo esc_attr(get_option('gmap_zoom')); ?>" /></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Map Type</th>
                            <td>
                                <select  name="gmap_type">
                                    <option <?php selected(esc_attr(get_option('gmap_type')), 'ROADMAP'); ?>>ROADMAP</option>
                                    <option <?php selected(esc_attr(get_option('gmap_type')), 'SATELLITE'); ?>>SATELLITE</option>
                                    <option <?php selected(esc_attr(get_option('gmap_type')), 'HYBRID'); ?>>HYBRID</option>
                                    <option <?php selected(esc_attr(get_option('gmap_type')), 'TERRAIN'); ?>>TERRAIN</option>
                                </select>
                            </td>
                        </tr>
                    </table>

                    <?php submit_button(); ?>

                </form>
            </div>
            <?php
        }

        /**
         * Google Map Help manual page
         */
        public function gmap_help_page() {
            ?>
            <h1><?php echo $this->plugin_name; ?> help manual</h1>
            <br/>
            <h2><b>Shortcode</b></h2>
            <h4>How to add <?php echo $this->plugin_name; ?> in post or single pages?</h4>

            1. Click on <b>Add Google Map</b>.<br/>
            <img src="<?php echo plugins_url('screenshot-5.png', __FILE__); ?>" style="border:2px white solid;padding:5px;box-shadow: 0px 0px 5px #D0D0D0;margin: 10px 0;"/><br/>
            2. Enter your map properties, then click on <b>insert into post or press <b>enter key</b></b>.<br/>
            <img src="<?php echo plugins_url('screenshot-6.png', __FILE__); ?>" style="border:2px white solid;padding:5px;box-shadow: 0px 0px 5px #D0D0D0;margin: 10px 0;"/>

            <br/>
            <br/>
            <b>You can also enter the following code manually in editor:</b>
            <br/>
            <br/>
            <code>
                [gmap title="Our Location" show_heading="1" latitude="23.2012841" longitude="90.01247147" width="80%" height="450px" map_type="ROADMAP"
            </code>
            <br/>

            <br/>
            <b>Supported Map Types are:</b> &nbsp; <code>ROADMAP, SATELLITE, HYBRID, TERRAIN</code>

            <br/>
            <br/>
            <br/>
            <h2><b>Widget</b></h2>

            <h4>How to add <?php echo $this->plugin_name; ?> widget?</h4>
            Go to <b>Appearance</b> &NestedGreaterGreater; <b>Widgets</b> .   
            Then search <?php echo $this->plugin_name; ?> and add it in any sidebar or footer.
            <br/><br/>Enter your map options, follow the screenshot
            <br/><br/>
            <b>For Example:</b>
            <br/>
            <br/>

            <img src="<?php echo plugins_url('screenshot-4.png', __FILE__); ?>"/>
            <br/>
            <br/>
            Then click on save and then map will appear in your website.

            <br/>
            To set Global Settings just click on <b><?php echo $this->plugin_name; ?></b> in the left navigation bar.
            <?php
        }

    }

    endif;

new gmap_main();
// including requird files
require_once plugin_dir_path(__FILE__) . '/includes/widget.php';
require_once plugin_dir_path(__FILE__) . '/includes/shortcodes.php';
require_once plugin_dir_path(__FILE__) . '/includes/editor_shortcode_button.php';
?>
