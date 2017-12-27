<?php 
/**
*   Admin => Advance Flat Rate Settings
*	
* @since 1.0.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shipping_class_link = admin_url( 'admin.php?page=wc-settings&tab=shipping&section=classes' );

/**
 * Array of settings
 */
return array(
	'title'            => array(
		'title'           => __( 'Method Title', 'woocommerce-shipping-afr' ),
		'type'            => 'text',
		'description'     => __( 'This controls the title which the user sees during checkout.', 'woocommerce-shipping-afr' ),
		'default'         => __( 'Advance Flat Rate', 'woocommerce-shipping-afr' ),
		'desc_tip'        => true
	),
	'calculation_type'   => array(
		'title'           => __( 'Calculation Type', 'woocommerce-shipping-afr' ),
		'type'            => 'select',
		'default'         => '',
		'class'           => 'calculation_type',
		'options'         => array(
			'per_item'       => __( 'Per Class: Charge shipping for each shipping class individually', 'woocommerce-shipping-afr' ),
			'per_order_max'    => __( 'Per order: Charge shipping for the most expensive shipping class', 'woocommerce-shipping-afr' ),
			'per_order_min'    => __( 'Per order: Charge shipping for the most cheap shipping class', 'woocommerce-shipping-afr' ),
			'per_order_avg'    => __( 'Per order: Charge shipping for the average of shipping class', 'woocommerce-shipping-afr' ),
		),
	),
	'table_rates'  => array(
		'type'            => 'adv_table_rates'
	)
);
