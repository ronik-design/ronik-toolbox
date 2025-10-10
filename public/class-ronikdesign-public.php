<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.ronikdesign.com/
 * @since      1.0.0
 *
 * @package    Ronikdesign
 * @subpackage Ronikdesign/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ronikdesign
 * @subpackage Ronikdesign/public
 * @author     Kevin Mancuso <kevin@ronikdesign.com>
 */
class Ronikdesign_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ronikdesign_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ronikdesign_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ronikdesign-public.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name . '2', plugin_dir_url(__FILE__) . 'assets/dist/main.min.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ronikdesign_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ronikdesign_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */		
		// For older sites we would want to enque new jquery but for newer sites we valid the disabled
		$f_disable = 'invalid';
		if ( $f_disable == 'invalid' ) {
			if (!wp_script_is('jquery', 'enqueued')) {
				wp_enqueue_script(
					$this->plugin_name . '-jquery',
					'https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js',
					array(),
					null,
					true
				);
			}
			// wp_enqueue_script($this->plugin_name.'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js', array(), null, true);
			$scriptName = $this->plugin_name.'jquery';
			// wp_enqueue_script($this->plugin_name.'-vimeo', 'https://player.vimeo.com/api/player.js', array($scriptName), $this->version, false);
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ronikdesign-public.js', array($scriptName), $this->version, false);
			wp_enqueue_script($this->plugin_name . '2', plugin_dir_url(__FILE__) . 'assets/dist/app.min.js', array($scriptName), $this->version, false);
		} else {
			// wp_enqueue_script($this->plugin_name.'-vimeo', 'https://player.vimeo.com/api/player.js', array(), $this->version, false);
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ronikdesign-public.js', array(), $this->version, false);
			wp_enqueue_script($this->plugin_name . '2', plugin_dir_url(__FILE__) . 'assets/dist/app.min.js', array(), $this->version, false);
		}

		// Ajax & Nonce
		wp_localize_script($this->plugin_name, 'wpVars', array(
			'ajaxURL' => admin_url('admin-ajax.php'),
			'nonce'	  => wp_create_nonce('ajax-nonce')
		));
	}


	function ajax_do_verification()
	{
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			wp_send_json_error('Security check failed', '400');
			wp_die();
		}
		// Helper Guide
		$helper = new RonikHelper;

		$f_val_type = $_POST['validationType'];
		$f_value = $_POST['validationValue'];
		$f_strict = $_POST['validationStrict'];
		$f_phone_api_key = get_field('abstract_api_phone_ronikdesign', 'option');
		$f_email_api_key = get_field('abstract_api_email_ronikdesign', 'option');

		// Header Manipulation && local needs to be set to false to work correctly.
		if (defined('SITE_ENV') && SITE_ENV == 'PRODUCTION') {
			$f_sslverify = true;
		} else {
			$f_sslverify = false;
		}
		$args = array(
			'headers'     => array(
				'Content-Type' => 'application/json',
				'User-Agent'    => 'PHP',
			),
			'blocking' => true,
			'sslverify' => $f_sslverify,
		);
		// Lets run phone validation.
		if ($f_val_type == 'phone') {
			// Check if phone number has a 1
			if (substr($f_value, 0, 1) !== '1') {
				$f_value = '1' . $f_value;
			}
			$f_url = 'https://phonevalidation.abstractapi.com/v1/?api_key=' . $f_phone_api_key . '&phone=' . $f_value . '';
			$response = wp_remote_get($f_url, $args);
			$helper->ronikdesigns_write_log_devmode('Phone Validation: '.$response, 'low');

			if ((!is_wp_error($response)) && (200 === wp_remote_retrieve_response_code($response))) {
				$responseBody = json_decode($response['body']);
				if (json_last_error() === JSON_ERROR_NONE) {
					if ($responseBody->valid == 1) {
						if($f_strict){
							wp_send_json_success($responseBody->valid);
						} else {
							wp_send_json_success($responseBody->valid);
						}
					} else {
						$helper->ronikdesigns_write_log_devmode('Phone Validation: Error 1', 'critical');
						wp_send_json_error('Error');
					}
				} else {
					$helper->ronikdesigns_write_log_devmode('Phone Validation: Error 2', 'critical');
					wp_send_json_error('Error');
				}
			} else {
				$helper->ronikdesigns_write_log_devmode('Phone Validation: Error 3', 'critical');
				wp_send_json_error('Error');
			}
		}
		// Lets run email validation.
		if ($f_val_type == 'email') {
			$f_url = 'https://emailvalidation.abstractapi.com/v1/?api_key=' . $f_email_api_key . '&email=' . $f_value . '';
			$response = wp_remote_get($f_url, $args);
			$helper->ronikdesigns_write_log_devmode('Email Validation: '.$response, 'low');
			if ((!is_wp_error($response)) && (200 === wp_remote_retrieve_response_code($response))) {
				$responseBody = json_decode($response['body']);
				if (json_last_error() === JSON_ERROR_NONE) {
					if ($responseBody->is_valid_format->value == 1) {
						if($f_strict){
							wp_send_json_success($responseBody->is_valid_format->value);
						} else {
							wp_send_json_success($responseBody->is_valid_format->value);
						}
					} else {
						$helper->ronikdesigns_write_log_devmode('Email Validation: Error 1', 'critical');
						wp_send_json_error('Error');
					}
				} else {
					$helper->ronikdesigns_write_log_devmode('Email Validation: Error 2', 'critical');
					wp_send_json_error('Error');
				}
			} else {
				$helper->ronikdesigns_write_log_devmode('Email Validation: Error 3', 'critical');
				wp_send_json_error('Error');
			}
		}
	}

	// Pretty much we add custom classes to the body.
	function ronik_body_class($classes)
	{
		// Helper Guide
		$helper = new RonikHelper;
		$f_custom_js_settings = get_field('custom_js_settings', 'options');
		if( !empty($f_custom_js_settings) ){
			$helper->ronikdesigns_write_log_devmode('Body Add custom class enabled', 'low');

			if ($f_custom_js_settings['dynamic_image_attr']) {
				$classes[] = 'dyn-image-attr';
			}
			if ($f_custom_js_settings['dynamic_button_attr']) {
				$classes[] = 'dyn-button-attr';
			}
			if ($f_custom_js_settings['dynamic_external_link']) {
				$classes[] = 'dyn-external-link';
			}
			if ($f_custom_js_settings['smooth_scroll']) {
				$classes[] = 'smooth-scroll';
			}
			if ($f_custom_js_settings['dynamic_svg_migrations']) {
				$classes[] = 'dyn-svg-migrations';
			}
			if ($f_custom_js_settings['enable_serviceworker']) {
				$classes[] = 'enable-serviceworker';
			}
		}
		return $classes;
	}


	/**
	 * Icon Set
	*/
	function ajax_do_init_svg_migration_ronik() {
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			wp_send_json_error('Security check failed', '400');
			wp_die();
		}
		// Check if user is logged in.
		if( !is_user_logged_in() ){
			return;
		}
		// Helper Guide
		$helper = new RonikHelper;

		$f_icons = get_field('page_migrate_icons', 'options');
		if($f_icons){
			//The folder path for our file should.
			$directory = get_stylesheet_directory().'/roniksvg/migration/';

			// First lets loop through everything to see if any icons are assigned to posts..
			// The meta query will search for any value that has part of the beginning of the file name.
			$args_id = array(
				'fields' => 'ids',
				'post_type'  => 'any',
				'post_status'  => 'any',
				'posts_per_page' => -1,
				'meta_query' => array(
					array(
					'value' => 'ronik-migration-svg_',
					'compare' => 'LIKE',
					)
				),
			);
			$f_postsid = get_posts( $args_id );
			if($f_postsid){
				$f_array = array();
				// Loop through all found posts...
				foreach($f_postsid as $i => $postid){
					$metavalue = get_post_meta($postid);
					$count = -1;
					// Loop through all post meta for the current postid...
					foreach($metavalue as $a => $val){
						// We determine the meta value and explode and compare accordingly..
						$pieces = explode("migration-svg_", $val[0]);
						if( $pieces[0] == 'ronik-'){
							$count++;
							$f_filename = str_replace("ronik-migration-svg_","",  $val);
							$f_array[$count]['acf-key'] = $a;
							foreach($f_icons as $s => $icons){
								$f_filename_svg = str_replace(".svg","", $icons['svg']['filename']);
								if( $f_filename[0] == $f_filename_svg ){
									// Increase Index by 1 so we dont run into a false positive..
									$f_array[$count]['acf-index'] = $s+1;
								}
							}
						}
					}
					// This is critical we check the array count vs the
					if( $f_array ){
						$f_array_count = count($f_array);
						$f_valid = 0;
						foreach($f_array as $array){
							// Check if empty and if index is greater then 0.
							if( !empty($array['acf-index']) && ($array['acf-index'] > 0) ){
								$helper->ronikdesigns_write_log_devmode('ajax_do_init_svg_migration_ronik: valid', 'low');
								$f_valid++;
							} else{
								$helper->ronikdesigns_write_log_devmode('ajax_do_init_svg_migration_ronik: invalid', 'low');
							}
						}
						if($f_array_count == $f_valid){
							$helper->ronikdesigns_write_log_devmode('ajax_do_init_svg_migration_ronik: passed', 'low');
							update_post_meta ( $postid, 'dynamic-icon_icon_select-history', $f_array  );
						}
					}
				}
			}
			sleep(.5);
			// Lets clean up all the icons within the folder
			$files = glob($directory.'*');
			foreach($files as $file) {
				if(is_file($file)){
					unlink($file);
				}
			}
			sleep(.5);
			// Next lets loop through all the options icons..
			foreach($f_icons as $key=> $icon2){
				// First lets copy the full image to the ronikdetached folder.
				$upload_dir   = wp_upload_dir();
				$link = wp_get_attachment_image_url( $icon2['svg']['id'], 'full' );
				$file_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $link);
				$file_name = explode('/', $link);
				//If the directory doesn't already exists.
				if(!is_dir($directory)){
					//Create our directory.
					mkdir($directory, 0777, true);
				}
				copy($file_path , $directory.'ronik-migration-svg_'.end($file_name));
			}
			sleep(.5);
			// Lastly lets loop through everything to see if we can reassign the icons
			foreach($f_icons as $key => $icon3){
				$args_id_3 = array(
					'fields' => 'ids',
					'post_type'  => 'any',
					'post_status'  => 'any',
					'posts_per_page' => -1,
					'meta_query' => array(
						array(
							'key' => 'dynamic-icon_icon_select',
							'value' => str_replace(".svg","", $icon3['svg']['filename']),
							'compare' => '!='
						)
					),
				);
				$f_postsid = get_posts( $args_id_3 );
				if($f_postsid){
					foreach($f_postsid as $j => $postid){
						$f_history = get_post_meta( $f_postsid[$j], 'dynamic-icon_icon_select-history', true );

						if($f_history){
							foreach($f_history as $k => $history){
								$f_file = str_replace(".svg","", 'ronik-migration-svg_'.$f_icons[$history['acf-index']-1]['svg']['filename']);
								update_post_meta ( $postid, $history['acf-key'] , $f_file  );
							}
						}
					}
				}
			}
		} else {
			$helper->ronikdesigns_write_log_devmode('ajax_do_init_svg_migration_ronik: Error 1', 'critical');
			wp_send_json_error('No rows found!');
		}
		wp_send_json_success('Done');
	}

	// modify the path to the icons directory
	function acf_icon_path_suffix( $path_suffix ) {
		return $path_suffix;
		// return 'roniksvg/migration/';
	}
	// modify the path to the above prefix
	function acf_icon_path( $path_suffix ) {
		return $path_suffix;
		// return get_stylesheet_directory_uri();
	}
	// modify the URL to the icons directory to display on the page
	function acf_icon_url( $path_suffix ) {
		return $path_suffix;
		// return get_stylesheet_directory_uri();
	}

	function ronikdesigns_rest_api_init(){
		// Include the Spam Blocker.
		foreach (glob(dirname(__FILE__) . '/rest-api/*.php') as $file) {
			include $file;
		}
	}

	function ronikdesigns_cache_on_post_save() {
		$version_ronikdesigns_increment = get_option( 'version_ronikdesigns_increment', 1 );
        update_option( 'version_ronikdesigns_increment', $version_ronikdesigns_increment+1 );
		wp_cache_flush();
	}

	
	function ronikdesigns_admin_logout() {
		$user_id = get_current_user_id();
		$r = '';

		wp_destroy_current_session();
		wp_clear_auth_cookie();
		wp_set_current_user( 0 );

        $userclick_actions = get_user_meta($user_id, 'user_tracker_actions', true);
		if( isset($userclick_actions['url']) ){
			$r = '?r='.urldecode($userclick_actions['url']);
		}
		wp_redirect( home_url('/nbcuni-sso/login/'.$r.'') );


		exit;
	}


	// Ajax function pretty much helps handle the landing of the redirect.
	function ajax_do_init_urltracking() {
		// Ajax Security check..
		ronik_ajax_security(false, false);
		// Next lets santize the post data.
		cleanInputPOST();

		if (class_exists('RonikAuthChecker')) {
			$authChecker = new RonikAuthChecker;
			if( $authChecker->urlCheckNoAuthPage($_POST['point_origin']) ){
				// We are checking if the url contains the /wp-content/
				if (!str_contains($_POST['point_origin'], '/wp-content/')) {
					$point_origin_url = str_replace($_SERVER['HTTP_ORIGIN'], "", $_POST['point_origin']);
				} else {
					if( $_SERVER['HTTP_ORIGIN'] && $_SERVER['HTTP_REFERER'] ){
						$point_origin_url = str_replace($_SERVER['HTTP_ORIGIN'], "", $_SERVER['HTTP_REFERER']);
					}
				}

				$authChecker->userTrackerActions($point_origin_url);
			}
		}
	}
}
