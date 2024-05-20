<?php
/**
 * 默认卡片
 */
?>
<div class="post-loop col">
    <div class="post-card post-grid card p-2">
        <div class="post-thumb">
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><img loading="lazy" src="<?php the_post_thumb(); ?>"></a>
        </div>
        <div class="post-info">
            <div class="grid-title py-2">
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_title(); ?></a>
            </div>
            <div class="grid-meta pt-2">
                <?php the_post_card_meta(); ?>
            </div>
        </div>
    </div>
</div>