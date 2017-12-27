<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
* WC_Shipping_AFR class.
*
* @inherits  WC_Shipping_Method
* @since 1.0.0
* @version 1.0.1
*/
class WC_Shipping_AFR extends WC_Shipping_Method {

	/**
	* Shipping class defined in wooc
	*
	* @access private
	* @since 1.0.0
	* @var array shipping classes
	*/
	private $shipping_class;

	/**
	* Constructor
	*
	* @access public
	* @since 1.0.0
	* @version 1.0.1
	* @param $instance_id of shipping method
	*/
	public function __construct( $instance_id = 0 ) {
		$this->id                               = 'afr';
		$this->instance_id                      = absint( $instance_id );
		$this->method_title                     = __( 'Advance Flat Rate', 'woocommerce-shipping-afr' );
		$this->method_description               = __( 'The AFR shipping extension allows you to manage shipping price per city and shipping classes.', 'woocommerce-shipping-afr' );
		$this->supports                         = array('shipping-zones','instance-settings','settings',);
		$this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
        $this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : $this->method_title;


		$my_wc_shipping = new WC_Shipping();
		$this->shipping_class = $my_wc_shipping->get_shipping_classes();

		$this->init();

	}
	/**
	* get shipping classes.
	*
	* @access public
	* @since 1.0.0
	* @param
	*/
	public function get_def_shipping_classes() {
		return $this->shipping_class;
	}
	/**
	* init function.
	*
	* @access public
	* @since 1.0.0
	* @param
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
	*
	* @access public
	* @since 1.0.0
	* @version 1.0.1
	* @param
	*/
	public function init_form_fields() {
		$this->instance_form_fields = include( dirname( __FILE__ ) . '/data/data-settings.php' );
		
		$this->form_fields = array(
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
	*
	* @access public
	* @since 1.0.0
	* @version 1.0.1
	* @param
	*/
	private function set_settings() {

		$this->debug                      = ( ( $bool = $this->get_option( 'debug' ) ) && $bool === 'yes' );
		$this->table_rates                      = $this->get_option( 'table_rates', array( ));
		$this->calculation_type                      = $this->get_option( 'calculation_type', 'per_item');

		if(!isset($this->table_rates['tr_city_name'][0]))
			$this->table_rates['tr_city_name'][0]='Default';
		if(!isset($this->table_rates['tr_no_class'][0]))
			$this->table_rates['tr_no_class'][0]='';
		if(!isset($this->table_rates['tr_enabled'][0]))
			$this->table_rates['tr_enabled'][0]='on';

		foreach($this->get_def_shipping_classes() as $sclass){
			if(!isset($this->table_rates['tr_class_'.$sclass->slug][0]))
				$this->table_rates['tr_class_'.$sclass->slug][0]='';
		}
	}

	

	/**
	* Process settings on save
	*
	* @access public
	* @since 1.0.0
	* @param
	*/
	public function process_admin_options() {
		parent::process_admin_options();

		$this->set_settings();
	}

	/**
	* Load admin scripts
	*
	* @access public
	* @since 1.0.0
	* @param
	*/
	public function load_admin_scripts() {
		//wp_enqueue_script( 'jquery-ui-sortable' );
	}

	/**
	* Output a message or error
	*
	* @access public
	* @since 1.0.0
	* @param $message text and $type of message like notice, warning, error
	*/
	public function debug_messages( $message, $type = 'notice' ) {

		if ( $this->debug || ( current_user_can( 'manage_options' ) && 'error' == $type ) ) {
			wc_add_notice( $message, $type );
		}

	}

	
	/**
	* generate_adv_table_rates_html function.
	*
	* @access public
	* @since 1.0.0
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
	* @access public
	* @since 1.0.0
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
		return $my_table_rates;
	}

	
	/**
	* is available function.
	*
	* @access public
	* @since 1.0.0
	* @param
	*/
	public function is_available( $package ) {
		if ( empty( $package['destination']['country']) || empty($package['destination']['city'] ) ) {
			return false;
		}

		return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', true, $package );
	}


	/**
	* Calculate Shipping
	*
	* @access public
	* @since 1.0.0
	* @version 1.0.1
	* @param $package product deatils, shipping details
	* @return shipping price
	*/
	public function calculate_shipping( $package = array() ) {
		
		if ( empty($package['destination']['country']) || empty($package['destination']['city'] ) ) {
			return false;
		}
	
		$this->package = $package;
	
		$cityIndex=0;

		foreach($this->table_rates['tr_city_name'] as $citykey => $cityname)
		{
			if( strtolower($cityname) == strtolower($package['destination']['city']) )
			{
				if(isset($this->table_rates['tr_enabled'][$citykey]) && $this->table_rates['tr_enabled'][$citykey]=='on')
				{
					$cityIndex=$citykey;
				}
				break;
			}
		}

		$allClassPrice=0;
		$minClassPrice=0;
		$maxClassPrice=0;
		$avgClassPrice=0;

		$sclasses_index = 0;

		$all_shipping_class_found = array();

		foreach ( $package['contents'] as $item_id => $values ) 
		{
			$cart_item_shipping_class = $values['data']->get_shipping_class();
			$all_shipping_class_found[] = $cart_item_shipping_class;

			$priceForClass = 0;

			if(!empty($cart_item_shipping_class) 
				&& isset($this->table_rates['tr_class_'.$cart_item_shipping_class][$cityIndex]) 
				&& $this->table_rates['tr_class_'.$cart_item_shipping_class][$cityIndex]>-1)
			{
				$priceForClass = $this->table_rates['tr_class_'.$cart_item_shipping_class][$cityIndex];
			}
			else if(isset($this->table_rates['tr_no_class'][$cityIndex]) && $this->table_rates['tr_no_class'][$cityIndex]>-1)
			{
				$priceForClass = $this->table_rates['tr_no_class'][$cityIndex];
			}
			else
			{
				$priceForClass = 0;	
			}

			$allClassPrice += $priceForClass;

			if($sclasses_index==0)
			{
				$minPriceClass=$priceForClass;
				$maxPriceClass=$priceForClass;
			}
			else
			{
				if($priceForClass<$minPriceClass)
					$minPriceClass = $priceForClass;
				if($priceForClass>$maxPriceClass)
					$maxPriceClass = $priceForClass;
			}

			$sclasses_index++;
		}

		$avgClassPrice = round($allClassPrice / $sclasses_index,2);

		$final_calculated_price=0;

		if($this->calculation_type == 'per_item')
		{
			$final_calculated_price=$allClassPrice;
		}
		else if($this->calculation_type == 'per_order_max')
		{
			$final_calculated_price=$maxPriceClass;
		}
		else if($this->calculation_type == 'per_order_min')
		{
			$final_calculated_price=$minPriceClass;			
		}
		else 
		{//per_order_avg
			$final_calculated_price=$avgClassPrice;
		}

		$this->debug_messages( __( 'AFR debug mode is on - to hide these messages, turn debug mode off in the settings.', 'woocommerce-shipping-afr' ) );
		$this->debug_messages( __( 'Shipping Class: '.implode(', ', $all_shipping_class_found), 'woocommerce-shipping-afr' ) );
		$this->debug_messages( __( 'Package Details: '.json_encode($package), 'woocommerce-shipping-afr' ) );

		$mrate = array(
	        'id' => $this->id,
	        'label' => $this->title,
	        'cost' => $final_calculated_price
	    );
	     
	    $this->add_rate( $mrate );

	}
}
