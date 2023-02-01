<?php

//在WordPress后台自定义提示
function remove_footer_admin () {
    echo '<span id="footer-thankyou">感谢使用 <b>AiYa-CMS</b> ，欢迎访问 <a href="https://www.yeraph.com" target="_blank" style="text-decoration:none">Yeraph Studio</a> 了解更多。</span>';
}
add_filter('admin_footer_text', 'remove_footer_admin');

//在WordPress前端自定义功能中添加提示
function theme_customizer( WP_Customize_Manager $wp_customize){
    function get_categories_select(){
        $teh_cats = get_categories();
        $results = [];
        $count = count($teh_cats);
        for ($i = 0; $i < $count; $i++) {
            if (isset($teh_cats[$i]))
            $results[$teh_cats[$i]->cat_ID] = $teh_cats[$i]->name;
            else
            $count++;
        }
        return $results;
    }
    //在自定义程序中添加节点 
    $wp_customize->add_section('aya_setting_index', array(
        'title'			=> 'AiYa-CMS 设置',
        'description'	=> 'AiYa-CMS 如果需要进行功能设置，请进入<a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=aiya-options">主题设置</a>。',
    ));
    
    //一个设置项
    $wp_customize->add_setting('aya_nopic', array(
        'default'		=> '',
        'transport'		=> 'refresh', //默认值refresh
    ));
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'aya_nopic',array(
        'label'      	=> '默认缩略图',
        'section'    	=> 'aya_setting_index',//设置组
        'settings'   	=> 'aya_nopic',
        'description'	=> '【必须设置】文章无图情况下的默认缩略图',
    ))
    );
    //使用设置项时：echo get_theme_mod('aya_nopic');
}
add_action('customize_register', 'theme_customizer');

//在WordPress后台删除仪表盘站点健康模块
function remove_dashboard_meta_box() {
    //删除 "站点健康" 模块
    remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
    //删除 "概况" 模块
    //remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
    //删除 "快速发布" 模块
    //remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    //删除 "引入链接" 模块
    //remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
    //删除 "插件" 模块
    //remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
    //删除 "动态" 模块
    //remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
    //删除 "近期评论" 模块
    //remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
    //删除 "近期草稿" 模块
    //remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
    //删除 "WordPress 开发日志" 模块
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
    //删除 "WordPress 新闻" 模块
    remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
}
add_action('wp_dashboard_setup', 'remove_dashboard_meta_box' );

//在WordPress后台仪表盘删除欢迎模块
remove_action('welcome_panel', 'wp_welcome_panel');

//WordPress 5.0 古腾堡默认样式
function fanly_remove_block_library_css() {
    wp_dequeue_style( 'wp-block-library' );
}
add_action( 'wp_enqueue_scripts', 'fanly_remove_block_library_css', 100 );

//移除顶部多余信息
remove_action( 'wp_head', 'feed_links_extra', 3 );          //移除meta feed
remove_action( 'wp_head', 'rsd_link' );                     //移除meta rsd+xml开放接口
remove_action( 'wp_head', 'wlwmanifest_link' );             //移除meta wlwmanifest+xml开放接口
remove_action( 'wp_head', 'wp_generator' );                 //移除meta WordPress版本
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );  //移除meta 默认固定链接
remove_action('wp_head', 'wp_oembed_add_discovery_links');  //移除meta json+oembed
remove_action('wp_head', 'wp_oembed_add_host_js');          //移除meta xml+oembed

//去除后台标题中的“—— WordPress”
add_filter('admin_title', 'wpdx_custom_admin_title', 10, 2);
function wpdx_custom_admin_title($admin_title, $title){
return $title.' &lsaquo; '.get_bloginfo('name');
}

