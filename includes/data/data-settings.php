<?php 
/**
*   Admin => Advance Flat Rate Settings
*	
* @since 1.0.0
* @version 1.1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shipping_class_link = admin_url( 'admin.php?page=wc-settings&tab=shipping&section=classes' );

/**
 * Array of settings
 */
$mysettings['title']      = array(
	'title'           => __( 'Method Title', 'woocommerce-shipping-afr' ),
	'type'            => 'text',
	'description'     => __( 'This controls the title which the user sees during checkout.', 'woocommerce-shipping-afr' ),
	'default'         => __( 'Advance Flat Rate', 'woocommerce-shipping-afr' ),
	'desc_tip'        => true
);
if($this->weight_factor):
	$mysettings['calculation_type'] = array(
		'title'           => __( 'Calculation Type', 'woocommerce-shipping-afr' ),
		'type'            => 'select',
		'default'         => '',
		'class'           => 'calculation_type',
		'options'         => array(
			'per_item'    => __( 'Per Item: Charge shipping for each item individually', 'woocommerce-shipping-afr' ),
			'per_order'   => __( 'Per Order: Charge shipping for comulative weight of all products.', 'woocommerce-shipping-afr' ),
		)
	);
else:
	$mysettings['calculation_type'] = array(
		'title'           => __( 'Calculation Type', 'woocommerce-shipping-afr' ),
		'type'            => 'select',
		'default'         => '',
		'class'           => 'calculation_type',
		'options'         => array(
			'per_item'       => __( 'Per Class: Charge shipping for each shipping class individually', 'woocommerce-shipping-afr' ),
			'per_order_max'  => __( 'Per Order: Charge shipping for the most expensive shipping class', 'woocommerce-shipping-afr' ),
			'per_order_min'  => __( 'Per Order: Charge shipping for the most cheap shipping class', 'woocommerce-shipping-afr' ),
		)
	);
endif;

$mysettings['table_rates']['type'] = 'adv_table_rates';


return $mysettings;
