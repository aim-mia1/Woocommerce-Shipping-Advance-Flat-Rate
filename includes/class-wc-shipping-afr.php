<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Shipping_AFR class.
 *
 * @extends WC_Shipping_Method
 */
class WC_Shipping_AFR extends WC_Shipping_Method {
	private $default_boxes;
	private $found_rates;
	private $services;
	
	private $shipping_class;

	/**
	 * Constructor
	 */
	public function __construct( $instance_id = 0 ) {
		$this->id                               = 'afr';
		$this->instance_id                      = absint( $instance_id );
		$this->method_title                     = __( 'Advance Flat Rate', 'woocommerce-shipping-afr' );
		$this->method_description               = __( 'The AFR shipping extension allows you to manage shipping price per city and shipping classes.', 'woocommerce-shipping-afr' );
		$this->supports                         = array('shipping-zones','instance-settings','settings',);

		$my_wc_shipping = new WC_Shipping();
		$this->shipping_class = $my_wc_shipping->get_shipping_classes();

		$this->init();
	}

	public function get_def_shipping_classes() {
		return $this->shipping_class;
	}
	/**
	 * init function.
	 */
	private function init() {
		// Load the settings.
		$this->init_form_fields();
		$this->set_settings();

		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );
	}

	/**
	 * init_form_fields function.
	 */
	public function init_form_fields() {
		$this->instance_form_fields = include( dirname( __FILE__ ) . '/data/data-settings.php' );
		
		$this->form_fields = array(
		    'city_view'      => array(
				'title'           => __( 'City Field', 'woocommerce-shipping-afr' ),
				'label'           => __( 'Enbale Selectable City', 'woocommerce-shipping-afr' ),
				'type'            => 'Checkbox',
				'default'         => 'no',
				
				'description'     => __( 'Check this to see dropdown for cities on checkouyt page. Make sure you have listed all the cities in Zone section.' )
			),
			'debug'      => array(
				'title'           => __( 'Debug Mode', 'woocommerce-shipping-afr' ),
				'label'           => __( 'Enable debug mode', 'woocommerce-shipping-afr' ),
				'type'            => 'checkbox',
				'default'         => 'no',
				
				'description'     => __( 'Enable debug mode to show debugging information on the cart/checkout.', 'woocommerce-shipping-afr' )
			),
		);
	}

	/**
	 * Initialize settings
	 */
	private function set_settings() {
		// Define user set variables
		$this->title                      = $this->get_option( 'title', $this->method_title );
		$this->city_view                      = ( ( $bool = $this->get_option( 'city_view' ) ) && $bool === 'yes' );
		$this->debug                      = ( ( $bool = $this->get_option( 'debug' ) ) && $bool === 'yes' );
		$this->table_rates                      = $this->get_option( 'table_rates', array( ));

		if(!isset($this->table_rates['tr_city_name'][0]))
			$this->table_rates['tr_city_name'][0]='Default';
		if(!isset($this->table_rates['tr_no_class'][0]))
			$this->table_rates['tr_no_class'][0]='0.00';
		if(!isset($this->table_rates['tr_enabled'][0]))
			$this->table_rates['tr_enabled'][0]='on';

		foreach($this->get_def_shipping_classes() as $sclass){
			if(!isset($this->table_rates['tr_class_'.$sclass->slug][0]))
				$this->table_rates['tr_class_'.$sclass->slug][0]='0.00';
		}
	}

	

	/**
	 * Process settings on save
	 */
	public function process_admin_options() {
		parent::process_admin_options();

		$this->set_settings();
	}

	/**
	 * Load admin scripts
	 */
	public function load_admin_scripts() {
		wp_enqueue_script( 'jquery-ui-sortable' );
	}

	/**
	 * Output a message or error
	 */
	public function debug( $message, $type = 'notice' ) {

		if ( $this->debug || ( current_user_can( 'manage_options' ) && 'error' == $type ) ) {
			wc_add_notice( $message, $type );
		}

	}

	
	/**
	 * generate_adv_table_rates_html function.
	 */
	public function generate_adv_table_rates_html() {
		ob_start();
		//echo 'MIA';
		include( 'views/html-table-rates.php' );
		return ob_get_clean();
	}
	/**
	 * validate_box_packing_field function.
	 *
	 * @param mixed $key
	 */
	public function validate_adv_table_rates_field( $key ) {
		$my_table_rates['tr_city_name']       = isset( $_POST['tr_city_name'] ) ? $_POST['tr_city_name'] : array();
		$my_table_rates['tr_no_class']      = isset( $_POST['tr_no_class'] ) ? $_POST['tr_no_class'] : array();

		foreach($this->get_def_shipping_classes() as $defclasses)
		{
			$my_table_rates['tr_class_'.$defclasses->slug] = isset( $_POST['tr_class_'.$defclasses->slug] ) ? $_POST['tr_class_'.$defclasses->slug] : array();
		}

		$my_table_rates['tr_enabled']    = isset( $_POST['tr_enabled'] ) ? $_POST['tr_enabled'] : array();

		//$boxes = array();

		
		return $my_table_rates;
	}
	/**
	 * sort_rates function.
	 */
	public function sort_rates( $a, $b ) {
		if ( $a['sort'] == $b['sort'] ) return 0;
		return ( $a['sort'] < $b['sort'] ) ? -1 : 1;
	}
}
