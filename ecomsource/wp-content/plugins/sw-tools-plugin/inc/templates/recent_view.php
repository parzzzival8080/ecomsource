				<div class="popup popup-recent popup-hidden" id="popup-recent">
					<div class="popup-screen">
						<div class="popup-position">
							<div class="popup-container popup-small">
								<div class="popup-html">
									<div class="popup-header">
										<span><i class="fa fa-recent"></i><?php echo esc_html__( 'Recent view products','sw-tools-plugin'); ?></span>
										<a class="popup-close" data-target="popup-close" data-popup-close="#popup-recent">&times;</a>
									</div>
									<div class="popup-content">
										<div class="form-content">
											<div class="row space">
											   <?php echo do_shortcode("[woocommerce_recently_viewed_products per_page=5 ]"); ?>
											</div>
										</div>
										<div class="clear"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>