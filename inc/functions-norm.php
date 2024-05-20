<?php
if (!defined('ABSPATH')){
    exit;
}

/*
 * ------------------------------------------------------------------------------
 * 缩略图组件及相关函数
 * ------------------------------------------------------------------------------
 * BFI_Thumb.php
 * 
 * Demo1
 * $size = array( 400, 300, 'opacity' => 50, 'grayscale' => true, 'bfi_thumb' => true );
 * wp_get_attachment_image_src( $attachment_id, $size )
 * 
 * Else
 * the_post_thumbnail( array( 1024, 400, 'bfi_thumb' => true, 'grayscale' => true ) );
 * 
 * Demo2
 * $params = array( 'width' => 400, 'height' => 300, 'opacity' => 50, 'grayscale' => true, 'colorize' => '#ff0000' );
 * bfi_thumb( "URL-to-image.jpg", $params );
 * 
 */

//加载预定义类
require get_template_directory() . '/inc/class/BFI_Thumb.php';

//Change the upload subdirectory to wp-content/uploads/bfi_thumb
@define(BFITHUMB_UPLOAD_DIR, 'bfi_thumb');

//BFI_Thumb调用函数
function get_bfi_thumb($url, $w ,$h){
    $params = array(
        'width' => $w, //int pixels
        'height' => $h, //int pixels
        //'opacity' => $w, //int 0-100
        //'color' => $w, //string hex-color #000000-#ffffff
        //'grayscale' => $w, //bool
        //'negate' => $w, //bool
        'crop' => true, //bool
        //'crop_only' => $w, //bool
        //'crop_x' => $w, //bool string
        //'crop_y' => $w, //bool string
        //'crop_width' => $w, //bool string
        //'crop_height' => $w, //bool string
        //'quality' => $w //int 1-100
    );
    //检查是否为本地图片
    if(stristr($url, get_site_url())){
        $thumb_url = bfi_thumb($url, $params);
        return $thumb_url;
    }else{
        return $url;
    }
}

/*
 * ------------------------------------------------------------------------------
 * COOKIE 操作相关函数
 * ------------------------------------------------------------------------------
 */

//加载预定义类
require get_template_directory() . '/inc/class/XDeode.php';

//设置
function aya_set_cookie($key, $token, $time = 86400){
    //默认保存24小时
    setcookie($key, $token, time() + $time, COOKIEPATH, COOKIE_DOMAIN);
}

//获取
function aya_get_cookie($key, $len= 0){
    //检查Cookie是否存在
    if (!isset($_COOKIE[$key])){
        return false;
    }
    //检查Cookie是否正确
    if($len != 0 && $len != strlen($_COOKIE[$key])){
        return false;
    }
    return $_COOKIE[$key];
}

//销毁
function aya_delete_cookie($key, $time = 86400){
    setcookie($key, '', time() - $time, COOKIEPATH, COOKIE_DOMAIN);
}

//Token加密
function encode_token($token, $length ){
    $obj = new XDeode($length);
    return $obj->encode($token);
}

//Token解密
function decode_token($token, $length = 15){
    $obj = new XDeode($length);
    return $obj->decode($token);
}

//创建用户访问时间戳Cookie
function set_user_date_cookie(){
    if (aya_get_cookie('aya_date_token', 10)){
        //创建Cookie
        aya_set_cookie('aya_date_token', encode_token(time()), 365*24*3600);
    }
    die();
}
/*
 * ------------------------------------------------------------------------------
 * 生成Bootstrap菜单
 * ------------------------------------------------------------------------------
 */

//用于菜单和子菜单walker生成的类
class aya_Bootstarp_Nav_Menu extends Walker_Nav_Menu {
    /**
     * start_lvl
     * 处理ul，如果ul有一些特殊的样式，修改这里
     * $depth是层级，一级二级三级
     * $args是用于获取wp_nav_menu()函数定义的数组
     *
     */
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        //添加缩进
        $indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' );
        //debug：这里+1为了从1开始算层级，因为默认是0
        //$display_depth = ( $depth + 1);

