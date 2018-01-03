# Advance Flat Rate Shipping For WooCommerce
== Description ==

Advance Flat Rate Shipping For WooCommerce provides ability to set different flat rate shipping cost for different cities in any country. It also allows you to set flate rate shipping costs for different Shipping Classes and Weights for each city.  
= Shipping Classes = 
By default it supports shipping classes based flat rates for cities and you set two different calculation types:
1) per_item 
	Charge shipping for each shipping class individually.
2) per_order
	Most Expensive: Charge shipping for most expensive shipping class from cart items.
	Most Cheap: Charge shipping for most cheap shipping class from cart items.

= Weight Based =
For weight based shipping you need to enable this from settings pages, and you will see a weight clasification section when you do so. You can create any number for weight classes with customized min and max weight limit. Here you will have two calculation types aswell but works differently as compared to Shipping Classes:
1) per_item
	 Charge shipping for each item in the cart individually.
2) per_order
	Charge shipping for comulative weight of all products in the cart

== Installation ==

1. Upload the `Advance Flat Rate Shipping For WooCommerce` folder to your plugins directory (e.g. `/wp-content/plugins/`)
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Update shipping settings in WooCommerce->Settings->Shipping

1, 2, 3: You're done!

== Screenshots ==

1. After activation, you will see a new shipping mathod in the dropdown on WooCommerce -> Settings -> Shipping -> Edit any zone
2. Calculation types
3. Pricing table
4. Enable weight based shipping and manage weight classifications 

== Changelog ==

= 1.1.0 =
*Release Date - 03 January, 2018*

* Weight Based shipping.
* Added Quantity factor in shipping classes based calculation.
* WP notice fix.

= 1.0.1 =
*Release Date - 27 December, 2017*

* Added Comments.
* Enable/disable checkbox fix.

= 1.0.0 =
*Release Date - 22 December, 2017*

* First Release

