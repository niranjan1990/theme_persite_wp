<?php
/*
Plugin Name: Q2W3 Fixed Widget
Plugin URI: https://wpadvancedads.com/fixed-widget-wordpress/
Description: Use the fixed widget plugin to create sticky widgets that stay in the visible screen area when the page is scrolled up or down and boost your conversions.
Text Domain: q2w3-fixed-widget
Author: Thomas Maier, Max Bond
Version: 5.1.7c
Author URI: https://wpadvancedads.com/fixed-widget-wordpress/
*/

add_action('init', array( 'q2w3_fixed_widget', 'init' )); // Main Hook

if ( class_exists('q2w3_fixed_widget', false) ) return; // if class is allready loaded return control to the main script

class q2w3_fixed_widget { // Plugin class
	
	const ID = 'q2w3_fixed_widget';
	
	const VERSION = '5.1.7';
	
	protected static $sidebars_widgets;
	
	protected static $fixed_widgets;
	
	protected static $settings_page_hook;
	
	
	public static function init() {
		
		$options = self::load_options();
		
		if ( $options['logged_in_req'] && !is_user_logged_in() ) return;
		
		if ( is_admin() ) {
			
			self::load_language();
			
			add_action('in_widget_form', array( __CLASS__, 'add_widget_option' ), 10, 3);
		
			add_filter('widget_update_callback', array( __CLASS__, 'update_widget_option' ), 10, 3);
			
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( __CLASS__, 'add_plugin_links' ) );
		
			add_action('admin_init', array( __CLASS__, 'register_settings' ));
		
			add_action('admin_menu', array( __CLASS__, 'admin_menu' ), 5);
			
			add_action('admin_enqueue_scripts', array( __CLASS__, 'settings_page_js' ));
			
            // add stylesheets for the plugin's backend
			add_action('admin_enqueue_scripts', array( __CLASS__, 'load_custom_be_styles' ));			
		
		} else {
		
			if ( $options['fix-widget-id'] ) self::registered_sidebars_filter(); 

			add_action('wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ));
			
			add_filter('widget_display_callback', array( __CLASS__, 'is_widget_fixed' ), 99, 3);
			
			//add_filter('widget_display_callback', array( __CLASS__, 'is_widget_fixed' ), $options['widget_display_callback_priority'], 3);

			//add_action('wp_loaded', array( __CLASS__, 'custom_ids' ));
			
			//add_action('wp_footer', array( __CLASS__, 'js_settings' ));
					
			
			
		}
						
	}
	
	public static function load_custom_be_styles() {   
        wp_register_style('fixedWidgetBEStyles', plugin_dir_url( __FILE__ ) . 'css/backend.css', false, '0.0.1' );
        wp_enqueue_style( 'fixedWidgetBEStyles' );        
	}  	
	
