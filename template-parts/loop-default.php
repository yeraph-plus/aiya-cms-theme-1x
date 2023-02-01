<div class="post_def post_loop">
    <div class="row g-3 g-sm-4">
        <div class="col-3">
            <a class="post_def_left" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php
            if ( has_post_thumbnail() ) {
                the_post_thumbnail(array(400, 280, true));
            } else {
                echo wp_get_attachment_image(get_theme_mod('aya_nopic'), array(400, 280, true));
            }
            ?>
            </a>
        </div>
        <div class="col-9">
            <div class="post_def_right">
                <div class="post_def_title">
                    <h2><a class="" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                    <p><?php echo wp_trim_words( get_the_content(), 90 ); ?></p>
                </div>
                <div class="post_info">
                    <div class="post_info_l">
                        <span><i class="bi bi-text-left"></i><?php the_category(', ') ?></span>
                        <span class="mobile_none"><i class="bi bi-clock"></i><?php the_time('Y.m.d'); ?></span>
                        <span class=""><i class="bi bi-eye"></i><?php post_views('',''); ?>人浏览</span>
                    </div>
                    <div class="post_info_r">
                        <?php the_tags( '<em><i class="bi bi-hash"></i>', '</em><em><i class="bi bi-hash"></i>', '</em>' ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>