<div class="post_related mb-3">
<h3 class="widget-title">相关文章</h3>
<?php
global $post;
$cats = wp_get_post_categories($post->ID);
if ($cats) {
$args = array(
'category__in' => array( $cats[0] ),
'post__not_in' => array( $post->ID ),
'showposts' => 6,
'ignore_sticky_posts' => 1
);
query_posts($args);
if (have_posts()) {
while (have_posts()) {
the_post(); update_post_caches($posts); ?>

    <div class="post_related_list">
        <a class="" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
    </div>

<?php } } else { echo ''; } wp_reset_query(); } else { echo ''; } ?>
</div>