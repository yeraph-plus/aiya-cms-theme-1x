<div class="post-loop mb-2">
    <div class="post-card post-image card p-2">
        <div class="post-thumb">
            <img loading="lazy" src="<?php echo get_post_thumb(0, 800, 400); ?>">
        </div>
        <div class="post-info">
            <div class="post-title pb-1">
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_title(); ?></a>
            </div>
            <div class="post-tag py-1">
                <em class="cat"><i class="bi bi-list"></i> <?php the_category(', '); ?></em>
                <?php the_tags( '<em><i class="bi bi-hash"></i>', '</em><em><i class="bi bi-hash"></i>', '</em>' ); ?>
            </div>
            <div class="post-words py-1">
                <?php the_post_preview(); ?>
            </div>
            <div class="post-meta pt-2">
                <?php the_post_meta(); ?>
            </div>
        </div>
    </div>
</div>