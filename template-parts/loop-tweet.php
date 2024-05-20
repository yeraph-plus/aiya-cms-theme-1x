<div class="post-loop mb-2">
    <div class="post-card post-tweet card p-2">
        <a class="post-tweet-read" href="<?php the_permalink(); ?>" title="阅读全文"><i class="bi bi-quote"></i></a>
        <div class="post-author pb-2">
            <?php home_tweet_author(); ?>
        </div>
        <div class="post-info py-1">
            <?php home_tweet_concent(); ?>
        </div>
        <div class="post-thumb">
            <?php home_tweet_fancybox_loop(); ?>
        </div>
        <div class="post-meta pt-2">
            <?php home_tweet_meta(); ?>
        </div>
    </div>
</div>