<?php
/*
 * ------------------------------------------------------------------------------
 * 注册主题功能
 * ------------------------------------------------------------------------------
 */

//自定义wp-login.php登录页面
function custom_login() { ?>
    <style type="text/css">
    body.login{background:url(https://api.kdcc.cn/img/rand.php);background-size:cover;background-repeat:no-repeat;background-position:center center;display:flex;align-items:center;justify-content:center;}
    #login{background:rgb(255 255 255 / 74%);padding:10px 10px 20px 10px;}
    #login h1{display:none!important;}
    #login form{border:none;box-shadow:none;background:none;padding-bottom:0px;}
    #login form p{margin-bottom:5px;}
    #login form p .input{margin-top:5px;}
    #login form .forgetmenot{width:100%;}
    #login form p.submit{width:100%;margin-top:20px;}
    #login form p #wp-submit{width:100%;height:42px;background:#58b3e8;border:none;box-shadow:none;text-shadow:none;}
    #login form p.forgetmenot{display:block;padding-bottom:20px;}
    #login form .clear{display:none!important;}
    #login #nav{}
    #login #nav a{margin:0px 10px;}
    #login .message{display:none}
    #login #login_error,
    #login .success{border:none;box-shadow:none;color:#fd1616;font-weight:300;margin-bottom:0px;background:none;padding:30px 30px 0px 30px;}
    #reg_passmail{display:none}
    .language-switcher{display:none!important;}
    @media (max-width:768px){
        body.login{background:#fff;}
        #login{width:100%;padding:30px;margin:0px;}
    }
    </style><?php 
}
add_action('login_head', 'custom_login');

//后端CSS控制
function my_admin_theme_style() { ?>
    <style type="text/css">
    #wp-admin-bar-wp-logo,
    #wp-admin-bar-my-account .avatar,
    #wp-admin-bar-user-actions,
    .user-comment-shortcuts-wrap,
    .user-rich-editing-wrap,
    .user-admin-bar-front-wrap,
    .user-first-name-wrap,
    .user-last-name-wrap,
    #wp-admin-bar-new-content,
    #wp-admin-bar-comments{display:none!important;}
    
    /*zidingyi*/
    #customize-theme-controls .accordion-section .attachment-thumb{height:80px;}
    #customize-theme-controls .accordion-section .customize-control{padding:20px;width:calc(100% - 40px)!important;background:#fff;border-radius:8px;position:relative;}
    #customize-theme-controls .accordion-section .description{color:#acacac!important;font-size:12px;margin-bottom:10px;}
    #customize-theme-controls .accordion-section .customize-control#customize-control-custom_css{margin-bottom:0px;margin-left:0px;}
    #customize-theme-controls #accordion-panel-nav_menus,
    #customize-theme-controls #accordion-panel-widgets{display:none!important;}
    #customize-theme-controls .chosen-container.chosen-with-drop .chosen-drop{position:absolute;}
    
    /*post_meta*/
    .edit-post-meta-boxes-area .csf-metabox .csf-field{padding:20px 0px!important;}
    .edit-post-meta-boxes-area .csf-metabox .csf-section-title{padding:20px 0px!important;background:none;}
    .edit-post-meta-boxes-area .csf-metabox .csf-section-title h3{font-size:16px;}
    .edit-post-meta-boxes-area .csf-metabox .csf-field.csf-field-repeater .csf-fieldset .csf-field{padding: 20px !important;}
    .edit-post-meta-boxes-area .postbox{background:#ffffff;margin:30px!important;border-radius:15px;padding:20px;border:1px solid #dfe0df!important;box-shadow:none;}
    .edit-post-meta-boxes-area .postbox-header{border-top:none!important;}
    .edit-post-meta-boxes-area #poststuff h2.hndle{padding:20px 10px!important;font-size:16px!important}
    .edit-post-meta-boxes-area .inside .csf-nav-inline{border-bottom:3px solid #e6e6e6;background:none;margin:20px 0px;}
    .edit-post-meta-boxes-area .inside .csf-nav-inline ul{margin-bottom:-3px;}
    .edit-post-meta-boxes-area .inside .csf-nav-inline ul li{}
    .edit-post-meta-boxes-area .inside .csf-nav-inline ul li a{color:#888;font-size:16px;padding:12px 0px;margin-right:25px;border-bottom:3px solid #e6e6e6;border-right:none;border-radius:0;background:none;}
    .edit-post-meta-boxes-area .inside .csf-nav-inline ul li a.csf-active{border-bottom:3px solid #2271b1;color:#333;}
    </style>
    <?php 
}
add_action('admin_enqueue_scripts', 'my_admin_theme_style');
add_action('login_enqueue_scripts', 'my_admin_theme_style');


/**
 * 检查更新组件info.json 格式说明
 * 
 * 2 | 主题的版本号
 * 3 | 升级说明文件链接
 * 4 | 主题下载链接
 * 
 *  {
 *      "version" : "1.0",
 *      "details_url" : "https://www.yeraph.com/aiya-themes/aiya-info.txt",
 *      "download_url" : "https://www.yeraph.com/aiya-themes/aiya-cms.zip"
 *  }
 */

//检查更新
require get_template_directory(). '/inc/class/update-checker.class.php'; 
new ThemeUpdateChecker(
	'AiYa-CMS', //主题名字
	'https://www.yeraph.com/aiya-themes/aiya-info.json'  //info.json 的访问地址
);

/* ---------- 注释 ----------
//启动主题自动跳转到自定义
global $pagenow;

if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) {
    wp_redirect( 
        admin_url( 'customize.php?return=%2Fwp-admin%2Fthemes.php' ) 
    );
exit;
}

//提醒上传默认缩略图
function nopic_des(){
        echo '<div class="nopic_des"><p>请进行设置默认缩略图，此为必设选项。</p><a class="" href="'.get_option('home').'/wp-admin/customize.php">立即前往</a></div>';

 */