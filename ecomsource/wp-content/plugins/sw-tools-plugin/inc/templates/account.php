
	<div class="popup popup-myaccount popup-hidden" id="popup-myaccount">
		<div class="popup-screen">
			<div class="popup-position">
				<div class="popup-container popup-small">
					<div class="popup-html">
						<div class="popup-header">
							<span><i class="fa fa-user"></i><?php echo esc_html__( 'My account','sw-tools-plugin'); ?></span>
							<a class="popup-close" data-target="popup-close" data-popup-close="#popup-myaccount">&times;</a>
						</div>
						<div class="popup-content">
							<div class="form-content">
								<div class="row space">
									<?php if ( is_user_logged_in() ) { ?>
										<div class="col sw-sm-4 sw-xs-6 txt-center">
											<div class="form-box">		
													<a class="account-url" href="<?php  echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>">
															<span class="ico ico-32 ico-sm"><i class="fa fa-account"></i></span><br>
															<span class="account-txt"><?php echo esc_html__( 'ACCOUNT','sw-tools-plugin'); ?></span>
													</a>
											</div>
										</div>
										<div class="col sw-sm-4 sw-xs-6 txt-center">
											<div class="form-box">
												<a class="account-url" href="<?php echo  wc_get_endpoint_url('orders', '', get_permalink(get_option('woocommerce_myaccount_page_id'))) ?>">
													<span class="ico ico-32 ico-sm"><i class="fa fa-history"></i></span><br>
													<span class="account-txt"><?php echo esc_html__( 'HISTORY','sw-tools-plugin'); ?></span>
												</a>
											</div>
										</div>
										<div class="col sw-sm-4 sw-xs-6 txt-center">
											<div class="form-box">
												<a class="account-url" href="<?php echo wc_get_cart_url(); ?>">
													<span class="ico ico-32 ico-sm"><i class="fa fa-shoppingcart"></i></span><br>
													<span class="account-txt"><?php echo esc_html__( 'SHOPPING CART','sw-tools-plugin'); ?></span>
												</a>
											</div>
										</div>
										<div class="col sw-sm-4 sw-xs-6 txt-center">
											<div class="form-box">
												<a class="account-url" href="<?php echo  wc_get_endpoint_url('downloads', '', get_permalink(get_option('woocommerce_myaccount_page_id'))) ?>">
													<span class="ico ico-32 ico-sm"><i class="fa fa-download"></i></span><br>
													<span class="account-txt"><?php echo esc_html__( 'DOWNLOAD','sw-tools-plugin'); ?></span>
												</a>
											</div>
										</div>
										<div class="col sw-sm-4 sw-xs-6 txt-center">
											<div class="form-box">
												<a class="account-url" href="<?php echo  wc_get_endpoint_url('edit-address', '', get_permalink(get_option('woocommerce_myaccount_page_id'))) ?>">
													<span class="ico ico-32 ico-sm"><i class="fa fa-register"></i></span><br>
													<span class="account-txt"><?php echo esc_html__( 'ADDRESS','sw-tools-plugin'); ?></span>
												</a>
											</div>
										</div>										
										<div class="col sw-sm-4 sw-xs-6 txt-center">
											<div class="form-box">									
												<a class="account-url" href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>">
													<span class="ico ico-32 ico-sm"><i class="fa fa-login"></i></span><br>
													<span class="account-txt"><?php echo esc_html__( 'LOGOUT','sw-tools-plugin'); ?></span>
												</a>
											</div>
										</div>										
									<?php } else { ?>
										<div class="col sw-sm-4 sw-xs-6 txt-center">
											<div class="form-box">
												<a class="account-url" href="<?php  echo site_url('/wp-login.php?action=register&redirect_to=' . get_permalink()); ?>">
													<span class="ico ico-32 ico-sm"><i class="fa fa-register"></i></span><br>
													<span class="account-txt"><?php echo esc_html__( 'REGISTER','sw-tools-plugin'); ?></span>
												</a>
											</div>
										</div>								
										<?php if (current_theme_supports( 'sw_theme' )) { ?>
											<div class="col sw-sm-4 sw-xs-6 txt-center">
												<div class="form-box">									
													<a href="javascript:void(0);" data-toggle="modal" data-target="#login_form">
														<span class="ico ico-32 ico-sm"><i class="fa fa-login"></i></span><br>
														<span class="account-txt"><?php echo esc_html__( 'LOGIN','sw-tools-plugin'); ?></a></span>
													</a>
												</div>
											</div>											
										<?php } else { ?>
											<div class="col sw-sm-4 sw-xs-6 txt-center">
												<div class="form-box">									
													<a class="account-url">
														<span class="ico ico-32 ico-sm"><i class="fa fa-login"></i></span><br>
														<span class="account-txt"><?php echo esc_html__( 'LOGIN','sw-tools-plugin'); ?></span>
													</a>
												</div>
											</div>	
											<div class="col sw-sm-12 sw-xs-12 sw-tool-plugin-form-login">
												<?php wp_login_form(); ?>
												<span class="account-txt sw-tool-plugin-form-login-back" ><?php echo esc_html__( 'BACK','sw-tools-plugin'); ?></span>
											</div>												
										<?php } ?>
									<?php } ?>
								</div>
							</div>
							<div class="clear"></div>
						</div>					
					</div>
				</div>
			</div>
		</div>
	</div>