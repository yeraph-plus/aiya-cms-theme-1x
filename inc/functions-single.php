<?php
if (!defined('ABSPATH')){
    exit;
}

/*
 * ------------------------------------------------------------------------------
 * 文章页面功能
 * ------------------------------------------------------------------------------
 */

//浏览量计数器
function record_visitors(){
    if (is_singular()) {
        global $post;

        if ($post->ID) {
            $post_views = (int)get_post_meta($post->ID, 'views', true);
            if (!update_post_meta($post->ID, 'views', ($post_views + 1))) {
                add_post_meta($post->ID, 'views', 0, true);
            }
        }
    }
}
add_action('wp_head', 'record_visitors');

//点赞 +1 计数器
function add_zan(){
    global $wpdb, $post;
    $id = $_POST["um_id"];
    $action = $_POST["um_action"];
    if ($action == 'ding') {
        $specs_raters = get_post_meta($id, 'specs_zan', true);
        $expire = time() + 99999999;
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; // make cookies work with localhost
        setcookie('specs_zan_' . $id, $id, $expire, '/', $domain, false);
        if (!$specs_raters || !is_numeric($specs_raters)) {
            update_post_meta($id, 'specs_zan', 1);
        } else {
            update_post_meta($id, 'specs_zan', ($specs_raters + 1));
        }
        return get_post_meta($id, 'specs_zan', true);
    }
    die;
}
add_action('wp_ajax_nopriv_add_zan', 'add_zan');
add_action('wp_ajax_add_zan', 'add_zan');

//点赞 -1 计数器
function sub_zan(){
    global $wpdb, $post;
    $id = $_POST["um_id"];
    $action = $_POST["um_action"];
    if ($action == 'duang') {
        $specs_raters = get_post_meta($id, 'specs_zan', true);
        $expire = time() - 99999999;
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; // make cookies work with localhost
        setcookie('specs_zan_' . $id, $id, $expire, '/', $domain, false);
        if (!$specs_raters || !is_numeric($specs_raters)) {
            update_post_meta($id, 'specs_zan', 1);
        } else {
            update_post_meta($id, 'specs_zan', ($specs_raters - 1));
        }
        return get_post_meta($id, 'specs_zan', true);
    }
    die;
}
add_action('wp_ajax_nopriv_sub_zan', 'sub_zan');
add_action('wp_ajax_sub_zan', 'sub_zan');

//移除密码保护文章标题前缀
function remove_protected_title_format( $format ) {
	return '';
}
add_filter('protected_title_format', 'remove_protected_title_format');

//移除私密文章标题前缀
function remove_private_title_format( $format ) {
	return '';
}
add_filter('private_title_format', 'remove_private_title_format');

/*
 * ------------------------------------------------------------------------------
 * 文章页面组件及相关函数
 * ------------------------------------------------------------------------------
 */

//提取文章中的图片
function aya_get_first_img($post_id = 0, $type = 0){
    if(!$post_id){
        global $post;
        $content = $post->post_content;
    }else{
        $content = get_post_field('post_content',$post_id);
    }
    //遍历内容提取图片
    preg_match_all('/<img[^>]*src=[\'"]?([^>\'"\s]*)[\'"]?[^>]*>/i', $content, $match);
    
    //判断返回参数类型
    if(is_numeric($type)){
        //返回指定第几张图片
        return isset($match[1][$type]) ? $match[1][$type] : aya_option('nopic');
    }
    else if($type = 'all'){
        //返回全部数组，交给其他函数处理
        return $match[1];
    }
    else{
        //参数错误
        return ;
    }
}

//检查文章标题是否为空
function get_post_title($post_id = 0){
    if(!$post_id){
        global $post;
        $post_id = $post->ID;
    }
    //检查文章标题
    if(get_the_title($post_id) != ''){
        return get_the_title($post_id);
    }else{
        return '无标题';
    }
}

//检查文章状态
function aya_test_post_status($post_id){
    //返回文本
    switch(get_post_status($post_id)){
        case 'publish':
            $status = '已发布';
            break;
        case 'pending':
            $status = '待审';
            break;
        case 'future':
            $status = '定时发布';
            break;
        case 'private':
            $status = '私密文章';
            break;
        case 'draft':
            $status = '草稿';
            break;
        case 'auto-draft':
            $status = '自动保存的草稿';
            break;
        case 'inherit':
            $status = '修订版本';
            break;
        case 'trash':
            $status = '已删除';
            break;
        default:
            $status = '';
            break;
    }
    return $status;
}

//获取缩略图
function get_post_thumb($post_id = 0, $w = 300, $h = 210){
    if(!$post_id){
        global $post;
        $post_id = $post->ID;
    }
    //如果存在特色图片
    if(has_post_thumbnail($post_id)){
        $img_url = get_the_post_thumbnail_url($post_id);
    }
    //否则获取文章第一张图片
    else{
        $img_url = esc_url(aya_get_first_img($post_id, 0));
    }
    return get_bfi_thumb($img_url, $w, $h);
}

