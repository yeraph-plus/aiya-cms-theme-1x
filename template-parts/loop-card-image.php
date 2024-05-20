<div class="post-loop col">
    <div class="post-card post-image post-grid card p-2">
        <div class="post-thumb">
            <img loading="lazy" src="<?php echo get_post_thumb(0, 300, 400); ?>">
        </div>
        <div class="grid-image-info">
            <div class="grid-title py-2">
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_title(); ?></a>
            </div>
            <div class="grid-meta pt-2">
                <?php the_post_card_meta(); ?>
            </div>
        </div>
    </div>
</div>