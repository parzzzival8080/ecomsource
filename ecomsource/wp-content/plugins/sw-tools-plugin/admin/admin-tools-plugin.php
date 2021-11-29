<?php 

class SW_Tools_Plugin {

	// class instance
	static $instance;

	// customer WP_List_Table object
	public $customers_obj;
 
	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'plugin_menu' ] );	
	}
	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	public function plugin_menu() {

		$hook = add_menu_page(
			esc_html__( 'SW Tools Plugin','sw-tools-plugin'),
			esc_html__( 'SW Tools Plugin','sw-tools-plugin'),
			'manage_options',
			'wp_setting_tools_plugin_class',
			[ $this, 'plugin_settings_page' ]
		);
	}
	
	/** Singleton instance */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
		

	public function plugin_settings_page() {

	$show_status = get_option( 'swquicktools_tools_plugin_show_status' );
	$show_position = get_option( 'swquicktools_tools_plugin_show_position' );
	$show_categories = get_option( 'swquicktools_tools_plugin_show_categories' );
	$show_categories_empty = get_option( 'swquicktools_tools_plugin_show_categories_empty' );
	$show_top = get_option( 'swquicktools_tools_plugin_show_top' );
	$show_cart = get_option( 'swquicktools_tools_plugin_show_cart' );
	$show_backtop = get_option( 'swquicktools_tools_plugin_show_backtop' );
	$show_recent_view = get_option( 'swquicktools_tools_plugin_show_recent_view' );
	$show_search = get_option( 'swquicktools_tools_plugin_show_search' );
	$show_account = get_option( 'swquicktools_tools_plugin_show_account' );
	$show_number = get_option( 'swquicktools_tools_plugin_show_number' );
	
		?>
		<div class="wrap">
			<h2><?php echo esc_html__( 'SW Tools Plugin','sw-tools-plugin'); ?></h2>

			<div id="poststuff">
				<div id="post-body" class="metabox-holder">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>?action=setting_tools_plugin" method="post">   
								<table class="form-table">
								    <tbody>
										<tr class="elementor_disable_color_schemes">
											<th scope="row"><?php echo esc_html__( 'Status','sw-tools-plugin'); ?></th>
											<td>
												<select id="show_status" name="show_status" style="padding-right: 30px;">
													<option value="1" <?php if($show_status == 1):?>selected="selected"<?php endif; ?>><?php echo esc_html__( 'Enabled','sw-tools-plugin'); ?></option>
													<option value="0" <?php if($show_status == 0):?>selected="selected"<?php endif; ?>><?php echo esc_html__( 'Disabled','sw-tools-plugin'); ?></option>
												</select>
										    </td>
										</tr>									
										<tr class="elementor_disable_color_schemes">
											<th scope="row"><?php echo esc_html__( 'Position','sw-tools-plugin'); ?></th>
											<td>
												<select id="show_position" name="show_position" style="padding-right: 30px;">
													<option value="1" <?php if($show_position == 1):?>selected="selected"<?php endif; ?>><?php echo esc_html__( 'Right','sw-tools-plugin'); ?></option>
													<option value="0" <?php if($show_position == 0):?>selected="selected"<?php endif; ?>><?php echo esc_html__( 'Left','sw-tools-plugin'); ?></option>
												</select>
										    </td>
										</tr>									
										<tr class="elementor_disable_color_schemes">
											<th scope="row"><?php echo esc_html__( 'Top (px)','sw-tools-plugin'); ?></th>
											<td>
											    <label>
												    <input type="text" id="show_top" name="show_top" value="<?php echo esc_attr($show_top); ?>">
				                                </label>
										    </td>
										</tr>
										<tr class="elementor_disable_color_schemes">
											<th scope="row"><?php echo esc_html__( 'Limit Recent Product','sw-tools-plugin'); ?></th>
											<td>
											    <label>
												    <input type="text" id="show_number" name="show_number" value="<?php echo esc_attr($show_number); ?>">
				                                </label>
										    </td>
										</tr>										
										
										<tr class="elementor_disable_color_schemes">
											<th scope="row"><?php echo esc_html__( 'Show category','sw-tools-plugin'); ?></th>
											<td>
											    <label>
												    <input type="checkbox" id="show_categories" name="show_categories" <?php checked( $show_categories, 'on' ); ?>>
				                                </label>
										    </td>
										</tr>
										<tr class="elementor_disable_color_schemes">
											<th scope="row"><?php echo esc_html__( 'Show category empty','sw-tools-plugin'); ?></th>
											<td>
											    <label>
												    <input type="checkbox" id="show_categories_empty" name="show_categories_empty" <?php checked( $show_categories_empty, 'on' ); ?>>
				                                </label>
										    </td>
										</tr>
										
										<tr class="elementor_disable_typography_schemes">
											<th scope="row"><?php echo esc_html__( 'Show cart','sw-tools-plugin'); ?></th>
											<td>
											    <label>
											        <input type="checkbox" id="show_cart" name="show_cart" <?php checked( $show_cart, 'on' ); ?>>
										        </label>
									        </td>
										</tr>
										<tr class="elementor_disable_typography_schemes">
											<th scope="row"><?php echo esc_html__( 'Show account','sw-tools-plugin'); ?></th>
											<td>
											    <label>
											        <input type="checkbox" id="show_account" name="show_account" <?php checked( $show_account, 'on' ); ?>>
										        </label>
									        </td>
										</tr>
										<tr class="elementor_disable_typography_schemes">
											<th scope="row"><?php echo esc_html__( 'Show search','sw-tools-plugin'); ?></th>
											<td>
											    <label>
											        <input type="checkbox" id="show_search" name="show_search" <?php checked( $show_search, 'on' ); ?>>
										        </label>
									        </td>
										</tr>
										<tr class="elementor_disable_typography_schemes">
											<th scope="row"><?php echo esc_html__( 'Show recent view','sw-tools-plugin'); ?></th>
											<td>
											    <label>
											        <input type="checkbox" id="show_recent_view" name="show_recent_view" <?php checked( $show_recent_view, 'on' ); ?>>
										        </label>
									        </td>
										</tr>
										<tr class="elementor_disable_typography_schemes">
											<th scope="row"><?php echo esc_html__( 'Show backtop','sw-tools-plugin'); ?></th>
											<td>
											    <label>
											        <input type="checkbox" id="show_backtop" name="show_backtop" <?php checked( $show_backtop, 'on' ); ?>>
										        </label>
									        </td>
										</tr>											
								    </tbody>
		                       </table>
								<?php
									wp_nonce_field( 'acme-settings-save', 'acme-custom-message' );
									submit_button();
								?>							   
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
	   <?php
	}
}
SW_Tools_Plugin::get_instance();
