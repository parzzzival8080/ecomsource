                    <div class="popup popup-categories popup-hidden" id="popup-categories">
						<div class="popup-screen">
							<div class="popup-position">
								<div class="popup-container popup-small">
									<div class="popup-header">
										<span><i class="fa fa-align-justify"></i><?php echo esc_html__( 'All Categories','sw-tools-plugin'); ?></span>
										<a class="popup-close" data-target="popup-close" data-popup-close="#popup-categories">&times;</a>
									</div>
									<div class="popup-content">
										<?php if ($categories): ?>
										<div class="nav-secondary">
											<ul>
												<?php foreach ($categories as $key => $category):  ?>											
													<li>
														<?php if ($category->childrens != array()): ?>	
															<span class="nav-action">
																<i class="fa fa-plus more"></i>
																<i class="fa fa-minus less"></i>
															</span>
														<?php endif; ?>
														<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"><i class="fa fa-chevron-down nav-arrow"></i><?php echo esc_attr($category->name); ?></a>
														<?php if ($category->childrens != array()): ?>	
															<ul class="level-2">
																<?php foreach ($category->childrens as $subchildrens):  ?>
																<?php if ($subchildrens->parent == $category->term_id): ?>	
																	<li>
																		<?php if ($subchildrens->childrens != array()): ?>
																			<span class="nav-action">
																				<i class="fa fa-plus more"></i>
																				<i class="fa fa-minus less"></i>
																			</span>
																		<?php endif; ?>
																		<a href="<?php echo esc_url( get_category_link( $subchildrens->term_id ) ); ?>"><i class="fa fa-chevron-right flip nav-arrow"></i><?php echo esc_attr($subchildrens->name); ?></a>
																		<?php if ($subchildrens->childrens != array()): ?>
																			<ul class="level-3">
																				<?php foreach ($subchildrens->childrens as $subchildrenschild):  ?>
																					<li>
																						<a href="<?php echo esc_url( get_category_link( $subchildrenschild->term_id ) ); ?>"><i class="fa fa-chevron-right flip nav-arrow"></i><?php echo esc_attr($subchildrenschild->name); ?></a>
																						<?php if ($subchildrenschild->childrens != array()): ?>
																						
																						<?php endif; ?>
																					</li>
																				<?php endforeach; ?>
																			</ul>	
																		<?php endif; ?>
																	</li>
																	<?php endif; ?>
																<?php endforeach; ?>
															</ul>
														<?php endif; ?>
													</li>
												<?php endforeach; ?>
											</ul>
										</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>