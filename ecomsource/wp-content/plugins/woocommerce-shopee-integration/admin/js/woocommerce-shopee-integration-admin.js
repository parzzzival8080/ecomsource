(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	 var ajaxUrl   = ced_shopee_admin_obj.ajax_url;
	 var ajaxNonce = ced_shopee_admin_obj.ajax_nonce;
	 var shop_id   = ced_shopee_admin_obj.shop_id;
	 var parsed_response;

	 /*-------------------Image swap on hover-----------------------*/

	$( document ).on(
		'hover',
		".ced_shopee_thumbnail li img",
		function(){
				$( '#preview-image img' ).attr( 'src',$( this ).attr( 'src' ).replace( ' ', '' ) );
				var cedimgSwap = [];
				var imgUrl     = "";
				$( ".ced_shopee_thumbnail li img" ).each(
					function(){
						imgUrl = this.src.replace( ' ', '' );
						cedimgSwap.push( imgUrl );
					}
				);
		}
	);

	$( document ).on(
		'click' ,
		'.ced_shopee_navigation' ,
		function() {
			$( '.ced_shopee_loader' ).show();
			var page_no = $( this ).data( 'page' );
			$( '.ced_shopee_metakey_body' ).hide();
			window.setTimeout( function() {$( '.ced_shopee_loader' ).hide()},500 );
			$( document ).find( '.ced_shopee_metakey_list_' + page_no ).show();
		}
	);

	 /*-----------------------Pop Up on Clicking Add Account In Account Section---------------*/

	$( document ).on(
		'click',
		'.ced_shopee_add_account_button',
		function(){

				$( document ).find( '.ced_shopee_add_account_popup_main_wrapper' ).addClass( 'show' );

		}
	);
	$( document ).on(
		'click',
		'.ced_shopee_add_account_popup_close',
		function(){

				$( document ).find( '.ced_shopee_add_account_popup_main_wrapper' ).removeClass( 'show' );

		}
	);

	 /*------------------------Pop UP for Preview Action in Products Section----------------------*/

	$( document ).on(
		'click',
		'#ced_shopee_preview',
		function(){

				var product_id = $( this ).attr( "data" );
				var shopid     = $( this ).attr( "data-shopid" );
				$.ajax(
					{

						url:ajaxUrl,
						data:{
							ajax_nonce:ajaxNonce,
							prodId:product_id,
							shopid:shopid,
							action:'ced_shopee_preview_product_detail'
						},
						type:'POST',
						success:function(response)
					{
							  $( ".ced_shopee_preview_product_popup_main_wrapper" ).html( response );
							  $( document ).find( '.ced_shopee_preview_product_popup_main_wrapper' ).addClass( 'show' );
						}

					}
				);

		}
	);
	$( document ).on(
		'click',
		'.ced_shopee_profiles_on_pop_up',
		function(){

				var product_id = $( this ).attr( "data-product_id" );
				var shopid     = $( this ).attr( "data-shopid" );
				$.ajax(
					{

						url:ajaxUrl,
						data:{
							ajax_nonce:ajaxNonce,
							prodId:product_id,
							shopid:shopid,
							action:'ced_shopee_profiles_on_pop_up'
						},
						type:'POST',
						success:function(response)
					{
							  $( ".ced_shopee_preview_product_popup_main_wrapper" ).html( response );
							  $( document ).find( '.ced_shopee_preview_product_popup_main_wrapper' ).addClass( 'show' );
						}

					}
				);

		}
	);
	$( document ).on(
		'click',
		'#ced_shopee_save_profile_through_popup',
		function(){
				$( '.ced_shopee_loader' ).show();
				var product_id = $( this ).attr( "data-prodId" );
				var shopid     = $( this ).attr( "data-shopid" );
				var profile_id = $( ".ced_shopee_profile_selected_on_popup" ).val();
				$.ajax(
					{

						url:ajaxUrl,
						data:{
							ajax_nonce:ajaxNonce,
							prodId:product_id,
							shopid:shopid,
							profile_id:profile_id,
							action:'ced_shopee_save_profile_through_popup'
						},
						type:'POST',
						success:function(response)
					{
							response = jQuery.trim( response );
							if (response == "null") {
								$( '.ced_shopee_loader' ).hide();
								$( document ).find( '.ced_shopee_preview_product_popup_main_wrapper' ).removeClass( 'show' );
								var notice = "";
								notice    += "<div class='notice notice-error'><p>No Profile Selected.</p></div>";
								$( ".success-admin-notices" ).append( notice );
								window.setTimeout( function(){window.location.reload()}, 2500 );

							} else {
								$( '.ced_shopee_loader' ).hide();
								location.reload( true );
							}
						}

					}
				);

		}
	);

	$( document ).on(
		'click',
		'.ced_shopee_preview_product_popup_close',
		function(){

				$( document ).find( '.ced_shopee_preview_product_popup_main_wrapper' ).removeClass( 'show' );

		}
	);

		$( document ).on(
			'click',
			'.ced_shopee_profile_popup_close',
			function(){

					$( document ).find( '.ced_shopee_preview_product_popup_main_wrapper' ).removeClass( 'show' );

			}
		);
	 /*-----------------------Button On Pop Up To Authorize account--------------------------------*/

	$( document ).on(
		'click',
		'#ced_shopee_authorise_account_button',
		function(){

				$.ajax(
					{
						url : ajaxUrl,
						data : {
							ajax_nonce : ajaxNonce,
							action : 'ced_shopee_authorise_account',
						},
						type : 'POST',
						success: function(response)
					{
							 var response         = jQuery.parseJSON( response );
							 window.location.href = response.apiUrl;
						}
					}
				);

		}
	);

	 /*---------------------------------Updating account status-------------------------------*/

	$( document ).on(
		'click',
		"#ced_shopee_update_account_status",
		function(){

				var status = $( "#ced_shopee_account_status" ).val();
				var id     = $( this ).attr( "data-id" );
				var url    = window.location.href;
				$.ajax(
					{
						url : ajaxUrl,
						data : {
							ajax_nonce : ajaxNonce,
							action : 'ced_shopee_change_account_status',
							status : status,
							id : id
						},
						type : 'POST',
						success: function(response)
					{
							 var response         = jQuery.parseJSON( response );
							 window.location.href = url;
						}
					}
				);

		}
	);

	 /*----------------------------------------Select Boxes Category Mapping--------------------*/

		$( document ).on(
			'change',
			'.ced_shopee_select_store_category_checkbox',
			function(){
					var store_category_id = $( this ).attr( 'data-categoryID' );
				if ( $( this ).is( ':checked' ) ) {
					  $( '#ced_shopee_categories_' + store_category_id ).show( 'slow' );
				} else {
					$( '#ced_shopee_categories_' + store_category_id ).hide( 'slow' );
				}
			}
		);

	$( document ).on(
		'change',
		'.ced_shopee_global_select_box',
		function(){

				var fieldId        = $( this ).attr( 'data-fieldId' );
				var selected_value = $( this ).val();
			if ( selected_value == "default" ) {

				  $( "#" + fieldId ).removeAttr( 'disabled' );
			}

		}
	);

	$( document ).on(
		'change',
		'.ced_shopee_select_category',
		function(){

				var store_category_id             = $( this ).attr( 'data-storeCategoryID' );
				var shopee_store_id               = $( this ).attr( 'data-shopeeStoreId' );
				var selected_shopee_category_id   = $( this ).val();
				var selected_shopee_category_name = $( this ).find( "option:selected" ).text();
				var level                         = $( this ).attr( 'data-level' );

			if ( level != '8' ) {
				  $( '.ced_shopee_loader' ).show();
				$.ajax(
					{
						url : ajaxUrl,
						data : {
							ajax_nonce : ajaxNonce,
							action : 'ced_shopee_fetch_next_level_category',
							level : level,
							name : selected_shopee_category_name,
							id : selected_shopee_category_id,
							store_id : store_category_id,
							shopee_store_id : shopee_store_id,
						},
						type : 'POST',
						success: function(response)
						{
							  response = jQuery.parseJSON( response );
							  $( '.ced_shopee_loader' ).hide();
							if ( response != 'No-Sublevel' ) {
								for (var i = 1; i < 8; i++) {
									$( '#ced_shopee_categories_' + store_category_id ).find( '.ced_shopee_level' + (parseInt( level ) + i) + '_category' ).closest( "td" ).remove();
								}
								if (response != 0 && selected_shopee_category_id != "" ) {
									$( '#ced_shopee_categories_' + store_category_id ).append( response );
								}
							} else {
								$( '#ced_shopee_categories_' + store_category_id ).find( '.ced_shopee_level' + (parseInt( level ) + 1) + '_category' ).remove();
							}
						}
						}
				);
			}

		}
	);
	 /*-----------------------------------Add custom profile section ----------------------------------*/

	$( document ).on(
		'change',
		'.ced_shopee_select_category_on_add_profile',
		function(){

				var shopee_store_id               = $( this ).attr( 'data-shopeeStoreId' );
				var selected_shopee_category_id   = $( this ).val();
				var selected_shopee_category_name = $( this ).find( "option:selected" ).text();
				var level                         = $( this ).attr( 'data-level' );

			if ( level != '8' ) {
				  $( '.ced_shopee_loader' ).show();
				$.ajax(
					{
						url : ajaxUrl,
						data : {
							ajax_nonce : ajaxNonce,
							action : 'ced_shopee_fetch_next_level_category_add_profile',
							level : level,
							name : selected_shopee_category_name,
							id : selected_shopee_category_id,
							shopee_store_id : shopee_store_id
						},
						type : 'POST',
						success: function(response)
						{
							  response = jQuery.parseJSON( response );
							  $( '.ced_shopee_loader' ).hide();
							if ( response != 'No-Sublevel' ) {
								for (var i = 1; i < 10; i++) {
									$( '#ced_shopee_categories_in_profile' ).find( '.ced_shopee_level' + (parseInt( level ) + i) + '_category' ).remove();
								}
								if (response != 0 && selected_shopee_category_id != "") {
									$( '#ced_shopee_categories_in_profile' ).append( response );
								}
							} else {
								$( '#ced_shopee_categories_in_profile' ).find( '.ced_shopee_level' + (parseInt( level ) + 1) + '_category' ).remove();
							}
						}
						}
				);
			}

		}
	);

	 /*----------------------Fetching Logistics--------------------------------------*/

	$( document ).on(
		'click',
		'#ced_shopee_fetch_logistics',
		function(){

				var id = $( this ).attr( "data-id" );
				$( '.ced_shopee_loader' ).show();
				$.ajax(
					{
						url : ajaxUrl,
						data : {
							ajax_nonce : ajaxNonce,
							action : 'ced_shopee_fetch_logistics',
							shopid:id
						},
						type : 'POST',
						success: function(response)
					{
							 $( '.ced_shopee_loader' ).hide();
							 var response  = jQuery.parseJSON( response );
							 var response1 = jQuery.trim( response.message );
							if (response1 == "Shop is Not Active") {
								var notice = "";
								notice    += "<div class='notice notice-error'><p>Currently Shop is not Active . Please activate your Shop in order to fetch logistics.</p></div>";
								$( ".success-admin-notices" ).append( notice );
								return;
							} else if (response.status == '200') {
								$( document ).find( '#ced_shopee_logistics_column' ).html( response.logisticHtml );
								location.reload();
							}
						}
					}
				);

		}
	);

	 /*-----------------------------Refreshing Categories----------------------------------------*/

	$( document ).on(
		'click',
		'#ced_shopee_category_refresh_button',
		function()
		{
				var store_id = $( this ).attr( 'data-shop_id' );
				$( '.ced_shopee_loader' ).show();
				$.ajax(
					{
						url : ajaxUrl,
						data : {
							ajax_nonce : ajaxNonce,
							action : 'ced_shopee_category_refresh_button',
							shopid:store_id
						},
						type : 'POST',
						success: function(response)
					{
							 $( '.ced_shopee_loader' ).hide();
							 var response  = jQuery.parseJSON( response );
							 var response1 = jQuery.trim( response.message );
							if (response1 == "Shop is Not Active") {
								var notice = "";
								notice    += "<div class='notice notice-error'><p>Currently Shop is not Active . Please activate your Shop in order to refresh categories.</p></div>";
								$( ".success-admin-notices" ).append( notice );
								return;
							} else if (response1 == "Unable To Fetch Categories") {
								var notice = "";
								notice    += "<div class='notice notice-error'><p>Unable To Fetch Categories</p></div>";
								$( ".success-admin-notices" ).append( notice );
								window.setTimeout( function(){window.location.reload()}, 2000 );
							} else {
								var notice = "";
								notice    += "<div class='notice notice-success'><p>Categories Updated Successfully</p></div>";
								$( ".success-admin-notices" ).append( notice );
								window.setTimeout( function(){window.location.reload()}, 2000 );
							}

						}
					}
				);
		}
	);

	 /*---------------------------------Fetch Orders------------------------------------------------*/

	$( document ).on(
		'click',
		'#ced_shopee_fetch_orders',
		function(event)
		{
				event.preventDefault();
				var store_id = $( this ).attr( 'data-id' );
				$( '.ced_shopee_loader' ).show();
				$.ajax(
					{
						url : ajaxUrl,
						data : {
							ajax_nonce : ajaxNonce,
							action : 'ced_shopee_get_orders',
							shopid:store_id
						},
						type : 'POST',
						success: function(response)
					{
							  $( '.ced_shopee_loader' ).hide();
							  var response  = jQuery.parseJSON( response );
							  var response1 = jQuery.trim( response.message );
							if (response1 == "Shop is Not Active") {
								var notice = "";
								notice    += "<div class='notice notice-error'><p>Currently Shop is not Active . Please activate your Shop in order to fetch orders.</p></div>";
								$( ".success-admin-notices" ).append( notice );
								return;
							} else {
								location.reload( true );
							}

						}
					}
				);
		}
	);

	/*-------------------------------------Preview Product Upload-----------------------------------------*/

	$( document ).on(
		'click',
		'.ced_shopee_preview_product_upload_button',
		function()
		{
				$( document ).find( '.ced_shopee_preview_product_popup_main_wrapper' ).removeClass( 'show' );
				var prodid = $( this ).attr( 'data-id' );
				$( '.ced_shopee_loader' ).show();
				$.ajax(
					{
						url : ajaxUrl,
						data : {
							ajax_nonce : ajaxNonce,
							action : 'ced_shopee_upload_by_popup',
							prodid:prodid,
							shopid:shop_id
						},
						type : 'POST',
						success: function(response)
					{
							 $( '.ced_shopee_loader' ).hide();
							 var response = jQuery.parseJSON( response );
							if (response.status == 200) {
								var id     = response.prodid;
								var notice = "";
								notice    += "<div class='notice notice-success'><p>" + response.message + "</p></div>";
								$( "#" + id + "" ).html( '<b class="success_upload_on_shopee">Uploaded</b>' );
								$( ".success-admin-notices" ).append( notice );
							} else if (response.status == 400) {
								var notice = "";
								notice    += "<div class='notice notice-error'><p>" + response.message + "</p></div>";
								$( ".success-admin-notices" ).append( notice );
							}
						}
					}
				);
		}
	);

	 /*------------------------------------Preview Product Update-----------------------------------*/

	$( document ).on(
		'click',
		'.ced_shopee_preview_product_update_button',
		function()
		{
				var prodid = $( this ).attr( 'data-id' );
				$( '.ced_shopee_loader' ).show();
				$.ajax(
					{
						url : ajaxUrl,
						data : {
							ajax_nonce : ajaxNonce,
							action : 'ced_shopee_quick_update_by_action',
							prodid:prodid,
							shopid:shop_id
						},
						type : 'POST',
						success: function(response)
					{
							 $( '.ced_shopee_loader' ).hide();
							 var response = jQuery.parseJSON( response );
							if (response.status == 200) {
								var notice = "";
								notice    += "<div class='notice notice-success'><p>" + response.message + "</p></div>";
								$( ".success-admin-notices" ).append( notice );
							} else if (response.status == 400) {
								var notice = "";
								notice    += "<div class='notice notice-error'><p>" + response.message + "</p></div>";
								$( ".success-admin-notices" ).append( notice );
							}
						}
					}
				);
		}
	);

	$( document ).on(
		'click',
		'#umb_shopee_ack_action',
		function()
		{
				var order_id = jQuery( this ).data( 'order_id' );
				$( '.ced_shopee_loader' ).show();
				$.ajax(
					{
						url : ajaxUrl,
						data : {
							ajax_nonce : ajaxNonce,
							action : 'ced_shopee_acknowledge_order',
							order_id : order_id,
						},
						type : 'POST',
						success: function(response)
					{

							  jQuery( ".ced_shopee_loader" ).hide();
							  var response = jQuery.parseJSON( response );
							if (response.status == "200") {
								window.location.reload();
							} else {
								alert( 'error' );
							}
						}
					}
				);
		}
	);

		$( document ).on(
			'click',
			'#ced_shopee_shipment_submit',
			function()
			{
				var order_id    = jQuery( this ).data( 'order_id' );
				var trackNumber = jQuery( '#umb_shopee_tracking_number' ).val();
				$( '.ced_shopee_loader' ).show();
				$.ajax(
					{
						url : ajaxUrl,
						data : {
							ajax_nonce : ajaxNonce,
							action : 'ced_shopee_ship_order',
							order_id : order_id,
							trackNumber : trackNumber,
						},
						type : 'POST',
						success: function(response)
					{
							jQuery( ".ced_shopee_loader" ).hide();
							var response = jQuery.parseJSON( response );
							if (response.status == "200") {
								window.location.reload();
							} else {
								alert( response.msg );
							}
						}
					}
				);
			}
		);

	 /*----------------------------------Save Category in Category mapping--------------------------*/

	$( document ).on(
		'click',
		'#ced_shopee_save_category_button',
		function(){

				var  shopee_category_array = [];
				var  store_category_array  = [];
				var  shopee_category_name  = [];
				var shopee_store_id        = $( this ).attr( 'data-shopeeStoreID' );

				jQuery( '.ced_shopee_select_store_category_checkbox' ).each(
					function(key) {

						if ( jQuery( this ).is( ':checked' ) ) {
							 var store_category_id = $( this ).attr( 'data-categoryid' );
							 var cat_level         = $( '#ced_shopee_categories_' + store_category_id ).find( "td:last" ).attr( 'data-catlevel' );

							 var selected_shopee_category_id = $( '#ced_shopee_categories_' + store_category_id ).find( '.ced_shopee_level' + cat_level + '_category' ).val();

							if ( selected_shopee_category_id == '' || selected_shopee_category_id == null ) {
								selected_shopee_category_id = $( '#ced_shopee_categories_' + store_category_id ).find( '.ced_shopee_level' + (parseInt( cat_level ) - 1) + '_category' ).val();
							}
							 var category_name = '';
							$( '#ced_shopee_categories_' + store_category_id ).find( 'select' ).each(
								function(key1){
									category_name += $( this ).find( "option:selected" ).text() + ' --> ';
								}
							);
							   var name_len = 0;
							if ( selected_shopee_category_id != '' && selected_shopee_category_id != null ) {
								shopee_category_array.push( selected_shopee_category_id );
								store_category_array.push( store_category_id );

								name_len      = category_name.length;
								category_name = category_name.substring( 0, name_len - 5 );
								category_name = category_name.trim();
								name_len      = category_name.length;
								if ( category_name.lastIndexOf( '--Select--' ) > 0 ) {
									 category_name = category_name.trim();
									 category_name = category_name.replace( '--Select--', '' );
									 name_len      = category_name.length;
									 category_name = category_name.substring( 0, name_len - 5 );
								}
								   name_len = category_name.length;

								   shopee_category_name.push( category_name );
							}
						}
					}
				);

				$( '.ced_shopee_loader' ).show();
				$.ajax(
					{
						url : ajaxUrl,
						data : {
							ajax_nonce : ajaxNonce,
							action : 'ced_shopee_map_categories_to_store',
							shopee_category_array : shopee_category_array,
							store_category_array : store_category_array,
							shopee_category_name : shopee_category_name,
							shopee_store_id : shopee_store_id,
						},
						type : 'POST',
						success: function(response)
					{
							$( '.ced_shopee_loader' ).hide();
							var html = "<div class='notice notice-success'><p>Profile Created Successfully</p></div>";
							$( "#profile_create_message" ).html( html );
							//window.setTimeout( function(){window.location.reload()}, 2000 );

						}
					}
				);

		}
	);

	 /*---------------------------------Bulk Actions in Manage Products-------------------------------------------------*/

	$( document ).on(
		'click',
		'#ced_shopee_bulk_operation',
		function(e){
				e.preventDefault();
				var operation = $( ".bulk-action-selector" ).val();
			if (operation <= 0 ) {
				  var notice = "";
				  notice    += "<div class='notice notice-error'><p>Please Select Operation To Be Performed</p></div>";
				  $( ".success-admin-notices" ).append( notice );
			} else {
				var operation          = $( ".bulk-action-selector" ).val();
				var shopee_products_id = new Array();
				$( '.shopee_products_id:checked' ).each(
					function(){
						shopee_products_id.push( $( this ).val() );
					}
				);
				performBulkAction( shopee_products_id,operation );
			}

		}
	);

	function performBulkAction(shopee_products_id,operation)
		{
		if (shopee_products_id == "") {
			var notice = "";
			notice    += "<div class='notice notice-error'><p>No Products Selected</p></div>";
			$( ".success-admin-notices" ).append( notice );
		}
		var shopee_product_id = shopee_products_id[0];
		$( '.ced_shopee_loader' ).show();
		$.ajax(
			{
				url : ajaxUrl,
				data : {
					ajax_nonce : ajaxNonce,
					action : 'ced_shopee_process_bulk_action',
					operation_to_be_performed : operation,
					id : shopee_product_id,
					shopid:shop_id
				},
				type : 'POST',
				success: function(response)
				{
					$( '.ced_shopee_loader' ).hide();
					var response  = jQuery.parseJSON( response );
					var response1 = jQuery.trim( response.message );
					if (response1 == "Shop is Not Active") {
						var notice = "";
						notice    += "<div class='notice notice-error'><p>Currently Shop is not Active . Please activate your Shop in order to perform operations.</p></div>";
						$( ".success-admin-notices" ).append( notice );
						return;
					} else if (response.status == 200) {
						var id               = response.prodid;
						var Response_message = jQuery.trim( response.message );
						var notice           = "";
						notice              += "<div class='notice notice-success'><p>" + response.message + "</p></div>";
						$( ".success-admin-notices" ).append( notice );
						if (Response_message == 'Product ' + id + ' Deleted Successfully') {
							$( "#" + id + "" ).html( '<b class="not_completed">Not Uploaded</b>' );
							$( "." + id + "" ).remove();
						} else {
							$( "#" + id + "" ).html( '<b class="success_upload_on_shopee">Uploaded</b>' );
						}

						var remainig_products_id = shopee_products_id.splice( 1 );
						if (remainig_products_id == "") {
							return;
						} else {
							performBulkAction( remainig_products_id,operation );
						}

					} else if (response.status == 400) {
						var notice = "";
						notice    += "<div class='notice notice-error'><p>" + response.message + "</p></div>";
						$( ".success-admin-notices" ).append( notice );
						var remainig_products_id = shopee_products_id.splice( 1 );
						if (remainig_products_id == "") {
							return;
						} else {
							performBulkAction( remainig_products_id,operation );
						}

					}
				}
			}
		);
	}

		$( document ).on(
			'change',
			'#ced_shopee_scheduler_info',
			function(){

				if (this.checked) {
					$( ".ced_shopee_scheduler_info" ).css( 'display','contents' );
				} else {
					$( ".ced_shopee_scheduler_info" ).css( 'display','none' );
				}
			}
		);

		$( document ).on(
			'click',
			'.ced_shopee_parent_element',
			function(){
				if ($( this ).find( '.ced_shopee_instruction_icon' ).hasClass( "dashicons-arrow-down-alt2" )) {
					$( this ).find( '.ced_shopee_instruction_icon' ).removeClass( "dashicons-arrow-down-alt2" );
					$( this ).find( '.ced_shopee_instruction_icon' ).addClass( "dashicons-arrow-up-alt2" );
				} else if ($( this ).find( '.ced_shopee_instruction_icon' ).hasClass( "dashicons-arrow-up-alt2" )) {
					$( this ).find( '.ced_shopee_instruction_icon' ).addClass( "dashicons-arrow-down-alt2" );
					$( this ).find( '.ced_shopee_instruction_icon' ).removeClass( "dashicons-arrow-up-alt2" );
				}
				$( this ).next( '.ced_shopee_child_element' ).toggle( 200 );
			}
		);

		$( document ).on(
			'click' ,
			'.ced_shopee_searched_product' ,
			function() {
				$( '.ced_shopee_loader' ).show();
				var post_id = $( this ).data( 'post-id' );
				$.ajax(
					{
						url : ajaxUrl,
						data : {
							ajax_nonce:ajaxNonce,
							post_id : post_id,
							action : 'ced_shopee_get_product_metakeys',
						},
						type:'POST',
						success : function( response ) {
							$( '.ced_shopee_loader' ).hide();
							parsed_response = jQuery.parseJSON( response );
							$( document ).find( '.ced-shopee-search-product-list' ).hide();
							$( ".ced_shopee_render_meta_keys_content" ).html( parsed_response.html );
							$( ".ced_shopee_render_meta_keys_content" ).show();
						}
					}
				);
			}
		);

		$( document ).on(
			'keyup' ,
			'#ced_shopee_search_product_name' ,
			function() {

				var keyword = $( this ).val();
				if ( keyword.length < 3 ) {
					var html = '';
					html    += '<li>Please enter 3 or more characters.</li>';
					$( document ).find( '.ced-shopee-search-product-list' ).html( html );
					$( document ).find( '.ced-shopee-search-product-list' ).show();
					return;
				}
				$.ajax(
					{
						url : ajaxUrl,
						data : {
							ajax_nonce : ajaxNonce,
							keyword : keyword,
							action : 'ced_shopee_search_product_name',
						},
						type:'POST',
						success : function( response ) {
							parsed_response = jQuery.parseJSON( response );
							$( document ).find( '.ced-shopee-search-product-list' ).html( parsed_response.html );
							$( document ).find( '.ced-shopee-search-product-list' ).show();
						}
					}
				);
			}
		);

		$( document ).on(
			'change',
			'.ced_shopee_meta_key',
			function(){
				$( '.ced_shopee_loader' ).show();
				var metakey = $( this ).val();
				var operation;
				if ( $( this ).is( ':checked' ) ) {
					operation = 'store';
				} else {
					operation = 'remove';
				}

				$.ajax(
					{
						url : ajaxUrl,
						data : {
							ajax_nonce : ajaxNonce,
							action : 'ced_shopee_process_metakeys',
							metakey : metakey ,
							operation : operation,
						},
						type : 'POST',
						success: function(response)
					{
							$( '.ced_shopee_loader' ).hide();
						}
					}
				);
			}
		);

})( jQuery );
