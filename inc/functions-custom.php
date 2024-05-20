<?php
if (!defined('ABSPATH')){
    exit;
}

/*
 * ------------------------------------------------------------------------------
 * 注册自定义页面
 * ------------------------------------------------------------------------------
 */

//注册主题自定页面
function aya_init_globals(){
    global $page_type, $aya_pages;
    $aya_pages = array('go', 'link');
    $page_type = apply_filters('page_type', $aya_pages);
    
    add_rewrite_tag('%page_type%', '([^&]+)');
}
add_action('init', 'aya_init_globals', 10, 0);

//注册路由规则
function aya_rewrite_rules($wp_rewrite){
    global $page_type;
    $new_rules = array();
    foreach ($page_type as $page) {
        $new_rules[$page] = 'index.php?page_type=' . $page;
    }
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    return $wp_rewrite;
}
add_filter('generate_rewrite_rules', 'aya_rewrite_rules');

//加载自定义模板
function aya_template_redirects(){
    $type = get_query_var('page_type');
    if ($type) {
        get_template_part('template-pages/page', $type);
        exit;
    }
}
add_filter('template_redirect', 'aya_template_redirects');

//DEBUG：URL排除反斜杠
function aya_cancel_redirect_canonical($redirect_url){
    if (get_query_var('page_type')) return false;
    return $redirect_url;
}
add_filter('redirect_canonical', 'aya_cancel_redirect_canonical');

/*
 * ------------------------------------------------------------------------------
 * 注册自定义分类法
 * ------------------------------------------------------------------------------
 */

//注册专题

/*
 * ------------------------------------------------------------------------------
 * 注册自定义文章类型
 * ------------------------------------------------------------------------------
 */

//注册推文
function init_tweet(){
    $labels = array(
        'name' => __('推文'),
        'singular_name' => __('推文'),
        'add_new' => __('发表推文'),
        'add_new_item' => __('发表推文'),
        'edit_item' => __('编辑推文'),
        'new_item' => __('新推文'),
        'view_item' => __('查看推文'),
        'search_items' => __('搜索推文'),
        'not_found' => __('暂无推文'),
        'not_found_in_trash' => __('没有已删除的推文'),
        'parent_item_colon' => '',
        'menu_name' => __('推文')
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'exclude_from_search' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'tweet',
            'with_front' => false
        ),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'menu_icon' => 'dashicons-format-quote',
        'supports' => array('editor', 'author', 'title', 'custom-fields', 'comments')
    );
    register_post_type('tweet', $args);
}
add_action('init', 'init_tweet');

//注册推文内页路由
function aya_custom_page_rewrites_init(){
    //文内路径
    add_rewrite_rule('tweet/([0-9]+)?.html$', 'index.php?post_type=tweet&p=$matches[1]', 'top' );
    //评论路径
    add_rewrite_rule('tweet/([0-9]+)?.html/comment-page-([0-9]{1,})$', 'index.php?post_type=tweet&p=$matches[1]&cpage=$matches[2]', 'top');
}
add_action( 'init', 'aya_custom_page_rewrites_init' );

//定义推文内页路径
function aya_custom_page_link( $link, $post = 0 ){
    if ( $post->post_type == 'tweet' ){
        return home_url( 'tweet/' . $post->ID .'.html' );
    } else {
        return $link;
    }
}
add_filter('post_type_link', 'aya_custom_page_link', 1, 3);
