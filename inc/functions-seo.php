<?php
if (!defined('ABSPATH')){
    exit;
}

/*
 * ------------------------------------------------------------------------------
 * SEO组件
 * ------------------------------------------------------------------------------
 */

//替换站点名称
function aya_site_name(){
    $site_name = (aya_option('seo_site_name') == '') ? get_bloginfo('name') : aya_option('seo_site_name');
    return $site_name;
}

//获取页面标题
function aya_head_title(){
    switch (aya_page_type()){
        case 'home':
            $title = '首页';
            break;
        case 'single':
            $title = get_post_title();
            break;
        case 'page':
            $title = get_post_title();
            break;
        case 'search':
            $title = '搜索 "'.get_query_var('s').'" 的结果';
            break;
        case 'category':
            $title = '分类 '.single_cat_title('', false).' 的文章';
            break;
        case 'tag':
            $title = '标签 '.single_tag_title('#', false) .' 的文章';
            break;
        case 'author':
            $title = '用户「'.get_the_author().'」的个人主页';
            break;
        case '404':
            $title = '404 NOT FOUND';
            break;
        default:
            $title = wp_get_document_title();
            break;
    }
    return $title;
}

//SEO：站点标题选择器
function aya_site_seo_title(){
    //获取主题设置
    $site_name = aya_site_name();
    $page_title = aya_head_title();
    $fgf = ' '.aya_option('title_fgf').' ';
    //获取当前页数
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    if ($paged > 1) {
        $paged = $fgf.'第'.$paged.'页';
    }
    else {
        $paged = '';
    }
    //生成页面标题
    switch (aya_page_type()){
        case 'home':
            $des_title = (aya_option('seo_site_description') == '') ? get_bloginfo('description') : aya_option('seo_site_description');
            $site_title = $site_name.$paged.$fgf.$des_title;
            break;
        default:
            $site_title = $page_title.$paged.$fgf.$site_name;
            break;
    }
    return $site_title;
}

//SEO：关键词
function aya_site_seo_keywords(){
    //获取主题设置
    $keywords = aya_option('seo_keywords');
    //返回文章标签
    switch (aya_page_type()){
        case 'single':
            $tags = get_the_tags();
            $meta_value = $tags ? implode(' ', wp_list_pluck($tags, 'name')) : '';
            break;
        case 'category':
            $meta_value = single_cat_title('', false);
            break;
        case 'tag':
            $meta_value = single_tag_title('', false);
            break;
        default:
            $meta_value = $keywords;
            break;
    }
    return $meta_value;
}

//SEO：描述
function aya_site_seo_description(){
    //获取主题设置
    $des = aya_option('seo_description');
    //返回分类描述或文章摘要
    switch (aya_page_type()){
        case 'single':
            $meta_value = wp_trim_words(get_the_content(), 150, '...');
            break;
        case 'page':
            $meta_value = wp_trim_words(get_the_content(), 150, '...');
            break;
        case 'category':
            $meta_value = term_description();
            break;
        case 'tag':
            $meta_value = term_description();
            break;
        default:
            $meta_value = $des;
            break;
    }
    return $meta_value;
}

//SEO：文章内图片添加ALT和TITLE
if(aya_option('seo_img_alt') == true) {
    function image_alt_tag($content){
        preg_match_all('/<img (.*?)\/>/', $content, $images);

        if(!is_null($images)) {
            foreach($images[1] as $index => $value){
                $img_alt = str_replace('<img', '<img loading="lazy" title="'.get_post_title().'-'.get_bloginfo('name').'" alt="'.get_post_title().'"', $images[0][$index]);
                $content = str_replace($images[0][$index], $img_alt, $content);
            }
        }
        return $content;
    }
    add_filter('the_content', 'image_alt_tag', 998);
}

//SEO：文章内链接转内链
if(aya_option('seo_link_jump') == true) {
    function aya_seo_link_jump($content){
        preg_match_all('/<a(.*?)href="(.*?)"(.*?)>/', $content, $matches);
        
        if($matches){
            foreach($matches[2] as $val){
                if(strpos($val,'://')!==false && strpos($val,home_url())===false && !preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff|zip|rar|exe|dmg|7z|svg|mp3|mp4|avi|3gp|webp)/i',$val)){
                    $content = str_replace("href=\"$val\"", "href=\"".home_url()."/go/?url=".base64_encode($val)."\" rel=\"nofollow\" target=\"_blank\"",$content);
                }
            }
        }
        return $content;
    }
    add_filter('the_content', 'aya_seo_link_jump', 999);
}

//SEO：文章内链接nofollow
if(aya_option('seo_link_jump') == false && aya_option('seo_link_nofollow') == true) {
    function aya_seo_link_nofollow($content){
        preg_match_all('/<a(.*?)href="(.*?)"(.*?)>/', $content, $matches);
        
        if($matches){
            foreach($matches[2] as $val){
                if(strpos($val,'://')!==false && strpos($val,home_url())===false && !preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff|zip|rar|exe|dmg|7z|svg|mp3|mp4|avi|3gp|webp)/i',$val)){
                    $content = str_replace("href=\"$val\"", "href=\"$val\" rel=\"nofollow\" target=\"_blank\"",$content);
                }
            }
        }
        return $content;
    }
    add_filter('the_content', 'aya_seo_link_nofollow', 999);
}

//SEO：页面链接添加html后缀
if(aya_option('seo_page_link') == true) {
    function aya_page_permalink(){
        global $wp_rewrite;
        //防止循环
        if(!strpos($wp_rewrite->get_page_permastruct(), '.html')){
            $wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
        }
    }
    add_action('init', 'aya_page_permalink', -1);
}

//DEBUG：检查多余斜杠
function do_trailingslashit($string, $type){
    if($type != 'single' && $type != 'page')
        $string = trailingslashit($string);
    return $string;
}
add_filter('user_trailingslashit', 'do_trailingslashit', 10, 2);

//SEO：创建robots.txt
function robtxt_filter_robots($output, $public) {
    //获取主题设置
    $content = aya_option('seo_Roboot_txt');
    if(empty($content)){
        $output = get_default_robots();
    }else{
        $output .= esc_attr(wp_strip_all_tags($content));
    }
    return $output;
}
add_filter('robots_txt', 'robtxt_filter_robots', 10, 2);

//生成robots.txt内容
function get_default_robots(){
    //兼容站点路径设置
    $site_url = parse_url(site_url());
    $path = !empty($site_url['path']) ? $site_url['path'] : '';
    //生成
    return '
User-agent: *
Disallow: /wp-admin/
Allow: /wp-admin/admin-ajax.php
Disallow: /wp-includes/
Disallow: /cgi-bin/

Disallow: '.$path.'/wp-content/plugins/
Disallow: '.$path.'/wp-content/themes/
Disallow: '.$path.'/wp-content/cache/
Disallow: '.$path.'/trackback/
Disallow: '.$path.'/feed/
Disallow: '.$path.'/comments/
Disallow: '.$path.'/search/
Disallow: '.$path.'/?s=
Disallow: '.$path.'/go/?url=
Disallow: '.$path.'/link/?url=

Sitemap: '.site_url().'/wp-sitemap.xml
';
}