//禁用emoji's
remove_action('admin_print_scripts',    'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_action('wp_head',        'print_emoji_detection_script', 7);
remove_action('wp_print_styles',    'print_emoji_styles');
remove_action('embed_head',     'print_emoji_detection_script');
remove_filter('the_content_feed',   'wp_staticize_emoji');
remove_filter('comment_text_rss',   'wp_staticize_emoji');
remove_filter('wp_mail',        'wp_staticize_emoji_for_email');
add_filter( 'emoji_svg_url',        '__return_false' );

//禁止头部加载 link s.w.org
function remove_dns_prefetch( $hints, $relation_type ) {
if ( 'dns-prefetch' === $relation_type ) {
return array_diff( wp_dependencies_unique_hosts(), $hints );
}
return $hints;
}
add_filter( 'wp_resource_hints', 'remove_dns_prefetch', 10, 2 );

//移除调用JS和CSS链接中的版本号
function wpdaxue_remove_cssjs_ver( $src ){
if( strpos( $src, 'ver=' ) )
$src = remove_query_arg( 'ver', $src );
return $src;
}
add_filter( 'style_loader_src', 'wpdaxue_remove_cssjs_ver', 999 );
add_filter( 'script_loader_src', 'wpdaxue_remove_cssjs_ver', 999 );

// 移除头部 wp-json 标签和 HTTP header 中的 link
remove_action('wp_head', 'rest_output_link_wp_head', 10 );
remove_action('template_redirect', 'rest_output_link_header', 11 );

//WordPress 移除头部 global-styles-inline-css
function fanly_remove_global_styles_inline(){
	wp_deregister_style( 'global-styles' );
	wp_dequeue_style( 'global-styles' );
}
add_action('wp_enqueue_scripts', 'fanly_remove_global_styles_inline');
/*
 * ------------------------------------------------------------------------------
 * 加载核心函数
 * ------------------------------------------------------------------------------
 */


//搜索结果排除所有页面
function search_filter_page($query) {
    if ($query->is_search && !$query->is_admin) {
        $query->set('post_type', 'post');
    }
    return $query;
}
add_filter('pre_get_posts', 'search_filter_page');

//后台快捷键回复
function admin_comment_ctrlenter() {
    echo '<script type="text/javascript">
        jQuery(document).ready(function($){
            $("textarea").keypress(function(e){
                if(e.ctrlKey&&e.which==13||e.which==10){
                    $("#replybtn").click();
                }
            });
        });
    </script>';
};
add_action('admin_footer', 'admin_comment_ctrlenter');

// 更改后台字体
function git_admin_style() {
    echo '<style type="text/css">
	.setting select.link-to option[value="post"],.setting select[data-setting="link"] option[value="post"]{display:none;}
	#wp-admin-bar-git_guide>.ab-item::before {content:"\f331";top:3px;}#wp-admin-bar-git_option>.ab-item::before{content:"\f507";top:3px;}.users #the-list tr:hover{background:rgba(132,219,162,.61)}#role {width:8%;}* { font-family: "Microsoft YaHei" !important; }.wp-admin img.rand_avatar {max-Width:50px !important;}i, .ab-icon, .mce-close, i.mce-i-aligncenter, i.mce-i-alignjustify, i.mce-i-alignleft, i.mce-i-alignright, i.mce-i-blockquote, i.mce-i-bold, i.mce-i-bullist, i.mce-i-charmap, i.mce-i-forecolor, i.mce-i-fullscreen, i.mce-i-help, i.mce-i-hr, i.mce-i-indent, i.mce-i-italic, i.mce-i-link, i.mce-i-ltr, i.mce-i-numlist, i.mce-i-outdent, i.mce-i-pastetext, i.mce-i-pasteword, i.mce-i-redo, i.mce-i-removeformat, i.mce-i-spellchecker, i.mce-i-strikethrough, i.mce-i-underline, i.mce-i-undo, i.mce-i-unlink, i.mce-i-wp-media-library, i.mce-i-wp_adv, i.mce-i-wp_fullscreen, i.mce-i-wp_help, i.mce-i-wp_more, i.mce-i-wp_page, .qt-fullscreen, .star-rating .star,.qt-dfw{ font-family: dashicons !important; }.mce-ico { font-family: tinymce, Arial}.fa { font-family: FontAwesome !important; }.genericon { font-family: "Genericons" !important; }.appearance_page_scte-theme-editor #wpbody *, .ace_editor * { font-family: Monaco, Menlo, "Ubuntu Mono", Consolas, source-code-pro, monospace !important; }
    </style>';
}
add_action('admin_head', 'git_admin_style');

//WordPress禁止用FEED组件
function digwp_disable_feed() {
    wp_die(__('<h1>Feed已经关闭, 请访问网站<a href="'.get_bloginfo('url').'">首页</a>!</h1>'));
}
add_action('do_feed', 'digwp_disable_feed', 1);
add_action('do_feed_rdf', 'digwp_disable_feed', 1);
add_action('do_feed_rss', 'digwp_disable_feed', 1);
add_action('do_feed_rss2', 'digwp_disable_feed', 1);
add_action('do_feed_atom', 'digwp_disable_feed', 1);

//后台文章列表显示缩略图
function my_add_posts_columns($defaults){
    $defaults['my_post_thumbs'] = '特色图像';
    return $defaults;
}
add_filter('manage_posts_columns', 'my_add_posts_columns', 5);
 
function my_custom_posts_columns($column_name, $id){
    if($column_name === 'my_post_thumbs'){
        echo the_post_thumbnail( array(80,50) );
    }
}
add_action('manage_posts_custom_column', 'my_custom_posts_columns', 5, 2);

// WordPress去除菜单多余类名
function my_css_attributes_filter($var) {
	return is_array($var) ? array_intersect($var, array('current-menu-item','current-post-ancestor','current-menu-ancestor','current-menu-parent')) : '';
}
add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);

