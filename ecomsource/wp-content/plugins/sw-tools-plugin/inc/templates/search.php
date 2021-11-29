				<div class="popup popup-mysearch popup-hidden" id="popup-mysearch">
					<div class="popup-screen">
						<div class="popup-position">
							<div class="popup-container popup-small">
								<div class="popup-html">
									<div class="popup-header">
										<span><i class="fa fa-search"></i><?php echo esc_html__( 'Search','sw-tools-plugin'); ?></span>
										<a class="popup-close" data-target="popup-close" data-popup-close="#popup-mysearch">&times;</a>
									</div>
									<div class="popup-content">
									<form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_attr(home_url( '/' )); ?>">
										<div class="form-content">
											<div class="rows space">
												<div class="col">
													<div class="form-box">
														<input type="text" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php echo esc_html__( 'Search','sw-tools-plugin'); ?>" id="input-search" class="field search-field" />
														<i class="fa fa-search sbmsearch"></i>
													</div>
												</div>
												<div class="col">
													<div class="form-box">
													    <input type="submit" id="button-search" class="btn button-search search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'sw-tools-plugin' ) ?>" />
													</div>
												</div>
												<input type="hidden" name="post_type" value="product">
											</div>
										</div>
									</form>	
										<div class="clear"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>