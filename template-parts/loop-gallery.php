<div class="post_gallery post_loop">
    <div class="post_gallery_head">
    <h2><a class="stretched-link" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
    <?php
    $i = 3;
    $attachments = get_attached_media( 'image', $post->ID );
    if ($attachments) { ?>
    <div class="row row-cols-4 g-3 mb-3">
    <?php $count = 0; foreach ( $attachments as $value) { ?>
        <div class="col">
            <?php echo wp_get_attachment_image($value->ID, array(400, 240, true)); ?>
        </div>
    <?php if( $count == $i ) break; $count++; } ?>
    </div>
    <?php } ?>
    </div>
    <div class="post_info">
        <div class="post_info_l">
            <span><i class="bi bi-text-left"></i><?php the_category(', ') ?></span>
            <span class="mobile_none"><i class="bi bi-clock"></i><?php the_time('Y.m.d'); ?></span>
            <span class=""><i class="bi bi-eye"></i><?php post_views('',''); ?>人浏览</span>
        </div>
        <div class="post_info_r">
            <?php the_tags( '<em><i class="bi bi-hash"></i>', '</em><em><i class="bi bi-hash"></i>', '</em>' ); ?>
        </div>
    </div>
</div>