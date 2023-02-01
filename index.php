<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * 
 */
get_header(); ?>

<section class="index_banner">
    <div class="container">
        <div class="row g-3">
<?php echo aya_option('aya_basic'); ?>
        </div>
    </div>
</section>


<section class="index_area">
    <div class="container">
        <div class="row g-3">
            <div class="col-lg-9">
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

<?php /*
<section class="links mobile_none">
    <div class="container">
        <span>友情链接：</span>
        <?php wp_list_bookmarks( 'title_li=&categorize=0&before=&after=' ); ?>
    </div>
</section>
*/ ?>
<?php 

get_footer(); ?>