	public static function enqueue_scripts() {
		
		self::custom_ids();
		
		self::fixed_wigets();
		
		wp_enqueue_script('jquery');
		
		wp_enqueue_script(self::ID, plugin_dir_url( __FILE__ ) . 'js/q2w3-fixed-widget.min.js', array('jquery'), self::VERSION, true);
		
		self::wp_localize_script();
		
	}
	
	
	protected static function wp_localize_script() {
		
		$options = self::load_options();
		
		if ( is_array(self::$fixed_widgets) && !empty(self::$fixed_widgets) ) {
		
			if ( isset($options['window-load-enabled']) && $options['window-load-enabled'] == 'yes' ) $window_load_hook = true; else $window_load_hook = false;
				
			if ( isset($options['width-inherit']) && $options['width-inherit'] ) $width_inherit = true; else $width_inherit = false;
				
			if ( isset($options['disable-mo-api']) && $options['disable-mo-api'] ) $disable_mo_api = true; else $disable_mo_api = false;
				
			if ( $options['refresh-interval'] > 0 ) $refresh_interval = $options['refresh-interval']; else $refresh_interval = 0;
				
			$i = 0;
			$sidebar_options = array();
				
			self::$fixed_widgets = apply_filters( 'q2w3-fixed-widgets', self::$fixed_widgets ); // this filter was requested by users
			
			foreach ( self::$fixed_widgets as $sidebar => $widgets ) {
		
				$sidebar_options[ $i ] = array(
						'sidebar' => $sidebar,
						'margin_top' => $options['margin-top'],
						'margin_bottom' => $options['margin-bottom'],
						'stop_id' => $options['stop-id'],
						'screen_max_width' => $options['screen-max-width'],
						'screen_max_height' => $options['screen-max-height'],
						'width_inherit' => $width_inherit,
						'refresh_interval' => $refresh_interval,
						'window_load_hook' => $window_load_hook,
						'disable_mo_api' => $disable_mo_api,
						'widgets' => array_values( $widgets )
				);
		
				$i++;
		
			}
				
			wp_localize_script( self::ID, 'q2w3_sidebar_options', $sidebar_options );
				
		}		
		
	}
	
	
	protected static function fixed_wigets() {
		
		$sidebars = wp_get_sidebars_widgets();
		
		if ( $sidebars && is_array($sidebars) ) foreach ( $sidebars as $sidebar_id => $sidebar_widgets ) {
		
			if ( ! (stristr($sidebar_id, 'orphaned_widgets') !== false || $sidebar_id == 'wp_inactive_widgets') ) {
			
				if ( $sidebar_widgets && is_array($sidebar_widgets) ) foreach ( $sidebar_widgets as $widget ) {
					
					$widget_id = substr(strrchr($widget, '-'), 1);
					
					$widget_type = stristr($widget, '-'.$widget_id, true);
										
					$widget_options = get_option('widget_' . $widget_type);
					
					if ( isset($widget_options[$widget_id]['q2w3_fixed_widget']) && $widget_options[$widget_id]['q2w3_fixed_widget']) self::$fixed_widgets[$sidebar_id][$widget] = $widget;
					
				}
			
			}
		
		}
		
	}
	
		
	public static function is_widget_fixed($instance, $widget, $args) { // deprecated
    	
		if ( isset($instance['q2w3_fixed_widget']) && $instance['q2w3_fixed_widget'] && ! isset(self::$fixed_widgets[$args['id']][$widget->id]) ) {

			//self::$fixed_widgets[$args['id']][$widget->id] = "'". $widget->id ."'";
					
			//echo '<!-- fixed widget -->';

			self::$fixed_widgets[$args['id']][$widget->id] = $widget->id;
				
			self::wp_localize_script();
			
		}
		
		return $instance;

	}
	
	
	protected static function custom_ids() {
		
		$options = self::load_options();
		
		if ( isset($options['custom-ids']) && $options['custom-ids'] ) {
		
			$ids = explode(PHP_EOL, $options['custom-ids']);
		
			foreach ( $ids as $id ) {
				
				$id = trim($id);

				//if ( $id ) self::$fixed_widgets[self::get_widget_sidebar($id)][$id] = "'". $id ."'";
				
				if ( $id ) self::$fixed_widgets[self::get_widget_sidebar($id)][$id] = $id;
				
			}
		
		}
		
	}
	
	protected static function get_widget_sidebar($widget_id) {
		
		if ( !self::$sidebars_widgets ) {
		
			self::$sidebars_widgets = wp_get_sidebars_widgets();
			
			unset(self::$sidebars_widgets['wp_inactive_widgets']);
	
		}
		
		if ( is_array(self::$sidebars_widgets) ) {
		
			foreach ( self::$sidebars_widgets as $sidebar => $widgets ) {
		
				$key = array_search($widget_id, $widgets);
		
				if ( $key !== false ) return $sidebar;
	
			}
		
		}
		
		return 'q2w3-default-sidebar';
		
	}
		
