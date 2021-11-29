<div class="ced_shopee_heading">
	<div class="ced_shopee_render_meta_keys_wrapper ced_shopee_global_wrap">
		<div class="ced_shopee_parent_element">
			<h2>
				<label class="basic_heading ced_shopee_render_meta_keys_toggle"><?php esc_html_e( 'METAKEYS AND ATTRIBUTES LIST', 'shopee-woocommerce-integration' ); ?></label>
				<span class="dashicons dashicons-arrow-down-alt2 ced_shopee_instruction_icon"></span>
			</h2>
		</div>
		<div class="ced_shopee_child_element">
			<table class="wp-list-table widefat fixed striped">
				<tr>
					<td><label>Search for the product by its title</label></td>
					<td colspan="2"><input type="text" name="" id="ced_shopee_search_product_name">
						<ul class="ced-shopee-search-product-list">
						</ul>
					</td>
				</tr>
			</table>
			<div class="ced_shopee_render_meta_keys_content">
				<?php
				$meta_keys_to_be_displayed = get_option( 'ced_shopee_metakeys_to_be_displayed', array() );
				$added_meta_keys           = get_option( 'ced_shopee_selected_metakeys', array() );
				$metakey_html              = ced_shopee_render_html( $meta_keys_to_be_displayed, $added_meta_keys );
				print_r( $metakey_html );
				?>
			</div>
		</div>
	</div>
</div>
