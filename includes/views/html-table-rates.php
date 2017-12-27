<?php 
/**
*   Admin => adv_table_rates view
*	
* @since 1.0.0
* @version 1.0.1
*/?>

<tr valign="top" id="packing_options">
	<th scope="row" class="titledesc"><?php _e( 'Shipping Prices', 'woocommerce-shipping-afr' ); ?></th>
	<td class="forminp">
		<style type="text/css">
			.afr_boxes td{
				vertical-align: middle;
				padding: 4px 7px;
			}
			.afr_boxes th {
				padding: 9px 7px;
			}
			.afr_boxes td input {
				margin-right: 4px;
			}
			.afr_boxes .check-column {
				vertical-align: middle;
				text-align: center!important;
				padding: 0 7px;
			}
			.afr_boxes .center{ 
				text-align: center!important;
			}

			.afr_boxes input[type="number"]{
				width:96px !important;
				line-height:20px !important;
			}

			
		</style> <?php //var_dump($this->table_rates);?>
		<table class="afr_boxes widefat">
			<thead>
				<tr>
					<?php 
						$newrec='<tr class="new">\
							<td class="check-column"><input type="checkbox" /></td>\
							<td><input type="text" size="25" name="tr_city_name[\' + size + \']" required  placeholder="City Name" /></td>\
							<td class="center"><input type="number" name="tr_no_class[\' + size + \']" required  placeholder="0.00"/></td>\ ';
						foreach($this->get_def_shipping_classes() as $sclass){ 
							$newrec.='<td class="center"><input type="number"  name="tr_class_'.$sclass->slug.'[\' + size + \']" required placeholder="0.00"/></td>\ '; }
							$newrec.='<td class="center"><input type="checkbox" name="tr_enabled[\' + size + \']" value="on" checked/></td>\
						</tr>';?>

					<th class="check-column"><input type="checkbox" /></th>
					<th><?php _e( 'City', 'woocommerce-shipping-afr' ); ?></th>
					<th class="center"><?php _e( 'Default ('.get_woocommerce_currency_symbol().')', 'woocommerce-shipping-afr' ); ?></th>
					<?php foreach($this->get_def_shipping_classes() as $sclass){?>
						<th class="center"><?php echo $sclass->name.' ('.get_woocommerce_currency_symbol().')'; ?></th>
					<?php }?>
					<th class="center"><?php _e( 'Enabled', 'woocommerce-shipping-fedex' ); ?></th>
				</tr>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th colspan="<?php echo count($this->get_def_shipping_classes())+4;?>">
						<a href="#" class="button plus insert"><?php _e( 'Add City', 'woocommerce-shipping-afr' ); ?></a>
						<a href="#" class="button minus remove"><?php _e( 'Remove selected cities', 'woocommerce-shipping-afr' ); ?></a>
					</th>
				</tr>
			</tfoot>
			<tbody id="rates">

				<tr>
					<td class="check-column"><input type="checkbox" disabled readonly /></td>
					<td><input type="text" size="25" name="tr_city_name[0]"  value="<?php echo $this->table_rates['tr_city_name'][0];?>" readonly required placeholder="City Name" /></td>
					<td class="center"><input type="number"  name="tr_no_class[0]"  value="<?php echo $this->table_rates['tr_no_class'][0];?>" required placeholder="0.00"/></td>
					<?php foreach($this->get_def_shipping_classes() as $sclass){?>
						<td class="center"><input type="number"  name="tr_class_<?php echo $sclass->slug; ?>[0]" value="<?php  echo $this->table_rates['tr_class_'.$sclass->slug][0];?>" required placeholder="0.00"/></td>
					<?php }?>
					<td class="center"><input type="checkbox" checked disabled readonly/></td>
				</tr>


				<?php if ( $this->table_rates['tr_city_name'] ) {
						foreach ( $this->table_rates['tr_city_name'] as $key => $trate ) {
							if ( ! is_numeric( $key ) || $key<1 )
								continue;
							?>
							<tr>
								<td class="check-column"><input type="checkbox" /></td>
								<td><input type="text" size="25" name="tr_city_name[<?php echo $key; ?>]" value="<?php echo $this->table_rates['tr_city_name'][$key];?>" required placeholder="City Name" /></td>
								<td class="center"><input type="number"  name="tr_no_class[<?php echo $key; ?>]" value="<?php echo $this->table_rates['tr_no_class'][$key];?>" required placeholder="0.00"/></td>
								<?php foreach($this->get_def_shipping_classes() as $sclass){?>
									<td class="center"><input type="number"  name="tr_class_<?php echo $sclass->slug; ?>[<?php echo $key; ?>]" value="<?php echo $this->table_rates['tr_class_'.$sclass->slug][$key];?>" required placeholder="0.00"/></td>
								<?php }?>
								<td class="center"><input type="checkbox" name="tr_enabled[<?php echo $key; ?>]" <?php checked( $this->table_rates['tr_enabled'][$key], 'on' ); ?> value="on"/></td>
							</tr>
							<?php
						}
					}?>

			</tbody>
		</table>
		<script type="text/javascript">

			jQuery(window).load(function(){

				jQuery('#woocommerce_afr_packing_method').change(function(){

					if ( jQuery(this).val() == 'box_packing' )
						jQuery('#packing_options').show();
					else
						jQuery('#packing_options').hide();

				}).change();

				jQuery('#woocommerce_afr_freight_enabled').change(function(){

					if ( jQuery(this).is(':checked') ) {

						var $table = jQuery('#woocommerce_afr_freight_enabled').closest('table');

						$table.find('tr:not(:first)').show();

					} else {

						var $table = jQuery('#woocommerce_afr_freight_enabled').closest('table');

						$table.find('tr:not(:first)').hide();
					}

				}).change();

				jQuery('.afr_boxes .insert').click( function() {
					var $tbody = jQuery('.afr_boxes').find('tbody');
					var size = $tbody.find('tr').size();
					var code = '<?php echo $newrec?>';

					$tbody.append( code );

					return false;
				} );

				jQuery('.afr_boxes .remove').click(function() {
					var $tbody = jQuery('.afr_boxes').find('tbody');

					$tbody.find('.check-column input:checked').each(function() {
						jQuery(this).closest('tr').remove();
					});

					return false;
				});

				// Ordering
				jQuery('.afr_services tbody').sortable({
					items:'tr',
					cursor:'move',
					axis:'y',
					handle: '.sort',
					scrollSensitivity:40,
					forcePlaceholderSize: true,
					helper: 'clone',
					opacity: 0.65,
					placeholder: 'wc-metabox-sortable-placeholder',
					start:function(event,ui){
						ui.item.css('baclbsround-color','#f6f6f6');
					},
					stop:function(event,ui){
						ui.item.removeAttr('style');
						afr_services_row_indexes();
					}
				});

				function afr_services_row_indexes() {
					jQuery('.afr_services tbody tr').each(function(index, el){
						jQuery('input.order', el).val( parseInt( jQuery(el).index('.afr_services tr') ) );
					});
				};

			});

		</script>
	</td>
</tr>