	/*public static function js_settings() { // deprecated
	
		$options = self::load_options();

		$js = '';
		
		if ( is_array(self::$fixed_widgets) && !empty(self::$fixed_widgets) ) {
			
			$js .= 'var q2w3_sidebar_options = new Array();'.PHP_EOL;
			
			if ( isset($options['window-load-enabled']) && $options['window-load-enabled'] == 'yes' ) $window_load_hook = 'true'; else $window_load_hook = 'false';
			
			if ( isset($options['width-inherit']) && $options['width-inherit'] ) $width_inherit = 'true'; else $width_inherit = 'false';
							
			if ( isset($options['disable-mo-api']) && $options['disable-mo-api'] ) $disable_mo_api = 'true'; else $disable_mo_api = 'false';
			
			if ( $options['refresh-interval'] > 0 ) $refresh_interval = $options['refresh-interval']; else $refresh_interval = 0;
						
			$i = 0;
			
			foreach ( self::$fixed_widgets as $sidebar => $widgets ) {
			
				$widgets_array = implode(',', $widgets);
				
				$js .= 'q2w3_sidebar_options['. $i .'] = { "sidebar" : "'. $sidebar .'", "margin_top" : '. $options['margin-top'] .', "margin_bottom" : '. $options['margin-bottom'] .', "stop_id" : "' . $options['stop-id'] .'", "screen_max_width" : '. $options['screen-max-width'] .', "screen_max_height" : '. $options['screen-max-height'] .', "width_inherit" : '. $width_inherit .', "refresh_interval" : '. $refresh_interval .', "window_load_hook" : '. $window_load_hook .', "disable_mo_api" : '. $disable_mo_api .', "widgets" : ['. $widgets_array .'] };'.PHP_EOL;
				
				$i++;
				
			}
				
		} 
		
		if ( $js && function_exists('wp_add_inline_script') && ! class_exists('BWP_MINIFY') ) {
			
			wp_add_inline_script(self::ID, $js, 'before'); 
		
		} elseif ( $js ) {
			
			echo '<script type="text/javascript">'. $js .'</script>';
			
		}
	
	}*/

	public static function add_widget_option($widget, $return, $instance) {  
	
		if ( isset($instance['q2w3_fixed_widget']) ) $iqfw = $instance['q2w3_fixed_widget']; else $iqfw = 0;
		
		echo '<p>'.PHP_EOL;
    	
		echo '<input type="checkbox" name="'. $widget->get_field_name('q2w3_fixed_widget') .'" value="1" '. checked( $iqfw, 1, false ) .'/>'.PHP_EOL;
    	
		echo '<label for="'. $widget->get_field_id('q2w3_fixed_widget') .'">'. __('Fixed widget', 'q2w3-fixed-widget') .'</label>'.PHP_EOL;
	
		echo '</p>'.PHP_EOL;    

	}

	public static function update_widget_option($instance, $new_instance, $old_instance){
    
    	if ( isset($new_instance['q2w3_fixed_widget']) && $new_instance['q2w3_fixed_widget'] ) {
			
    		$instance['q2w3_fixed_widget'] = 1;
    
    	} else {
    	
    		$instance['q2w3_fixed_widget'] = false;
    	
    	}
    
    	return $instance;

	}
	
	protected static function load_language() {
	
		$languages_path = plugin_basename( dirname(__FILE__).'/lang' );
		
		load_plugin_textdomain( 'q2w3-fixed-widget', false, $languages_path );
	
	}
	
	public static function admin_menu() {
		
		remove_action('admin_menu', array( 'q2w3_fixed_widget', 'admin_menu' )); // Remove free version plugin
		
		self::$settings_page_hook = add_submenu_page( 'themes.php', __('Fixed Widget Options', 'q2w3-fixed-widget'), __('Fixed Widget Options', 'q2w3-fixed-widget'), 'activate_plugins', self::ID, array( __CLASS__, 'settings_page' ) );
		
	}
	
