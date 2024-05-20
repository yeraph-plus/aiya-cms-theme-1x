<?php
if (!defined('ABSPATH')){
    exit;
}

/*
 * ------------------------------------------------------------------------------
 * 后台功能及相关函数
 * ------------------------------------------------------------------------------
 */

//注册后台样式表
function aya_admin_add_scripts(){
    //获取主题版本
    $theme_ver = aya_get_theme_version();
    //注册样式表
    wp_register_style('admin-style', get_template_directory_uri(). '/assets/admin-custom/admin-style.css', array(), $theme_ver, 'all');
    //输出
    wp_enqueue_style('admin-style');
}
add_action('admin_enqueue_scripts', 'aya_admin_add_scripts');

//注册登录页样式表
function aya_login_add_scripts(){
    //获取主题版本
    $theme_ver = aya_get_theme_version();
    //注册样式表
    wp_register_style('admin-login', get_template_directory_uri(). '/assets/admin-custom/admin-login.css', array(), $theme_ver, 'all');
    //输出
    wp_enqueue_style('admin-login');
}
add_action('login_enqueue_scripts', 'aya_login_add_scripts');

//WordPress后台：定义登录页面样式
function custom_admin_style(){
    echo aya_css_custom();
}
add_action('login_head', 'custom_admin_style');
add_action('admin_head', 'custom_admin_style');

//WordPress后台：注册自定义的配色方案
function add_admin_colors() {
    //获取主题版本
    $theme_ver = aya_get_theme_version();
    $plugin_url = get_template_directory_uri().'/assets/admin-custom/admin-color.css?ver='.$theme_ver;
    wp_admin_css_color(
        'AiYa-CMS 自定义配色',
        'AiYa-CMS 自定义配色',
        $plugin_url,
        array('var(--aya-body-color)', 'var(--aya-normal-color)', 'var(--aya-normal-hover)', 'var(--aya-tip-color)' ),
        array('base' => '#fff', 'focus' => '#fff', 'current' => '#fff',)
    );
}
add_action('admin_init','add_admin_colors');

//WordPress后台：注册头像和默认头像 
function add_new_gravatar($avatar_defaults){
    $image_url = aya_option('default_comment_avatar');
    $avatar_defaults[$image_url] = 'AiYa-CMS 自定义头像';

    return $avatar_defaults;
}
add_filter('avatar_defaults', 'add_new_gravatar');

//WordPress后台：后台标题
function custom_admin_title($admin_title, $title){
    return get_bloginfo('name').' - '.$title;
}
add_filter('admin_title', 'custom_admin_title', 10, 2);
add_filter('login_title', 'custom_admin_title', 10, 2);

//WordPress后台：后台页脚信息
function remove_footer_admin (){
    echo '<span id="footer-thankyou">感谢使用 <b>AiYa-CMS</b> ，欢迎访问 <a href="https://www.yeraph.com" target="_blank" style="text-decoration:none">Yeraph Studio</a> 了解更多。</span>';
}
add_filter('admin_footer_text', 'remove_footer_admin');

//WordPress后台：隐藏左上角WordPress标志
function remove_admin_bar_logo() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
}
add_action('wp_before_admin_bar_render', 'remove_admin_bar_logo', 0);

//WordPress编辑器：文章形式选择器文字替换
function rename_post_formats( $safe_text ) {
    if ( $safe_text == '图片' ) return '图片（全图展示）';
    if ( $safe_text == '相册' ) return '相册（图集展示）';
    return $safe_text;
}
add_filter('esc_html', 'rename_post_formats');

//WordPress编辑器：给推文添加一个置顶选项
function add_tweet_meta_box(){
    add_meta_box('tweet_product_sticky', '置顶', 'tweet_product_sticky', 'tweet', 'side', 'high' );
}
add_action('add_meta_boxes', 'add_tweet_meta_box');

function tweet_product_sticky(){
    printf(
        '<p>
            <label for="super-sticky" class="selectit">
                <input id="super-sticky" name="sticky" type="checkbox" value="sticky" %s />
                %s
            </label>
        </p>',
        checked(is_sticky(), true, false),
        esc_html__('置顶这篇文章', 'AiYa-CMS')
    );
}

//WordPress编辑器：TinyMCE编辑器配置
function enable_more_buttons($buttons){
    $buttons[] = 'hr';
    $buttons[] = 'del';
    $buttons[] = 'sub';
    $buttons[] = 'sup';
    $buttons[] = 'fontselect';
    $buttons[] = 'fontsizeselect';
    $buttons[] = 'cleanup';
    $buttons[] = 'styleselect';
    $buttons[] = 'wp_page';
    $buttons[] = 'anchor';
    $buttons[] = 'backcolor';

    return $buttons;
}
add_filter("mce_buttons_3", "enable_more_buttons");

//WordPress后台：禁用古腾堡的Google字体
function remove_gutenberg_styles($translation, $text, $context, $domain){
    if($context != 'Google Font Name and Variants' || $text != 'Noto Serif:400,400i,700,700i') {
        return $translation;
    }
    return 'off';
}
add_filter('gettext_with_context', 'remove_gutenberg_styles',10, 4);

