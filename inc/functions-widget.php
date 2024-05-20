<?php
if (!defined('ABSPATH')){
    exit;
}

/*
 * ------------------------------------------------------------------------------
 * 小工具组件及相关函数
 * ------------------------------------------------------------------------------
 */

//加载小工具文件
function widgets_include(){
    //定义Widget目录位置
    $dir = realpath(get_template_directory().'/widget');
    
    if($dir){
        $files = scandir($dir);
        sort($files);
        foreach($files as $file){
            if(substr($file, -4) == '.php'){
                include_once $dir.'/'.$file;
            }
        }
    }
}
widgets_include();

//注册小工具
function aya_init_widgets() {
    register_widget('Aya_Hot_Posts');
    register_widget('Aya_Connent_Posts');
    register_widget('Aya_Random_Posts');
    register_widget('Aya_Tweet_Posts');
    register_widget('Aya_Tag_Cloud');
    register_widget('Aya_Late_Comments');
    register_widget('Aya_Search_Box');
    register_widget('Aya_Sitebar_Menu');
    register_widget('Aya_Author_Box');
    register_widget('Aya_List_Posts');
    register_widget('Aya_Text_Html');
}
add_action('widgets_init', 'aya_init_widgets');

//加载预定义类
require get_template_directory().'/inc/class/widget-cache.php';

//注册小工具位置
function aya_init_widget_sidebar(){
    register_sidebar(array(
        'name' => '首页侧栏',
        'id' => 'index_widgets',
        'description' => '首页右边侧栏',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
        'name' => '文章侧栏',
        'id' => 'single_widgets',
        'description' => '文章右边侧栏',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
        'name' => '页面侧栏',
        'id' => 'page_widgets',
        'description' => '页面右边侧栏',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'aya_init_widget_sidebar');