	protected static function defaults() {
		
		$d['margin-top'] = 10;
			
		$d['margin-bottom'] = 0;
		
		$d['stop-id'] = '';
		
		$d['refresh-interval'] = 1500;
		
		$d['screen-max-width'] = 0;
		
		$d['screen-max-height'] = 0;
		
		$d['fix-widget-id'] = 'yes';
		
		$d['window-load-enabled'] = false;
		
		$d['logged_in_req'] = false;
		
		$d['width-inherit'] = false;
		
		//$d['widget_display_callback_priority'] = 30;
		
		$d['disable-mo-api'] = false;
		
		return $d;
		
	}
	
	protected static function load_options() {
		
		$options = get_option(self::ID);

		$options_old = get_option('q2w3_fixed_widget');
		
		return array_merge(self::defaults(), (array)$options_old, (array)$options);
		
	}
	
	public static function register_settings() {
		
		register_setting(self::ID, self::ID, array( __CLASS__, 'save_options_filter' ) );
		
	}
	
	public static function save_options_filter($input) { // Sanitize user input
		
		$input['margin-top'] = (int)$input['margin-top'];
			
		$input['margin-bottom'] = (int)$input['margin-bottom'];
		
		$input['refresh-interval'] = (int)$input['refresh-interval'];

		$input['screen-max-width'] = (int)$input['screen-max-width'];
		
		$input['screen-max-height'] = (int)$input['screen-max-height'];
		
		$input['custom-ids'] = trim(wp_strip_all_tags($input['custom-ids']));
		
		$input['stop-id'] = trim(wp_strip_all_tags($input['stop-id']));
		
		if ( !isset($input['fix-widget-id']) ) $input['fix-widget-id'] = false;
		
		if ( !isset($input['window-load-enabled']) ) $input['window-load-enabled'] = false;
		
		if ( !isset($input['logged_in_req']) ) $input['logged_in_req'] = false;
		
		if ( !isset($input['width-inherit']) ) $input['width-inherit'] = false;
		
		if ( !isset($input['disable-mo-api']) ) $input['disable-mo-api'] = false;
		
		return $input;
		
	}
	
	public static function settings_page_js($hook) {
	
		if( self::$settings_page_hook != $hook ) return;
		
		wp_enqueue_script('postbox');
	
	}
	
	public static function settings_page() {
		
		$screen = get_current_screen();
		
		add_meta_box(self::ID.'-general', __('General Options', 'q2w3-fixed-widget'), array( __CLASS__, 'settings_page_general_box' ), $screen, 'normal');
		
		add_meta_box(self::ID.'-compatibility', __('Compatibility', 'q2w3-fixed-widget'), array( __CLASS__, 'settings_page_compatibility_box' ), $screen, 'normal');

		add_meta_box(self::ID.'-custom-ids', __('Custom IDs', 'q2w3-fixed-widget'), array( __CLASS__, 'settings_page_custom_ids_box' ), $screen, 'normal');
				
		add_meta_box(self::ID.'-recommend', __('Recommended Integration', 'q2w3-fixed-widget'), array( __CLASS__, 'settings_page_recommend_box' ), $screen, 'side', 'high');
		
		add_meta_box(self::ID.'-help', __('Documentation and Support', 'q2w3-fixed-widget'), array( __CLASS__, 'settings_page_help_box' ), $screen, 'side');
		
		$options = self::load_options();
						
		echo '<div class="wrap"><div id="icon-themes" class="icon32"><br /></div><h2>'. __('Fixed Widget Options', 'q2w3-fixed-widget') .'</h2>'.PHP_EOL;
		
		if ( isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' ) { 
		
			echo '<div id="message" class="updated"><p>'. __('Settings saved.') .'</p></div>'.PHP_EOL;
		
		}
		
		echo '<form method="post" action="options.php">'.PHP_EOL;
		
		settings_fields(self::ID);
		
		wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
		
		wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
		
		echo '<div id="poststuff" class="metabox-holder has-right-sidebar">'.PHP_EOL;
		
		echo '<div class="inner-sidebar" id="side-info-column">'.PHP_EOL;
		
		do_meta_boxes( $screen, 'side', $options );
		
		echo '</div>'.PHP_EOL;
		
		echo '<div id="post-body-content">'.PHP_EOL;
		
		do_meta_boxes( $screen, 'normal', $options );
		
		echo '</div>'.PHP_EOL;

		echo '<p><em>'. __(" Note for users of caching plugins. Please, don’t forget to clear the cache after changing options.", 'q2w3-fixed-widget') .'</em></p>'.PHP_EOL;
		
		echo '<p class="submit"><input type="submit" class="button-primary" value="'. __('Save Changes') .'" /></p>'.PHP_EOL;

		echo '</div><!-- #poststuff -->'.PHP_EOL;
		
		echo '</form>'.PHP_EOL;
		
		echo '<script>jQuery(document).ready(function(){ postboxes.add_postbox_toggles(pagenow); });</script>'.PHP_EOL;
						
		echo '</div><!-- .wrap -->'.PHP_EOL;
		
	}
	