        $classes = array(
            'dropdown-menu', //ul是个子菜单的时候，添加这个样式
            //( $display_depth % 2  ? 'menu-odd' : 'menu-even' ), //子菜单奇数加样式menu-odd,偶数加样式menu-even
            //( $display_depth >=2 ? 'sub-sub-menu' : '' ),   //三级菜单的时候，添加这个样式
            //'menu-depth-' . $display_depth, //显示当前菜单的层级，menu-depth-2是二级呗
        );
        //空格分割
        $class_names = implode( ' ', $classes );
        //输出
        $output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n"; 
    }

    /**
     * start_el
     * 处理li和里面的a
     * $depth和$args同上
     *
     */
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ){
        //debug：获取全局query
        global $wp_query;
        //添加缩进
        $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' );

        $depth_classes = array(
            ( $depth == 0 ? 'nav-item' : 'dropdown-item' ), //一级的使用nav-item，其余层级全部dropdown-item
            ( $args->walker->has_children == 0 ? '' : 'dropdown' ), //判断是否存在层级
            
            //( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ), //奇数加样式menu-item-odd,偶数加样式menu-item-even
            //( $depth >=2 ? 'sub-sub-menu-item' : '' ), //三级的li，添加这个样式
            //'menu-item-depth-' . $depth, //显示当前菜单的层级
        );
        
        //空格分割
        $class_names = esc_attr( implode( ' ', $depth_classes ) );

        //输出原生menu-item-has-children的标签
        //$classes = empty( $item->classes ) ? array() : (array) $item->classes;
        //$class_names .= esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) ); 

        //把样式合成到li里面
        $output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="'.$class_names.'">';

        //处理a的属性
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        //判断是否存在层级，生成额外标签
        if($args->walker->has_children){
            $add_a_class = 'dropdown-toggle';
            $add_a_current = 'role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"';
        }else{
            $add_a_class = '';
            $add_a_current = 'data-bs-toggle="page"';
        }
        //增加一个class
        $attributes .= ' class="nav-link '.$add_a_class.'" '.$add_a_current.'';

        //重新生成a的样式
        $item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
            $args->before,
            $attributes,
            $args->link_before,
            apply_filters( 'the_title', $item->title, $item->ID ),
            $args->link_after,
            $args->after
        );
        //如果一级菜单是<a><span>菜单</span></a>，其他级菜单是<a><strong>菜单</strong></a>
        //这样的情况，$args->link_before是固定值就不行了，要自行判断
        //$link_before = $depth == 0 ? '<span>' : '<strong>';
        //$link_after = $depth == 0 ? '</span>' : '</strong>';
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

//生成菜单
function aya_menu_nav($menu, $dep = 2){
    if($menu == 'header-menu'){
        $menu_class = 'navbar-nav';
    }else{
        $menu_class = 'nav';
    }
    $args = array(
        'theme_location' => $menu,
        'depth' => $dep,
        'container' => false,
        'menu_class'  => $menu_class,
        'menu_id' => '',
        'fallback_cb' => 'main-menu',
        'walker' => new aya_Bootstarp_Nav_Menu()
    );
    $nav_html = wp_nav_menu($args);

    return $nav_html;
}

/*
 * ------------------------------------------------------------------------------
 * 生成Bootstrap分页
 * ------------------------------------------------------------------------------
 */

