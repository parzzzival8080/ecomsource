<div class="xs-content-header">
    <h2 class="xs-content-title version-2"><?php echo esc_html($head_title); ?></h2>

    <div class="customNavigation xs-custom-nav">    
       <div class="tab-button-prev swiper-prev-<?php echo esc_attr($this->get_id()); ?>">
           <i class="xs xs-chevron-left-solid"></i>
       </div>
       <div class="tab-button-next swiper-next-<?php echo esc_attr($this->get_id()); ?>">
           <i class="xs xs-chevron-right-solid"></i>
       </div>
    </div>
    <div class="clearfix"></div>
</div>
<div data-controls="<?php echo esc_attr($widgets_controls); ?>" class="xs-product-slider-1 swiper">
    <div class="swiper-wrapper">
        <?php
        $count = 1;
        if ($xs_query->have_posts()):
            while ($xs_query->have_posts()) :
                $xs_query->the_post();
                $xs_product = wc_get_product(get_the_id());
                $img_link = xs_resize(get_post_thumbnail_id(), 71, 70, true);
                $even = $product_per_column;
                ?>
                <?php if ($count % $even == 1): ?>
                <div class="xs-product-slider-item swiper-slide">
            <?php endif; ?>
                <div class="xs-product-widget media version-2">
                    <?php if (!empty($img_link)): ?>
                    <a class="xs_product_img_link" href="<?php echo esc_url(get_the_permalink()) ?>">
                            <?php if(!empty($img_link)){
                                        echo wp_get_attachment_image(get_post_thumbnail_id($xs_query->ID), array(71, 70), false, array(
                                            'alt'  =>  get_the_title()
                                        ));
                                    }
                            ?>
                    </a>
                    <?php endif; ?>
                    <div class="media-body align-self-center product-widget-content">
                        <a class="product-title small" href="<?php echo esc_url(get_the_permalink()); ?>"><?php echo get_the_title(); ?></a>
                        
                        <span class="price small"><?php echo marketo_return($xs_product->get_price_html()); ?> </span>
                    </div><!-- .product-widget-content .version-2 END -->
                </div>
                <?php if ($count % $even == 0): ?>
                </div>
            <?php endif; ?>
                <?php
                $count++;
            endwhile;
            if ($count % $even != 1) echo "</div>";
            wp_reset_postdata();
        endif;
        ?>
    </div>
</div>