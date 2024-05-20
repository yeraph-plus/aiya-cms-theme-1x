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

if (!defined('ABSPATH')){
    die('Invalid request.');
}

if (version_compare($GLOBALS['wp_version'], '5.6', '<')){
    wp_die('请升级WordPress到5.6以上版本');
}

if (!class_exists('Classic_Editor')){ 
   echo '<div id="message" class=" notice is-dismissible notice-info"><p>经典编辑器未启用。</p></div>';
}

/*
 * ------------------------------------------------------------------------------
 * 加载CodeStar-Framework
 * ------------------------------------------------------------------------------
 */

//加载codestar后台设置框架
require get_template_directory() . '/codestar-framework/codestar-framework.php';

//加载framework设置项
require get_template_directory() . '/codestar-framework/codestar-framework-path-1.php';
require get_template_directory() . '/codestar-framework/codestar-framework-path-2.php';
require get_template_directory() . '/codestar-framework/codestar-framework-path-3.php';

/*
 * ------------------------------------------------------------------------------
 * 注册主题功能
 * ------------------------------------------------------------------------------
 */

//获取主题版本
function aya_get_theme_version(){
    $theme = wp_get_theme();
    return $theme->get('Version');
}

//注册主题功能
function aya_theme_setup(){
	//注册多语言文本文件
    //load_theme_textdomain( '_','/languages' );
    //支持自动 Feed 链接
	add_theme_support('automatic-feed-links');
    //注册缩略图支持
    add_theme_support('post-thumbnails',
        array(
            'post',
            'page',
        )
    );
    //支持标签
	add_theme_support('title-tag');
    //自定义文章类型
    add_theme_support('post-formats',
        array(
            'gallery',
            'image',
        )
    );
    //注册导航菜单
    register_nav_menus(
        array(
            'header-menu' => ('顶部菜单'),
            'footer-menu' => ('底部菜单'),
            'widget-menu' => ('小工具菜单'),
        )
    );
}
add_action('after_setup_theme', 'aya_theme_setup');

