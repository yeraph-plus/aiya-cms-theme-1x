<?php
/**
 * 
 */
$vars = $_GET['var'];
get_header(); ?>
<!--main-->
<section class="index-main py-3">
    <div class="container">
        <div class="row g-3">
            <div class="col-lg-9">
                <!--query-->
                <div class="query-info-card card p-2 mb-2 col">
                    <?php echo $vars; ?>
                </div>
                <!--loop-->
                <div class="post-loop">
                    <?php do_action('aya_loop_before'); ?>
                    <?php
                    //自定义LOOP
                    $the_query = new WP_Query($vars);
                    while ($the_query->have_posts()){
                        $the_query->the_post();
                        get_template_part('template-parts/loop', loop_post_type());
                    }
                    //重置查询
                    wp_reset_query();
                    //如果没有文章
                    if (!have_posts()){
                        get_template_part('template-parts/loop', 'none');
                    }
                    ?>
                    <?php do_action('aya_loop_after'); ?>
                </div>
                <div class="post-nav">
                    <?php loop_paged_type() ?>
                </div>
            </div>
            <div class="col-lg-3">
                <!--sidebar-->
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>