<?php
if (!defined('ABSPATH')){
    exit;
}

/*
 * ------------------------------------------------------------------------------
 * 头像功能函数及相关组件
 * ------------------------------------------------------------------------------
 */

//替换Gravatar为国内Cravatar
if (aya_option('get_cravatar') == true) {
    function get_cravatar_url($url){
        $sources = array(
            'www.gravatar.com',
            '0.gravatar.com',
            '1.gravatar.com',
            '2.gravatar.com',
            'secure.gravatar.com',
            'cn.gravatar.com',
            'gravatar.com',
        );
        return str_replace($sources, 'cravatar.cn', $url);
    }
    function set_defaults_for_cravatar($avatar_defaults){
        $avatar_defaults['gravatar_default'] = 'Cravatar 标志';

        return $avatar_defaults;
    }
    function set_user_profile_picture_for_cravatar(){
        return '<a href="https://cravatar.cn" target="_blank">您可以在 Cravatar 修改您的资料图片</a>';
    }
    add_filter( 'um_user_avatar_url_filter', 'get_cravatar_url', 1 );
    add_filter( 'bp_gravatar_url', 'get_cravatar_url', 1 );
    add_filter( 'get_avatar_url', 'get_cravatar_url', 1 );
    add_filter( 'avatar_defaults', 'set_defaults_for_cravatar', 1 );
    add_filter( 'user_profile_picture_description', 'set_user_profile_picture_for_cravatar', 1 );
}

//替换Gravatar为七牛CDN源
if (aya_option('get_gravatar_cdn') == true && aya_option('get_cravatar') == false) {
    function get_gravatar_url_cdn($url){
        $sources = array(
            'www.gravatar.com',
            '0.gravatar.com',
            '1.gravatar.com',
            '2.gravatar.com',
            'secure.gravatar.com',
            'cn.gravatar.com',
            'gravatar.com',
        );
        $cdn = 'dn-qiniu-avatar.qbox.me';
        return str_replace($sources, $cdn, $url);
    }
    add_filter( 'um_user_avatar_url_filter', 'get_gravatar_url_cdn', 1 );
    add_filter( 'bp_gravatar_url', 'get_gravatar_url_cdn', 1 );
    add_filter( 'get_avatar_url', 'get_gravatar_url_cdn', 1 );
}

