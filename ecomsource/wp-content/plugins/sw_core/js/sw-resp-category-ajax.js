/*
	** Category Ajax Js
	** Version: 1.0.0
*/
(function ($) {
	$(document).ready(function(){
		/* Category slider ajax */
		$('[data-catload=ajax_res]').on('click', function() {
			sw_tab_res_click_ajax( $(this) );
		});
		
		function sw_tab_res_click_ajax( element ) {			
			var target 		= $( element.attr( 'href' ) );
			var id 				= element.attr( 'href' );
			var length		= element.data( 'length' );
			var ltype     = element.data( 'type' );
			var layout 		= element.data( 'layout' );
			var orderby 	= element.data( 'orderby' );
			var order 		= element.data( 'order' );
			var item_row  = element.data( 'row' );
			var sorder    = element.data( 'sorder' );
			var catid 		= element.data( 'category' );
			var number 		= element.data( 'number' );
			var columns 	= element.data( 'lg' );
			var columns1 	= element.data( 'md' );
			var columns2 	= element.data( 'sm' );
			var columns3 	= element.data( 'xs' );
			var columns4 	= element.data( 'mobile' );
			var interval 	= element.data( 'interval' );
			var scroll 		= element.data( 'scroll' );
			var speed 		= element.data( 'speed' );
			var autoplay 	= element.data( 'autoplay' );	
			var tg_append = element.parents( '.sw-ajax' ).find( ' .tab-content' );
			var action 		= 'sw_ajax_tab_listing';
			var ajaxurl   = frontend_val.ajaxurl;
			if( !element.parent().hasClass ('loaded') ){
				tg_append.addClass( 'loading' );
				var data 		= {
					action: action,
					catid: catid,
					number: number,
					target: id,
					layout: layout,
					item_row: item_row,
					sorder: sorder,
					orderby: orderby,
					order: order,
					columns: columns,
					columns1: columns1,
					columns2: columns2,
					columns3: columns3,
					columns4: columns4,
					interval: interval,
					speed: speed,
					scroll: scroll,
					autoplay: autoplay,
				};
				jQuery.post(ajaxurl, data, function(response) {
					element.parent().addClass( 'loaded' );
					tg_append.find( '.tab-pane' ).removeClass( 'active' );
					tg_append.append( response );
					sw_slider_ajax( id );
					tg_append.removeClass( 'loading' );
					$( '.entry-cat a' ).each(function(){
						var data = $(this).data( 'color' );
						if( data != '' ){
							$(this).css( 'background', data );
						}
					});
				});
			}
		}
		
		function sw_slider_ajax( target ) {	
			var element 	= $(target).find( '.responsive-slider' );
			var $col_lg 	= element.data('lg');
			var $col_md 	= element.data('md');
			var $col_sm 	= element.data('sm');
			var $col_xs 	= element.data('xs');
			var $col_mobile = element.data('mobile');
			var $speed 		= element.data('speed');
			var $interval 	= element.data('interval');
			var $scroll 	= element.data('scroll');
			var $autoplay 	= element.data('autoplay');
			var $rtl 		= $('body').hasClass( 'rtl' );
			$target = $(target).find( '.responsive' );
			$target.slick({
			  appendArrows: $(target),
			  prevArrow: '<span data-role="none" class="res-button slick-prev" aria-label="previous"></span>',
			  nextArrow: '<span data-role="none" class="res-button slick-next" aria-label="next"></span>',
			  dots: false,
			  infinite: true,
			  speed: $speed,
			  slidesToShow: $col_lg,
			  slidesToScroll: $scroll,
			  autoplay: $autoplay,
			  autoplaySpeed: $interval,
			  rtl: $rtl,			  
			  responsive: [
				{
				  breakpoint: 1199,
				  settings: {
					slidesToShow: $col_md,
					slidesToScroll: $col_md
				  }
				},
				{
				  breakpoint: 991,
				  settings: {
					slidesToShow: $col_sm,
					slidesToScroll: $col_sm
				  }
				},
				{
				  breakpoint: 767,
				  settings: {
					slidesToShow: $col_xs,
					slidesToScroll: $col_xs
				  }
				},
				{
				  breakpoint: 480,
				  settings: {
					slidesToShow: $col_mobile,
					slidesToScroll: $col_mobile					
				  }
				}
			  ]
			});
			setTimeout(function(){
				element.removeClass("loading");
			}, 500);
		}
	});
})(jQuery);