//注册主题css及js
function css_js_add_scripts(){
    //获取主题版本
    $theme_ver = aya_get_theme_version();

    //配置静态资源版本
    $jquery_ver = '3.6.1';
    $bootstrap_ver = '5.2.3';
    $icons_ver = '1.10.3';

    //获取主题设置
    $type_cdn = aya_option('js_cdn_type');

    //加载静态文件
    if($type_cdn = aya_magic('0') || $type_cdn == null){
        //jquery.js
        wp_register_script('jquery-bundle', get_template_directory_uri(). '/assets/jquery/jquery.min.js', array(), $jquery_ver, true); //false就在页头加载
        //bootstrap
        wp_register_script('bootstrap', get_template_directory_uri(). '/assets/bootstrap/js/bootstrap.min.js', array(), $bootstrap_ver, true);
        wp_register_script('bootstrap-bundle', get_template_directory_uri(). '/assets/bootstrap/js/bootstrap.bundle.min.js', array(), $bootstrap_ver, true);
        wp_register_style('bootstrap', get_template_directory_uri(). '/assets/bootstrap/css/bootstrap.min.css', array(), $bootstrap_ver, 'all');
        wp_register_style('bifont', get_template_directory_uri(). '/assets/bootstrap-icons/bootstrap-icons.css', array(), $icons_ver, 'all');
    }
    elseif($type_cdn = '1'){
        wp_register_script('jquery-bundle', '//cdn.staticfile.org/jquery/'.$jquery_ver.'/jquery.min.js', array(), $jquery_ver, true); 
        wp_register_script('bootstrap', '//cdn.staticfile.org/bootstrap/'.$bootstrap_ver.'/js/bootstrap.min.js', array(), $bootstrap_ver, true);
        wp_register_script('bootstrap-bundle', '//cdn.staticfile.org/bootstrap/'.$bootstrap_ver.'/js/bootstrap.bundle.min.js', array(), $bootstrap_ver, true);
        wp_register_style('bootstrap', '//cdn.staticfile.org/bootstrap/'.$bootstrap_ver.'/css/bootstrap.min.css', array(), $bootstrap_ver, 'all');
        wp_register_style('bifont', '//cdn.staticfile.org/bootstrap-icons/'.$icons_ver.'/font/bootstrap-icons.css', array(), $icons_ver, 'all');
    }
    elseif($type_cdn = '2'){
        wp_register_script('jquery-bundle', '//cdn.jsdelivr.net/npm/jquery@'.$jquery_ver.'/dist/jquery.min.js', array(), $jquery_ver, true); 
        wp_register_script('bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@'.$bootstrap_ver.'/dist/js/bootstrap.min.js', array(), $bootstrap_ver, true);
        wp_register_script('bootstrap-bundle', '//cdn.jsdelivr.net/npm/bootstrap@'.$bootstrap_ver.'/dist/js/bootstrap.bundle.min.js', array(), $bootstrap_ver, true);
        wp_register_style('bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@'.$bootstrap_ver.'/dist/css/bootstrap.min.css', array(), $bootstrap_ver, 'all');
        wp_register_style('bifont', '//cdn.jsdelivr.net/npm/bootstrap-icons@'.$icons_ver.'/font/bootstrap-icons.css', array(), $icons_ver, 'all');
    }
    //fancybox.js
    wp_register_script('fancybox', get_template_directory_uri(). '/assets/fancybox/fancybox.js', array(), '4.0.31', true);
    wp_register_style('fancybox', get_template_directory_uri(). '/assets/fancybox/fancybox.min.css', array(), '4.0.31', 'all');
    //highlight.js
    wp_register_script('highlight', get_template_directory_uri(). '/assets/highlight/highlight.min.js', array(), '11.7.0', true);
    wp_register_style('highlight', get_template_directory_uri(). '/assets/highlight/styles/atom-one-dark.min.css', array(), '11.7.0', 'all');
    wp_register_script('highlight-num', get_template_directory_uri(). '/assets/highlight/highlightjs-line-numbers.min.js', array(), '1.0.0', true);
    //player-loader
    wp_register_script('hls', get_template_directory_uri(). '/assets/player-loader/hls.min.js', array(), '1.4.2', true);
    wp_register_script('hls-light', get_template_directory_uri(). '/assets/player-loader/hls.light.min.js', array(), '1.4.2', true);
    wp_register_script('meting', get_template_directory_uri(). '/assets/player-loader/Meting.min.js', array(), '2.0.1', true);
    wp_register_script('dplayer', get_template_directory_uri(). '/assets/player-loader/DPlayer.min.js', array(), '1.27.1', true);
    wp_register_style('aplayer', get_template_directory_uri(). '/assets/player-loader/APlayer.min.css', array(), '1.10.1', 'all');
    wp_register_script('aplayer', get_template_directory_uri(). '/assets/player-loader/APlayer.min.js', array(), '1.10.1', true);
    //主题文件
    wp_register_script('qrcode', get_template_directory_uri(). '/assets/qrcode.min.js', array(), '1.0.0', true);
    wp_register_script('main', get_template_directory_uri(). '/assets/main.min.js', array(), $theme_ver, true);
    wp_register_style('style', get_template_directory_uri(). '/style.css', array(), $theme_ver, 'all');
    wp_register_style('entry', get_template_directory_uri(). '/entry.css', array(), $theme_ver, 'all');
    
    //开始加载静态文件
    wp_enqueue_style('bootstrap');
    wp_enqueue_style('style');
    wp_enqueue_style('bifont');

    //加载JS组件
    wp_enqueue_script('jquery-bundle');
    wp_enqueue_script('main');

    //加载AJAX位置
    wp_localize_script('main', 'aya_ajax', array('url' => admin_url('admin-ajax.php')));
    
    if (aya_option('popper_js') == true) {
        wp_enqueue_script('bootstrap-bundle');
    }else{
        wp_enqueue_script('bootstrap');
    }

    if (aya_option('fancybox_js') == true) {
        wp_enqueue_style('fancybox');
        wp_enqueue_script('fancybox');
    }

    //在文章页调用
    if (is_single() || is_page()) {
        wp_enqueue_style('entry');
        wp_enqueue_script('qrcode');
        if (aya_option('highlight_js') == true) {
            wp_enqueue_style('highlight');
            wp_enqueue_script('highlight');
            wp_enqueue_script('highlight-num');
        }
    }
}
add_action('wp_enqueue_scripts', 'css_js_add_scripts');

/*
 * ------------------------------------------------------------------------------
 * 加载函数
 * ------------------------------------------------------------------------------
 */

//WP优化组件
require get_template_directory() . '/inc/functions-optimize.php';
//主题功能
require get_template_directory() . '/inc/functions-norm.php';
//后台功能
require get_template_directory() . '/inc/functions-admin.php';
//用户功能
require get_template_directory() . '/inc/functions-user.php';
//SEO功能
require get_template_directory() . '/inc/functions-seo.php';
//自定义页面组件
require get_template_directory() . '/inc/functions-custom.php';
//文章功能
require get_template_directory() . '/inc/functions-single.php';
//LOOP模板
require get_template_directory() . '/inc/functions-loop.php';
//评论模板
require get_template_directory() . '/inc/functions-comments.php';
//邮件模板
require get_template_directory() . '/inc/functions-email.php';
//页面模板
require get_template_directory() . '/inc/functions-template.php';
//短代码组件
require get_template_directory() . '/inc/functions-shotcode.php';
//小工具组件
require get_template_directory() . '/inc/functions-widget.php';
//DEBUG组件
//require get_template_directory() . '/inc/functions-debug.php';

/*
 * ------------------------------------------------------------------------------
 * 速效救心丸
 * ------------------------------------------------------------------------------
 */

//处理空格
//require get_template_directory().'/inc/whitespace-fix.php';
