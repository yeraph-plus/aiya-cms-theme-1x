<?php
/**
 * Template Name: 标签云页面
 */

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
                    <div class="single-article py-3 border-top">
                        <article class="entry-main">
                            <?php
                            while (have_posts()) : the_post();
                                the_content();
                            endwhile;
                            ?>
                        </article>
                    </div>
                    <div class="single-tagcloud">
                        <?php
                            $tags = get_tags();
                            foreach($tags as $tag){
                                $count = intval($tag->count);
                                $name = apply_filters('the_title', $tag->name);
                                $url = esc_attr(get_tag_link($tag->term_id));

                                echo '<a href="'.$url.'" class="tag-item" title="浏览和'.$name.'有关的文章">'.$name.'<span>+'.$count.'</span></a>';
                            }
                        ?>
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