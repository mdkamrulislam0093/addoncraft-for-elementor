<?php
/**
 * Plugin Name: AddonCraft for Elementor
 * Plugin URI: https://codewithkamrul.me/
 * Description: AddonCraft Elementor Addons is a plugin you install after Elementor! It’s packed with a variety of stunning elements and different types of widgets to enhance your website design.
 * Version: 1.53
 * Author: Kamrul
 * Author URI: https://codewithkamrul.me/
 * License: GPLv3
 * License URI: https://opensource.org/licenses/GPL-3.0
 * Text Domain: addoncraft-for-elementor
 */


defined( 'ABSPATH' ) || exit;

define( 'ACE__PLUGIN_PATH', trailingslashit(plugin_dir_path(__FILE__)) );
define( 'ACE__PLUGIN_URL', trailingslashit(plugins_url('/', __FILE__)) );
define( 'ACE__PLUGIN_VERSION', '1.0.0');

class ACE_ELEMENTOR_ADDON {
	
	private static $instance;

	public static function get_instance(){
		if (null === self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct(){
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_new_widgets' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'ace_cat_register' ] );
		add_action( 'admin_notices', [ $this, 'build_dependencies_notice' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'custom_wp_enqueue_script' ] );
	}

	/**
	 * Register Enqueue Scripts
	 *
	 * @since v1.0.0
	 */
	public function custom_wp_enqueue_script() {
		$css_deps = [ 'elementor-frontend' ];
		
		wp_register_style( 'ACE_custom_style', plugins_url( '/assets/css/style.css', __FILE__ ), $css_deps, ACE__PLUGIN_VERSION, 'all' );
		wp_enqueue_style( 'ACE_custom_style' );

		wp_register_style( 'ace_after-before-image-style', plugins_url( '/assets/css/ace_after-before-image.css', __FILE__ ), $css_deps, ACE__PLUGIN_VERSION, 'all' );
		wp_enqueue_style( 'ace_after-before-image-style' );



		wp_register_script( 'ACE_main_script', plugins_url( '/assets/js/main.js', __FILE__ ), ['jquery'], ACE__PLUGIN_VERSION, true );
		wp_enqueue_script('ACE_main_script');

		wp_register_script( 'ACE_before_after_image_script', plugins_url( '/assets/js/before-after-image-comparison.js', __FILE__ ), ['jquery'], ACE__PLUGIN_VERSION, true );
		wp_enqueue_script('ACE_before_after_image_script');
		

	}

	/**
	 * Register Categories
	 *
	 * @since v1.0.0
	 */

	public function ace_cat_register( $elements_manager ) {

		$categories = [];
		$categories['ACE_category_advanced'] =
			[
				'title' => 'AddonCraft',
				'icon'  => 'fa fa-plug',
			];

		$old_categories = $elements_manager->get_categories();

		$categories = array_merge(
			array_slice($old_categories, 0, 4),
			$categories,
			array_slice($old_categories, 4, null),
		);

		$set_categories = function ( $categories ) {
			$this->categories = $categories;
		};

		$set_categories->call( $elements_manager, $categories );

	}

	/**
	 * Register widgets
	 *
	 * @since v1.0.0
	 */

	public function register_new_widgets() {
	    require_once 'elementor/element/marquee.php';
	    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ACE_Marquee );

	    if ( class_exists( 'WooCommerce' ) ) {
		    require_once 'elementor/element/product-slider.php';
		    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ACE_Product_Slider );
	    }

		require_once 'elementor/element/before-after-image-comparison.php';
	    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new BeforeAfterImageComparison );
		
		require_once 'elementor/element/logo-infinite-carousel.php';
	    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ACE_Logo_Infinite_Carousel );

		require_once 'elementor/element/products-slider.php';
	    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ACE_Products_Slider );


	}

    /**
     * Check if a plugin is installed
     *
     * @since v1.0.0
     */
    public function is_plugin_installed( $basename ) {
        if (!function_exists('get_plugins')) {
            include_once ABSPATH . '/wp-admin/includes/plugin.php';
        }

        $installed_plugins = get_plugins();

        return isset( $installed_plugins[ $basename ] );
    }

	/**
	 * Output a admin notice when build dependencies not met.
	 *
	 * @return void
	 */

	public function build_dependencies_notice() {

        if (!current_user_can('activate_plugins')) {
            return;
        }

        if ( ! did_action( 'elementor/loaded' ) && is_admin() ) {
        	
        	$elementor = 'elementor/elementor.php';

	        if ($this->is_plugin_installed($elementor)) {
	            $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $elementor . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor);

	            /* translators: %s AddonCraft for Elementor %s requires %s Elementor %s plugin to be active. Please activate Elementor to continue. */

	            $message = sprintf(__('%1$sAddonCraft for Elementor%2$s requires %1$sElementor%2$s plugin to be active. Please activate Elementor to continue.', 'addoncraft-for-elementor'), "<strong>", "</strong>");

	            $button_text = __('Activate Elementor', 'addoncraft-for-elementor');
	        } else {
	            $activation_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');

	             /* translators: %s AddonCraft for Elementor %s requires %s Elementor %s plugin to be installed and activated. Please install Elementor to continue. */

	            $message = sprintf(__('%1$sAddonCraft for Elementor%2$s requires %1$sElementor%2$s plugin to be installed and activated. Please install Elementor to continue.', 'addoncraft-for-elementor'), '<strong>', '</strong>');
	            $button_text = __('Install Elementor', 'addoncraft-for-elementor');
	        }

	        $button = '<p><a href="' . esc_url( $activation_url ) . '" class="button-primary">' . esc_html( $button_text ) . '</a></p>';

	        printf( '<div class="error"><p>%1$s</p>%2$s</div>', wp_kses_post($message), wp_kses_post($button) );

        }

	}

}


// Load the plugin
add_action( 'plugins_loaded', function(){
	ACE_ELEMENTOR_ADDON::get_instance();
}, 99);


