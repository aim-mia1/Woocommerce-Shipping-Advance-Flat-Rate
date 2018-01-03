<?php 
/**
*   Admin => adv_weight_ranges view
*	
* @since 1.0.0
* @version 1.1.0
*/?>
<tr valign="top" id="weight_ranges">
	<th scope="row" class="titledesc"><?php _e( 'Weight Classification', 'woocommerce-shipping-afr' ); ?></th>
	<td class="forminp">
		<style type="text/css">
			.afr_weight_ranges td{
				vertical-align: middle;
				padding: 4px 7px;
			}
			.afr_weight_ranges th {
				padding: 9px 7px;
			}
			.afr_weight_ranges td input {
				margin-right: 4px;
			}
			.afr_weight_ranges .check-column {
				vertical-align: middle;
				text-align: center!important;
				padding: 0 7px;
			}
			.afr_weight_ranges .center{ 
				text-align: center!important;
			}

			.afr_weight_ranges input[type="number"]{
				width:96px !important;
				line-height:20px !important;
			}
		</style>
		<table class="afr_weight_ranges widefat">
			<thead>
				<tr>
					<th class="check-column"><input type="checkbox" /></th>
					<th><?php _e( 'Weight Class', 'woocommerce-shipping-afr' ); ?></th>
					<th class="center"><?php _e( 'Min Weight ('.$current_weight_unit.')', 'woocommerce-shipping-afr' ); ?></th>
					<th class="center"><?php _e( 'Max Weight ('.$current_weight_unit.')', 'woocommerce-shipping-fedex' ); ?></th>
				</tr>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th colspan="<?php echo count($this->get_def_shipping_classes())+4;?>">
						<a href="#" class="button plus insert"><?php _e( 'Add New Class', 'woocommerce-shipping-afr' ); ?></a>
						<a href="#" class="button minus remove"><?php _e( 'Remove Selected Classes', 'woocommerce-shipping-afr' ); ?></a>
					</th>
				</tr>
			</tfoot>
			<tbody id="rates">

				<?php if ( $this->weight_ranges ) :
						foreach ( $this->weight_ranges['weight_class'] as $key => $trate ) :
							?>
							<tr>
								<td class="check-column"><input type="checkbox" /></td>
								<td><input type="text" size="25" name="weight_class[<?php echo $key; ?>]" value="<?php echo $this->weight_ranges['weight_class'][$key];?>" required placeholder="Weight Class" /></td>
								<td class="center"><input type="number"  name="min_weight[<?php echo $key; ?>]" value="<?php echo $this->weight_ranges['min_weight'][$key];?>" required placeholder="0.00"/></td>
								<td class="center"><input type="number"  name="max_weight[<?php echo $key; ?>]" value="<?php echo $this->weight_ranges['max_weight'][$key];?>" required placeholder="0.00"/></td>
							</tr>
							<?php
						endforeach;
					endif;?>

			</tbody>
		</table>
		<script type="text/javascript">

			jQuery(window).load(function(){

				jQuery('.afr_weight_ranges .insert').click( function() {
					var $tbody = jQuery('.afr_weight_ranges').find('tbody');
					var size = $tbody.find('tr').size();
					var code = '<tr class="new"><td class="check-column"><input type="checkbox" /></td><td><input type="text" size="25" name="weight_class[' + size + ']" required  placeholder="Weight Class" /></td><td class="center"><input type="number" name="min_weight[' + size + ']" required placeholder="0.00"/></td><td class="center"><input type="number" name="max_weight[' + size + ']" required  placeholder="0.00"/></td></tr>';

					$tbody.append( code );

					return false;
				} );

				jQuery('.afr_weight_ranges .remove').click(function() {
					var $tbody = jQuery('.afr_weight_ranges').find('tbody');

					$tbody.find('.check-column input:checked').each(function() {
						jQuery(this).closest('tr').remove();
					});

					return false;
				});

				if(!jQuery('#woocommerce_afr_weight_factor').is(':checked'))
					jQuery('#weight_ranges').toggle();

				jQuery('#woocommerce_afr_weight_factor').click(function() {
					jQuery('#weight_ranges').toggle(); 
					//alert('mia');
					return true;
				});

			});

		</script>
	</td>
</tr>