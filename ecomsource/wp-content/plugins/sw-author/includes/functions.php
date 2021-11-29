<?php 
/**
* Custom function 
**/

include( SAPATH . 'includes/widgets/sw-widgets.php' );
include( SAPATH . 'includes/shortcodes/sw-author-categories.php' );

/*
** Check override template
*/
function sw_author_override_check( $path, $file ){
	$paths = '';
	if( locate_template( 'author/'.$path . '/' . $file . '.php' ) ){
		$paths = locate_template( 'author/'.$path . '/' . $file . '.php' );
	}else{
		$paths = SAPATH . 'includes/' . $path . '/' . $file . '.php';
	}
	return $paths;
}
function sw_author_info(){
	global $post;
	$authors = get_post_meta( $post->ID, 'author_product', true );
	
	if( !is_array( $authors ) && count( $authors ) < 1 ){
		return;
	}	
	$html = '<div class="author-info">';
	foreach( $authors as $key => $author ){
		$str = ( $key == 0 ) ? '' : '';
		$html .= $str .	'<div class="item-inner">';
		$html .= $str . '<div class="item-images">'. get_the_post_thumbnail( $author , 'medium' ) .'</div>';
		$html .= $str . '<div class="item-content custom-font">';	
		$html .= $str . '<a href="'. get_permalink( $author ) .'">'. get_the_title( $author ) .'</a>';
		$html .= $str . '<div class="decription">'. wp_trim_words( get_the_content($author), 55, '...' ).'</div>';
		$html .= $str .'</div>';
		$html .= $str .'</div>';
	}
	$html .= '</div>';
	echo apply_filters( 'sw_author_list_product_filter', $html );
}
add_action( 'author_info', 'sw_author_info' );
/*
** List author in product
*/
function sw_author_list_in_product(){
	global $post;
	$authors = get_post_meta( $post->ID, 'author_product', true );
	
	if( !is_array( $authors ) && count( $authors ) < 1 ){
		return;
	}	
	$html = '<div class="author-listing-product">';
	foreach( $authors as $key => $author ){
		$str = ( $key == 0 ) ? '' : ', ';
		$html .= $str . '<a href="'. get_permalink( $author ) .'">'. get_the_title( $author ) .'</a>';
	}
	$html .= '</div>';
	echo apply_filters( 'sw_author_list_product_filter', $html );
}
add_action( 'sw_author_listing_product', 'sw_author_list_in_product' );

/*
** Set number of Author show in archive
*/
function sw_author_set_posts_per_page( $query ){
	global $wp_the_query;
	if ( ( ! is_admin() ) && ( $query === $wp_the_query ) && ( isset( $query->query['post_type'] ) && $query->query['post_type'] == 'book_author' ) ) {
		$query->set( 'posts_per_page', -1 );
	}
	return $query;
}
add_action( 'pre_get_posts',  'sw_author_set_posts_per_page', 10 );


// Get template content
function sw_author_template_load( $template ){
	// Hotel template check
	if( is_post_type_archive( 'book_author' ) || is_tax( 'author_cat' ) ){
		$template = sw_author_override_check( 'loop', 'archive-author' );
	}
	if( is_singular( 'book_author' ) ){
		$template = sw_author_override_check( 'single-author', 'single-author' );
	}
	
	//event template check
	if( is_post_type_archive( 'event' ) || is_tax( 'event_cat' ) ){
		$template = sw_author_override_check( 'event', 'archive-event' );
	}
	if( is_singular( 'event' ) ){
		$template = sw_author_override_check( 'event', 'single-event' );
	}
	
	if( is_search() ){
		$search_author = isset( $_GET['search_author'] ) ? $_GET['search_author'] : 0;
		if( $search_author ){
			$template = sw_author_override_check( 'search', 'search-author' );
		}
	}
	return $template;
}
add_filter( 'template_include', 'sw_author_template_load' );


/*
** Enqueue script frontend
*/
function sw_author_enqueue_script(){
	if( is_post_type_archive( 'book_author' ) || is_singular( 'product' ) ) {
		$args = array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ), 
			'id' => get_the_ID(),
			'preview_text' => esc_html__( 'Preview', 'sw_author' ),
			'wc_quantity' => esc_url( SAURL . '/js/wc-quantity-increment.min.js' )
		);
		wp_register_script( 'sw-author-frontend', SAURL . '/js/author-frontend-script.js', array( 'jquery' ), null, true );
		wp_localize_script( 'sw-author-frontend', 'sw_author_frontend', $args );
		wp_enqueue_script( 'sw-author-frontend' );
	}
	if( is_singular( 'product' ) ){
		wp_enqueue_script( 'pdf', SAURL . '/js/pdf.js', array( 'jquery' ), null, true  );
	}
}
add_action( 'wp_enqueue_scripts', 'sw_author_enqueue_script' );