	public static function settings_page_general_box($options) {
		
		echo '<p><span style="display: inline-block; width: 150px;">'. __('Margin Top:', 'q2w3-fixed-widget') .'</span><input type="text" name="'. self::ID .'[margin-top]" value="'. $options['margin-top'] .'" style="width: 50px; text-align: center;" />&nbsp;'. __('px', 'q2w3-fixed-widget') .'</p>'.PHP_EOL;
		
		echo '<p><span style="display: inline-block; width: 150px;">'. __('Margin Bottom:', 'q2w3-fixed-widget') .'</span><input type="text" name="'. self::ID .'[margin-bottom]" value="'. $options['margin-bottom'] .'" style="width: 50px; text-align: center;" />&nbsp;'. __('px', 'q2w3-fixed-widget') .'</p>'.PHP_EOL;
		
		echo '<p><span style="display: inline-block; width: 150px;">'. __('Stop ID:', 'q2w3-fixed-widget') .'</span><input type="text" name="'. self::ID .'[stop-id]" value="'. $options['stop-id'] .'" style="width: 150px;">&nbsp;'. __('You need to provide the HTML tag ID here. The position of that HTML element will determine the margin-bottom value.', 'q2w3-fixed-widget') .'</p>'.PHP_EOL;
			
		echo '<p><span style="display: inline-block; width: 150px;">'. __('Refresh interval:', 'q2w3-fixed-widget') .'</span><input type="text" name="'. self::ID .'[refresh-interval]" value="'. $options['refresh-interval'] .'" style="width: 50px; text-align: center;" />&nbsp;'. __('milliseconds', 'q2w3-fixed-widget') .' / '. __('Used only for compatibility with browsers without MutationObserver API support. Set 0 to disable it completely.', 'q2w3-fixed-widget') .'</p>'.PHP_EOL;
		
		echo '<p><span style="display: inline-block; width: 150px;">'. __('Disable Width:', 'q2w3-fixed-widget') .'</span><input type="text" name="'. self::ID .'[screen-max-width]" value="'. $options['screen-max-width'] .'" style="width: 50px; text-align: center;" />&nbsp;'. __('px', 'q2w3-fixed-widget') .' / '. __('Use this option to disable the plugin on portable devices. When the browser screen width is less than the specified value, the plugin will be disabled.', 'q2w3-fixed-widget') .'</p>'.PHP_EOL;

		echo '<p><span style="display: inline-block; width: 150px;">'. __('Disable Height:', 'q2w3-fixed-widget') .'</span><input type="text" name="'. self::ID .'[screen-max-height]" value="'. $options['screen-max-height'] .'" style="width: 50px; text-align: center;" />&nbsp;'. __('px', 'q2w3-fixed-widget') .' / '. __(' Works like the Disable Width option.', 'q2w3-fixed-widget') .'</p>'.PHP_EOL;
		
	}
	
	public static function settings_page_custom_ids_box($options) {
		$custom_ids = isset($options['custom-ids']) ? $options['custom-ids'] : '' ;
        echo '<p><span >'. __('Custom HTML IDs (each one on a new line):', 'q2w3-fixed-widget') .'</span><br/><br/><textarea name="'. self::ID .'[custom-ids]" style="width: 320px; height: 120px;">'. $custom_ids .'</textarea>'.PHP_EOL;
	}
	