//文章去除图片
function aya_clear_text($text, $type = 0){
    if(!$text){
        return;
    }
    //清理预定义字符
    $text = trim($text);
    //清理图片
    $text = preg_replace('/<a(.*?)href=("|\')([^>]*).(bmp|gif|jpeg|jpg|png|swf|webp)("|\')(.*?)>(.*?)<\/a>/i','',$text);
    $text = preg_replace('/<img[^>]*src=[\'"]?([^>\'"\s]*)[\'"]?[^>]*>/i','',$text);
    //清理html
    if($type == 0){
        $text = strip_tags($text);
        $text = preg_replace('/<[^<]+>/', '',$text);
    }
    //返回
    return trim($text);
}

//浏览量计算器
function aya_restyle_views($number) {
    if($number >= 1000) {
        return round($number/1000,2) . 'k';
    }else{
        return $number;
    }
}

//获取文章访问量
function get_post_views($post_id = 0){
    if(!$post_id){
        global $post;
        $post_id = $post->ID;
    }
    $views = get_post_meta($post_id, 'views', true);

    if($views > 0){
        return aya_restyle_views($views);
    }else{
        return '0';
    }
};

//获取文章点赞数
function get_post_specs_zan($post_id = 0){
    if(!$post_id){
        global $post;
        $post_id = $post->ID;
    }
    $raters = get_post_meta($post_id, 'specs_zan', true);
    if($raters > 0){
        return $raters;
    }else{
        return '0';
    }
};

//获取文章摘要
function get_post_preview($post_id = 0, $size = 150){
    if(!$post_id){
        global $post;
        $excerpt = $post->post_excerpt;
        $content = $post->post_content;
    }else{
        $excerpt = get_post($post_id)->post_excerpt;
        $content = get_post($post_id)->post_content;
    }
    //如果文章加密
    if (post_password_required()){
        return '这篇文章受密码保护，输入密码才能阅读。';
    }
    //如果用户设置了摘要，则直接输出摘要内容
    if ($excerpt){
        $preview = $excerpt;
    }else{
        //DEBUG：WP原生的摘要函数好像完全没法判断中文长度，改用PHP判断
        //$preview = wp_trim_words($content, $size);
        $preview = mb_strimwidth(aya_clear_text(strip_shortcodes($content)), 0, $size,'...');
    }
    //如果摘要为空
    if ($preview != ''){
        return $preview;
    }else{
        return '这篇文章没有摘要内容。';
    }
}

//计算已发布时间
function aya_timeago($time){
    //获取主题设置
    if (aya_option('single_timeago') == true){
        //使用WordPress内置方法
        return human_time_diff($time, current_time('timestamp')).'前';
    }else{
        $format = get_option('date_format');
        return date($format, $time);
    }
}

//获取文章发布时间
function get_post_date($post_id = 0){
    if(!$post_id){
        global $post;
        $post_id = $post->ID;
    }
    $publish_time = get_the_time('U', $post_id);
    return aya_timeago($publish_time);
}

//获取文章上次更新时间
function get_post_last_update($post_id = 0){
    if(!$post_id){
        global $post;
        $post_id = $post->ID;
    }
    $publish_time = get_the_time('U', $post_id);
    $modified_time = get_the_modified_time('U', $post_id);
    if($modified_time >= $publish_time + 86400){
        return '（上次更新于 '.aya_timeago($modified_time).' ）';
    }
    return '';
}

//检查文章是否已过时
function get_post_is_outmoded($post_id = 0){
    if(!$post_id){
        global $post;
        $post_id = $post->ID;
    }
	$publish_time = get_the_time('U', $post_id);
	$modified_time = get_the_modified_time('U', $post_id);
    //时间30天
	if ( $modified_time >= $publish_time + 86400*30 ) {
        return true;
    }else{
        return false;
    }
}

//获取当前分类和子分类文章数量
function get_cat_postcount($cat_id){
    $cat = get_category($cat_id);
    $tax_cat = get_terms('category', array('child_of' => $cat_id));
    $count = (int) $cat->count;
    //循环累加
    foreach($tax_cat as $tax_cat){
        $count += $tax_term->count;
    }
    return $count;
}

//获取子分类的根分类
function aya_get_root_category($cat_id){
    $this_category = get_category($cat_id);
    //若当前分类有上级分类时，循环 
    while($this_category->category_parent){
        //将当前分类设为上级分类（往上爬）
        $this_category = get_category($this_category->category_parent);
    }
    //返回根分类的id号
    return $this_category->term_id;
}