/**
* Add sidebar to single author
**/
function sw_author_widgets_init(){
	register_sidebar(
		array(
			'name' => esc_html__( 'Bottom Detail Author', 'sw_author' ),
			'id'   => 'bottom_detail_author',
			'before_widget' => '<div class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>'
		)
	);
}
add_action( 'widgets_init', 'sw_author_widgets_init', 100 );
function sw_count_author(){
	global  $post;
	$bookpl = get_post_meta( $post->ID, 'bookpl', true );
		if($bookpl !='' ){ 
	?>

	 <div class="item-meta">

        <?php echo ( $bookpl != '' ) ? '<span class="meta-value">' . $bookpl . '</span><span class="label-meta"> '. esc_html__( 'Published Books', 'sw_author' ) .' </span>'	: ''; ?>
    </div>
	<?php
	}
}
add_action( 'count_author', 'sw_count_author');
/**
* Get all info on single author
**/
function sw_social_author(){

	global  $post;
	$age = get_post_meta( $post->ID, 'age', true );
	$languages = get_post_meta( $post->ID, 'languages', true );
	$bookpl = get_post_meta( $post->ID, 'bookpl', true );
	if($age != '' || $languages != '' || $bookpl !='' ){ 
?>
<div class="Categories">
    <div class="item-meta">
        <?php echo ( $age != '' ) ? '<span class="label-meta">'. esc_html__( 'Age', 'sw_author' ) .': </span><span class="meta-value"> ' . $age . ' </span>'	: ''; ?>
    </div>
    <div class="item-meta">
        <?php echo ( $languages != '' ) ? '<span class="label-meta">'. esc_html__( 'Languages', 'sw_author' ) .': </span><span class="meta-value"> ' . $languages . ' </span>'	: ''; ?>
    </div>
    <div class="item-meta">
    		
        <?php echo ( $bookpl != '' ) ? '<span class="label-meta">'. esc_html__( 'Books Pulished', 'sw_author' ) .': </span><span class="meta-value"> ' . $bookpl . ' </span>'	: ''; ?>
    </div>
</div>

<?php 
	}
}
add_action( 'social_author_sw', 'sw_social_author');

function sw_share_author(){
	global  $post;
	$facebook = get_post_meta( $post->ID, 'facebook', true );	
	$twitter = get_post_meta( $post->ID, 'twitter', true );
	$instagram = get_post_meta( $post->ID, 'instagram', true );
	$linkedin = get_post_meta( $post->ID, 'linkedin', true );
		if( $facebook != '' || $twitter != '' || $instagram != '' || $linkedin != '' ){ 
?>
<div class="item-social">
    <?php if( $facebook != '' ){ ?>
    <div class="team-facebook">
        <a href="<?php echo esc_attr( $facebook ); ?>"><i class="fa fa-facebook"></i></a>
    </div>
    <?php } ?>
    <?php if( $twitter != '' ){ ?>
    <div class="team-twitter">
        <a href="<?php echo esc_attr( $twitter ); ?>"><i class="fa fa-twitter"></i></a>
    </div>
    <?php } ?>
    <?php if( $instagram != '' ){ ?>
    <div class="team-instagram">
        <a href="<?php echo esc_attr( $instagram ); ?>"><i class="fa fa-instagram"></i></a>
    </div>
    <?php } ?>
    <?php if( $linkedin != '' ){ ?>
    <div class="team-linkedin">
        <a href="<?php echo esc_attr( $linkedin ); ?>"><i class="fa fa-linkedin"></i></a>
    </div>
    <?php } ?>
</div>
<?php 
	}
}
add_action( 'sw_share_author_sw', 'sw_share_author');
/**
* Get all award on single author
**/
function sw_single_author_awards(){
	if( !is_single() ){
		return;
	}
	global $post;
	$award_title  = get_post_meta( $post->ID, 'sw_awards_title', true );
	$award_year  = get_post_meta( $post->ID, 'sw_awards_year', true );
	$award_book  = get_post_meta( $post->ID, 'sw_awards_book', true );
	
	if( is_array( $award_title ) && is_array( $award_year ) && is_array( $award_book ) ) { 
?>
	<div class="single-awards">
		<h2 class="custom-font"><?php echo esc_html__( 'Awards & Honorable Mentions', 'sw_author' ); ?></h2>
		<ul class="single-awards-wrapper">
			<?php foreach( $award_title as $key => $award ){ ?>
				<li class="awards-item">
					<h4><?php echo esc_html( $award ); ?></h4>
					<div class="item-content">
						<?php echo isset( $award_year[$key] ) ? '<span class="award-year">'. esc_html( $award_year[$key] ) .'</span>' : ''; ?>
						<?php echo isset( $award_book[$key] ) ? '<span class="award-book">' . esc_html(  $award_book[$key] ) .'</span>' :  '' ; ?>
					</div>
				</li>
			<?php }	?>
		<ul>
	</div>
<?php 
	}
}
add_action( 'sw_author_bottom_detail', 'sw_single_author_awards', 10 );


function sw_single_product_by_author(){
	if( is_active_sidebar( 'bottom_detail_author' ) ){
?>
	<div class="bottom-sidebar-author">
		<?php dynamic_sidebar( 'bottom_detail_author' ); ?>
	</div>
<?php 
	}
}
add_action( 'sw_author_bottom_detail', 'sw_single_product_by_author', 15 );

