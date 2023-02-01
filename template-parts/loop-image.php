<div class="post_images post_loop">
    <?php
    if ( has_post_thumbnail() ) {
        the_post_thumbnail(array(920, 400, true));
    } else {
        echo wp_get_attachment_image(get_theme_mod('aya_nopic'), array(920, 400, true));
    }
    ?>
    <div class="post_images_foot">
        <h2><a class="stretched-link" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
        <p><?php echo wp_trim_words( get_the_content(), 150 ); ?></p>
        <div class="post_info">
            <div class="post_info_l">
                <span><i class="bi bi-text-left"></i><?php the_category(', ') ?></span>
                <span><i class="bi bi-clock"></i><?php the_time('Y.m.d'); ?></span>
                <span><i class="bi bi-eye"></i><?php post_views('',''); ?>人浏览</span>
            </div>
        </div>
    </div>
</div>