//WordPress后台：注册编辑器插件
function add_more_buttons_plugin($plugin_array){
    //获取主题版本
    $theme_ver = aya_get_theme_version();
    $plugin_array['tinymceJs'] = get_template_directory_uri().'/assets/admin-custom/tinymce.js?ver='.$theme_ver;

    return $plugin_array;
}
add_filter('mce_external_plugins', 'add_more_buttons_plugin');

//排序编辑器第一行
function try_mce_buttons_1($buttons){
    $buttons = array(
        'bold',
        'italic',
        'underline',
        'strikethrough',
        'bullist',
        'numlist',
        'blockquote',
        'hr',
        'alignleft',
        'alignright',
        'aligncenter',
        'alignjustify',
        'subscript',
        'superscript',
        'link',
        'unlink',
        'wp_more',
        'wp_adv'
    );

    return $buttons;
}
add_filter( 'mce_buttons', 'try_mce_buttons_1' );

//排序编辑器第二行
function try_mce_buttons_2($buttons){
    $buttons = array(
        'formatselect',
        'fontsizeselect',
        'forecolor',
        'backcolor',
        'charmap',
        'pastetext',
        'removeformat',
        'spellchecker',
        'fullscreen',
        'undo',
        'redo',
        'indent',
        'outdent',
        'cleanup',
        'charmap',
        'wp_help',
        'code',
        'sub',
        'sup',
        'anchor',
    );

    return $buttons;
}
add_filter( 'mce_buttons_2', 'try_mce_buttons_2' );

//排序编辑器第三行
function more_mce_buttons($buttons){
    $buttons = array(
        'btnCode',
        'btnPanel',
        'btnPost',
        'btnVideo',
        'btnMusic',
    );
    return $buttons;
}
add_filter("mce_buttons_3", "more_mce_buttons");

//WordPress后台：后台删除仪表盘站点健康模块
if (aya_option('remove_wp_dashboard') == true) {
    function remove_dashboard_meta_box(){
        //删除 "欢迎" 模块
        remove_action('welcome_panel', 'wp_welcome_panel');
        delete_user_meta( get_current_user_id(), 'show_welcome_panel' );
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
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side');
        //删除 "WordPress 新闻" 模块
        remove_meta_box( 'dashboard_secondary', 'dashboard', 'side');
    }
    add_action('wp_dashboard_setup', 'remove_dashboard_meta_box');
}

//WordPress后台：禁用默认小工具
if (aya_option('remove_wp_widget') == true) {
    function widgets_unregister(){
        unregister_widget('WP_Widget_Archives');        //年份文章归档
        unregister_widget('WP_Widget_Calendar');        //日历
        unregister_widget('WP_Widget_Categories');      //分类列表
        unregister_widget('WP_Widget_Links');           //链接
        unregister_widget('WP_Widget_Media_Audio');     //音乐
        unregister_widget('WP_Widget_Media_Video');     //视频
        unregister_widget('WP_Widget_Media_Gallery');   //相册
        unregister_widget('WP_Widget_Custom_HTML' );    //html
        unregister_widget('WP_Widget_Media_Image');     //图片
        unregister_widget('WP_Widget_Text');            //文本
        unregister_widget('WP_Widget_Meta');            //默认工具链接
        unregister_widget('WP_Widget_Pages');           //页面
        unregister_widget('WP_Widget_Recent_Comments'); //评论
        unregister_widget('WP_Widget_Recent_Posts');    //文章列表
        unregister_widget('WP_Widget_RSS');             //RSS订阅
        unregister_widget('WP_Widget_Search');          //搜索
        unregister_widget('WP_Widget_Tag_Cloud');       //标签云
        unregister_widget('WP_Nav_Menu_Widget');        //菜单
        unregister_widget('WP_Widget_Block');           //区块
    }
    add_action('widgets_init', 'widgets_unregister');
}

//WordPress后台：后台一言显示
if (aya_option('add_hitokoto') == true) {
    function hello_hitokoto(){
        echo '<p id="hello-hitokoto"><span dir="ltr">'.aya_hitokoto().'</span></p>';
    }
    add_action('admin_notices', 'hello_hitokoto');
}

//WordPress后台：后台登陆数学验证码
if (aya_option('current_user_check') == true) {
    function add_login_fields(){
        //获取两个随机数, 范围0~9
        $num_0 = rand(0,9);
        $num_1 = rand(0,9);
        //输出到登录表单
        echo '<label for="math" class="small">验证码</label>
        <input type="text" name="sum" placeholder="'.$num_0.' + '.$num_1.' = ?" class="input" value="" size="10" tabindex="4">
        <input type="hidden" name="num_0" value="'.$num_0.'"><input type="hidden" name="num_1" value="'.$num_1.'">';
    }
    add_action('login_form','add_login_fields');

    //登录验证码计算器
    function login_val(){
        //用户提交的计算结果
        $sum = !empty($_POST['sum']) ? $_POST['sum'] : null;
        $num_0 = !empty($_POST['num_0']) ? $_POST['num_0'] : null;
        $num_1 = !empty($_POST['num_1']) ? $_POST['num_1'] : null;
        //计算
        switch($sum){
            //直接跳出
            case $num_0 + $num_1 :
                break;
            //未填写
            case null :
                wp_die('错误：请输入验证码。');
                break;
            //计算错误
            default :
                wp_die('错误：验证码错误，请重试。');
                break;
        }
    }
    add_action('login_form_login','login_val');
}