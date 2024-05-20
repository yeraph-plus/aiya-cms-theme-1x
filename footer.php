<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 */
?>
<!--footer-->
<?php do_action('aya_footer'); ?>
<footer class="footer py-3">
    <div class="container">
        <div class="row g-3">
            <div class="col-lg-6 footer-logo mobile-hide">
                <?php header_logo(); ?>
            </div>
            <div class="col-lg-6 footer-menu">
                <?php aya_menu_nav('footer-menu', 2); ?>
            </div>
            <div class="col-lg-6 footer-copyright mobile-hide">
                <?php footer_copyright();?>
            </div>
            <div class="col-lg-6 footer-beian">
                <?php footer_beian();?>
            </div>
        </div>
    </div>
</footer>
<?php wp_footer();?>
</body>
</html>