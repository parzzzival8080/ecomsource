(function($) {
	"use strict";
	
	/* 
	** Add Click On Ipad 
	*/
	$(window).resize(function(){
		var $width = $(this).width();
		if( $width < 1199 ){
			$( '.primary-menu .nav .dropdown-toggle'  ).each(function(){
				$(this).attr('data-toggle', 'dropdown');
			});
		}
	});
	
	/*
	** Blog Masonry
	*/
	$(window).load(function() {
		$('body').find('.blog-content-grid').isotope({ 
			layoutMode : 'masonry'
		});		
	});

	$('.header-style19 .header-top-elementor19 .header-dark').on('click', function(){
		$('body').toggleClass('light-style');
	});
	
	$('.header-top-elementor19 .top-left1 .icon-sidebar').on('click', function(){
		$('.header-sibar-home17').toggleClass('open');
		$('body').toggleClass('open');
	});
	
	$('.header-sibar-home17 .close-sidebar').on('click', function(){
		$('.header-sibar-home17').toggleClass('open');
		$('body').toggleClass('open');
	});
	
	
	$('.header-bottom-elementor22 .top-left1 .icon-sidebar').on('click', function(){
		$('.header-sibar-home22').toggleClass('open');
		$('body').toggleClass('open');
		$('html').css( 'overflow', 'hidden' );
	});
	
	$('.header-sibar-home22 .close-sidebar').on('click', function(){
		$('.header-sibar-home22').toggleClass('open');
		$('body').toggleClass('open');
		$('html').css( 'overflow', 'visible' );
	});
	
	$('.header-bottom-elementor23 .top-right1 .icon-sidebar').on('click', function(){
		$('.header-sibar-home23').toggleClass('open');
		$('body').toggleClass('open');
	});
	
	$('.header-sibar-home23 .close-sidebar').on('click', function(){
		$('.header-sibar-home23').toggleClass('open');
		$('body').toggleClass('open');
	});
	
	$('.header-mid-elemntor24 .top-left1 .icon-sidebar').on('click', function(){
		$('.header-sibar-home24').toggleClass('open');
		$('body').toggleClass('open');
	});
	
	$('.header-sibar-home24 .close-sidebar').on('click', function(){
		$('.header-sibar-home24').toggleClass('open');
		$('body').toggleClass('open');
	});
	
	$('.header-bottom-elementor31 .icon-sidebar').on('click', function(){
		$('.header-sidebar-home31').toggleClass('open');
		$('body').toggleClass('open');
	});
	
	$('.header-sidebar-home31 .close-sidebar').on('click', function(){
		$('.header-sidebar-home31').toggleClass('open');
		$('body').toggleClass('open');
	});
	
	$('.header-bottom-elementor33 .top-left1 .icon-sidebar').on('click', function(){
		$('.header-sidebar-home33').toggleClass('open');
		$('body').toggleClass('open');
		$('html').css( 'overflow', 'hidden' );
	});
	
	$('.header-sidebar-home33 .close-sidebar').on('click', function(){
		$('.header-sidebar-home33').toggleClass('open');
		$('body').toggleClass('open');
		$('html').css( 'overflow', 'visible' );
	});
	
	$('.header-bottom-elementor34 .icon-sidebar').on('click', function(){
		$('.header-sidebar-home34').toggleClass('open');
		$('body').toggleClass('open');
		$('html').css( 'overflow', 'hidden' );
	});
	
	$('.header-sidebar-home34 .close-sidebar').on('click', function(){
		$('.header-sidebar-home34').toggleClass('open');
		$('body').toggleClass('open');
		$('html').css( 'overflow', 'visible' );
	});
	
	$('.header-bottom-elementor35 .icon-sidebar').on('click', function(){
		$('.header-sidebar-home35').toggleClass('open');
		$('body').toggleClass('open');
		$('html').css( 'overflow', 'hidden' );
	});
	
	$('.header-sidebar-home35 .close-sidebar').on('click', function(){
		$('.header-sidebar-home35').toggleClass('open');
		$('body').toggleClass('open');
		$('html').css( 'overflow', 'visible' );
	});
	
	$('.header-bottom-elementor37 .top-right1 .elementor-widget-wp-widget-nav_menu h5').on('click', function(){
		$('.header-bottom-elementor37 .top-right1 .elementor-widget-wp-widget-nav_menu').toggleClass('open');
	});
	
	/*
	** Search on click
	*/
	$('.search-cate .search-tog').on('click', function(){
		$('.search-cate .emarket_top').slideToggle();
		$('.fa').toggleClass('open');
	});
	
	$('.header-right .menu-confirmation .text-confirmation').on('click', function(){
		$('.header-right .menu-confirmation').toggleClass("open");
	});
	
	$('.main-menu .header-close').on('click', function(){
		$('.main-menu').removeClass("open");
	});
	$('.header-open').on('click', function(){
		$('.main-menu').toggleClass("open");
	});
	
	// VIDEO 1
	$('.book_video[data-video]').one('click', function() {

	var $this = $(this);
	var width = $this.attr("width");
	var height = $this.attr("height");

	$this.html('<iframe height="'+ height +'" frameborder="0" src="https://www.youtube.com/embed/' + $this.data("video") + '?autoplay=1"></iframe>');
	});
	
	// VIDEO 2
	$('.book_video2[data-video]').each(function(){
		var $this = $(this);
		var width = $this.attr("width");
		if( $(window).width() > 1170 ){
			var height = $this.attr("height");
		}else{
			var height = ($(window).width())/2.05;
		}
		$this.css("height", height);
		$('.book_video2[data-video]').on('click', function(){
			$this.html('<iframe height="'+ height +'" frameborder="0" src="https://www.youtube.com/embed/' + $this.data("video") + '?autoplay=1"></iframe>');
		});
	});
	
	
	// Accordion Shop Filter
	$('.sw-filter-accordion .sw-filter-above-shop .widget').each(function(i) {
		$(this).attr('id', 'widget_filter'+(i+1));
	});
	$('.sw-filter-accordion .sw-filter-above-shop .widget').each(function(i){
		var id = $(this).attr("id");
		$('#' + id + ' .widget-inner .block-title-widget h2').on('click', function(e){
			if( $('#' + id).hasClass("open") ){
				$('#' + id).removeClass("open");
			}else{
				$('.sw-filter-accordion .sw-filter-above-shop .widget').removeClass('open');
				$('#' + id).toggleClass("open");
			}
			e.preventDefault();
        });
	});
	
	$('.page-filter-drawer .products-nav .sw-filter-button').on('click', function(){
		$('.page-filter-drawer .sidebar-row').toggleClass("active");
	});
	
	// click filter
	$(document).on( 'click', '.box-shop .products-nav .sw-filter-button', function(){
		$(".box-content-archive ").toggleClass( 'active_filters' );
		$(".sw-filter-button ").toggleClass("closex");
	});
	
	/*
	**  show menu mobile
	*/
	
	$( ".mobile-search .icon-seach" ).click(function() {
		$(this).toggleClass( 'close' );
		$( ".mobile-search .top-form.top-search" ).slideToggle(100);
	});
	
	$( ".header-mobile-style1 .mobile-search .icon-seach" ).click(function() {
	  $( ".header-mobile-style1 .mobile-search .top-form.top-search" ).slideToggle( "slow", function() {
	  });
	});
	
	$( ".header-mobile-style2 .mobile-search .icon-seach" ).click(function() {
	  $( ".header-mobile-style2 .mobile-search .top-form.top-search" ).slideToggle( "slow", function() {
	  });
	});

	$('.header-menu-categories .open-menu').on('click', function(){
		$('.main-menu').toggleClass("open");
	});
	
	$('.footer-mstyle1 .footer-menu .footer-search a').on('click', function(){
		$('.top-form.top-search').toggleClass("open");
	});
	
	$('.footer-mstyle1 .footer-menu .footer-more a').on('click', function(){
		$('.menu-item-hidden').toggleClass("open");
	});
	
	/*
	** js mobile
	*/
	$('.single-product .social-share .title-share').on('click', function(){
		$('.single-product .social-share').toggleClass("open");
	});
	$('.single-post .social-share .title-share').on('click', function(){
		$('.single-post .social-share').toggleClass("open");
	});

	$('.single-post .social-share.open .title-share').on('click', function(){
		$('.single-post .social-share').removeClass("open");
	});
	
	$('.products-nav .filter-product').on('click', function(){
		$('.products-wrapper .filter-mobile').toggleClass("open");
		$('.products-wrapper').toggleClass('show-modal');
	});
	
	$('.products-nav .filter-product').on('click', function(){
		if( $( ".products-wrapper .products-nav .filter-product" ).not( ".filter-mobile" ) ){
			$('.products-wrapper').removeClass('show-modal');
		}	
	});
	
	$('.mobile-layout .back-history').on('click', function(){
		window.history.back();
	});
	
	$('.footer-mstyle2 .footer-container .footer-open').on('click', function(){
		$('.footer-mstyle2').toggleClass('open');
	});
	
	$('.footer-mstyle2 .mobile_menu2')
	.find('li:gt(7)') /*you want :gt(4) since index starts at 0 and H3 is not in LI */
	.hide()
	.end()
	.each(function(){
			if($(this).children('li').length > 8){ //iterates over each UL and if they have 5+ LIs then adds Show More...
				$(this).append(
					$('<li><a><span class="menu-title">See more</span><span class="menu-img"></span></a></li>')
					.addClass('showMore')
					.on('click',function(){
						if($(this).siblings(':hidden').length > 0){
							$(this).html('<a><span class="menu-title">See less</span><span class="menu-img"></span></a>').siblings(':hidden').show(400);
						}else{
							$(this).html('<a><span class="menu-title">See more</span><span class="menu-img"></span></a>').show().siblings('li:gt(7)').hide(400);
						}
					})
				);
			}
		});
	
	$('.product-categories').each(function(){
		var number	 = $(this).data( 'number' ) - 1;
		var lesstext = $(this).data( 'lesstext' );
		var moretext = $(this).data( 'moretext' );
		if( number > 0 ) {
			$(this).find( '>li:gt('+ number +')' ).hide().end();		
			if( $(this).children('li').length > number ){ 
				$(this).append(
					$('<li><a>'+ moretext +'   </a></li>')
					.addClass('showMore')
					.on('click',function(){
						if($(this).siblings(':hidden').length > 0){
							$(this).html( '<a>'+ lesstext +'   </a>' ).siblings(':hidden').show(400);
						}else{
							$(this).html( '<a>'+ moretext +'   </a>' ).show().siblings( ':gt('+ number +')' ).hide(400);
						}
					})
					);
			}
		}
		if( $(this).hasClass( 'accordion-categories' ) ){
			$(this).find( 'li.cat-parent' ).append( '<span class="child-category-more"></span>' );
		}
	});
	
	$( ".content-left-home35 ul.accordion-categories li" ).first().addClass( "active" );
	
	$(document).on( 'click', '.child-category-more', function(){
		$(this).parent().toggleClass( 'active' );
		//$(this).parent().find( '> ul.children' ).toggle(500);
	});
	
	$('.header-sidebar-home31 ul.menu')
	.find('li:gt(9)') /*you want :gt(4) since index starts at 0 and H3 is not in LI */
	.hide()
	.end()
	.each(function(){
			if($(this).children('li').length > 8){ //iterates over each UL and if they have 5+ LIs then adds Show More...
				$(this).find( '>li:gt(8)' ).hide().end();
				$(this).append(
					$('<li><a class="show-more"><span class="menu-title">show more</span></a></li>')
					.addClass('showMore')
					.on('click',function(){
						if($(this).siblings(':hidden').length > 0){
							$(this).html('<a class="show-less"><span class="menu-title">Show less</span></a>').siblings(':hidden').show(400);
						}else{
							$(this).html('<a class="show-more"><span class="menu-title">show more</span></a>').show().siblings('li:gt(8)').hide(400);
						}
					})
				);
			}
		});
	
	$('.sw-category-slider5 ul')
	.find('li:gt(7)') /*you want :gt(4) since index starts at 0 and H3 is not in LI */
	.hide()
	.end()
	.each(function(){
			if($(this).children('li').length > 8){ //iterates over each UL and if they have 5+ LIs then adds Show More...
				$(this).append(
					$('<li><a><span class="menu-title">show all</span></a></li>')
					.addClass('showMore')
					.on('click',function(){
						if($(this).siblings(':hidden').length > 0){
							$(this).html('<a><span class="menu-title">Show less</span></a>').siblings(':hidden').show(400);
						}else{
							$(this).html('<a><span class="menu-title">show all</span></a>').show().siblings('li:gt(7)').hide(400);
						}
					})
				);
			}
		});
		
	/*add title to button*/
	$("a.compare").attr('title', custom_text.compare_text);
	$(".yith-wcwl-add-button a").attr('title', custom_text.wishlist_text);
	$("a.fancybox").attr('title', custom_text.quickview_text);
	$("a.add_to_cart_button").attr('title', custom_text.cart_text);
	
	/* 
     ** Filter Shop
     */
    $(' .sw-filter-button').on('click', function() {
        $('.sw-filter-above-shop').toggleClass("open");
        $('.sw-filter-button').toggleClass("off");
		$('body.page-shop-filter-canvas').toggleClass('open');
    });
    $('.sw-filter-button').on('click', function(e) {
        var target = $(this).data('target');
        $(target).toggleClass('open');
        e.preventDefault();
    });

    $('.sw-filter-close').on('click', function(e) {
        var target = $(this).data('target');
        $(target).removeClass('open');
		$('.sw-filter-button').removeClass("off");
		$('body').removeClass('open');
        e.preventDefault();
    });
	
	$('.sw-sidebar-button').on('click', function(e) {
        var target = $(this).data('target');
        $(target).toggleClass('open');
		$('body').toggleClass('open');
        e.preventDefault();
    });

    $('.sw-sidebar-close').on('click', function(e) {
        var target = $(this).data('target');
        $('#'+ target).removeClass('open');
		$('body').removeClass('open');
        e.preventDefault();
    });
	
	$(".single-product-style8 .product_detail .tabs ul > li").each( function(){
		 $('.single-product-style8 .product_detail .tabs ul > li').removeClass('active');
	}); 
	
	$('.single-product-style8 .product_detail .tabs ul > li > a').on('click', function(e) {
        $('.single-product-style8 .product_detail .tabs .tab-content').addClass('open');
        e.preventDefault();
    });

    $('.single-product-style8 .product_detail .tabs .tab-content .close-tab').on('click', function(e) {
       $('.single-product-style8 .product_detail .tabs .tab-content').removeClass('open');
        e.preventDefault();
    });
	
	 $(window).scroll(function() {
        if ($(this).scrollTop() >= whtml / 25) {
            $('.home25-stick-image').css({ top: '50%', margin: '-' + ($('.home25-stick-image img').height() / 2) + 'px 0 0 0' });
        } else {
            $('.home25-stick-image').css({ top: '225px', margin: '0' });
        }
    });
	
	/*sticky single*/
	  if( $('body').hasClass( 'single-product' ) && $(window).width() > 991 ) {
        $('.single-product.single-sticky').each(function() {
            var stickyDetail = $('.single-product .tabs').offset().top;

            var stickyDT = function() {
                var scrollTop = $(window).scrollTop();
                if (scrollTop > stickyDetail) {
                    $('.addcart-wrapper').addClass('sticky-detail-buton');
                    $('.sticky-detail').addClass('sticky');

                } else {
                    $('.addcart-wrapper').removeClass('sticky-detail-buton');
                    $('.sticky-detail').removeClass('sticky');
                }
            };


            stickyDT();
            $(window).scroll(function() {
                stickyDT();
            });
        });
    }
	
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip(); 
	});
	/*
	** Product listing order hover
	*/

	
	/*
	** Product listing select box
	*/
	$('.catalog-ordering .orderby .current-li a').html($('.catalog-ordering .orderby ul li.current a').html());
	$('.catalog-ordering .sort-count .current-li a').html($('.catalog-ordering .sort-count ul li.current a').html());
	
	/*
	** Quickview and single product slider
	*/
	$(document).ready(function(){
		if( $.isFunction( $.fancybox ) ){
			$('.fancybox').fancybox({
				'width'     : 850,
				'height'   : '500',
				'autoSize' : false,
				afterShow: function() {
					$( '.quickview-container .product-images' ).each(function(){
						var $id 					= this.id;
						var $rtl 					= $('body').hasClass( 'rtl' );
						var $img_slider 	= $(this).find('.product-responsive');
						var $thumb_slider = $(this).find('.product-responsive-thumbnail' )
						$img_slider.slick({
							slidesToShow: 1,
							slidesToScroll: 1,
							fade: true,
							arrows: false,
							asNavFor: $thumb_slider
						});
						$thumb_slider.slick({
							slidesToShow: 4,
							slidesToScroll: 1,
							asNavFor: $img_slider,
							arrows: true,
							focusOnSelect: true,
							rtl: $rtl,
							responsive: [				
							{
								breakpoint: 360,
								settings: {
									slidesToShow: 2    
								}
							}
							]
						});

						var el = $(this);
						setTimeout(function(){
							el.removeClass("loading");
							var height = el.find('.product-responsive').outerHeight();
							var target = el.find( ' .item-video' );
							target.css({'height': height,'padding-top': (height - target.outerHeight())/2 });

							var thumb_height = el.find('.product-responsive-thumbnail' ).outerHeight();
							var thumb_target = el.find( '.item-video-thumb' );
							thumb_target.css({ height: thumb_height,'padding-top':( thumb_height - thumb_target.outerHeight() )/2 });
						}, 1000);
					});
				}
			});
		}
		/* 
		** Slider single product image
		*/
		$( '.product-images' ).each(function(){
			var $rtl 			= $('body').hasClass( 'rtl' );
			var $vertical		= $(this).data('vertical');
			var $vendor			= $(this).data('vendor');
			var $autoplay		= $(this).data('autoplay');
			var $dots			= $(this).data('dots');
			var $img_slider 	= $(this).find('.product-responsive');
			var $thumb_slider 	= $(this).find('.product-responsive-thumbnail' );
			var video_link 		= $(this).data('video');
			var number_mobile 	= ( $vertical ) ? 2: 3;
			if( $vertical ){
				
				var number = 4;
				
				if( $vendor ){
					
					number = 3;
					
				}else{
					
					number = 4;
					
				}
			}else{
				
				var number = 5;
				
			}
			
			$img_slider.slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				fade: true,
				arrows: false,
				autoplay: $autoplay,
				autoplaySpeed: 2000,
				dots: $dots,
				asNavFor: $thumb_slider,
			});
			$thumb_slider.slick({
				slidesToShow: number,
				slidesToScroll: 1,
				asNavFor: $img_slider,
				arrows: true,
				vertical: $vertical,
				verticalSwiping: $vertical,
				focusOnSelect: true,
				responsive: [
				{
					breakpoint: 768,
					settings: {
						slidesToShow: 3    
					}
				},
				{
					breakpoint: 480,
					settings: {
						slidesToShow: number_mobile    
					}
				},
				{
					breakpoint: 360,
					settings: {
						slidesToShow: 2    
					}
				}
				]
			});
			var el = $(this);
			setTimeout(function(){
				el.removeClass("loading");				
			}, 1000);
			if( video_link != '' ) {
				$img_slider.append( '<button data-type="popup" class="featured-video-button fa fa-video-camera" data-video="'+ video_link +'"></button>' );
			}
		});
		
		/* for bundle */
		$( '.product-images-bundle' ).each(function(){
			var $rtl 					= $('body').hasClass( 'rtl' );
			var $vertical			= $(this).data('vertical');
			var $img_slider 	= $(this).find('.products-thumb-big');
			var $thumb_slider = $(this).find('.product-responsive-thumbnail' );
			
			$img_slider.slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				fade: true,
				arrows: false,
				rtl: $rtl,
				asNavFor: $thumb_slider,
				infinite: false
			});
			$thumb_slider.slick({
				slidesToShow: 3,
				slidesToScroll: 1,
				asNavFor: $img_slider,
				arrows: true,
				rtl: $rtl,
				infinite: false,
				vertical: $vertical,
				verticalSwiping: $vertical,
				focusOnSelect: true,
				responsive: [
				{
					breakpoint: 1190,
					settings: {
						slidesToShow: 3    
					}
				},
				{
					breakpoint: 992,
					settings: {
						slidesToShow: 3    
					}
				},
				{
					breakpoint: 767,
					settings: {
						slidesToShow: 3    
					}
				},
				{
					breakpoint: 481,
					settings: {
						slidesToShow: 3    
					}
				}
				]
			});

			var el = $(this);
			setTimeout(function(){
				el.removeClass("loading");
				var height = el.find('.product-responsive').outerHeight();
				var target = el.find( ' .item-video' );
				target.css({'height': height,'padding-top': (height - target.outerHeight())/2 });

				var thumb_height = el.find('.product-responsive-thumbnail' ).outerHeight();
				var thumb_target = el.find( '.item-video-thumb' );
				thumb_target.css({ height: thumb_height,'padding-top':( thumb_height - thumb_target.outerHeight() )/2 });
			}, 1000);
		});
		
		/*
		** Popup content
		*/
		if( $('body').html().match( /sw-popup-bottom/ ) ){
			var qv_target =  $('.sw-popup-bottom');
			$(document).on( 'click', 'button[data-type="popup"]', function(){
				var video_url = $(this).data( 'video' );
				qv_target.addClass( 'show loading' );					
				setTimeout(function(){
					qv_target.find( '.popup-inner' ).append( '<div class="video-wrapper"><iframe width="560" height="390" src="'+ video_url +'" frameborder="0" allowfullscreen></iframe></div>' );	
					qv_target.find( '.popup-content' ).css( 'margin-top', ( $(window).height() - qv_target.find( '.popup-content' ).outerHeight() ) /2 );
					qv_target.removeClass( 'loading' );
				}, 1000);
			});
			
			$( '.popup-close' ).on('click', function(){
				qv_target.removeClass( 'show' );
				qv_target.find( '.popup-inner' ).html('');			
			});
			$(document).click(function(e) {			
				var container = qv_target.find( '.popup-inner' );
				if ( !container.is(e.target) && container.has(e.target).length === 0 && qv_target.find( '.popup-inner' ).html().length > 0 ){
					qv_target.removeClass( 'show' );
					qv_target.find( '.popup-inner' ).html('');
				}
			});
		}
	});

	/*
	** Hover on mobile and tablet
	*/
	var mobileHover = function () {
		$('*').on('touchstart', function () {
			$(this).trigger('hover');
		}).on('touchend', function () {
			$(this).trigger('hover');
		});
	};
	mobileHover();
	
	
	/*
	** Vertical Menu 
	*/
	
	$('.vertical-megamenu').each(function(){
		var number	 = $(this).parent().data( 'number' ) - 1;
		var lesstext = $(this).parent().data( 'lesstext' );
		var moretext = $(this).parent().data( 'moretext' );
		$(this).find(	' > li:gt('+ number +')' ).hide().end();		
		if($(this).children('li').length > number ){ 
			$(this).append(
				$('<li><a class="open-more-cat">'+ moretext +'</a></li>')
				.addClass('showMore')
				.on('click', function(){
					if($(this).siblings(':hidden').length > 0){
						$(this).html('<a class="close-more-cat">'+ lesstext +'</a>').siblings(':hidden').show(400);
					}else{
						$(this).html('<a class="open-more-cat">'+ moretext +'</a>').show().siblings( ':gt('+ number +')' ).hide(400);
					}
				})
				);
		}
	});	

	/* 
	** Fix accordion heading state 
	*/
	$('.accordion-heading').each(function(){
		var $this = $(this), $body = $this.siblings('.accordion-body');
		if (!$body.hasClass('in')){
			$this.find('.accordion-toggle').addClass('collapsed');
		}
	});	
	
	/*
	** Footer accordion
	*/
	$('.mobile-layout .cusom-menu-mobile .widget_nav_menu h2.widgettitle').append('<span class="icon-footer"></span>');

	$(".mobile-layout .cusom-menu-mobile .widget_nav_menu h2.widgettitle").each(function(){
		$(this).on('click', function(){
			$(this).parent().find("ul.menu").slideToggle();
		});
	});
	
	if ($(window).width() < 768) {	

		$('.footer .widget_nav_menu h2.widgettitle').append('<span class="icon-footer"></span>');
		$('.footer .wpb_content_element .info-footer h3').append('<span class="icon-footer"></span>');

		$(".footer .widget_nav_menu h2.widgettitle").each(function(){
			$(this).on('click', function(){
				$(this).parent().find("ul.menu").slideToggle();
			});
		});
		
		$(".footer .wpb_content_element .info-footer h3").each(function(){
			$(this).on('click', function(){
				$(this).parent().find("ul").slideToggle();
			});
		});	
		
	}
	
	if ($(window).width() < 768) {	
		
		$('.footer .footer-style7 .wpb_content_element .newletter h3').append('<span class="icon-footer"></span>');
		
		$(".footer .footer-style7 .wpb_content_element h3").each(function(){
			$(this).on('click', function(){
				$(this).parent().find(".wrapper-footer").slideToggle();
			});
		});	
		
		
		$('.footer .footer-home10 .wpb_content_element h3').append('<span class="icon-footer"></span>');

		$(".footer .footer-home10 .wpb_content_element h3").each(function(){
			$(this).on('click', function(){
				$(this).parent().find(".wrapper-footer").slideToggle();
			});
		});		
		
	}
	
	if ($(window).width() < 768) {	

		$('.cusom-menu-mobile .elementor-widget-wp-widget-nav_menu h5').append('<span class="icon-footer"></span>');
		
		$(".cusom-menu-mobile .elementor-widget-wp-widget-nav_menu h5").each(function(){
			$(this).on('click', function(){
				$(this).parent().find("ul.menu").slideToggle();
			});
		});
		
	}
	
	/*
	** Back to top
	**/
	$("#emarket-totop").hide();
	var wh = $(window).height();
	var whtml = $(document).height();
	$(window).scroll(function () {
		if ($(this).scrollTop() > whtml/10) {
			$('#emarket-totop').fadeIn();
		} else {
			$('#emarket-totop').fadeOut();
		}
	});
	
	$('#emarket-totop').click(function () {
		$('body,html').animate({
			scrollTop: 0
		}, 800);
		return false;
	});
	/* end back to top */
	
	$(".home-style9 .header-bar").show();
		
 /*
 ** Fix js 
 */
 $('.wpb_map_wraper').on('click', function () {
 	$('.wpb_map_wraper iframe').css("pointer-events", "auto");
 });

 $( ".wpb_map_wraper" ).on('mouseleave', function() {
 	$('.wpb_map_wraper iframe').css("pointer-events", "none"); 
 });

	
	$(document).ready(function(){
		var className = $('#comments .commentlist .entry-summary').children().eq(1).attr('class');
		if( className != 'children' ){
			$('.commentlist').addClass("not-child");
		}
	});
	
	$('#nav').onePageNav();
	
	/*
	** Change Layout 
	*/
	$( window ).load(function() {	
		if( $( 'body' ).hasClass( 'tax-product_cat' ) || $( 'body' ).hasClass( 'post-type-archive-product' ) || $( 'body' ).hasClass( 'tax-dc_vendor_shop' ) ) {
			$('.grid-view').on('click',function(){
				//$('.list-view').removeClass('active');
				$('.list-view2').removeClass('active');
				$('.grid-view').addClass('active');
				jQuery("ul.products-loop").fadeOut(300, function() {
					$(this).fadeIn(300).addClass( 'grid' ).removeClass( 'list2' );			
				});
			});
		
			$('.list-view2').on('click',function(){
				$( '.grid-view' ).removeClass('active');
				//$( '.list-view' ).removeClass('active');
				$( '.list-view2' ).addClass('active');
				$("ul.products-loop").fadeOut(300, function() {
					jQuery(this).addClass("list2").fadeIn(300).removeClass( 'grid' );
				});
			});
			/* End Change Layout */
		} 
	});

	/*remove loading*/
	$(".sw-woo-tab").fadeIn(300, function() {
		var el = $(this);
		setTimeout(function(){
			el.removeClass("loading");
		}, 1000);
	});
	$(".responsive-slider").fadeIn(300, function() {
		var el = $(this);
		setTimeout(function(){
			el.removeClass("loading");
		}, 1000);
	});
	
	function sw_buynow_variation_product(){
		var element = $( '.single-product' );
		var target = $( '.single-product .variations_form' );
		var bt_addcart = target.find( '.single_add_to_cart_button' );
		var variation  = target.find( '.variation_id' ).val();
		var bt_buynow  = element.find( '.button-buynow' );
		var url = bt_buynow.data( 'url' );
		var qty = $('.single-product input.qty').val();
		if( typeof variation != 'undefined' ){
			if( variation == 0 ){
				bt_buynow.addClass( 'disabled' );
			}else{
				bt_buynow.removeClass( 'disabled' );
			}
			if( variation != '' ){
				bt_buynow.attr( 'href', url + '='+variation + '&quantity='+ qty );
			}else{
				bt_buynow.attr( 'href', url + '&quantity='+ qty );
			}
		}else{
			bt_buynow.attr( 'href', url + '&quantity='+ qty );
		}
	}
	$(window).on( 'change', function(){
		sw_buynow_variation_product();
	});
	$(document).ready(function(){
		sw_buynow_variation_product();
	});
	
}(jQuery));

