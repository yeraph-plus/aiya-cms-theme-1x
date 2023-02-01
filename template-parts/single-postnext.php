<div class="next_prev_posts">
<?php
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    if(!empty($prev_post)):?>
    <div class="prev_next_box nav_previous"  style="<?php if ($next_post) {} else { echo 'width:100%'; }?>" >
        <a href="<?php echo get_permalink($prev_post->ID);?>" title="<?php echo $prev_post->post_title;?>" rel="prev" style="background-image: url(<?php
            if ( has_post_thumbnail($prev_post->ID) ) {
                $data = wp_get_attachment_image_src(get_post_thumbnail_id($prev_post->ID), array(800,200,true)); echo $data[0];
            } else {
                $data = wp_get_attachment_image_src(get_theme_mod('aya_nopic'), array(800,200,true)); echo $data[0];
            } ?>);">
        <div class="prev_next_info">
            <small>上一篇</small>
            <p><?php echo $prev_post->post_title;?></p>
        </div>
        </a>
    </div>
<?php endif;?>
<?php
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    if(!empty($next_post)):?>
    <div class="prev_next_box nav_next" style="<?php if ($prev_post) {} else { echo 'width:100%'; }?>">
        <a href="<?php echo get_permalink($next_post->ID);?>" title="<?php echo $next_post->post_title;?>" rel="next" style="background-image: url(<?php
            if ( has_post_thumbnail($next_post->ID) ) {
                $data = wp_get_attachment_image_src(get_post_thumbnail_id($next_post->ID), array(800,200,true)); echo $data[0];
            } else {
                $nopic = wp_get_attachment_image_src(get_theme_mod('aya_nopic'), array(800,300,true)); echo $nopic[0];
            } ?>);">
        <div class="prev_next_info">
            <small>下一篇</small>
            <p><?php echo $next_post->post_title;?></p>
        </div>
        </a>
    </div>
<?php endif;?>
</div>