//WordPress分类描述删除p标签
function deletehtml($description) {
	$description = trim($description);
	$description = strip_tags($description,'');
	return ($description);
}
add_filter('category_description', 'deletehtml');

//禁止代码标点转换
remove_filter('the_content', 'wptexturize');

//关闭 pingback
function deel_setup(){
    //阻止站内PingBack
    if( dopt('d_pingback_b') ){
        add_action('pre_ping','deel_noself_ping');
    }
}

//WordPress 禁用 XML-RPC 的 pingback 端口
function remove_xmlrpc_pingback_ping( $methods ) {
    unset( $methods['pingback.ping'] );
    return $methods;
}
add_filter( 'xmlrpc_methods', 'remove_xmlrpc_pingback_ping' );

//WordPress禁用修订版本功能
function specs_wp_revisions_to_keep( $num, $post ) {
    return 0;//修订版本保存个数0
}
add_filter( 'wp_revisions_to_keep', 'specs_wp_revisions_to_keep', 10, 2 );

//WordPress禁用自动保存
function disable_autosave(){  
    wp_deregister_script('autosave'); 
}
add_action('wp_print_scripts','disable_autosave');

//WordPress开关前台顶部工具栏
add_filter('show_admin_bar', function ($flag) { return false; });

//WordPress调出原生链接功能
//add_filter( 'pre_option_link_manager_enabled', '__return_true' );

//WordPress原生Sitemap.xml 禁止 wp-sitemap-users-1.xml
add_filter( 'wp_sitemaps_add_provider', function ($provider, $name) { return ( $name == 'users' ) ? false : $provider; }, 10, 2);

//给文章图片自动添加alt和title信息
function aya_seo_imagesalt($content) {
    global $post;
    $pattern ="/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
    $replacement = '<a$1href=$2$3.$4$5 alt="'.$post->post_title.'" title="'.$post->post_title.'"$6>';
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
}
add_filter('the_content', 'aya_seo_imagesalt');