/*
** Check comment form
*/
function submitform(){
	if(document.commentform.comment.value=='' || document.commentform.author.value=='' || document.commentform.email.value==''){
		alert('Please fill the required field.');
		return false;
	} else return true;
}
(function($){		
	

	$(".widget_nav_menu li.menu-compare a").hover(function() {
		$(this).css('cursor','pointer').attr('title', 'Compare');
	}, function() {
		$(this).css('cursor','auto');
	});
	$(".widget_nav_menu li.menu-wishlist a").hover(function() {
		$(this).css('cursor','pointer').attr('title', 'My Wishlist');
	}, function() {
		$(this).css('cursor','auto');
	});
	

	$(window).scroll(function() {   
		if( $( 'body' ).hasClass( 'mobile-layout' ) ) {
			var target = $( '.mobile-layout #header-page' );
			var sticky_nav_mobile_offset = $(".mobile-layout #header-page").offset();
			if( sticky_nav_mobile_offset != null ){
				var sticky_nav_mobile_offset_top = sticky_nav_mobile_offset.top;
				var scroll_top = $(window).scrollTop();
				if ( scroll_top > sticky_nav_mobile_offset_top ) {
					$(".mobile-layout #header-page").addClass('sticky-mobile');
				}else{
					$(".mobile-layout #header-page").removeClass('sticky-mobile');
				}
			}
		}
	});
	
	/*
	** Ajax login
	*/
	$('form#login_ajax').on('submit', function(e){
		var target = $(this);		
		var usename = target.find( '#username').val();
		var pass 	= target.find( '#password').val();
		if( usename.length == 0 || pass.length == 0 ){
			target.find( '#login_message' ).addClass( 'error' ).html( custom_text.message );
			return false;
		}
		target.addClass( 'loading' );
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: custom_text.ajax_url,
			headers: { 'api-key':target.find( '#woocommerce-login-nonce').val() },
			data: { 
				'action': 'emarket_custom_login_user', //calls wp_ajax_nopriv_ajaxlogin
				'username': target.find( '#username').val(), 
				'password': target.find( '#password').val(), 
				'security': target.find( '#woocommerce-login-nonce').val() 
			},
			success: function(data){
				target.removeClass( 'loading' );
				target.find( '#login_message' ).html( data.message );
				if (data.loggedin == false){
					target.find( '#username').css( 'border-color', 'red' );
					target.find( '#password').css( 'border-color', 'red' );
					target.find( '#login_message' ).addClass( 'error' );
				}
				if (data.loggedin == true){
					target.find( '#username').removeAttr( 'style' );
					target.find( '#password').removeAttr( 'style' );
					document.location.href = data.redirect;
					target.find( '#login_message' ).removeClass( 'error' );
				}
			}
		});
		e.preventDefault();
	});
	
	/*
	** Responsive Menu
	*/
	$( '.resmenu-container-sidebar .more-menu > a' ).on( 'click', function(e){
		$(this).parent().find( '>ul' ).toggle();
		e.preventDefault();
	});
	
	var $sidebar2   = $('.elementor-page .home1-left'),
    $content2   = $('.elementor-page .home1-right');

	if ($sidebar2.length > 0 && $content2.length > 0) {
		var $window    = $(window),
			offset     = $sidebar2.offset(),
			timer;
		$window.scroll(function() {
			if ($content2.height() > $sidebar2.height()) {
				var new_margin = $window.scrollTop() - offset.top;
				if ($window.scrollTop() > offset.top && ($sidebar2.height()+new_margin) <= $content2.height()) {
					// Following the scroll...
					$sidebar2.stop().animate({ marginTop: new_margin }, 1000);
					$sidebar2.addClass('fixed');
				} else if (($sidebar2.height()+new_margin) > $content2.height()) {
					// Reached the bottom...
					$sidebar2.stop().animate({ marginTop: $content2.height()-$sidebar2.height() }, 1000);
				} else if ($window.scrollTop() <= offset.top) {
					// Initial position...
					$sidebar2.stop().animate({ marginTop: 0 }, 1000);
					$sidebar2.removeClass('fixed');
				}
			}
		});
	}	
	$(".circle").circleText({
        glue: "",
        turn: true,
        padding: 0,
        radius: 70,
        duration: 7,
        repeat: 6,
    });
})(jQuery);

