(function($) {	
	function RespSlider( $id, $append, $target ){
		var $col_lg = $id.data('lg');
		var $col_md = $id.data('md');
		var $col_sm = $id.data('sm');
		var $col_xs = $id.data('xs');
		var $col_mobile = $id.data('mobile');
		var $speed = $id.data('speed');
		var $interval = $id.data('interval');
		var $scroll = $id.data('scroll');
		var $autoplay = $id.data('autoplay');		
		var $vertical = ( typeof( $id.data('vertical') ) != "undefined" ) ? $id.data('vertical') : false;
		var $fade 	= ( typeof( $id.data('fade') ) != "undefined"  ) ? $id.data('fade') : false;
	    var $dots 	= ( typeof( $id.data('dots')  ) != "undefined" ) ? $id.data('dots') : false;
		var $rtl = ( $('body').hasClass('rtl') && $vertical == false ) ? true : false;
		$target.not('.slick-initialized').slick({
		  appendArrows: $append,
		  prevArrow: '<span data-role="none" class="res-button slick-prev" aria-label="previous"></span>',
		  nextArrow: '<span data-role="none" class="res-button slick-next" aria-label="next"></span>',
		  dots: $dots,
		  infinite: true,
		  speed: $speed,
		  slidesToShow: $col_lg,
		  slidesToScroll: $scroll,
		  autoplay: $autoplay,
		  autoplaySpeed: $interval,
		  rtl: $rtl,
		  vertical: $vertical,		  
		  responsive: [
			{
			  breakpoint: 1199,
			  settings: {
				slidesToShow: $col_md
			  }
			},
			{
			  breakpoint: 991,
			  settings: {
				slidesToShow: $col_sm
			  }
			},
			{
			  breakpoint: 767,
			  settings: {
				slidesToShow: $col_xs
			  }
			},
			{
			  breakpoint: 480,
			  settings: {
				slidesToShow: $col_mobile    
			  }
			}
			// You can unslick at a given breakpoint now by adding:
			// settings: "unslick"
			// instead of a settings object
		  ]
		});
		$id.fadeIn(1000, function() {		
			setTimeout(function(){
				$(this).removeClass("loading");
			}, 500);
		});
	}
	$(document).ready(function(){
		$('.responsive-slider').each( function(){
			var $this 	= $(this);
			var $app 	= $this.data('append');
			var $append	= ( typeof( $app ) == 'undefined' ) ? $this : $(this).find($app);
			var $target = $(this).find( '.responsive' );
			RespSlider( $this, $append, $target );
		});
	});
		
	$( '.testimonial-post-slider' ).each(function(){
			var $rtl 					= $('body').hasClass( 'rtl' );
			var $img_slider 	= $(this).find('.responsive-content');
			var $thumb_slider = $(this).find('.responsive-thumbnail');
			
			var $interval     = $(this).data('interval');
			var $autoplay     = $(this).data('autoplay');
				
			$('.responsive-content').not('.slick-initialized').slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				autoplay: $autoplay,
				autoplaySpeed: $interval,
				arrows: true,
				dots: true,
				fade: false,
				rtl: $rtl,
				adaptiveHeight: true,
				infinite: false,
				useTransform: false,
				speed: 800,
				cssEase: 'cubic-bezier(0.77, 0, 0.18, 1)',
			 });

			 $('.responsive-thumbnail')
				.on('init', function(event, slick) {
					$('.responsive-thumbnail .slick-slide.slick-current').addClass('is-active');
				})
				.not('.slick-initialized').slick({
					slidesToShow: 4,
					slidesToScroll: 2,
					centerMode: true,
					dots: false,
					focusOnSelect: false,
					infinite: false,
					responsive: [{
						breakpoint: 1024,
						settings: {
							slidesToShow: 4,
							slidesToScroll: 1,
						}
					}, {
						breakpoint: 640,
						settings: {
							slidesToShow: 4,
							slidesToScroll: 1,
						}
					}, {
						breakpoint: 420,
						settings: {
							slidesToShow: 3,
							slidesToScroll: 1,
					}
					}]
				});

			 $('.responsive-content').on('afterChange', function(event, slick, currentSlide) {
				$('.responsive-thumbnail').slick('slickGoTo', currentSlide);
				var currrentNavSlideElem = '.responsive-thumbnail .slick-slide[data-slick-index="' + currentSlide + '"]';
				$('.responsive-thumbnail .slick-slide.is-active').removeClass('is-active');
				$(currrentNavSlideElem).addClass('is-active');
			 });

			 $('.responsive-thumbnail').on('click', '.slick-slide', function(event) {
				event.preventDefault();
				var goToSingleSlide = $(this).data('slick-index');

				$('.responsive-content').slick('slickGoTo', goToSingleSlide);
			 });
			 
			  $('.responsive-thumbnail .slick-slide').removeClass('slick-active');

			 //set active class to first thumbnail slides
			 $('.responsive-thumbnail .slick-slide').eq(0).addClass('is-active');
			 
			var el = $(this);
			setTimeout(function(){
				el.removeClass("loading");
			}, 1000);
		});
	
})(jQuery);