//文章自动nofollow
function aya_seo_nofollow( $content ) {
 $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>";
 if(preg_match_all("/$regexp/siU", $content, $matches, PREG_SET_ORDER)) {
     if( !empty($matches) ) {

         $srcUrl = get_option('siteurl');
         for ($i=0; $i < count($matches); $i++)
         {

             $tag = $matches[$i][0];
             $tag2 = $matches[$i][0];
             $url = $matches[$i][0];

             $noFollow = '';
             $pattern = '/target\s*=\s*"\s*_blank\s*"/';
             preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
             if( count($match) < 1 )
                 $noFollow .= ' target="_blank" ';

             $pattern = '/rel\s*=\s*"\s*[n|d]ofollow\s*"/';
             preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
             if( count($match) < 1 )
                 $noFollow .= ' rel="nofollow" ';

             $pos = strpos($url,$srcUrl);
             if ($pos === false) {
                 $tag = rtrim ($tag,'>');
                 $tag .= $noFollow.'>';
                 $content = str_replace($tag2,$tag,$content);
             }
         }
     }
 }

 $content = str_replace(']]>', ']]>', $content);
 return $content;
}
add_filter( 'the_content', 'aya_seo_nofollow');

//
add_action( 'load-themes.php',  'no_category_base_refresh_rules');
add_action('created_category', 'no_category_base_refresh_rules');
add_action('edited_category', 'no_category_base_refresh_rules');
add_action('delete_category', 'no_category_base_refresh_rules');
function no_category_base_refresh_rules() {
 global $wp_rewrite;
 $wp_rewrite -> flush_rules();
}

// register_deactivation_hook(__FILE__, 'no_category_base_deactivate');
// function no_category_base_deactivate() {
// 	remove_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
// 	// We don't want to insert our custom rules again
// 	no_category_base_refresh_rules();
// }

// Remove category base
add_action('init', 'no_category_base_permastruct');
function no_category_base_permastruct() {
 global $wp_rewrite, $wp_version;
 if (version_compare($wp_version, '3.4', '<')) {
     // For pre-3.4 support
     $wp_rewrite -> extra_permastructs['category'][0] = '%category%';
 } else {
     $wp_rewrite -> extra_permastructs['category']['struct'] = '%category%';
 }
}

