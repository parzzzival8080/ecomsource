<?php
	$numberposts = (int)$numberposts;
	$length_desc = (int)$length_desc;
	$length		 = (int)$length;
	$default = array(
        'post_type' => 'post',
		'post_status' => 'publish',
        'orderby' => $orderby,
        'order' => $order,
        'posts_per_page' => ($numberposts),
		'post__not_in' => get_option( 'sticky_posts' ),
    );
    if ($category != '') {
        $default['tax_query'] = array(
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => $category
            ),
            array(
                'taxonomy' => 'post_format',
                'field' => 'slug',
                'terms' => array('post-format-video'),
            )
        );
		$term = get_term_by( 'slug', $category, 'category' );
		if( $term ) :
			$term_name = $term->name;
			$viewall = get_term_link( $term->term_id, 'category' );
		endif;
    }else{
		$default['tax_query'] = array(
            array(
                'taxonomy' => 'post_format',
                'field' => 'slug',
                'terms' => array('post-format-video'),
            )
        );
	}
	
	$list = new WP_Query($default);
	$frame = array();
	$video_link = array();
	$video_img = array();
	$i = 0;
	
	$count_item = ( ($list -> found_posts) > $numberposts ) ? $numberposts : $list -> found_posts;
	while($list->have_posts()) : $list->the_post();
	global $post;
	$format = get_post_format();
	$categoryInfo = get_the_category($post->ID);

	$categoryName[$i] = $categoryInfo[0]->name;
	$categoryLink[$i] = get_category_link( $categoryInfo[0]->term_id );
	$postTime[$i]     = get_the_date('j M Y - ').'<span>'.get_the_date('H:i').'</span>';
	
	if($length_desc != 0){
		$content[$i] = ya_trim_words($post->post_content, (int)$length_desc, ' ');	
	}else{
		$content[$i] = $post->post_content;
	}
	
	if($length != 0){
		$titlePost[$i] = ya_trim_words($post->post_title, (int)$length, ' ') ;
	}else{
		$titlePost[$i] = $post->post_title;
	}
	
	$linkPost[$i] = get_permalink($post->ID);
	if( $format == 'video' ){
		$pattern = '#(www\.|https?://)?[a-z0-9]+\.[a-z0-9]{2,4}\S*#i';
		preg_match_all($pattern, $post->post_content, $matches);
		if($matches[0] != null){
			$url = $matches[0][0];
			if( _is_youtube($url) ){
				$pt_youtube = '/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/';
				preg_match($pt_youtube, $url, $match);
				if (count($match) && strlen($match[7]) == 11)
				{
					$url_id = $match[7];
					$video_link[$i] = 'http://www.youtube.com/watch?v='.$url_id;
				  
					if(has_post_thumbnail()){
						$video_img[$i] = get_the_post_thumbnail($post->ID,'onews_blog-responsive2');
					}else{ 
						$video_img[$i] = "<img src='http://img.youtube.com/vi/".$url_id."/mqdefault.jpg' alt='" . esc_attr__( 'Video Box', 'sw_video' ) ."' title='" . esc_attr__( 'Video Box', 'sw_video' ) ."'/>";
					}
					$frame[$i] = '<iframe class="box-youtube" style="width:100%; height:400px" src="//www.youtube.com/embed/'.$url_id.'" frameborder="0" allowfullscreen></iframe>';
				}
			}
			elseif( _is_vimeo($url) ){				
				$pt_vimeo = '/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/';
				preg_match($pt_vimeo, $url, $match);
				if (count($match))
				{
					$url_id =  $match[2];		  
					$frame[$i] = '<iframe src="http://player.vimeo.com/video/'.$url_id.'?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=ffffff" width="" height="" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
					$video_link[$i] = 'http://player.vimeo.com/video/'.$url_id;
					$xml_url = "http://vimeo.com/api/v2/video/$url_id.xml";
					$handle = curl_init($xml_url);
					curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
					$response = curl_exec($handle);
					$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
					if( $httpCode != 404 ){
						$hash = simplexml_load_string( @file_get_contents($xml_url) );
						$hash = json_encode($hash);
						$hash = json_decode($hash);
					}
					if(has_post_thumbnail()){
						$video_img[$i] = get_the_post_thumbnail($post->ID,'onews_blog-responsive2');
					}else{ 
						if( isset( $hash->video ) ){
							$video_img[$i] = "<img src='".esc_url( $hash->video->thumbnail_medium )."' alt='" . esc_attr__( 'Video Box', 'sw_video' ) ."' title='" . esc_attr__( 'Video Box', 'sw_video' ) ."'/>";
						}
					}
				} 
			}else{
				_e('Please insert a Youtube video link or Vimeo link to content!', 'sw_video');
			}
			// curl_close($handle);
		}
		else{  
			_e('Please insert a Youtube video link or Vimeo link to content!', 'sw_video');
		}
	}
	$i++;
	endwhile;
	wp_reset_query();
	$suffix = rand().time();
	$tag_id = 'sw_boxvideo'.$suffix;
	if( count($video_link) > 0 ){
?>
<div id="<?php echo $tag_id;?>" class="sw-videobox clearfix">
	<?php if ($title != '') { ?>
		<div class="box-title clearfix">
			<h3 class="pull-left"><span><?php echo $title; ?></span></h3>
			<a class="see-all pull-right" href="<?php echo esc_url( $viewall );?>"><?php echo esc_html__('View all','sw_core'); ?></a>
		</div>
	<?php } ?>
	<div class="wrap-content">
	<?php $dem = 1; foreach( $video_link as $key => $value ){
	?>
		<div data-url="<?php echo $value; ?>" class="sw-video-list-item">
			<div class="wrap-item">
				<div class="sw-video-image">
					<a class="popup-youtube" href="<?php echo $value?>">
						<?php echo $video_img[$key]; ?>
					</a>
				</div>	
			<?php if($display_title || $display_desc || $display_category || $display_time){?>
				<div class="sw-video-content">	
				<?php if($display_title){?>
					<div class="widget-title">
						<h4>
							<a href="<?php echo $linkPost[$key];?>">
								<?php echo $titlePost[$key];?>						
							</a>
						</h4>
						<div class="entry-date">
							<?php echo $postTime[$key];?>
						</div>
					</div>
				<?php }?>
				</div>
		<?php }?>
			</div>
		</div>	
	<?php 
		$dem++;
	} 
	?>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
    jQuery(document).ready(function($){
	   $('.popup-youtube, .popup-vimeo').magnificPopup({
          disableOn: 700,
          type: 'iframe',
          mainClass: 'mfp-fade',
          removalDelay: 160,
          preloader: false,
          fixedContentPos: false
        });

    });
//]]>
</script>
<?php
	}else{
		esc_html_e( 'Has no item to show!', 'sw_video' );
	}
?>
