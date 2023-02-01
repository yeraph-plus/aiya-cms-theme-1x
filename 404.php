<?php 
/**
 * The template for displaying 404 pages (not found)
 * 
 */

get_header();?>

<style>
    .header,.footer{display: none;}
</style>
<main style="display:flex;flex-direction:column;justify-content:center;align-items:center;height:100vh;">
    <h2 class="h1 mt-5 mb-3">这是一个404页面</h2>
    <p class="h5 f300 mb-5"><img src="<?php bloginfo('template_directory'); ?>/assets/404.png"></p>
    <p class="h5 f300 mb-5">正如你所看到的那样，这里什么都没有。</p>
    <p class="h5 f300 mb-5">不过至少你还可以<a href="<?php bloginfo('url'); ?>" title="返回首页" class="border-bottom">返回首页</a>。</p>
</main>

<?php 

get_footer(); 
?>