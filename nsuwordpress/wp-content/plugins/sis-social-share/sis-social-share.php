<?php
/*
Plugin Name: 	Social Share Buttons
Plugin URI: 	http://wordpress.org/plugins/sis-social-share/
Description: 	Add various social networking share buttons to your website, including Facebook, Twitter, digg, delicious, StumbleUpon, Pinterest and Linkedin.
Version: 		1.1.0
Author: 		Sayful Islam, Sayful IT
Author URI: 	http://sayfulit.com
Text Domain: 	socialsharebuttons
Domain Path: 	/languages/
License: 		GPLv2 or later
*/

if ( !class_exists('SIS_Social_Share')):

class SIS_Social_Share {

	protected static $instance = null;

	public function __construct(){
		add_action( 'plugins_loaded', array( $this, 'load_textdomain') );
		add_action( 'admin_init', array( $this, 'settings_init') );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts') );
		add_action( 'admin_menu', array( $this, 'settings_page') );
		add_action( 'wp_head', array( $this, 'custom_style') );
		add_action( 'wp_footer', array( $this, 'custom_script') );
		
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
	}

	public static function get_instance(){
		if (null == self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function action_links( $links ) {
		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=sis-social-share' ) . '">' . __( 'Settings', 'dialogcf' ) . '</a>'
		);

		return array_merge( $plugin_links, $links );
	}

	public static function get_options(){
		$options_array = array(
	      	'show_button' 	=> 'left', 
	        'button_top' 	=> '100px', 
	        'googleplus' 	=> '',
	        'facebook' 		=> '',
	        'twitter' 		=> '',
	        'digg' 			=> '',
	        'delicious' 	=> '',
	        'stumbleupon' 	=> '',
	        'linkedin' 		=> '',
	        'pinterest' 	=> ''
	    );
		$options = wp_parse_args(get_option( 'sis_social_share_settings' ), $options_array);
	   	return $options;
	}

	public function load_textdomain() {
	  load_plugin_textdomain( 'socialsharebuttons', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	public function settings_init(){
	    register_setting( 'sis_social_share_settings', 'sis_social_share_settings' );
	}

	public function frontend_scripts() {
		wp_enqueue_script('sis_share_main_script',plugins_url( '/jquery.sharrre.js' , __FILE__ ),array( 'jquery' ));
	}

	public function settings_page() {
		add_menu_page( __( 'Social Share Settings', 'socialsharebuttons' ), __( 'Social Share', 'socialsharebuttons' ), 'manage_options', 'sis-social-share', array( $this, 'settings_page_callback') );
	}

	public function settings_page_callback() {

		?>
		<div class="wrap">
		    <h2><?php _e('Social Share Settings', 'socialsharebuttons' ) ?></h2>

			<form method="post" action="options.php">

				<?php settings_fields( 'sis_social_share_settings' ); ?>
				<?php $options = self::get_options(); ?>

				<table class="form-table">
					<!-- Option 1: Check Social Share -->
					<tr valign="top">
						<th scope="row">
							<label><?php _e( 'Check Social Share you want to show', 'socialsharebuttons' ); ?></label>
						</th>
						<td>
							<input id="sis_social_share_settings[googleplus]" name="sis_social_share_settings[googleplus]" type="checkbox" value="true" <?php checked( 'true', $options['googleplus'] ); ?> /><?php _e('Google Plus', 'socialsharebuttons'); ?><br>

							<input id="sis_social_share_settings[facebook]" name="sis_social_share_settings[facebook]" type="checkbox" value="true" <?php checked( 'true', $options['facebook'] ); ?> /><?php _e('Facebook', 'socialsharebuttons'); ?><br>

							<input id="sis_social_share_settings[twitter]" name="sis_social_share_settings[twitter]" type="checkbox" value="true" <?php checked( 'true', $options['twitter'] ); ?> /><?php _e('Twitter', 'socialsharebuttons'); ?><br>

							<input id="sis_social_share_settings[digg]" name="sis_social_share_settings[digg]" type="checkbox" value="true" <?php checked( 'true', $options['digg'] ); ?> /><?php _e('Digg', 'socialsharebuttons'); ?><br>

							<input id="sis_social_share_settings[delicious]" name="sis_social_share_settings[delicious]" type="checkbox" value="true" <?php checked( 'true', $options['delicious'] ); ?> /><?php _e('Delicious', 'socialsharebuttons'); ?><br>

							<input id="sis_social_share_settings[stumbleupon]" name="sis_social_share_settings[stumbleupon]" type="checkbox" value="true" <?php checked( 'true', $options['stumbleupon'] ); ?> /><?php _e('Stumbleupon', 'socialsharebuttons'); ?><br>

							<input id="sis_social_share_settings[linkedin]" name="sis_social_share_settings[linkedin]" type="checkbox" value="true" <?php checked( 'true', $options['linkedin'] ); ?> /><?php _e('Linkedin', 'socialsharebuttons'); ?><br>

							<input id="sis_social_share_settings[pinterest]" name="sis_social_share_settings[pinterest]" type="checkbox" value="true" <?php checked( 'true', $options['pinterest'] ); ?> /><?php _e('Pinterest', 'socialsharebuttons'); ?><br>
							
							<p class="description"><?php _e( 'Check Social Share you want to show', 'socialsharebuttons' ); ?></p>
						</td>
					</tr>
					<!-- Option 2: Check Social Share Position -->
					<tr valign="top">
						<th scope="row">
							<label for="sis_social_share_settings[show_button]"><?php _e( 'Show Social Share', 'socialsharebuttons' ); ?></label>
						</th>
						<td>
							<input type="radio" name="sis_social_share_settings[show_button]" value="left" <?php checked( $options['show_button'], 'left' ); ?> />Left<br />

							<input type="radio" name="sis_social_share_settings[show_button]" value="right" <?php checked( $options['show_button'], 'right' ); ?> />Right<br />

							<p class="description"><?php _e( 'Choose where you want to show buttons left or right side of your site.', 'socialsharebuttons' ); ?></p>
						</td>
					</tr>
					<!-- Option 3: Social Share Position from top-->
					<tr valign="top">
	                    <th scope="row">
	                        <label for="sis_social_share_settings[button_top]"><?php _e('Buttons position from top', 'socialsharebuttons') ?></label>
	                    </th>
	                    <td>
	                        <input type="text" placeholder="100px" name="sis_social_share_settings[button_top]" value="<?php esc_attr_e($options['button_top']); ?>" class="">

	                        <p class="description"><?php _e('Write button position from top in pixels. Example: 100px', 'socialsharebuttons') ?></p>
	                    </td>
	                </tr>
				</table>
				<p class="submit"><input type="submit" value="<?php _e('Save Changes', 'socialsharebuttons') ?>" class="button button-primary" id="submit" name="submit"></p>
			</form>

		</div><!-- END wrap -->

	<?php
	}

	public function custom_style(){
		$options = self::get_options();
		?><style>
			#mysocilasharing{
				overflow: hidden;
				display: block;
				position: fixed;
				<?php echo $options['show_button']; ?>: 10px;
				top: <?php echo $options['button_top']; ?>;
				z-index: 9999;
			}
			.button {
			  display: block;
			  margin: 5px 0;
			  padding: 5px 10px;
			  background: none;
			}
			.button.pinterest {
			  	margin-top: 30px;
			}
		</style><?php
	}

	public function custom_script(){
		?>
			<?php $options = self::get_options(); ?>
			<div id="mysocilasharing"><div id="shareme" data-url="<?php bloginfo('url'); ?>" data-text="<?php bloginfo('name'); ?>"></div></div>
			<script type="text/javascript">
		  		jQuery(document).ready(function(){
					jQuery('#shareme').sharrre({
						share: {
							googlePlus: <?php echo ($options['googleplus'] != true) ? 'false' : 'true' ?>,
							facebook: <?php echo ($options['facebook'] != true) ? 'false' : 'true' ?>,
							twitter: <?php echo ($options['twitter'] != true) ? 'false' : 'true' ?>,
							digg: <?php echo ($options['digg'] != true) ? 'false' : 'true' ?>,
							delicious: <?php echo ($options['delicious'] != true) ? 'false' : 'true' ?>,
							stumbleupon: <?php echo ($options['stumbleupon'] != true) ? 'false' : 'true' ?>,
							linkedin: <?php echo ($options['linkedin'] != true) ? 'false' : 'true' ?>,
							pinterest: <?php echo ($options['pinterest'] != true) ? 'false' : 'true' ?>,
						},
						buttons: {
							googlePlus: {size: 'tall', annotation:'bubble'},
							facebook: {layout: 'box_count'},
							twitter: {count: 'vertical'},
							digg: {type: 'DiggMedium'},
							delicious: {size: 'tall'},
							stumbleupon: {layout: '5'},
							linkedin: {counter: 'top'},
							pinterest: {layout: 'vertical'}
						},
						enableHover: false,
						enableCounter: false,
						enableTracking: true
					});
		  		});
			</script>
		<?php
	}
}
add_action('plugins_loaded', array('SIS_Social_Share','get_instance'));
endif;

function sis_social_share_activation_redirect( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        exit( wp_redirect( admin_url( 'admin.php?page=sis-social-share' ) ) );
    }
}
add_action( 'activated_plugin', 'sis_social_share_activation_redirect' );