	public static function settings_page_compatibility_box($options) {
			
		echo '<p><span style="display: inline-block; width: 280px;">'. __('Auto fix widget ID:', 'q2w3-fixed-widget') .'</span><input type="checkbox" name="'. self::ID .'[fix-widget-id]" value="yes" '. checked('yes', $options['fix-widget-id'], false) .' /> </p>'.PHP_EOL;

		echo '<p><span style="display: inline-block; width: 280px;">'. __('Disable MutationObserver:', 'q2w3-fixed-widget') .'</span><input type="checkbox" name="'. self::ID .'[disable-mo-api]" value="yes" '. checked('yes', $options['disable-mo-api'], false) .' /> '. __('If MutationObserver is disabled, the plugin will use the refresh interval to reflect page changes (version 4 behavior)', 'q2w3-fixed-widget') .'</p>'.PHP_EOL;
				
		echo '<p><span style="display: inline-block; width: 280px;">'. __('Enable the plugin for logged-in users only:', 'q2w3-fixed-widget') .'</span><input type="checkbox" name="'. self::ID .'[logged_in_req]" value="yes" '. checked('yes', $options['logged_in_req'], false) .' /> '. __('Enable this option for debug purposes (frontend problems and etc.)', 'q2w3-fixed-widget') .'</p>'.PHP_EOL;
				
		echo '<p><span style="display: inline-block; width: 280px;">'. __('Inherit widget width from the parent container:', 'q2w3-fixed-widget') .'</span><input type="checkbox" name="'. self::ID .'[width-inherit]" value="yes" '. checked('yes', $options['width-inherit'], false) .' /> '. __('Enable this option for themes with a responsive sidebar', 'q2w3-fixed-widget') .'</p>'.PHP_EOL;
				
		echo '<p><span style="display: inline-block; width: 280px;">'. __('Use jQuery(window).load() hook:', 'q2w3-fixed-widget') .'</span><input type="checkbox" name="'. self::ID .'[window-load-enabled]" value="yes" '. checked('yes', $options['window-load-enabled'], false) .' /> '. __('Enable this option only if you have problems with other scroll oriented javascript code', 'q2w3-fixed-widget') .'</p>'.PHP_EOL;
				
		//echo '<p><span style="display: inline-block; width: 280px;">'. __('widget_display_callback hook priority:', 'q2w3-fixed-widget') .'</span><select name="'. self::ID .'[widget_display_callback_priority]"><option value="1" '. selected('1', $options['widget_display_callback_priority'], false) .'>1</option><option value="10" '. selected('10', $options['widget_display_callback_priority'], false) .'>10</option><option value="20" '. selected('20', $options['widget_display_callback_priority'], false) .'>20</option><option value="30" '. selected('30', $options['widget_display_callback_priority'], false) .'>30</option><option value="50" '. selected('50', $options['widget_display_callback_priority'], false) .'>50</option><option value="100" '. selected('100', $options['widget_display_callback_priority'], false) .'>100</option></select></p>'.PHP_EOL;
	
	}
	
