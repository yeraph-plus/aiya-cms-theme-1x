<?php
/**
 * The template for displaying all single posts and attachments
 *
 */

get_header(); ?>
<section class="index-single py-3">
    <div class="container">
        <div class="row g-3">
            <div class="col-lg-9">
                <!--single-->
                <?php
                while (have_posts()) : the_post();
                    switch (get_post_type()){
                        case 'tweet':
                            get_template_part('template-parts/single', 'tweet');
                            break;
                        default:
                            get_template_part('template-parts/single', 'default');
                            break;
                    }
                endwhile;
                ?>
                <!--comments-->
                <?php 
                if (comments_open() || get_comments_number()){
                    //输出
                    comments_template();
                } 
                ?>
            </div>
            <div class="col-lg-3">
                <!--sidebar-->
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</section>
<?php get_footer();?>