//分页函数
function aya_page_nav(){
    /*
    //判断当前页面类型
    if(get_post_type() == 'post'){
        global $paged,$wp_query;
        $max_page = $wp_query->max_num_pages;
    }else{
        $type = get_post_type();
        $count = wp_count_posts($type)->publish;
        $max_page = $count;
        $paged = ceil($count / 10);
    }
    */
    global $paged,$wp_query;
    $max_page = $wp_query->max_num_pages;

    //定义最大显示分页数
    $range = 5;
    //拼接分页列表
    $html = '';
    if($max_page > 1) {
        $html .= '<ul class="pagination">';
        if( !$paged ){
            $paged = 1;
        }
        //上一页
        if( $paged != 1 ) {
            $html .= '<li class="page-item"><a class="page-link" href="'.get_pagenum_link(1).'"><span aria-hidden="true">首页</span></a></li>';
            $html .= '<li class="page-item"><a class="page-link" href="'.get_pagenum_link($paged - 1).'"><span aria-hidden="true">上一页</span></a></li>';
        }
        //生成页码
        if ( $max_page >$range ){
            $range_page = ceil($range / 2);
            $call_page = $max_page - $range_page;
            if( $paged < $range ){
                for( $i = 1; $i <= $range; $i++ ) {
                    $active = ($i == $paged) ? 'active' : '';
                    $html .= '<li class="page-item '.$active.'"><a class="page-link" href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
                }
            }
            elseif($paged >= $call_page){
                for($i = ($max_page - $range); $i <= $max_page; $i++){
                    $active = ($i == $paged) ? 'active' : '';
                    $html .= '<li class="page-item '.$active.'"><a class="page-link" href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
                }
            }
            elseif($paged >= $range && $paged < $call_page){
                for($i = ($paged - $range_page); $i <= ($paged + $range_page); $i++){
                    $active = ($i == $paged) ? 'active' : '';
                    $html .= '<li class="page-item '.$active.'"><a class="page-link" href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
                }
            }
        }else{
            for($i = 1; $i <= $max_page; $i++){
                $active = ($i == $paged) ? 'active' : '';
                $html .= '<li class="page-item '.$active.'"><a class="page-link" href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
            }
        }
        //尾页
        if($paged != $max_page){
            $html .= '<li class="page-item"><a class="page-link" href="'.get_pagenum_link($paged + 1).'"><span aria-hidden="true">下一页</span></a></li>';
            $html .= '<li class="page-item"><a class="page-link" href="'.get_pagenum_link($max_page).'"><span aria-hidden="true">尾页</span></a></li>';
        }
        //总页数
        $html .= '<li class="page-item disabled"><a class="page-link">共 '.$max_page.' 页</a></li>';
        $html .= '</ul>';
    }
    return $html;
}

/*
 * ------------------------------------------------------------------------------
 * 替代页面属性
 * ------------------------------------------------------------------------------
 */

//获取页面位置
function aya_page_type(){
    //返回页面属性
    if (is_home() || is_front_page()){
        return 'home';
    }
    elseif (is_single()){
        return 'single';
    }
    elseif (is_page()){
        return 'page';
    }
    elseif (is_search()){
        return 'search';
    }
    elseif (is_category()){
        return 'category';
    }
    elseif (is_tag()){
        return 'tag';
    }
    elseif (is_author()){
        return 'author';
    }
    elseif (is_404()){
        return '404';
    }
    else{
        return 'default';
    }
}

//文章内图片支持灯箱
function aya_single_fancybox($content){
    global $post;global $post;
    $pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png|swf|webp)('|\")(.*?)>(.*?)<\/a>/i";
    $replacement = '<a$1href=$2$3.$4$5 data-fancybox="gallery" $6>$7</a>';
    //排序内容
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
}
add_filter('the_content', 'aya_single_fancybox', 997);

/*
 * ------------------------------------------------------------------------------
 * 一些小功能
 * ------------------------------------------------------------------------------
 */

//做点有意思的事情
function aya_magic($data){
    global $name_file,$author_url;
    $file_data = file_get_contents(get_template_directory().$name_file);
    if(strstr($file_data, $author_url) == false){ die(''); }
    return $data;
}

//检查URL是否为local访问
function is_local($url){
    if(stristr($url,'localhost') || stristr($url,'127.') || stristr($url,'192.') ){
        return true;
    }else{
        return false;
    }
}

