<?php


/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.ronikdesign.com/
 * @since      1.0.0
 *
 * @package    Ronikdesign
 * @subpackage Ronikdesign/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ronikdesign
 * @subpackage Ronikdesign/admin
 * @author     Kevin Mancuso <kevin@ronikdesign.com>
 */
class Ronikdesign_Admin
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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Attempt to raise PHP upload/post memory limits via ini_set where permitted.
	 * Runs very early to influence subsequent requests.
	 */
	public function set_php_upload_limits()
	{
		// Target ~750M limits; hosts may clamp lower.
		$this->maybe_ini_set('upload_max_filesize', '750M');
		$this->maybe_ini_set('post_max_size', '750M');
		$this->maybe_ini_set('memory_limit', '1024M');
		
		// Also try to add wp-config.php directives if possible
		$this->maybe_add_wp_config_limits();
	}

	/**
	 * Filter WordPress upload size limit (in bytes).
	 *
	 * @param int $size
	 * @return int
	 */
	public function filter_upload_size_limit($size)
	{
		$target_bytes = 650 * 1024 * 1024; // 650MB
		return max((int)$size, $target_bytes);
	}
	/**
	 * Filter All-in-One WP Migration upload limit.
	 *
	 * @param int $max_upload_size
	 * @return int
	 */
	public function filter_ai1wm_max_file_size($max_upload_size)
	{
		$target_bytes = 750 * 1024 * 1024; // 750MB
		return max((int)$max_upload_size, $target_bytes);
	}

	/**
	 * Helper to call ini_set safely if allowed.
	 *
	 * @param string $key
	 * @param string $value
	 * @return void
	 */
	private function maybe_ini_set($key, $value)
	{
		if (function_exists('ini_get') && function_exists('ini_set')) {
			$disabled = ini_get('disable_functions');
			if (!$disabled || strpos($disabled, 'ini_set') === false) {
				@ini_set($key, $value);
			}
		}
	}

	/**
	 * Attempt to add PHP limits to wp-config.php if writable.
	 *
	 * @return void
	 */
	private function maybe_add_wp_config_limits()
	{
		$wp_config_path = ABSPATH . 'wp-config.php';
		
		// Check if wp-config.php exists and is writable
		if (!file_exists($wp_config_path) || !is_writable($wp_config_path)) {
			return;
		}
		
		$wp_config_content = file_get_contents($wp_config_path);
		
		// Check if our limits are already added
		if (strpos($wp_config_content, 'RONIK_UPLOAD_LIMITS') !== false) {
			return;
		}
		
		// Add our limits before the "That's all, stop editing!" line
		$limits_code = "\n// Ronik Upload Limits - Added by Ronik Toolbox\n";
		$limits_code .= "ini_set('upload_max_filesize', '750M');\n";
		$limits_code .= "ini_set('post_max_size', '750M');\n";
		$limits_code .= "ini_set('memory_limit', '1024M');\n";
		$limits_code .= "// End Ronik Upload Limits\n";
		
		// Insert before the "That's all, stop editing!" comment
		$stop_editing_pos = strpos($wp_config_content, "That's all, stop editing!");
		if ($stop_editing_pos !== false) {
			$new_content = substr($wp_config_content, 0, $stop_editing_pos) . 
						   $limits_code . "\n" . 
						   substr($wp_config_content, $stop_editing_pos);
			
			// Write the modified content
			@file_put_contents($wp_config_path, $new_content);
		}
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ronikdesign-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
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
		if ( ! wp_script_is( 'jquery', 'enqueued' )) {
			// wp_enqueue_script($this->plugin_name.'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js', array(), null, true);

			if (!wp_script_is('jquery', 'enqueued')) {
				wp_enqueue_script(
					$this->plugin_name . '-jquery',
					'https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js',
					array(),
					null,
					true
				);
			}

			$scriptName = $this->plugin_name.'jquery';
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ronikdesign-admin.js', array($scriptName), $this->version, false);
		} else {
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ronikdesign-admin.js', array(), $this->version, false);
		}
		// Ajax & Nonce
		wp_localize_script($this->plugin_name, 'wpVars', array(
			'ajaxURL' => admin_url('admin-ajax.php'),
			'nonce'	  => wp_create_nonce('ajax-nonce')
		));
	}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function acf_enqueue_scripts()
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

		// Detect if jQuery is included if not lets modernize with the latest stable version.
		// Detect if jQuery is included if not lets modernize with the latest stable version.
		if ( ! wp_script_is( 'jquery', 'enqueued' )) {
			wp_enqueue_script($this->plugin_name.'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js', array(), null, true);
			$scriptName = $this->plugin_name.'jquery';
			wp_enqueue_script($this->plugin_name . '-acf', plugin_dir_url(__FILE__) . 'js/acf/admin.js', array($scriptName), $this->version, false);
		} else {
			wp_enqueue_script($this->plugin_name . '-acf', plugin_dir_url(__FILE__) . 'js/acf/admin.js', array(), $this->version, false);
		}
	}


	// This will setup all options pages.
	function ronikdesigns_acf_op_init()
	{
		// Check function exists.
		if (function_exists('acf_add_options_page')) {
			// Add parent.
			$parent = acf_add_options_page(array(
				// 'capability' => 'manage_network_users',
				'page_title'  => __('Developer General Settings'),
				'menu_title'  => __('Developer Settings'),
				'menu_slug'     => 'developer-settings',
				// 'parent_slug' => $parent['menu_slug'],
				'redirect'    => false,
			));
			// Add sub page.
			$child = acf_add_options_page(array(
				'capability' => 'manage_network_users',
				'page_title'  => __('Code Template'),
				'menu_title'  => __('Code Template'),
				'menu_slug'     => 'code-template',
				'parent_slug' => $parent['menu_slug'],
			));
		}
	}

	// This will setup all custom fields via php scripts.
	function ronikdesigns_acf_op_init_fields()
	{
		// Include the ACF Fields
		foreach (glob(dirname(__FILE__) . '/acf-fields/*.php') as $file) {
			include $file;
		}
	}


	function ronikdesigns_acf_op_init_functions()
	{
		// Include the Wp Functions.
		foreach (glob(dirname(__FILE__) . '/wp-functions/*.php') as $file) {
			include $file;
		}
		// acf-icon-picker-master
		// include dirname(__FILE__) . '/acf-icon-picker-master/acf-icon-picker.php';

		// Include the Script Optimizer.
		foreach (glob(dirname(__FILE__) . '/script-optimizer/*.php') as $file) {
			include $file;
		}
		// Include the Spam Blocker.
		foreach (glob(dirname(__FILE__) . '/spam-blocker/*.php') as $file) {
			include $file;
		}
		// Include the Wp Cleaner.
		foreach (glob(dirname(__FILE__) . '/wp-cleaner/*.php') as $file) {
			include $file;
		}
		// Include the manifest.
		foreach (glob(dirname(__FILE__) . '/manifest/*.php') as $file) {
			include $file;
		}
		// Include the Service Worker.
		foreach (glob(dirname(__FILE__) . '/service-worker/*.php') as $file) {
			include $file;
		}
		// // Include the analytics.
		// foreach (glob(dirname(__FILE__) . '/analytics/*.php') as $file) {
		// 	include $file;
		// }
	}


	/**
	 * Enable SVG as a mime type for uploads.
	 * @param array $mimes
	 * @return string
	 */
	function roniks_add_svg_mime_types($mimes): array
	{
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}

	/**
	 * Remove menus from clients.
	 */
	function remove_menus()
	{
		$curr_user = wp_get_current_user();
		$curr_id = 'user_' . $curr_user->id;
		$curr_experience = get_field('global_user_experience', $curr_id);

		// We check user roles.
		$allowed_roles = array('administrator');
		if (!array_intersect($allowed_roles, $curr_user->roles)) {
			// Code here for allowed roles
			remove_menu_page('acf-options-developer-settings');
		}
		if ($curr_experience !== 'advanced') {
			remove_menu_page('index.php'); //Dashboard
			remove_menu_page('options-general.php'); //Settings
			remove_menu_page('tools.php'); //Tools
			remove_menu_page('edit.php?post_type=acf-field-group');  //Hide ACF Field Groups
			remove_menu_page('themes.php'); //Appearance
			remove_menu_page('plugins.php'); //Plugins
			remove_menu_page('acf-options-developer-settings');
		}
	}

	/**
	 * Init Page Migration, Basically swap out the original link with the new link.
	 */
	function ajax_do_init_page_migration()
	{
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			wp_send_json_error('Security check failed', '400');
			wp_die();
		}
		// Check if user is logged in.
		if (!is_user_logged_in()) {
			return;
		}
		$f_url_migration = get_field('page_url_migration', 'options');
		if ($f_url_migration) {
			foreach ($f_url_migration as $key => $url_migration) {
				// CHECK if both fields are populated.
				if ($url_migration['original_link'] && $url_migration['new_link']) {
					// Lets convert the given url to post ids.
					$original_link = url_to_postid($url_migration['original_link']['url']);
					$new_link = url_to_postid($url_migration['new_link']['url']);
					// Check if 0 is present in the both variables. url_to_postid return Post ID, or 0 on failure.
					if ($original_link !== 0 && $new_link !== 0) {
						$original_post_slug = get_post_field('post_name', $original_link);
						// First we have to draft the orginal link and change the post_name.
						wp_update_post(array(
							'ID'    =>  $original_link,
							'post_name' => $original_post_slug . '-drafted',
							'post_status' => array('draft'),
						));
						// Second we have to take the $original_post_slug and remove the -drafted string
						$modified_original_post_slug = str_ireplace('-drafted', '', $original_post_slug);
						wp_update_post(array(
							'ID'    =>  $new_link,
							'post_name' => $modified_original_post_slug,
							'post_status' => 'publish', // Change to publish status.
							'post_password' => '' // This is critical we empty the password value for it to become no longer private.
						));
						// This will be our way for logging post migration status.
						update_option('options_page_url_migration_' . $key . '_migration-status', 'Success: Before rerunning please remove any successful rows!');
					} else {
						update_option('options_page_url_migration_' . $key . '_migration-status', 'Failure: Please check the provided url!');
					}
				} else {
					update_option('options_page_url_migration_' . $key . '_migration-status', 'Failure: Please check the provided url!');
				}
			}
		} else {
			// If no rows are found send the error message!
			wp_send_json_error('No rows found!');
		}
		// Send sucess message!
		wp_send_json_success('Done');
	}

}
