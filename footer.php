<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 */

$start_load = sprintf('%d 次查询 用时 %.3f 秒, 耗费了 %.2fMB 内存', get_num_queries() , timer_stop(0, 3) , memory_get_peak_usage() / 1024 / 1024);

$site_copyright = '<p>'.aya_option('cop_text').'</p>';
$site_load_time = '<p>'.$start_load.'</p>';
$site_beian = '<a class="beian" href="https://beian.miit.gov.cn/" rel="external nofollow" target="_blank" title="备案号"><i class="bi bi-shield-check me-1"></i>'.aya_option('beian').'</a>';

$footer_js = '<script>'.aya_option('footer_js').'</script>';
?>

<footer class="footer">
	<section class="footbox">
	    <div class="container">
	    	<div class="foot">
		    	<div class="copyright">
				<?php echo $site_copyright;?>
				<?php if( aya_option('load_time') == true): echo $site_load_time; endif;?>
		    	</div>
		    	<div class="foot_nav">
					<?php wp_nav_menu(
					    array(
					    'theme_location'  => 'footnav',
					    'container'       => 'nav',
					    'container_class' => 'dbdh',
					    'depth'           => 1,
					    )
					);
					?>
		    	</div>
				<?php if( aya_option('show_beian') == true): echo $site_beian; endif;?>
		    </div>
	    </div>
	</section>
	<button class="scrollToTopBtn" title="返回顶部"><i class="bi bi-chevron-up"></i></button>
</footer>

<?php echo $footer_js;?>
<?php wp_footer();?>
</body>
</html>