jQuery(document).ready(function($) {

var $sidebar   = $('.home-style7 #col-left-home7'),
$content   = $('.home-style7 #col-right-home7');

if ($sidebar.length > 0 && $content.length > 0) {
    var $window    = $(window),
        offset     = $sidebar.offset(),
        timer;

    $window.scroll(function() {
        clearTimeout(timer);
        timer = setTimeout(function() {
            if ($content.height() > $sidebar.height()) {
                var new_margin = $window.scrollTop() - offset.top;
                if ($window.scrollTop() > offset.top && ($sidebar.height()+new_margin) <= $content.height()) {
                    // Following the scroll...
                    $sidebar.stop().animate({ marginTop: new_margin }, 1000);
					$sidebar.addClass('fixed');
                } else if (($sidebar.height()+new_margin) > $content.height()) {
                    // Reached the bottom...
                    $sidebar.stop().animate({ marginTop: $content.height()-$sidebar.height() }, 1000);
                } else if ($window.scrollTop() <= offset.top) {
                    // Initial position...
                    $sidebar.stop().animate({ marginTop: 0 }, 1000);
					$sidebar.removeClass('fixed');
                }
            }
        }, 100); 
    });
}

});

jQuery(document).ready(function($) {
	var  $content  = $('#single-left'),
	$sidebar   = $('#single-right');

	if ($sidebar.length > 0 && $content.length > 0) {
		var $window    = $(window),
			offset     = $sidebar.offset(),
			timer;

		$window.scroll(function() {
			clearTimeout(timer);
			timer = setTimeout(function() {
				if ($content.height() > $sidebar.height()) {
					var new_margin = $window.scrollTop() - offset.top;
					if ($window.scrollTop() > offset.top && ($sidebar.height()+new_margin) <= $content.height()) {
						// Following the scroll...
						$sidebar.stop().animate({ marginTop: new_margin }, 300);
						$sidebar.addClass('fixed');
					} else if (($sidebar.height()+new_margin) > $content.height()) {
						// Reached the bottom...
						$sidebar.stop().animate({ marginTop: $content.height()-$sidebar.height() }, 300);
					} else if ($window.scrollTop() <= offset.top) {
						// Initial position...
						$sidebar.stop().animate({ marginTop: 0 }, 300);
						$sidebar.removeClass('fixed');
					}
				}else{
					var new_margin = $window.scrollTop() - offset.top;
					if ($window.scrollTop() > offset.top && ($content.height()+new_margin) <= $sidebar.height()) {
						// Following the scroll...
						$content.stop().animate({ marginTop: new_margin }, 300);
						$content.addClass('fixed');
					} else if (($content.height()+new_margin) > $sidebar.height()) {
						// Reached the bottom...
						$content.stop().animate({ marginTop: $sidebar.height()-$content.height() }, 300);
					} else if ($window.scrollTop() <= offset.top) {
						// Initial position...
						$content.stop().animate({ marginTop: 0 }, 300);
						$content.removeClass('fixed');
					}
				}
			}, 100); 
		});
	}
});
