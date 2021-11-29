(function($) {	
    "use strict";
    // SW Quick Tools
    $(document).ready(function($) {
		
		$('.account-url').on( "click", function(e)
		{
			$('.account-url').css('display','none');
			$('.sw-tool-plugin-form-login').css('display','block');
		});
		
		$('.sw-tool-plugin-form-login-back').on( "click", function(e)
		{
			$('.account-url').css('display','block');
			$('.sw-tool-plugin-form-login').css('display','none');
		});		

		$('.popup').on( "click", function(e)
		{
			var container = $(".popup-container");
			if (!container.is(e.target) && container.has(e.target).length === 0)
			{
				$('.popup-close').click();
			}
		});
		
		
    	$('.sw-groups-sticky .sticky-backtop').on('click', function() {
			$('html, body').animate({ scrollTop: 0 }, 'slow', function () {});
		});
	
		$('.sw-groups-sticky *[data-target="popup"]').on('click', function() {
			$('html').addClass('overflow-hidden');
			$($(this).attr('data-popup')).removeClass('popup-hidden');
			$('.popup').animate({
				scrollTop:'0px'
			}, 500);
		});
		
		$('.sw-groups-sticky .nav-secondary ul li span i').on( "click", function() {
			if ($(this).hasClass('more')) {
				$('.sw-groups-sticky .nav-secondary ul li').removeClass('active');
				$(this).parent().parent().addClass('active');
				$(this).parent().parent().children('ul').stop(true, true).slideDown('slow');
				$('.sw-groups-sticky .nav-secondary ul li').each(function() {
					if ($(this).hasClass('active')) {
						$(this).parent('ul').parent('li').addClass('active');
						$(this).children('ul').slideDown('slow');
					}
				})
				$('.sw-groups-sticky .nav-secondary ul li:not(".active") ul').stop(true, true).slideUp('slow');
			}
			else {
				$(this).parent().parent().children('ul').stop(true, true).slideUp('slow');
				$(this).parent().parent().removeClass('active');
			}
		});		
		
		$('.sw-groups-sticky *[data-target="popup-close"]').on('click', function() {
			$('html').removeClass('overflow-hidden');
			$($(this).attr('data-popup-close')).addClass('popup-hidden');
		});
		

		$('.sw-groups-sticky #input-search').on('keydown', function(e) {
			if (e.keyCode == 13) {
				$('.sw-groups-sticky #button-search').trigger('click');
			}
		});			
	
	})

	$(document).keyup(function(e) {
	     if (e.keyCode == 27) {
	        $('html').removeClass('overflow-hidden');
			$('.sw-groups-sticky .popup').addClass('popup-hidden');
	    }
	});	
	

})(jQuery);