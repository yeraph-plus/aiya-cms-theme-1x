<?php 
/**
 * The template for displaying 404 pages (not found)
 * 
 */

get_header(); ?>
<section class="index-not-found">
    <div class="container">
        <div class="page-not-found">
            <p class="h1 mt-5 mb-3">这是一个404页面</p>
            <p class="h5 f300 mb-5"><img loading="lazy" src="<?php echo get_template_directory_uri().'/image/404.png'; ?>"></p>
            <p class="h5 f300 mb-5">如你所见，这里什么都没有。</p>
            <p class="h5 f300 mb-5">不过至少你还可以 <a href="<?php bloginfo('url'); ?>" title="返回首页" class="border-bottom">返回首页</a> 。</p>
        </div>
    </div>
</section>
<?php get_footer();?>