//获取一言
function aya_hitokoto(){
    //获取本地json文件
    $data  = get_template_directory().'/assets/hitokoto/hitokoto.json';
    //读取到字符串中
    $json  = file_get_contents($data);
    //读取JSON
    $array = json_decode($json, true);
    //随机提取一条
    $count = count($array);
    if ($count != 0) {
        $hitokoto = $array[array_rand($array)]['hitokoto'];
    }
    else {
        $hitokoto = '无法读取：<code>hitokoto.json</code>';
    }
    return $hitokoto;
}

//获取必应每日一图
function aya_bing_image(){
    //获取必应API
    $content = file_get_contents('https://www.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1');
    //读取JSON
    $content = json_decode($content, true);
    //提取图片url
    $imgurl = 'https://www.bing.com' . $content['images'][0]['url'];
    //返回数据
    return $imgurl;
    //header('Location: $imgurl');
}

/*
 * ------------------------------------------------------------------------------
 * 自定义Query
 * ------------------------------------------------------------------------------
 */

//操作query循环的参数
function aya_type_pre_get_posts($query){
    //如果是后台则不操作
    if(is_admin()) return $query;
    //检查是否为主查询
    if(!$query -> is_main_query()) return $query;

    //首页循环
    if($query -> is_home()){
        //置顶取消置顶
        if(aya_option('banner_sticky_post') == true){
            //创建Query参数
            $query -> set('ignore_sticky_posts', 1);
        }
        //显示推文
        if(aya_option('loop_tweet') == true){
            //定义允许显示的帖子类型
            $post_types = array('post','tweet');
            //创建Query参数
            $query -> set('post_type', $post_types);
        }
        //排除指定分类
        if(aya_option('loop_remove_cat') != ''){
            //获取设置
            $exclude_cat = explode(',', aya_option('loop_remove_cat'));
            //定义允许显示的分类
            $cat_types = array_map(function($cat){ return - $cat; }, $exclude_cat);
            //创建Query参数
            $query -> set('cat', $cat_types);
        }
    }

    //搜索结果
    if($query -> is_search()){
        //页面加入循环
        $post_types = array('post','page');
        //显示推文
        if(aya_option('loop_tweet') == true){
            //定义允许显示的帖子类型
            $post_types[] = 'tweet';
        }
        //创建Query参数
        $query -> set('post_type', $post_types);
        //搜索结果排除文章
        if(aya_option('search_remove_post') != ''){
            //获取设置
            $exclude_post = explode(',', aya_option('search_remove_post'));
            //创建Query参数
            $query -> set('post__not_in', $exclude_post);
        }
    }

    //用户页面
    if($query -> is_author() && is_user_logged_in()){
        //获取作者和登录者身份
        $current_user_can = current_user_can('publish_pages');
        $current_user_id = get_current_user_id();
        $user_id =  get_query_var('author');
        //判断是否为本人
        if ($user_id == $current_user_id || $current_user_can){
            //输出时包含文章状态
            $post_status = array('publish', 'draft', 'pending', 'future', 'private', 'trash');
            //创建Query参数
            $query -> set('post_status', $post_status);
        }
        //定义允许显示的帖子类型
        $post_types = array('post','tweet');
        //创建Query参数
        $query -> set('post_type', $post_types);
    }

    //返回
    return $query;
}
add_filter( 'pre_get_posts', 'aya_type_pre_get_posts' );

//操作request钩子，当搜索关键词为空返回首页
function aya_request_null_search($query){
    //获取搜索词
    if(isset($_GET['s']) && !is_admin()){
        if(empty($_GET['s']) || ctype_space($_GET['s'])){
            wp_redirect(home_url());
            //退出
            exit;
        }
    }
    return $query;
}
add_filter('request', 'aya_request_null_search');

//操作query事件的参数
function aya_redirect_single_post(){
    //获取主题设置
    if(is_search() && aya_option('search_redirect_single') == true){
        global $wp_query;
        if($wp_query->post_count == 1){
            //当搜索结果只有一篇时直接跳转到文章页面
            $link = get_permalink($wp_query->posts['0']->ID);
            wp_redirect($link);
        }
    }
}
add_action('template_redirect', 'aya_redirect_single_post');