function sw_author_filter_callback(){
	$character 	 = ( isset( $_POST["character"] ) ) ? $_POST["character"] : 'all';	
	global $wpdb;
	$query = '';
	if( $character == 'all' ){
		$query = $wpdb->prepare( "SELECT SQL_CALC_FOUND_ROWS {$wpdb->posts}.ID FROM {$wpdb->posts} WHERE {$wpdb->posts}.post_type=%s
		AND ({$wpdb->posts}.post_password = '') AND ({$wpdb->posts}.post_status = 'publish') 
		ORDER BY {$wpdb->posts}.post_title DESC", 'book_author' );
	}else{
		$query = $wpdb->prepare( "SELECT SQL_CALC_FOUND_ROWS {$wpdb->posts}.ID FROM {$wpdb->posts} WHERE {$wpdb->posts}.post_title LIKE %s AND {$wpdb->posts}.post_type='book_author'
		AND ({$wpdb->posts}.post_password = '') AND ({$wpdb->posts}.post_status = 'publish') 
		GROUP BY {$wpdb->posts}.ID 
		ORDER BY {$wpdb->posts}.post_title LIKE %s DESC", $character . '%', $character . '%' );
	}
	
	$postids = $wpdb->get_col( $query );
	if( count( $postids ) ){
		foreach( $postids as $key => $post_id ){
?>
	<div id="post-<?php echo esc_attr( $post_id );?>" class="<?php echo implode( ' ', get_post_class( $post_id ) ); ?>">
		<a href="<?php echo get_permalink( $post_id ); ?>">
			<div class="item-img"><?php echo get_the_post_thumbnail( $post_id, 'medium' ); ?></div>
			<h4><?php echo get_the_title( $post_id ); ?></h4>
		</a>
	</div>
<?php 	
		}
	}else{
		echo '<div class="alert alert-warning alert-dismissible" role="alert">
		<a class="close" data-dismiss="alert">&times;</a>
		<p>'. esc_html__( 'There is not author with this letter', 'sw_author' ) .'</p>
		</div>';
	}
	exit(0);
}
add_action( 'wp_ajax_sw_author_filter', 'sw_author_filter_callback' );
add_action( 'wp_ajax_nopriv_sw_author_filter', 'sw_author_filter_callback' );

function sw_product_preview_callback(){
	$attach_id 	 = ( isset( $_POST["attach_id"] ) ) ? $_POST["attach_id"] : 0;	
	$meta_id = get_post_meta( $attach_id, 'author_product_preview', true );
	$query_args = array(
		'post_type'	=> 'product',
		'p'	=> $attach_id
	);
	$outputraw = $output = '';
	$r = new WP_Query( $query_args );
	
	if($r->have_posts()){ 
		while ( $r->have_posts() ){ $r->the_post(); setup_postdata( $r->post );
		global $product;
?>
	<div class="product-preview product_detail">
		<h2 class="custom-font"><?php echo get_the_title( $attach_id ); ?></h2>		
		<div id="product_preview">
			<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
		</div>
		<div class="product-preview-bottom product content_product_detail">
			<?php wc_get_template( 'single-product/add-to-cart/simple.php' ); ?>
		</div>
	</div>
<?php 
		}; wp_reset_postdata();
	} 
?>
	<script>
		var url = '<?php echo wp_get_attachment_url( $meta_id ) ?>';

		var pdfjsLib = window['pdfjs-dist/build/pdf'];
		pdfjsLib.GlobalWorkerOptions.workerSrc = '<?php echo esc_url( SAURL . '/js/pdf.worker.js' ) ?>';
		var pdfDoc = null;
		var scale = 1.2;
		function onNextPage() {
		  if (pageNum >= pdfDoc.numPages) {
			return;
		  }
		  pageNum++;
		  queueRenderPage(pageNum);
		}

		pdfjsLib.getDocument(url).promise.then(function(pdf) {
			pdfDoc = pdf;
			viewer = document.getElementById('product_preview');

			for(page = 1; page <= pdf.numPages; page++) {
			  canvas = document.createElement("canvas");    
			  canvas.className = 'pdf-page-canvas';         
			  viewer.appendChild(canvas);            
			  renderPage(page, canvas);
			}
		});	

		function renderPage(num, canvas) {
		  pageRendering = true;
		  pdfDoc.getPage(num).then(function(page) {
			var viewport = page.getViewport({scale: scale});
			canvas.height = viewport.height;
			canvas.width = viewport.width;

			var renderContext = {
			  canvasContext: canvas.getContext('2d'),
			  viewport: viewport
			};
			var renderTask = page.render(renderContext);		
		  });

		}
	</script>
<?php 
	exit(0);
}
add_action( 'wp_ajax_sw_product_preview', 'sw_product_preview_callback' );
add_action( 'wp_ajax_nopriv_sw_product_preview', 'sw_product_preview_callback' );