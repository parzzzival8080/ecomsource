(function($) {
	"use strict";
	var author_target = $( '#author_listing' );
	$( '.author-filter > a' ).on( 'click', function(e){
		if( $(this).hasClass( 'active' ) ){
			return;
		}
		$(this).addClass('active').siblings().removeClass('active');
		var character = $(this).data( 'character' );
		author_target.addClass( 'loading' );
		$.ajax({
			url: sw_author_frontend.ajaxurl,				
			type: 'POST',
			data: {
				action: 'sw_author_filter',
				character: character,
			},
			success(result,status,xhr){
				author_target.html( result );
				
				author_target.removeClass( 'loading' );
			}
		});
		e.preventDefault();
	});

	if( $('body').hasClass( 'single-product' ) ){
		$('.woocommerce-product-gallery__wrapper').append( '<button data-type="preview" class="book-preview" data-id="'+ sw_author_frontend.id +'">'+ sw_author_frontend.preview_text +'</button>' );
	}
	
	$(document).on( 'click', '.book-preview', function(){
		$( '.sw-quickview-bottom' ).addClass( 'product-preview-popup show loading' );
		var attach_id = $(this).data( 'id' );
		var data = {
			action: 'sw_product_preview',
			attach_id: attach_id,
		};
		jQuery.post(sw_author_frontend.ajaxurl, data, function(response) {
			$('.sw-quickview-bottom').find( '.quickview-inner' ).append( response );
			$( '.sw-quickview-bottom' ).removeClass( 'loading' );
			$.getScript(sw_author_frontend.wc_quantity);
		});
	});
	$(document).on('click','.quickview-close', function(){
		$('.sw-quickview-bottom').removeClass('product-preview-popup');
	});
	$('.sw-quickview-bottom .quickview-inner').css( 'height', ( $(window).height() - 100 ) );
	$(window).on( 'resize', function(){
		$('.sw-quickview-bottom .quickview-inner').css( 'height', ( $(window).height() - 100 ) );
	});
})(jQuery);