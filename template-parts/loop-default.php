<?php
/**
 * 默认卡片
 */
?>
<div class="post-loop mb-2">
    <div class="post-card card p-2">
        <div class="row g-3">
            <div class="post-thumb col-4 col-lg-3">
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><img loading="lazy" src="<?php the_post_thumb(); ?>"></a>
            </div>
            <div class="post-info col-8 col-lg-9">
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
                <div class="post-meta py-2">
                    <?php the_post_meta(); ?>
                </div>
            </div>
        </div>
    </div>
</div>