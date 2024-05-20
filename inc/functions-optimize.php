<?php
if (!defined('ABSPATH')){
    exit;
}

/*
 * ------------------------------------------------------------------------------
 * WordPress 优化函数
 * ------------------------------------------------------------------------------
 */

//WordPress设置：移除Hard多余信息
remove_action( 'wp_head', 'feed_links_extra', 3 );//移除Feed
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'rsd_link' );//移除rsd+xml开放接口
remove_action( 'wp_head', 'wlwmanifest_link' );//移除wlwmanifest+xml开放接口
remove_action( 'wp_head', 'index_rel_link' );//当前文章的索引
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
remove_action( 'wp_head', 'wp_resource_hints',2 );
remove_action( 'wp_head', 'wp_generator');//移除WordPress版本
remove_action( 'wp_head', 'wp_shortlink_wp_head',10,0 );//移除默认固定链接
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );//移除头部 wp-json 标签
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );//移除json+oembed
remove_action( 'wp_head', 'wp_oembed_add_host_js');//移除xml+oembed
remove_filter( 'wp_robots', 'wp_robots_max_image_preview_large' );
remove_action( 'rest_api_init', 'wp_oembed_register_route' );
remove_filter( 'rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4) ;
remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
remove_filter( 'oembed_response_data',   'get_oembed_response_data_rich',  10, 4 );
remove_action( 'template_redirect','wp_shortlink_header', 11, 0 );//移除 HTTP header 中的 link

//WordPress设置：原生缩略图尺寸为空
add_filter('pre_option_thumbnail_size_w', '__return_zero');
add_filter('pre_option_thumbnail_size_h', '__return_zero');
add_filter('pre_option_medium_size_w', '__return_zero');
add_filter('pre_option_medium_size_h', '__return_zero');
add_filter('pre_option_large_size_w', '__return_zero');
add_filter('pre_option_large_size_h', '__return_zero');

//WordPress设置：禁用原生页面标题输出
remove_action( 'wp_head', '_wp_render_title_tag', 1 );

//WordPress设置：插入图片自动设置为媒体文件
update_option('image_default_link_type', 'file');

//WordPress设置：禁用图片缩放
add_filter('big_image_size_threshold', '__return_false');

//WordPress设置：关闭新用户注册通知站长的邮件
add_filter( 'wp_new_user_notification_email_admin', '__return_false' );

//WordPress设置：关闭新用户注册用户邮件通知
add_filter( 'wp_new_user_notification_email', '__return_false' );

//WordPress设置：禁用前台顶部工具栏
add_action('show_admin_bar', '__return_false');

//WordPress优化：调出原生链接功能
if (aya_option('friend_link') == true) {
    add_filter('pre_option_link_manager_enabled', '__return_true');
}

//WordPress优化：禁用FEED组件
if (aya_option('feed_close') == true) {
    function disable_feed(){
        wp_die(__('<h1>Feed已经关闭, 返回<a href="'.get_bloginfo('url').'">首页</a>。</h1>'));
    }
    add_action('do_feed', 'disable_feed', 1);
    add_action('do_feed_rdf', 'disable_feed', 1);
    add_action('do_feed_rss', 'disable_feed', 1);
    add_action('do_feed_rss2', 'disable_feed', 1);
    add_action('do_feed_atom', 'disable_feed', 1);
}

//WordPress优化：FEED增加查看全文链接
if (aya_option('feed_tweet') == true) {
    function feed_tweet_request($query) {
        if (isset($query['feed']) && !isset($query['post_type'])){
            $query['post_type'] = array('post', 'shuoshuo' );
        }
        return $query;
    }
    add_filter('request', 'feed_tweet_request');
}

//WordPress优化：FEED增加查看全文链接
if (aya_option('feed_permalink') == true) {
    function feed_read_more($content) {
        return $content . '<p><a rel="bookmark" href="'.get_permalink().'" target="_blank">查看全文</a></p>';
    }
    add_filter ('the_excerpt_rss', 'feed_read_more');
}

//WordPress优化：禁用原生emoji's
if (aya_option('diable_emoji') == true) {
    add_filter('emoji_svg_url', '__return_false');
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script');
    remove_action( 'admin_print_styles', 'print_emoji_styles');
    remove_action( 'wp_head', 'print_emoji_detection_script', 7);
    remove_action( 'wp_print_styles', 'print_emoji_styles');
    remove_action( 'embed_head','print_emoji_detection_script');
    remove_filter( 'the_content_feed', 'wp_staticize_emoji');
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji');
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email');
    remove_filter( 'the_content', 'capital_P_dangit' );
    remove_filter( 'the_title', 'capital_P_dangit' );
    remove_filter( 'comment_text', 'capital_P_dangit' );
}

//WordPress优化：禁用标点转换功能
if (aya_option('diable_texturize') == true) {
    remove_filter('the_content', 'wptexturize');
    add_filter('run_wptexturize', '__return_false');
}

//WordPress优化：禁用内置链接解析功能 auto-embeds for WordPress >= v3.5
if (aya_option('diable_autoembed') == true) {
    remove_filter( 'the_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );
}

//WordPress优化：禁用修订版本功能
if (aya_option('friend_link') == true) {
    function remove_to_keep_specs($num, $post){
        //设置修订版本保存个数0
        return 0; 
    }
    add_filter('wp_revisions_to_keep', 'remove_to_keep_specs', 10, 2);
}

//WordPress优化：禁用自动保存
if (aya_option('diable_autosave') == true) {
    function remove_autosave(){
        wp_deregister_script('autosave');
    }
    add_action('wp_print_scripts', 'remove_autosave');
}

//WordPress优化：移除一些用不到的样式
if (aya_option('remove_wp_unused_css') == true) {
    function remove_unused_css(){
        //移除谷歌字体
        wp_dequeue_style('open-sans','');
        wp_deregister_style('open-sans');
        wp_dequeue_style('wp-editor-font');
        wp_deregister_style('wp-editor-font');
        //移除古腾堡编辑器样式
        wp_dequeue_style('classic-theme-styles');
        //移除全局样式
        wp_dequeue_style('global-styles');
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-blocks-style');
        wp_dequeue_style('wc-blocks-vendors-style');
        wp_dequeue_style('bp-member-block');
        wp_dequeue_style('bp-members-block');
    }
    add_action('wp_enqueue_scripts', 'remove_unused_css');

}

//WordPress优化：禁用JS和CSS加载时生成版本号
if (aya_option('remove_css_ver') == true) {
    function remove_css_js_ver($src){
        if (strpos($src, 'ver='))
            $src = remove_query_arg('ver', $src);
        return $src;
    }
    add_filter('style_loader_src', 'remove_css_js_ver', 999);
    add_filter('script_loader_src', 'remove_css_js_ver', 999);
}

//WordPress优化：后台文章、页面、分类的列表中显示 ID
if (aya_option('add_ssid_column') == true) {
    function ssid_column($cols){
        //添加一个新的列
        $cols['ssid'] = 'ID';
        return $cols;
    }
    function ssid_value($column_name, $id){
        if ($column_name == 'ssid')
            echo $id;
    }
    function ssid_return_value($value, $column_name, $id){
        if ($column_name == 'ssid')
            $value = $id;
        return $value;
    }
    add_filter('manage_posts_columns', 'ssid_column');
    add_action('manage_posts_custom_column', 'ssid_value', 10, 2);
    add_filter('manage_pages_columns', 'ssid_column');
    add_action('manage_pages_custom_column', 'ssid_value', 10, 2);
    add_filter('manage_media_columns', 'ssid_column');
    add_action('manage_media_custom_column', 'ssid_value', 10, 2);
    add_filter('manage_link-manager_columns', 'ssid_column');
    add_action('manage_link_custom_column', 'ssid_value', 10, 2);
    add_action('manage_edit-link-categories_columns', 'ssid_column');
    add_filter('manage_link_categories_custom_column', 'ssid_return_value', 10, 3);
    add_action('manage_users_columns', 'ssid_column');
    add_filter('manage_users_custom_column', 'ssid_return_value', 10, 3);
    add_action('manage_edit-comments_columns', 'ssid_column');
    add_action('manage_comments_custom_column', 'ssid_value', 10, 2);
}

//WordPress安全性：禁用 XML-RPC
if (aya_option('remove_xmlrpc') == true) {
    function remove_xmlrpc_methods($methods){
        $methods = array(); //empty the array
        return $methods;
    }
    add_filter('xmlrpc_enabled', '__return_false');
    add_filter('xmlrpc_methods', 'remove_xmlrpc_methods');
}

//WordPress优化：禁用 PingBack
if (aya_option('remove_pingback') == true) {
    //禁用 pingbacks, enclosures, trackbacks
    remove_action( 'do_pings', 'do_all_pings', 10 );
    //禁用 _encloseme 和 do_ping 动作
    remove_action( 'publish_post','_publish_post_hook',5 );

    function remove_xmlrpc_pingback($methods){
        $methods['pingback.ping'] = '__return_false';
        $methods['pingback.extensions.getPingbacks'] = '__return_false';
        return $methods;
    }
    add_filter('xmlrpc_methods', 'remove_xmlrpc_pingback');
}
//WordPress优化：不禁用 PingBack 则阻止 PingBack 自己
else{
    function remove_self_ping(&$links) {
        $home = get_bloginfo('url');

        foreach($links as $l => $link)
            if(0 === strpos( $link, $home)){
                unset($links[$l]);
            }
    }
    add_action( 'pre_ping', 'remove_self_ping' );
}

//WordPress安全性：禁用dns-prefetch
if (aya_option('remove_dns_prefetch') == true) {
    function remove_dns_prefetch($hints, $relation_type){
        if ('dns-prefetch' === $relation_type) {
            return array_diff(wp_dependencies_unique_hosts(), $hints);
        }
        return $hints;
    }
    add_filter('wp_resource_hints', 'remove_dns_prefetch', 10, 2);
}

//WordPress安全性：原生Sitemap.xml禁止生成users列表
if (aya_option('remove_sitemaps_provider') == true) {
    function remove_sitemaps_add_provider($provider, $name){
        return ($name == 'users') ? false : $provider;
    }
    add_filter('wp_sitemaps_add_provider', 'remove_sitemaps_add_provider', 10, 2);
}

///WordPress安全性：直接去掉函数 comment_class() 和 body_class() 中输出的 "comment-author-" 和 "author-"避免 WordPress 登录用户名被暴露
if (aya_option('remove_comment_author') == true) {
    function comment_body_class($content){
        $pattern = "/(.*?)([^>]*)author-([^>]*)(.*?)/i";
        $replacement = '$1$4';
        $content = preg_replace($pattern, $replacement, $content);
        return $content;
    }
    add_filter('comment_class', 'comment_body_class');
    add_filter('body_class', 'comment_body_class');
}

//WordPress安全性：禁止订阅用户进入后台
if (aya_option('current_user_admin') == true) {
    function admin_plugin_init(){
        if(is_admin()){
            if( !current_user_can('edit_posts') && $_SERVER['PHP_SELF'] != '/wp-admin/admin-ajax.php'){
                wp_redirect('/');
            }
        }
    }
    add_action('admin_init', 'admin_plugin_init', 10);
}

//WordPress安全性：禁止未登录用户读取 REST API
if (aya_option('current_user_restapi') == true) {
    function rest_only_for_authorized_users($wp_rest_server){
        if(!is_user_logged_in()) {
            wp_die('错误：喵喵？喵喵喵喵？');
        }
    }
    add_filter('rest_api_init','rest_only_for_authorized_users',99);
}

///WordPress安全性：禁用找回密码
if (aya_option('remove_password_reset') == true) {
    add_filter('allow_password_reset', '__return_false');
}

//WordPress安全性：关闭管理员邮箱确认
if (aya_option('admin_email_check') == true) {
    add_filter('admin_email_check_interval', '__return_false');
}

//WordPress安全性：禁止使用 admin 和 id=1 的用户名尝试登录
if (aya_option('admin_user_check') == true) {
    function no_admin_user($user){
        $admin = new WP_User(1);

        if($user == 'admin' || $user == $admin->user_login){
            wp_die('错误：用户名或密码错误，请重试。');
        }
    }
    add_filter('wp_authenticate', 'no_admin_user');
    
    function sanitize_no_admin($username, $raw_username, $strict){
        if($raw_username == 'admin' || $username == 'admin'){
            wp_die('错误：该用户名已存在。');
        }
        return $username;
    }
    add_filter('sanitize_user', 'sanitize_no_admin', 10, 3);
}
//隐藏面板登陆错误信息
add_filter('login_errors', '__return_false');
//add_filter('login_errors', create_function('$a', "return null;"));