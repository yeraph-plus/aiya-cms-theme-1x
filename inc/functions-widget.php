<?php

// 任何添加于主题目录/widget内的都被调用到这里
define('FUNCTIONS', get_template_directory().'/widget');

IncludeAll( FUNCTIONS );
function IncludeAll($dir){
    $dir = realpath($dir);
    if($dir){
        $files = scandir($dir);
        sort($files);
        foreach($files as $file){
            if (substr($file, -4) == '.php') {
                include_once $dir . '/' . $file;
            }
        }
    }
}

//禁用默认自带小工具
function unregisterWidgets() {
    unregister_widget( 'WP_Widget_Archives' );          //年份文章归档
    unregister_widget( 'WP_Widget_Calendar' );          //日历
    unregister_widget( 'WP_Widget_Categories' );        //分类列表
    unregister_widget( 'WP_Widget_Links' );             //链接
    unregister_widget( 'WP_Widget_Media_Audio' );       //音乐
    unregister_widget( 'WP_Widget_Media_Video' );       //视频
    unregister_widget( 'WP_Widget_Media_Gallery' );     //相册
    //unregister_widget( 'WP_Widget_Custom_HTML' );     //html
    //unregister_widget( 'WP_Widget_Media_Image' );     //图片
    //unregister_widget( 'WP_Widget_Text' );            //文本
    unregister_widget( 'WP_Widget_Meta' );              //默认工具链接
    unregister_widget( 'WP_Widget_Pages' );             //页面
    unregister_widget( 'WP_Widget_Recent_Comments' );   //自带的丑丑的评论
    //unregister_widget( 'WP_Widget_Recent_Posts' );      //文章列表
    unregister_widget( 'WP_Widget_RSS' );               //RSS订阅
    //unregister_widget( 'WP_Widget_Search' );          //搜索
    unregister_widget( 'WP_Widget_Tag_Cloud' );         //自带的丑丑的标签云
    unregister_widget( 'WP_Nav_Menu_Widget' );          //菜单
}
add_action( 'widgets_init', 'unregisterWidgets' );


//注册小工具
function widgets_init() {
    register_sidebar( array(
        'name' => '分类侧栏',
        'id' => 'index_widgets',
        'description' => '分类右边侧栏',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );
    register_sidebar( array(
        'name' => '文章侧栏',
        'id' => 'single_widgets',
        'description' => '文章右边侧栏',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );
    register_sidebar( array(
        'name' => '页面侧栏',
        'id' => 'page_widgets',
        'description' => '页面右边侧栏',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );
    }
add_action( 'widgets_init', 'widgets_init' );
