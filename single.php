<?php 
/**
 * The template for displaying all single posts and attachments
 *
 */

get_header();?>

<section class="index_area">
    <div class="container">
        <div class="row g-3">
            <div class="col-lg-9">
        		<div class="post_container_title">
		    		<h1><?php the_title(); ?></h1>
		    		<p>
		    			<span><i class="bi bi-clock"></i><?php the_time('Y-m-d'); ?></span>
		    			<span><i class="bi bi-eye"></i><?php post_views('',''); ?></span>
		    			<span><i class="bi bi-chat-square-text"></i><?php echo get_post($post->ID)->comment_count; ?></span>
		    		</p>
		    	</div>
            	<div class="post_container">
					<?php while( have_posts() ): the_post(); $p_id = get_the_ID(); ?>
					<article class="wznrys">
					<?php the_content(); ?>
					</article>
					<?php endwhile; ?>
				</div>
				<div class="post_author">
					<div class="post_author_l">
						<?php echo get_avatar(get_the_author_meta('email'), 30); ?>
						<span><?php the_author_meta('nickname'); ?></span>
					</div>
					<div class="post_author_r">
						<div class="post_author_tag">
							<?php the_tags( '<em><i class="bi bi-hash"></i>', '</em><em><i class="bi bi-hash"></i>', '</em>' ); ?>
							<em><i class="bi bi-list"></i><?php the_category(', ') ?></em>
						</div>

						<div class="post_author_icon">
							<a href="#post_comment_anchor"><i class="bi bi-chat-square-dots-fill"></i><?php echo number_format_i18n( get_comments_number() );?></a>
							<a href="javascript:;" data-action="ding" data-id="<?php the_ID(); ?>" class="specsZan <?php if(isset($_COOKIE['specs_zan_'.$post->ID])) echo 'done';?>"><i class="bi bi-hand-thumbs-up-fill"></i><small class="count"><?php if( get_post_meta($post->ID,'specs_zan',true) ){echo get_post_meta($post->ID,'specs_zan',true);} else {echo '0';}?></small>
							</a>
						</div>
					</div>
				</div>
				<div class="post_comment" id="post_comment_anchor">
					<?php
					if ( comments_open() || get_comments_number() ) :
					    comments_template();
					endif;
					?>
				</div>
            </div>
            <?php if (wp_is_mobile()) { } else { ?>
            <div class="col-lg-3">
                <div class="sidebar_sticky">
                    <?php if ( is_active_sidebar( 'single_widgets' ) ) : ?>
                        <?php dynamic_sidebar( 'single_widgets' ); ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>


<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/assets/highlight/atom-one-light.css">
<script src="<?php bloginfo('template_directory'); ?>/assets/highlight/highlight.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/assets/highlight/highlightjs-line-numbers.min.js"></script>
<script>
    hljs.initHighlightingOnLoad();
    $(document).ready(function() {
        $('.wznrys pre code').each(function(i, block) {
            hljs.lineNumbersBlock(block);
        });
    });
</script>

<?php 

get_footer(); ?>