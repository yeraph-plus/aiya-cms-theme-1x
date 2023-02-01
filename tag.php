<?php 
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 */

get_header(); ?>

<section class="index_area">
    <div class="container">
        <div class="row g-3">
            <div class="col-lg-9">
                <div class="cat_info_top">
                    <h2><?php single_tag_title(); ?></h2>
                    <p><?php echo category_description();?></p>
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