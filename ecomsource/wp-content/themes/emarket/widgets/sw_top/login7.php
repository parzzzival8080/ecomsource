<?php 
$emarket_header_style = emarket_options()->getCpanelValue('header_style');
?>
<?php do_action( 'before' ); ?>
<?php if ( class_exists( 'WooCommerce' ) ) { ?>
<?php global $woocommerce; ?>
<div class="top-login">
	<?php if ( ! is_user_logged_in() ) {  ?>
	<ul>
		<li>
			<?php echo '<a class="login" href="javascript:void(0);" data-toggle="modal" data-target="#login_form"><span>'.__('Login', 'emarket').'</span></a>'; ?>					
		</li>
	</ul>
	<?php } else{ ?>
	<div class="div-logined">
		<ul>
			<li>
				<?php 
				$user_id = get_current_user_id();
				$user_info = get_userdata( $user_id );	
				?>
				<a class="login" href="<?php echo wp_logout_url( home_url('/') ); ?>" title="<?php esc_attr_e( 'Logout', 'emarket' ) ?>"><span><?php esc_html_e('Logout', 'emarket'); ?></span></a>
			</li>
		</ul>
	</div>
	<?php } ?>
</div>
<?php }