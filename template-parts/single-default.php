<div class="content-area card p-3 mb-2">
    <h1 class="single-title py-1">
        <?php the_post_title(); ?>
    </h1>
    <div class="single-meta py-2">
        <?php the_single_meta(); ?>
    </div>
    <div class="single-article border-top py-2">
        <article class="entry-main">
            <?php
            the_content();
            wp_link_pages(array('before' => '<div class="page-links">', 'after'  => '</div>',));
            ?>
        </article>
        <?php single_check_zan(); ?>
    </div>
    <div class="single-tag py-2">
        <em class="single-cat"><i class="bi bi-list"></i> <?php the_category(', '); ?></em>
        <?php the_tags('<em><i class="bi bi-hash"></i>', '</em><em><i class="bi bi-hash"></i>', '</em>'); ?>
    </div>
    <div class="single-declare py-2">
        <?php the_single_declare(); ?>
    </div>
    <div class="single-author border-top pt-2">
        <?php the_single_author(); ?>
    </div>
</div>
<?php do_action('aya_single_after'); ?>