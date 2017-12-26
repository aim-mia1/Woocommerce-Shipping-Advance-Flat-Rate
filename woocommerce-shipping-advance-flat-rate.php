<?php

/**
 Plugin Name: Advance Flat Rate Shipping For WooCommerce
 Description: Advance Flat Rate Shipping For WooCommerce provides ability to set different shipping rates for shipping classes and cities.
 Author: DatumSquare
 Version: 1.0.0
 Author URI: http://datumsquare.com/
 Plugin URI: https://datumsquare.com/products/wp/plugins/woo-shipping-adv-flat-rate
 WC requires at least: 4.0
 WC tested up to: 4.9
 Copyright: 2017 DatumSquare.
 License: GNU General Public License v3.0
 License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
{
 
	define( 'WC_SHIPPING_AFR_VERSION', '1.0.0' );
	define( 'WC_SHIPPING_AFR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	define( 'WC_SHIPPING_AFR_MINIMUM_WP_VERSION', '2.6.0' );
	define( 'WC_SHIPPING_AFR_MINIMUM_WC_VERSION', '3.2.0' );
	define( 'WP_VERSION', $wp_version );

	/**
	 * The code that runs during plugin activation.
	 */
	function activate_wc_shipping_afr() {
	}
	register_activation_hook( __FILE__, 'activate_wc_shipping_afr' );

	/**
	 * The code that runs during plugin deactivation.
	 */
	function deactivate_wc_shipping_afr() {
		global $wpdb;
		$wpdb->query( "DELETE from {$wpdb->prefix}woocommerce_shipping_zone_methods WHERE method_id='afr' ") ;
	}
	register_deactivation_hook( __FILE__, 'deactivate_wc_shipping_afr' );

	 


	class WC_Shipping_AFR_Init {
		
		
		private static $instance;

		public static function get_instance() {
			return null === self::$instance ? ( self::$instance = new self ) : self::$instance;
		}

		public function __construct() {
			
			if ( class_exists( 'WC_Shipping_Method' ) ) {
				add_action( 'admin_init', array( $this, 'maybe_install' ), 5 );
				add_action( 'init', array( $this, 'load_textdomain' ) );
				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_links' ) );
				
				add_action( 'woocommerce_shipping_init', array( $this, 'includes' ) );
				add_filter( 'woocommerce_shipping_methods', array( $this, 'add_method' ) );
			
				//add_action( 'admin_notices', array( $this, 'upgrade_notice' ) );
				//add_action( 'wp_ajax_afr_dismiss_upgrade_notice', array( $this, 'afr_dismiss_upgrade_notice' ) );
				//add_action( 'wp_ajax_nopriv_afr_dismiss_upgrade_notice', array( $this, 'afr_dismiss_upgrade_notice' ) );

			} else {
				add_action( 'admin_notices', array( $this, 'wc_deactivated' ) );
			}
		}

		public function wc_deactivated() {
			echo '<div class="error"><p>' . sprintf( __( 'WooCommerce Advance Flate Rate Shipping requires %s to be installed and active.', 'woocommerce-shipping-afr' ), '<a href="https://woocommerce.com" target="_blank">WooCommerce</a>' ) . '</p></div>';
		}
		public function wc_incompitable_version() {
			echo '<div class="error"><p>' . sprintf( __( 'WooCommerce Advance Flate Rate Shipping requires %s version %s to be installed and active.', 'woocommerce-shipping-afr' ), '<a href="https://woocommerce.com" target="_blank">WooCommerce</a>',WC_SHIPPING_AFR_MINIMUM_WC_VERSION ) . '</p></div>';
		}
		public function wp_incompitable_version() {
			echo '<div class="error"><p>' . sprintf( __( 'WooCommerce Advance Flate Rate Shipping requires %s version %s or greater to be installed and active.', 'woocommerce-shipping-afr' ), '<a href="https://wordpress.org" target="_blank">WordPress</a>',WP_VERSION ) . '</p></div>';
		}

		public function maybe_install() {
			if ( version_compare( WP_VERSION, WC_SHIPPING_AFR_MINIMUM_WP_VERSION, '>=' )  ) 
			{
				if(version_compare( WC_VERSION, WC_SHIPPING_AFR_MINIMUM_WC_VERSION, '>=' ) )
				{
					$this->install();
				}
				else
				{
					add_action( 'admin_notices', array( $this, 'wc_incompitable_version' ) );
				}
			}
			else
			{
				add_action( 'admin_notices', array( $this, 'wp_incompitable_version' ) );
			}

			return true;
		}

		public function install() {
			// get all saved settings and cache it
			$afr_settings = get_option( 'woocommerce_afr_settings', false );

			// settings exists
			if ( $afr_settings ) {
				global $wpdb;

				// unset un-needed settings
				unset( $afr_settings['enabled'] );

				// add it to the "rest of the world" zone when no AFR.
				//if ( ! $this->is_zone_has_afr( 0 ) ) {
				//	$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}woocommerce_shipping_zone_methods ( zone_id, method_id, method_order, is_enabled ) VALUES ( %d, %s, %d, %d )", 0, 'afr', 1, 1 ) );
					// add settings to the newly created instance to options table
				//	$instance = $wpdb->insert_id;
				//	add_option( 'woocommerce_afr_' . $instance . '_settings', $afr_settings );
				//}

				update_option( 'woocommerce_afr_show_upgrade_notice', 'yes' );
			}
		}

		/**
		 * Helper method to check whether given zone_id has AFR method instance.
		 */
		public function is_zone_has_afr( $zone_id ) {
			global $wpdb;

			return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(instance_id) FROM {$wpdb->prefix}woocommerce_shipping_zone_methods WHERE method_id = 'afr' AND zone_id = %d", $zone_id ) ) > 0;
		}
		/**
		 * Localisation
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'woocommerce-shipping-aft', false, basename( dirname( __FILE__ ) ) . '/languages' );
		}

		/**
		* Plugin page links.
		*/
		public function plugin_links( $links ) {
			$plugin_links = array(
				'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=shipping&section=afr' ) . '">' . __( 'Settings', 'woocommerce-shipping-afr' ) . '</a>',
				'<a href="https://support.datumsquare.com/">' . __( 'Support', 'woocommerce-shipping-afr' ) . '</a>',
				'<a href="https://docs.datumsquare.com/document/afr/">' . __( 'Docs', 'woocommerce-shipping-aft' ) . '</a>',
			);

			return array_merge( $plugin_links, $links );
		}


		/**
		 * woocommerce_init_shipping_table_rate function.
		 */
		public function includes() {
			include_once( dirname( __FILE__ ) . '/includes/class-wc-shipping-afr.php' );
		}

		/**
		 * Add AFR shipping method to WC
		 */
		public function add_method( $methods ) {
			$methods['afr'] = 'WC_Shipping_AFR';
			

			return $methods;
		}

		/**
		 * Show the user a notice for plugin updates
		 */
		public function upgrade_notice() {
			$show_notice = get_option( 'woocommerce_afr_show_upgrade_notice' );

			if ( 'yes' !== $show_notice ) {
				return;
			}

			$query_args = array( 'page' => 'wc-settings', 'tab' => 'shipping' );
			$zones_admin_url = add_query_arg( $query_args, get_admin_url() . 'admin.php' );
			?>
			<div class="notice notice-success is-dismissible wc-afr-notice">
				<p><?php echo sprintf( __( 'AFR now supports shipping zones. The zone settings were added to a new AFR method on the "Rest of the World" Zone. See the zones %1$shere%2$s ', 'woocommerce-shipping-afr' ),'<a href="' . $zones_admin_url . '">','</a>' ); ?></p>
			</div>

			<script type="application/javascript">
				jQuery( '.notice.wc-afr-notice' ).on( 'click', '.notice-dismiss', function () {
					wp.ajax.post('afr_dismiss_upgrade_notice');
				});
			</script>
			<?php
		}

		/**
		 * Turn of the dismisable upgrade notice.
		 */
		public function afr_dismiss_upgrade_notice() {
			update_option( 'woocommerce_afr_show_upgrade_notice', 'no' );
		}
	}

	add_action( 'plugins_loaded' , array( 'WC_Shipping_AFR_Init', 'get_instance' ), 0 );


}