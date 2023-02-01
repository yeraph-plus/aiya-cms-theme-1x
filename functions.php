<?php
/**
 * Twenty Sixteen functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * @link https://developer.wordpress.org/themes/advanced-topics/child-themes/
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://developer.wordpress.org/plugins/}
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

if ( version_compare( $GLOBALS['wp_version'], '5.6-alpha', '<' ) ) {
	wp_die('请升级WordPress到5.6以上版本');
}
/*
 * ------------------------------------------------------------------------------
 * 加载外置程序
 * ------------------------------------------------------------------------------
 */

//加载codestar后台设置框架
require get_template_directory(). '/codestar-framework/codestar-framework.php';
//加载framework设置项
require get_template_directory(). '/codestar-framework/codestar-framework-path.php'; 

//预定义类
//thumbnails.class.php//裁剪核心
//update-checker.class.php//检查更新插件
//decode.class.php//加密解密类
//qrcode.class.php//二维码生成
//recaptcha.class.php//验证码核心

/*
 * ------------------------------------------------------------------------------
 * 注册主题功能
 * ------------------------------------------------------------------------------
 */

//注册主题css及js
function css_js_add_scripts(){
    wp_register_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css' );
    wp_enqueue_style( 'bootstrap' );
    wp_register_style( 'bifont', get_template_directory_uri() . '/assets/bifont/bootstrap-icons.css' );
    wp_enqueue_style( 'bifont' );
    wp_register_style( 'stylecss', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'stylecss' );
    wp_register_script( 'jquery-min', get_template_directory_uri() . '/assets/js/jquery.min.js', array(), '', false ); //false就在页头显示
    wp_enqueue_script( 'jquery-min' );
    wp_register_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array(), '', true );
    wp_enqueue_script( 'bootstrap' );
    wp_register_script( 'mainjs', get_template_directory_uri() . '/assets/js/main.js', array(), '', true );
    wp_enqueue_script( 'mainjs' );
}
add_action('wp_enqueue_scripts', 'css_js_add_scripts');

//注册导航菜单
    register_nav_menus(
        array(
        'main'     => __( '顶部菜单' ),
        'footnav'  => __( '底部菜单' )
        )
    );

//自定义文章形式 增加 图片 和 图库
    add_theme_support(
        'post-formats', array(
            'gallery',
            'image',
            'video',
            'audio'
        )
    );
//注册说说文章类型

/*
 * ------------------------------------------------------------------------------
 * 加载函数
 * ------------------------------------------------------------------------------
 */

//首次启动函数
require get_template_directory(). '/inc/functions-action.php';
//后台功能函数
require get_template_directory(). '/inc/functions-norm.php';
//缩略图裁剪函数
require get_template_directory(). '/inc/functions-thumbnails.php';
//文章功能函数
require get_template_directory(). '/inc/functions-single.php';
//评论功能函数
require get_template_directory(). '/inc/functions-comments.php';
//邮件SMTP函数
//require get_template_directory(). '/inc/functions-mail.php';
//小工具
require get_template_directory(). '/inc/functions-widget.php';
//短代码
//require get_template_directory(). '/inc/functions-shotcode.php';

/*
 * ------------------------------------------------------------------------------
 * 核心功能函数
 * ------------------------------------------------------------------------------
 */

//列表循环翻页函数
function get_posts_nav() {
    $args = array(
        'prev_next' => 1,
        'before_page_number' => '',
        'mid_size' => 1,
        'prev_text' => __('上页'),
        'next_text' => __('下页'),
    );
    if( aya_option('home_load' ) == 1){
        echo '<div class="posts-nav">'.paginate_links($args).'</div>';
    }else{
        echo '<div class="post-read-more">'.get_next_posts_link('加载更多','').'</div>';
    }
}

//文章访浏览量
function record_visitors(){
	if (is_singular()) {
		global $post;
		$post_ID = $post->ID;
		if($post_ID){
			$post_views = (int)get_post_meta($post_ID, 'views', true);
			if(!update_post_meta($post_ID, 'views', ($post_views+1))) 
			{
				add_post_meta($post_ID, 'views', 1, true);
			}
		}
	}
}
add_action('wp_head', 'record_visitors');  

//文章访问计数器
function post_views($before = '(点击 ', $after = ' 次)', $echo = 1){
	global $post;
	$post_ID = $post->ID;
	$views = (int)get_post_meta($post_ID, 'views', true);
	if ($echo) echo $before, number_format($views), $after;
	else return $views;
};

//获取全站文章浏览量
function aya_get_all_view() {
    global $wpdb;
    $count=0;
    $views= $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key='views'");
    foreach($views as $key=>$value){
        $meta_value=$value->meta_value;
        if($meta_value!=' '){
            $count+=(int)$meta_value;
        }
    }
    return $count;
}

//获取当前分类一周文章数量
function aya_get_category_week($input = '') {
    global $wpdb;
    if($input == '') {
        $category = get_the_category();
        return $category[0]->category_count;
    }
    elseif(is_numeric($input)) {
        $SQL = "SELECT {$wpdb->term_taxonomy}.count FROM {$wpdb->terms},  {$wpdb->term_taxonomy} WHERE {$wpdb->terms}.term_id = {$wpdb->term_taxonomy}.term_id AND {$wpdb->term_taxonomy}.term_id = {$input}";
        return $wpdb->get_var($SQL);
    }
    else {
        $SQL = "SELECT {$wpdb->term_taxonomy}.count FROM {$wpdb->terms}, {$wpdb->term_taxonomy} WHERE {$wpdb->terms}.term_id = {$wpdb->term_taxonomy}.term_id AND {$wpdb->terms}.slug='{$input}'";
        return $wpdb->get_var($SQL);
    }
}

