<?php 
/**
*   Admin => adv_table_rates view
*	
* @since 1.0.0
* @version 1.1.0
*/?>

<tr valign="top" id="packing_options">
	<th scope="row" class="titledesc"><span class="woocommerce-help-tip" data-tip="<?php if($this->weight_factor): echo 'Enter a cost for each weight class against each city.<br>e.g: 10.00<br><br>Insert<br>0 for free shipping &<br>-1 for defualt shipping.'; else: echo 'Enter a cost for each shipping class against each city.<br>e.g: 10.00 OR 10.00*[qty]<br><br>Use [qty] to consider the quanitity of item in the cart.<br><br>Insert<br>0 for free shipping &<br>-1 for defualt shipping.'; endif;?>"></span> <label><?php _e( 'Shipping Prices', 'woocommerce-shipping-afr' );?></label><br><small><?php _e( (($this->weight_factor)?'Weight Base':'Shipping Classes'), 'woocommerce-shipping-afr' );?></small></th>
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

			.afr_boxes td.center input[type="text"]{
				width:96px !important;
				line-height:20px !important;
			}

			
		</style> 
		<?php 
		$newrec = ''; 
		if($this->weight_factor): ?>
		<table class="afr_boxes widefat">
			<thead>
				<tr>
					<?php 
						$newrec='<tr class="new">\
							<td class="check-column"><input type="checkbox" /></td>\
							<td><input type="text" size="25" name="tr_city_name[\' + size + \']" required  placeholder="City Name" /></td>\
							<td class="center"><input type="text" name="tr_no_class[\' + size + \']" required  placeholder="0.00"/></td>\ ';
						foreach($this->weight_ranges['weight_class'] as $sclass):
							$newrec.='<td class="center"><input type="text"  name="tr_class_'.$this->clean($sclass).'[\' + size + \']" required placeholder="0.00"/></td>\ '; endforeach;
							$newrec.='<td class="center"><input type="checkbox" name="tr_enabled[\' + size + \']" value="on" checked/></td>\
						</tr>';?>

					<th class="check-column"><input type="checkbox" /></th>
					<th><?php _e( 'City', 'woocommerce-shipping-afr' ); ?></th>
					<th class="center"><?php _e( 'Default ('.get_woocommerce_currency_symbol().')', 'woocommerce-shipping-afr' ); ?></th>
					<?php foreach($this->weight_ranges['weight_class'] as $sclass):?>
						<th class="center"><?php echo esc_html($sclass.' ('.get_woocommerce_currency_symbol().')'); ?></th>
					<?php endforeach;?>
					<th class="center"><?php _e( 'Enabled', 'woocommerce-shipping-fedex' ); ?></th>
				</tr>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th colspan="<?php echo count( $this->weight_ranges['weight_class'] )+4;?>">
						<a href="#" class="button plus insert"><?php _e( 'Add City', 'woocommerce-shipping-afr' ); ?></a>
						<a href="#" class="button minus remove"><?php _e( 'Remove selected cities', 'woocommerce-shipping-afr' ); ?></a>
					</th>
				</tr>
			</tfoot>
			<tbody id="rates">

				<tr>
					<td class="check-column"><input type="checkbox" disabled readonly /></td>
					<td><input type="text" size="25" name="tr_city_name[0]"  value="<?php echo esc_html($this->table_rates['tr_city_name'][0]);?>" readonly required placeholder="City Name" /></td>
					<td class="center"><input type="text"  name="tr_no_class[0]"  value="<?php echo esc_html($this->table_rates['tr_no_class'][0]);?>" required placeholder="0.00"/></td>
					<?php foreach($this->weight_ranges['weight_class'] as $sclass):?>
						<td class="center"><input type="text"  name="tr_class_<?php echo $this->clean($sclass); ?>[0]" value="<?php  echo esc_html($this->table_rates['tr_class_'.$this->clean($sclass)][0]);?>" required placeholder="0.00"/></td>
					<?php endforeach;?>
					<td class="center"><input type="checkbox" checked disabled readonly/></td>
				</tr>


				<?php if ( $this->table_rates['tr_city_name'] ) :
						foreach ( $this->table_rates['tr_city_name'] as $key => $trate ) :
							if ( ! is_numeric( $key ) || $key<1 )
								continue;
							?>
							<tr>
								<td class="check-column"><input type="checkbox" /></td>
								<td><input type="text" size="25" name="tr_city_name[<?php echo $key; ?>]" value="<?php echo esc_html($this->table_rates['tr_city_name'][$key]);?>" required placeholder="City Name" /></td>
								<td class="center"><input type="text"  name="tr_no_class[<?php echo $key; ?>]" value="<?php echo @esc_html($this->table_rates['tr_no_class'][$key]);?>" required placeholder="0.00"/></td>
								<?php foreach($this->weight_ranges['weight_class'] as $sclass):?>
									<td class="center"><input type="text"  name="tr_class_<?php echo $this->clean($sclass); ?>[<?php echo $key; ?>]" value="<?php echo @esc_html($this->table_rates['tr_class_'.$this->clean($sclass)][$key]);?>" required placeholder="0.00"/></td>
								<?php endforeach;?>
								<td class="center"><input type="checkbox" name="tr_enabled[<?php echo $key; ?>]" <?php checked( @$this->table_rates['tr_enabled'][$key], 'on' ); ?> value="on"/></td>
							</tr>
							<?php
						endforeach;
					endif;?>
			</tbody>
		</table>
		<?php  else : ?>
		<table class="afr_boxes widefat">
			<thead>
				<tr>
					<?php 
						$newrec='<tr class="new">\
							<td class="check-column"><input type="checkbox" /></td>\
							<td><input type="text" size="25" name="tr_city_name[\' + size + \']" required  placeholder="City Name" /></td>\
							<td class="center"><input type="text" name="tr_no_class[\' + size + \']" required  placeholder="0.00"/></td>\ ';
						foreach($this->get_def_shipping_classes() as $sclass): 
							$newrec.='<td class="center"><input type="text"  name="tr_class_'.esc_html($sclass->slug).'[\' + size + \']" required placeholder="0.00"/></td>\ '; endforeach;
							$newrec.='<td class="center"><input type="checkbox" name="tr_enabled[\' + size + \']" value="on" checked/></td>\
						</tr>';?>

					<th class="check-column"><input type="checkbox" /></th>
					<th><?php _e( 'City', 'woocommerce-shipping-afr' ); ?></th>
					<th class="center"><?php _e( 'Default ('.get_woocommerce_currency_symbol().')', 'woocommerce-shipping-afr' ); ?></th>
					<?php foreach($this->get_def_shipping_classes() as $sclass):?>
						<th class="center"><?php echo esc_html($sclass->name.' ('.get_woocommerce_currency_symbol().')'); ?></th>
					<?php endforeach;?>
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
					<td><input type="text" size="25" name="tr_city_name[0]"  value="<?php echo esc_html($this->table_rates['tr_city_name'][0]);?>" readonly required placeholder="City Name" /></td>
					<td class="center"><input type="text"  name="tr_no_class[0]"  value="<?php echo esc_html($this->table_rates['tr_no_class'][0]);?>" required placeholder="0.00"/></td>
					<?php foreach($this->get_def_shipping_classes() as $sclass){?>
						<td class="center"><input type="text"  name="tr_class_<?php echo esc_html($sclass->slug); ?>[0]" value="<?php  echo esc_html($this->table_rates['tr_class_'.esc_html($sclass->slug)][0]);?>" required placeholder="0.00"/></td>
					<?php }?>
					<td class="center"><input type="checkbox" checked disabled readonly/></td>
				</tr>


				<?php if ( $this->table_rates['tr_city_name'] ):
						foreach ( $this->table_rates['tr_city_name'] as $key => $trate ) :
							if ( ! is_numeric( $key ) || $key<1 )
								continue;
							?>
							<tr>
								<td class="check-column"><input type="checkbox" /></td>
								<td><input type="text" size="25" name="tr_city_name[<?php echo $key; ?>]" value="<?php echo esc_html($this->table_rates['tr_city_name'][$key]);?>" required placeholder="City Name" /></td>
								<td class="center"><input type="text"  name="tr_no_class[<?php echo $key; ?>]" value="<?php echo esc_html($this->table_rates['tr_no_class'][$key]);?>" required placeholder="0.00"/></td>
								<?php foreach($this->get_def_shipping_classes() as $sclass){?>
									<td class="center"><input type="text"  name="tr_class_<?php echo esc_html($sclass->slug); ?>[<?php echo $key; ?>]" value="<?php echo @esc_html($this->table_rates['tr_class_'.esc_html($sclass->slug)][$key]);?>" required placeholder="0.00"/></td>
								<?php }?>
								<td class="center"><input type="checkbox" name="tr_enabled[<?php echo $key; ?>]" <?php checked( $this->table_rates['tr_enabled'][$key], 'on' ); ?> value="on"/></td>
							</tr>
							<?php
						endforeach;
					endif;?>

			</tbody>
		</table>
		<?php endif; ?>

		<script type="text/javascript">

			jQuery(window).load(function(){

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

			});

		</script>
	</td>
</tr>