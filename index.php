<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * 
 */


get_header(); ?>
<!--banner-->
<?php do_action('aya_index_banner'); ?>
<!--main-->
<section class="index-main py-3">
    <div class="container">
        <!--custom-loop-->
        <?php do_action('aya_index_custom'); ?>
        <div class="row g-3">
            <div class="col-lg-9">
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
<?php do_action('aya_index_after'); ?>
<?php get_footer(); ?>