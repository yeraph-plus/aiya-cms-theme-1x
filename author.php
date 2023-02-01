<?php
get_header(); ?>

<section class="index_area">
    <div class="container">
        <div class="row g-3">
            <div class="col-lg-9">
                <div class="cat_info_top">
                    
                </div>
                <div class="post_box">
                    <?php while( have_posts() ): the_post(); 
                        if ( has_post_format( 'gallery' )) {
                            get_template_part( 'template-parts/loop', 'gallery' );
                        } else if  ( has_post_format( 'image' )) { 
                            get_template_part( 'template-parts/loop', 'image' );
                        } else{ 
                            //标准 
                            get_template_part( 'template-parts/loop', 'default' );
                        } 
                    endwhile; ?>
                </div>
                <?php get_posts_nav(); ?>
            </div>
            <?php get_sidebar() ?>
        </div>
    </div>
</section>

<?php 

get_footer(); ?>