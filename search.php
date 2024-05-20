<?php
/**
 * The template for displaying search results pages
 *
 */

get_header(); ?>
<!--main-->
<section class="index-main py-3">
    <div class="container">
        <div class="row g-3">
            <div class="col-lg-9">
                <!--search-->
                <?php the_archive_des(); ?>
                <!--loop-->
                <?php loop_layout(); ?>
            </div>
            <div class="col-lg-3">
                <!--sidebar-->
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>