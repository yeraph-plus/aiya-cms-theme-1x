<?php
/**
 * Template Name: 归档页面
 */
$year = '1970';
$mon = '01';
get_header(); ?>
<section class="index-single py-3">
    <div class="container">
        <div class="row g-3">
            <div class="col-lg-9">
                <!--page-->
                <div class="content-area card p-3 mb-2">
                <h1 class="single-title pb-2">
                    <?php the_post_title(); ?>
                </h1>
                <div class="single-article border-top">
                    <article class="entry-main">
				    <?php
                        query_posts('posts_per_page=-1&ignore_sticky_posts=1' ); 
                        while (have_posts()) : the_post();

                            $post_year = get_the_time('Y');
                            $post_mon = get_the_time('m');

                            if ($year != $post_year){
                                $year = $post_year;
                                echo '<h3 class="year">'.$year.'年</h3>';
                            }
                            if ($mon != $post_mon){
                                $mon = $post_mon;
                                echo '<h5 class="mon">'.$mon.'月</h5>';
                            }
                            echo '<p>'. get_the_time('Y-m-d') .'：<a href="'. get_permalink() .'">'. get_post_title() .'</a></p>';
                        endwhile; wp_reset_query();
					?>
                    </article>
                </div>
            </div>
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