// Add our custom category rewrite rules
add_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
function no_category_base_rewrite_rules($category_rewrite) {
 //var_dump($category_rewrite); // For Debugging

 $category_rewrite = array();
 $categories = get_categories(array('hide_empty' => false));
 foreach ($categories as $category) {
     $category_nicename = $category -> slug;
     if ($category -> parent == $category -> cat_ID)// recursive recursion
         $category -> parent = 0;
     elseif ($category -> parent != 0)
         $category_nicename = get_category_parents($category -> parent, false, '/', true) . $category_nicename;
     $category_rewrite['(' . $category_nicename . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
     $category_rewrite['(' . $category_nicename . ')/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
     $category_rewrite['(' . $category_nicename . ')/?$'] = 'index.php?category_name=$matches[1]';
 }
 // Redirect support from Old Category Base
 global $wp_rewrite;
 $old_category_base = get_option('category_base') ? get_option('category_base') : 'category';
 $old_category_base = trim($old_category_base, '/');
 $category_rewrite[$old_category_base . '/(.*)$'] = 'index.php?category_redirect=$matches[1]';

 //var_dump($category_rewrite); // For Debugging
 return $category_rewrite;
}


// Add 'category_redirect' query variable
add_filter('query_vars', 'no_category_base_query_vars');
function no_category_base_query_vars($public_query_vars) {
 $public_query_vars[] = 'category_redirect';
 return $public_query_vars;
}

// Redirect if 'category_redirect' is set
add_filter('request', 'no_category_base_request');
function no_category_base_request($query_vars) {
 //print_r($query_vars); // For Debugging
 if (isset($query_vars['category_redirect'])) {
     $catlink = trailingslashit(get_option('home')) . user_trailingslashit($query_vars['category_redirect'], 'category');
     status_header(301);
     header("Location: $catlink");
     exit();
 }
 return $query_vars;
}

//禁止谷歌字体
function remove_open_sans() {
    wp_deregister_style( 'open-sans' );
    wp_register_style( 'open-sans', false );
    wp_enqueue_style('open-sans','');
}
add_action( 'init', 'remove_open_sans' );

/*

//一言加载器
function hello_hitokoto(){
	$hitokoto = hitokoto();

    echo $hitokoto;
}
add_action( 'admin_notices', 'hello_hitokoto' );

//cURL库

function curl_post($url, $postfields = '', $headers = '', $timeout = 20, $file = 0) {
    $ch = curl_init();
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_HEADER => false,
        CURLOPT_NOBODY => false,
        CURLOPT_POST => true,
        CURLOPT_MAXREDIRS => 20,
        CURLOPT_USERAGENT => 'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0
    );
    if (is_array($postfields) && $file == 0) {
        $options[CURLOPT_POSTFIELDS] = http_build_query($postfields);
    } else {
        $options[CURLOPT_POSTFIELDS] = $postfields;
    }
    curl_setopt_array($ch, $options);
    if (is_array($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    $result = curl_exec($ch);
    $code = curl_errno($ch);
    $msg = curl_error($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    return array(
        'data' => $result,
        'code' => $code,
        'msg' => $msg,
        'info' => $info
    );
}

//时间显示方式‘xx以前’
function time_ago($type = 'commennt', $day = 7) {
    $d = $type == 'post' ? 'get_post_time' : 'get_comment_time';
    if (time() - $d('U') > 60 * 60 * 24 * $day) return;
    echo ' (', human_time_diff($d('U') , strtotime(current_time('mysql', 0))) , '前)';
}
function timeago($ptime) {
    $ptime = strtotime($ptime);
    $etime = time() - $ptime;
    if ($etime < 1) return '刚刚';
    $interval = array(
        12 * 30 * 24 * 60 * 60 => '年前 (' . date('Y-m-d', $ptime) . ')',
        30 * 24 * 60 * 60 => '个月前 (' . date('m-d', $ptime) . ')',
        7 * 24 * 60 * 60 => '周前 (' . date('m-d', $ptime) . ')',
        24 * 60 * 60 => '天前',
        60 * 60 => '小时前',
        60 => '分钟前',
        1 => '秒前'
    );
    foreach ($interval as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . $str;
        }
    };
}
 
//gravatar国内加速
function replace_gravatar($avatar) {
    $avatar = str_replace(array("//gravatar.com/", "//secure.gravatar.com/", "//www.gravatar.com/", "//0.gravatar.com/",
    "//1.gravatar.com/", "//2.gravatar.com/", "//cn.gravatar.com/"), "//gravatar.loli.net/", $avatar);
    return $avatar;}
    add_filter( 'get_avatar', 'replace_gravatar' );

//首页循环里不出现置顶
function exclude_sticky_posts($query){
    if ( is_admin() || ! $query->is_main_query() )
        return;
    if ( $query->is_home() && $query->is_main_query() ) {
        $query->set( 'ignore_sticky_posts', 1 );
    }
}
add_action('pre_get_posts','exclude_sticky_posts');


//最新发布加new 单位'小时'
function deel_post_new($timer = '48') {
    $t = (strtotime(date("Y-m-d H:i:s")) - strtotime($post->post_date)) / 3600;
    if ($t < $timer) echo "<i>new</i>";
}


//压缩html代码
if (git_get_option('git_compress')) {
    function wp_compress_html(){
        function wp_compress_html_main($buffer){
            if ( substr( ltrim( $buffer ), 0, 5) == '<?xml' ) return $buffer;
            $initial = strlen($buffer);
            $buffer = explode("<!--wp-compress-html-->", $buffer);
            $count = count($buffer);
            for ($i = 0; $i <= $count; $i++) {
                if (stristr($buffer[$i], '<!--wp-compress-html no compression-->')) {
                    $buffer[$i] = str_replace("<!--wp-compress-html no compression-->", " ", $buffer[$i]);
                } else {
                    $buffer[$i] = str_replace("\t", " ", $buffer[$i]);
                    $buffer[$i] = str_replace("\n\n", "\n", $buffer[$i]);
                    $buffer[$i] = str_replace("\n", "", $buffer[$i]);
                    $buffer[$i] = str_replace("\r", "", $buffer[$i]);
                    while (stristr($buffer[$i], '  ')) {
                        $buffer[$i] = str_replace("  ", " ", $buffer[$i]);
                    }
                }
                $buffer_out .= $buffer[$i];
            }
            $final = strlen($buffer_out);
            if ($initial !== 0) {
                $savings = ($initial - $final) / $initial * 100;
            } else {
                $savings = 0;
            }
            $savings = round($savings, 2);
            $buffer_out .= "\n<!--压缩前的大小: {$initial} bytes; 压缩后的大小: {$final} bytes; 节约：{$savings}% -->";
            return $buffer_out;
        }
            ob_start("wp_compress_html_main");
    }
    add_action('get_header', 'wp_compress_html');
    function git_unCompress($content)
    {
        if (preg_match_all('/(crayon-|<?xml|script|textarea|<\\/pre>)/i', $content, $matches)) {
            $content = '<!--wp-compress-html--><!--wp-compress-html no compression-->' . $content;
            $content .= '<!--wp-compress-html no compression--><!--wp-compress-html-->';
        }
        return $content;
    }
    add_filter('the_content', 'git_unCompress');
}
//保护后台登录
//救命啊！ps.很好，搜索这段代码很可能意味着你把自己后台给锁了，将下面保护后台登录这段代码删除即可
//开始删除
if (git_get_option('git_admin')) {
    function git_login_protection()
    {
        if ($_GET[''.git_get_option('git_admin_q').''] !== git_get_option('git_admin_a')) {
            header('Location: http://www.baidu.com');//不用密码登录，直接滚到百度去
            exit;
        }
    }
    add_action('login_enqueue_scripts', 'git_login_protection');
}

//获取页面id，并且不可重用
function git_page_id( $pagephp ) {
    global $wpdb;
    $pagephp = esc_sql($pagephp);
    $pageid = $wpdb->get_row("SELECT `post_id` FROM `{$wpdb->postmeta}` WHERE `meta_value` = 'pages/{$pagephp}.php'", ARRAY_A) ['post_id'];
    return $pageid;
}
//懒加载
if (git_get_option('git_lazyload')) {
    function lazyload($content){
        if (!is_feed() || !is_robots()) {
            $content = preg_replace('/<img(.+)src=[\'"]([^\'"]+)[\'"](.*)>/i', "<img\$1data-original=\"\$2\" \$3>\n<noscript>\$0</noscript>", $content);
        }
        return $content;
    }
    add_filter('the_content', 'lazyload');
}
//去掉描述P标签
function deletehtml($description) {
    $description = trim($description);
    $description = strip_tags($description,"");
    return ($description);
    }
    add_filter('category_description', 'deletehtml');

    
    //修改url重写后的作者存档页的链接变量
    add_filter( 'author_link', 'yundanran_author_link', 10, 2 );
    function yundanran_author_link( $link, $author_id) {
        global $wp_rewrite;
        $author_id = (int) $author_id;
        $link = $wp_rewrite->get_author_permastruct();
        if ( empty($link) ) {
            $file = home_url( '/' );
            $link = $file . '?author=' . $author_id;
        } else {
            $link = str_replace('%author%', $author_id, $link);
            $link = home_url( user_trailingslashit( $link ) );
        }
        return $link;
    }
    //此处做的是，在url重写之后，把author_name替换为author
    add_filter( 'request', 'yundanran_author_link_request' );
    function yundanran_author_link_request( $query_vars ) {
        if ( array_key_exists( 'author_name', $query_vars ) ) {
            global $wpdb;
            $author_id=$query_vars['author_name'];
            if ( $author_id ) {
                $query_vars['author'] = $author_id;
                unset( $query_vars['author_name'] );
            }
        }
        return $query_vars;
    }

//WordPress获取子分类的上级ID
function get_category_root_id($cat) {
	//取得当前分类
	$this_category = get_category($cat);
	//若当前分类有上级分类时，循环 
	while($this_category->category_parent){
		//将当前分类设为上级分类（往上爬）
		$this_category = get_category($this_category->category_parent);
	}
	//返回根分类的id号
	return $this_category->term_id; 
}

function get_category_cat() { 
	$catID = get_query_var('cat');
	$thisCat = get_category($catID);
	$parentCat = get_category($thisCat->parent);
	echo get_category_link($parentCat->term_id);
}