	public static function settings_page_recommend_box($options) {
		echo '<p>';
        echo '<a href="https://wordpress.org/plugins/advanced-ads/" target="_blank"><b>Advanced Ads</b></a>: ';
        echo __('This ad management plugin provides many features to optimize your ads and to boost your conversions. It works perfectly with the Q2W3 Fixed Widget plugin.', 'q2w3-fixed-widget');
        echo '</p>';
        echo '<div class="review">';
        echo '<h5>"Perfect plugin"</h5>';
        echo '<p class="content">"The plugin contains everything I need for the ads management and publishing. Fair price, stable and functional."</p>';
        echo '<p class="subline">from David H. on wordpress.org</p>';    
        echo '</div>';
        echo ''.PHP_EOL;
        
        if (! defined('ADVADS_VERSION')) {
        	//  check whether is's installed
        	$plugins = get_plugins();
        	if( isset( $plugins['advanced-ads/advanced-ads.php'] ) ){
	        	// advanced-ads is deactivated
        		$link = '<a class="button-var1" href="' . wp_nonce_url( 'plugins.php?action=activate&amp;plugin=advanced-ads/advanced-ads.php&amp', 'activate-plugin_advanced-ads/advanced-ads.php' ) . '">'. __('Activate Now', 'q2w3-fixed-widget') .'</a>';
        	}
        	else{
        		// advanced-ads is not installed
        		$link = '<a class="button-var1" href="' . wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=' . 'advanced-ads'), 'install-plugin_' . 'advanced-ads') . '">'. __('Install Now', 'q2w3-fixed-widget') .'</a>';
        	}
        	echo '<div style="margin-top:20px; text-align:center;">' . $link . '</div>';
        }
	}
	
	public static function settings_page_help_box($options) {
		echo '<ul>';
		echo '<li><a href="https://wpadvancedads.com/fixed-widget-wordpress/?utm_source=fixed-widget&utm_medium=link&utm_campaign=BackendSidebar" target="_blank">FAQ</a></li>';
        echo '<li><a href="https://wordpress.org/support/plugin/q2w3-fixed-widget/" target="_blank">Support</a></li>';
        echo '</ul>';
        echo ''.PHP_EOL;	
	}
	
	public static function add_plugin_links( $links ) {
		if ( ! is_array( $links ) ) {
			return $links;
		}
		// add link to the settings
		$extend_link = '<a href="' . get_site_url() . '/wp-admin/themes.php?page=' . self::ID . '">' . __( 'Settings', 'q2w3-fixed-widget' ) . '</a>';
		array_unshift( $links, $extend_link );
		return $links;
	}
		
	public static function registered_sidebars_filter() {
		
		global $wp_registered_sidebars;
		
		if ( !is_array($wp_registered_sidebars) ) return;
		
		foreach ( $wp_registered_sidebars as $id => $sidebar ) {
		
			if ( strpos($sidebar['before_widget'], 'id="%1$s"') === false && strpos($sidebar['before_widget'], 'id=\'%1$s\'') === false ) {
			
				if ( $sidebar['before_widget'] == '' || $sidebar['before_widget'] == ' ' ) {
					
					$wp_registered_sidebars[$id]['before_widget'] = '<div id="%1$s">';
					
					$wp_registered_sidebars[$id]['after_widget'] = '</div>';
					
				} elseif ( strpos($sidebar['before_widget'], 'id=') === false ) {
					
					$tag_end_pos = strpos($sidebar['before_widget'], '>');
					
					if ( $tag_end_pos !== false ) {
						
						$wp_registered_sidebars[$id]['before_widget'] = substr_replace($sidebar['before_widget'], ' id="%1$s"', $tag_end_pos, 0);
						
					} 
					
				} else {
		
					$str_array = explode(' ', $sidebar['before_widget']);
					
					if ( is_array($str_array) ) {
						
						foreach ( $str_array as $str_part_id => $str_part ) {
							
							if ( strpos($str_part, 'id="') !== false ) {
								
								$p1 = strpos($str_part, 'id="');
								
								$p2 = strpos($str_part, '"', $p1 + 4);
		
								$str_array[$str_part_id] = substr_replace($str_part, 'id="%1$s"', $p1, $p2 + 1);
								
							} elseif ( strpos($str_part, 'id=\'') !== false ) {
								
								$p1 = strpos($str_part, 'id=\'');
								
								$p2 = strpos($str_part, "'", $p1 + 4);
								
								$str_array[$str_part_id] = substr_replace($str_part, 'id=\'%1$s\'', $p1, $p2);
								
							}
							
						}
		
						$wp_registered_sidebars[$id]['before_widget'] = implode(' ', $str_array);
						
					}
											
				}	
			
			} // if id is wrong
			
		} // foreach
				
	} // registered_sidebars_filter()
	
} // q2w3_fixed_widget_pro class