//取得一言
function hitokoto(){
    $data  = get_template_directory(). '/assets/hitokoto.json';
    $json  = file_get_contents($data);
    $array = json_decode($json, true);
    $count = count($array);
    if ($count != 0){
        $hitokoto = $array[array_rand($array)]['hitokoto'];
        echo $hitokoto;
    }else{
        echo '无法读取：<code>hitokoto.json</code>';
    }

}

//中文附件上传
function uazoh_wp_upload_filter($file){  
    $time=date("YmdHis");  
    $file['name'] = $time."".mt_rand(1,100).".".pathinfo($file['name'] , PATHINFO_EXTENSION);  
    return $file;  
}  
add_filter('wp_handle_upload_prefilter', 'uazoh_wp_upload_filter'); 

//页面伪静态
function html_page_permalink(){
    global $wp_rewrite;
    if (!strpos($wp_rewrite->get_page_permastruct(), '.html')) {
        $wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
    }
}
add_action('init', 'html_page_permalink', -1);

//懒加载
function aya_seo_lazyload($content){
    if (!is_feed() || !is_robots()) {
        $content = preg_replace('/<img(.+)src=[\'"]([^\'"]+)[\'"](.*)>/i', "<img\$1data-original=\"\$2\" \$3>\n<noscript>\$0</noscript>", $content);
    }
    return $content;
}
add_filter('the_content', 'aya_seo_lazyload');


// WordPress获取分类和子分类文章数量
function get_cat_postcount($id) {
	// 获取当前分类信息
	$cat = get_category($id);

	// 当前分类文章数
	$count = (int) $cat->count;

	// 获取当前分类所有子分类
	$tax_terms = get_terms('category', array('child_of' => $id));

	foreach ($tax_terms as $tax_term) {
		// 子分类文章数累加
		$count +=$tax_term->count;
	}
	return $count;
}

//网站标题选择器
function aya_site_title_type(){
    $site_name = aya_option('site_name');
    $site_des = aya_option('site_description');
    $fgf = ' '.aya_option('title_fgf').' ';
    
    if ( is_home() || is_front_page() ) {
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        if ( $paged > 1 ) { $paged = $fgf.'第'.$paged.'页'; } else { $paged = '';};
        $title = $site_name.$paged.$fgf.$site_des;
    }
    if ( is_single() || is_page() ) {
        $title = ''.get_the_title().''.$fgf.$site_name;
    }
    if ( is_category() ) {
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        if ( $paged > 1 ) { $paged = $fgf.'第'.$paged.'页'; } else { $paged = '';};
        $title = '分类 '.single_cat_title('',false).' 的文章'.$paged.$fgf.$site_name;
    }
    if ( is_tag() ) {
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        if ( $paged > 1 ) { $paged = $fgf.'第'.$paged.'页'; } else { $paged = '';};
        $title = '标签 '.single_tag_title('#',false).' 的文章'.$paged.$fgf.$site_name;
    }
    if ( is_author() ) {
        $title = '「'.get_the_author().'」的个人主页'.$fgf.$site_name;
    }
    if ( is_search() ) {
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        if ( $paged > 1 ) { $paged = $fgf.'第'.$paged.'页'; } else { $paged = '';};
        $title = '搜索 '.get_query_var('s').' 的结果'.$paged.$fgf.$site_name;
    }
    if ( is_404() ) {
        $title = '404 NOT FOUND'.$fgf.$site_name;
    }
    return $title;
}


//SEO关键词
function aya_add_seo_keywords(){
    $keywords = aya_option('seo_keywords');

    if ( is_home() || is_front_page() ) {
        $keywords = $keywords;
    } 
    if ( is_single() || is_page() ) {
        $tags = get_the_tags();
        if ( $tags ) { 
            foreach ( $tags as $tag ) {
                $keywords = $tag->name . ','.$keywords;
            } 
        }
    }
    if ( is_category() ) { 
        $keywords = single_cat_title('',false).$keywords; 
    }
    if ( is_tag() ) { 
        $keywords = single_tag_title('',false).$keywords; 
    } 
    if ( is_search() ) { 
        $keywords = get_query_var('s'); 
    } 
    return $keywords;
}

//SEO描述
function aya_add_seo_description(){
    $description = aya_option('seo_description');

    if ( is_home() || is_front_page() ) { 
        $description = $description; 
    } 
    if ( is_single() || is_page() ) { 
		$description = wp_trim_words( get_the_content(), 150, '...' );
    } 
    if ( is_category() ) { 
        $description = '分类 '.single_cat_title('',false).' 的文章'; 
    } 
    if ( is_tag() ) { 
        $description = '标签 '.single_tag_title('',false).' 的文章'; 
    } 
    if ( is_search() ) { 
        $description = '搜索 '.get_query_var('s').' 的结果'; 
    }
    return $description;

}

function aya_add_css_mod(){
    if( aya_option('dark_mod') == true){
        $css = '';

    }
    if( aya_option('gray_mod') == true){
        $css = '
        html {
            -webkit-filter: grayscale(100%);
            -moz-filter: grayscale(100%);
            -ms-filter: grayscale(100%);
            -o-filter: grayscale(100%);
            filter: grayscale(100%);
            filter: progid:DXImageTransform.Microsoft.BasicImage(grayscale=1);
        }
        ';
    }
    if( aya_option('snow_mod') == true){
        $css = '';

    }
    return $css;
}