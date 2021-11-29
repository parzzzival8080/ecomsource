(function ($, elementor) {
	"use strict";

	var Marketo = {
		init: function () {
			var widgets = {
				'ekit-vertical-menu.default': Marketo.Vertical_Menu,
			};

			$.each(widgets, function (widget, callback) {
				elementor.hooks.addAction('frontend/element_ready/' + widget, callback);
			});
		},


		Vertical_Menu: function ($scope) {
			if($scope.find('.ekit-vertical-main-menu-on-click').length > 0) {
				let menu_container = $scope.find('.ekit-vertical-main-menu-on-click'),
					target = $scope.find('.ekit-vertical-menu-tigger');

				target.on('click', function (e) {
					e.preventDefault();
					menu_container.toggleClass('vertical-menu-active');
				})
			}

			if($scope.find('.elementskit-megamenu-has').length > 0) {
				let target = $scope.find('.elementskit-megamenu-has'),
					parents_container = $scope.parents('.elementor-container'),
					vertical_menu_wraper = $scope.find('.ekit-vertical-main-menu-wraper'),
					final_width = Math.floor((parents_container.width() - vertical_menu_wraper.width())) + 'px';

				target.on('mouseenter',function () {
					let data_width = $(this).data('vertical-menu'),
						megamenu_panel = $(this).children('.elementskit-megamenu-panel');

					if(data_width && data_width !== undefined && !(final_width <= data_width)) {
						if(typeof data_width === 'string') {
							if(/^[0-9]/.test(data_width)) {
								megamenu_panel.css({
									width: data_width
								})
							} else {
								$(window).bind('resize', function () {
									if($(document).width() > 1024) {
										megamenu_panel.css({
											width: Math.floor((parents_container.width() - vertical_menu_wraper.width()) - 10) + 'px'
										})
									} else {
										megamenu_panel.removeAttr('style');
									}
								}).trigger('resize');
							}
						} else {
							megamenu_panel.css({
								width: data_width + 'px'
							})
						}
					} else {
						$(window).bind('resize', function () {
							if($(document).width() > 1024) {
								megamenu_panel.css({
									width: Math.floor((parents_container.width() - vertical_menu_wraper.width()) - 10) + 'px'
								})
							} else {
								megamenu_panel.removeAttr('style');
							}
						}).trigger('resize');
					}
				});
				target.trigger('mouseenter');
			}
		},
	};
	$(window).on('elementor/frontend/init', Marketo.init);
}(jQuery, window.elementorFrontend));
