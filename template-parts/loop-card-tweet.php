<div class="post-loop col">
    <div class="post-card post-tweet post-grid card p-2">
        <span class="post-tweet-read"><i class="bi bi-quote"></i></span>
        <div class="post-author pb-2">
            <?php home_tweet_author(); ?>
        </div>
        <div class="grid-tweet-info py-1">
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_title(); ?></a>
        </div>
        <div class="grid-meta pt-2">
            <?php home_tweet_meta(); ?>
        </div>
    </div>
</div>