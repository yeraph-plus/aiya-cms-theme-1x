<div class="content-area card p-3 mb-2">
    <span class="single-tweet-read">
        <i class="bi bi-quote"></i>
    </span>
    <div class="single-author single-tweet pb-3">
        <?php the_single_author(); ?>
    </div>
    <div class="single-article border-top py-2">
        <article class="entry-main">
        <h1><?php the_post_title(); ?></h1>
        <?php
        the_content();
        ?>
        </article>
        <?php single_check_zan(); ?>
    </div>
    <div class="single-meta border-top pt-3">
        <?php the_tweet_meta(); ?>
    </div>
</div>