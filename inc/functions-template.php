<?php
if (!defined('ABSPATH')){
    exit;
}

/*
 * ------------------------------------------------------------------------------
 * HTML加载模板
 * 
 * 主题钩子载入顺序：
 * do_action('aya_head');
 * do_action('aya_header_canvas_box');
 * do_action('aya_header');
 * do_action('aya_index_banner');
 * do_action('aya_index_after');
 * do_action('aya_single');
 * do_action('aya_single_after');
 * do_action('aya_footer');
 * ------------------------------------------------------------------------------
 */

//加载head
function head_add_html(){
    //输出
    echo aya_favicon_ico();
    echo aya_site_seo();
    echo aya_css_custom();
    //输出额外代码
    echo aya_option('header_js');
}
add_action('aya_head', 'head_add_html');

//配置可选CSS样式
function aya_css_custom(){
    //组装自定义样式表
    $befor_css = '<style type="text/css">'."\n";
    $in_css = '';
    $after_css = "\n".'</style>'."\n";
    //检查主题设置
    if(aya_option('custom_mod') == true){
        return $befor_css.aya_option('more_css').$after_css;
    }else{
        $in_css .= aya_option('more_css');
    }
    if(!is_admin()){
        //全局灰色特效
        if(aya_option('gray_mod') == true){
            $in_css .= 'html { filter: grayscale(100%); filter: progid:DXImageTransform.Microsoft.BasicImage(grayscale=1); -webkit-filter: grayscale(100%); -moz-filter: grayscale(100%); -ms-filter: grayscale(100%); -o-filter: grayscale(100%); }';
            $in_css .= "\n";
        }
        //检查背景图设置
        if(aya_option('background_image') != '' && aya_option('background_bing') == false){
            $background_image = aya_option('background_image');
            $background_center = (aya_option('background_center') == true) ? '--aya-bg-position: center center; --aya-bg-repeat: no-repeat; --aya-bg-size: cover; ' : '';
            $in_css .= ':root { --aya-bg-image: url('.$background_image.'); '.$background_center.'}';
            $in_css .= "\n";
        }
        //必应每日一图
        if(aya_option('background_bing') == true){
            $background_image = aya_bing_image();
            $in_css .= ':root { --aya-bg-image: url('.$background_image.'); --aya-bg-position: center center; --aya-bg-repeat: no-repeat; --aya-bg-size: cover; }';
            $in_css .= "\n";
        }
    }
    //额外样式设置
    $in_css .= ':root {
    --aya-bg-fixed: fixed;
    --aya-body-color: '.aya_option('color_body').';
    --aya-body-bg: '.aya_option('background_color').';
    --aya-link-color: '.aya_option('color_body').';
    --aya-link-hover-color: '.aya_option('color_primary').';
    --aya-border-randius: 0.375rem;
    --aya-border-color: #f1f2f3;
    --aya-tip-color: '.aya_option('color_tip').';
    --aya-normal-bg: #fff;
    --aya-normal-color: '.aya_option('color_primary').';
    --aya-normal-hover: '.aya_option('color_secondary').';
    --aya-header-bg: #fff;
    --aya-footer-bg: '.aya_option('color_footer').';
    --aya-btn-bg: '.aya_option('color_primary').';
    --aya-btn-active-bg: '.aya_option('color_primary').';
    --aya-btn-hover: #fff;
    --aya-btn-active: #fff;
    --aya-less-text-color: '.aya_option('color_less').';
    --aya-text-bg: #f5f5f5;
    --aya-card-bg: #fff;
    --aya-card-box-shadow: 5px 5px 10px 5px #0000000f;
}';

    return $befor_css.$in_css.$after_css;
}

//操作class
function aya_body_class($classes){
    
    $clearfix = 'clearfix';
    $bg_mode = (aya_option('background_after') == false) ? 'bg-shade' : '';
    $dark_mode = (aya_option('dark_mode') == true) ? 'dark' : '';
    return array_merge($classes, array($clearfix, $bg_mode, $dark_mode));
}
add_filter('body_class','aya_body_class');

//配置favicon.ico
function aya_favicon_ico(){
    $favicon = aya_option('favicon');

    $html = '';
    $html .= '<link rel="icon" type="image/png" href="'.$favicon.'" />'."\n";
    $html .= '<link rel="apple-touch-icon" href="'.$favicon.'" />'."\n";
    $html .= '<meta name="msapplication-TileColor" content="#ffffff">'."\n";
    $html .= '<meta name="msapplication-TileImage" content="/favicon.ico">'."\n";
    $html .= '<meta name="theme-color" content="#ffffff">'."\n";
    return $html;
}

//配置keywords和description
function aya_site_seo(){
    $html = '';
    $html .= '<title>'.aya_site_seo_title().'</title>'."\n";
    $html .= '<meta name="keywords" content="'.aya_site_seo_keywords().'" />'."\n";
    $html .= '<meta name="description" content="'.aya_site_seo_description().'" />'."\n";

    return $html;
}
/*
 * ------------------------------------------------------------------------------
 * 导航栏
 * ------------------------------------------------------------------------------
 */

//判断导航栏置顶
function navbar_sticky_top(){
    if (aya_option('sticky_top') == true) echo 'sticky-top';
}

//导航栏LOGO
function header_logo(){
    $site_name = aya_site_name();
    $site_url = get_bloginfo('url');

    //文字显示
    if(aya_option('header_logo_text') == true){
        $name_text = ' | '.$site_name;
    }else{
        $name_text = '';
    }
    echo '<a class="logo" href="'.$site_url.'" title="'.$site_name.'"><img loading="eager" src="'.aya_option('header_logo').'">'.$name_text.'</a>';
}

//生成导航栏用户按钮
function header_switch_button(){
    $site_url = get_bloginfo('url');

    $html = '';
    $html .= '<div class="switch d-flex mt-3 mt-lg-0">';
    $html .= '<button class="btn btn-hollow mx-1" onclick="switchDarkMode()"><i class="bi bi-lightbulb-fill"></i></button>';
    if(is_user_logged_in() && current_user_can('edit_posts')){
        $html .= '<a href="'.$site_url.'/wp-admin/" class="btn btn-normal mx-1" role="button" title="管理后台"><i class="bi bi-sliders"></i></a>';
    }

    //登录注册按钮
    if(aya_option('header_login_button') == true){
        if(is_user_logged_in()){
            $html .= '<a href="'.$site_url.'/wp-admin/profile.php" class="btn btn-normal mx-1" role="button" title="个人资料"><i class="bi bi-person-lines-fill"></i></a>';
            $html .= '<a href="'.$site_url.'/wp-login.php?action=logout" class="btn btn-normal mx-1" role="button" title="注销登录"><i class="bi bi-person-x-fill"></i></a>';
        }else{
            $html .= '<a href="'.$site_url.'/wp-login.php" class="btn btn-normal mx-1" role="button" title="登录"><i class="bi bi-person-fill"></i></a>';
            if(get_option ('users_can_register')){
                $html .= '<a href="'.$site_url.'/wp-login.php?action=register" class="btn btn-normal mx-1" role="button" title="新用户注册"><i class="bi bi-person-plus-fill"></i></a>';
            }
        }
    }
    $html .= '</div>';
    
    echo $html;
}
add_action('aya_header_canvas_box', 'header_switch_button');

//生成导航栏搜索框
function header_search_box(){
    $type = (aya_option('header_search_box') == true) ? '' : 'pc-hide';

    $html = '';
    $html .= '<div class="search d-flex mt-3 mt-lg-0 '.$type.'">';
    $html .= '<form role="search" method="get" id="search-form" class="search-form" action="/" type="text">';
    $html .= '<input type="text" class="search-input" value="" name="s" placeholder="搜索内容">';
    $html .= '<button type="submit" class="search-submit" id="search-submit"><i class="bi bi-search"></i></button>';
    $html .= '</form></div>';
    
    echo $html;
}
add_action('aya_header_canvas_box', 'header_search_box');

/*
 * ------------------------------------------------------------------------------
 * 页脚
 * ------------------------------------------------------------------------------
 */

//页脚版权信息
function footer_copyright(){
    $html = '';
    $html .= '<p class="copyright">';
    $html .= aya_option('cop_text');
    if(aya_option('load_time') == true){
        $load_time = sprintf('%d 次查询 用时 %.3f 秒, 耗费了 %.2fMB 内存。', get_num_queries() , timer_stop(0, 3) , memory_get_peak_usage() / 1024 / 1024);
        $html .= '</br><span class="load">'.$load_time.'</span>';
    }
    $html .= '</p>';
    echo $html;
}

//页脚备案号
function footer_beian(){
    if(aya_option('beian') == '' || aya_option('show_beian') == false){
        $html = '';
    }else{
        $html = '<a class="beian" href="https://beian.miit.gov.cn/" rel="external nofollow" target="_blank" title="备案信息"><i class="bi bi-shield-check me-1"></i>'.aya_option('beian').'</a>';
    }
    echo $html;
}

//友情链接
function footer_friendly_section(){
    if(aya_option('friend_link') == false) return;
    get_template_part('template-parts/section', 'friendly-link');
}
add_action('aya_footer', 'footer_friendly_section');

//页脚悬浮按钮
function footer_scroll_button(){
    $html = '';
    $html .= '<div class="scroll-button scroll-hide">';

    if(is_single() || is_page()){
        //$html .= '<button class="button" onclick="scrollToTop()" data-bs-toggle="tooltip" data-bs-placement="left" title="阅读模式"><i class="bi bi-book"></i></button>';
        //$html .= '<button class="button" onclick="scrollToTop()" data-bs-toggle="tooltip" data-bs-placement="left" title="二维码"><i class="bi bi-qr-code-scan"></i></button>';
    }
    //$html .= '<button class="button" onclick="switchDarkMode()" data-bs-toggle="tooltip" data-bs-placement="left" title="开灯 / 关灯"><i class="bi bi-lightbulb-fill"></i></button>';
    $html .= '<button class="button" onclick="scrollToTop()" data-bs-toggle="tooltip" data-bs-placement="left" title="返回顶部"><i class="bi bi-chevron-up"></i></button>';
    $html .= '</div>';
    echo $html;
}
add_action('aya_footer', 'footer_scroll_button');

//加载页脚
function footer_add_html(){
    //输出额外代码
    echo aya_option('footer_html');
}
add_action('aya_footer', 'footer_add_html');

/*
 * ------------------------------------------------------------------------------
 * 首页组件
 * ------------------------------------------------------------------------------
 */

//轮播：生成组件
function index_banner_section(){
    //获取主题设置
    $mode_option = aya_option('banner_mode');
    if($mode_option == '0') return;
    //检查列表是否为空
    $post_array = banner_add_posts();
    if($post_array == false) return;
    //检查移动端和设置
    if(!wp_is_mobile() && $mode_option == '1' && count($post_array) > 4){
        get_template_part('template-parts/section', 'banner-cms');
    }else{
        get_template_part('template-parts/section', 'banner-default');
    }
}
add_action('aya_index_banner', 'index_banner_section');

//轮播：生成指示器
function banner_indicators($array = array()){
    //生成Bootstrap格式的指示器
    $i = 0;
    $html = '';
    //循环
    foreach($array as $key => $value){
        $active = ($i == 0) ? 'active' : '';
        $html .= '<button type="button" data-bs-target="#carousel-banner" data-bs-slide-to="'.$key.'"aria-label="Slide '.$key.'" class="'.$active.'"></button>';
        $i++;
    }
    echo $html;
}

//轮播：生成内容
function banner_inner($array = array()){
    //生成Bootstrap格式的DIV
    $i = 0;
    $html = '';
    foreach($array as $key => $value){
        $active = ($i == 0) ? 'active' : '';
        $html .= '<div class="banner-item carousel-item '.$active.'">';
        $html .= '<a class="stretched-link" href="'.$value['url'].'">';
        $html .= '<div class="banner-thumb"><img loading="lazy" src="'.$value['img'].'"></div>';
        $html .= '<div class="banner-title"><h5>'.$value['title'].'</h5><p>'.$value['des'].'</p></div>';
        if(!empty($value['tag'])){
            $html .= '<em>'.$value['tag'].'</em>';
        }
        $html .= '</a></div>';
        $i++;
    }
    echo $html;
}

//轮播：生成内容
function banner_outer($array = array()){
    //生成DIV
    $html = '';
    foreach($array as $key => $value){
        $html .= '<div class="banner-card card">';
        $html .= '<a class=" stretched-link" href="'.$value['url'].'">';
        $html .= '<div class="banner-thumb"><img loading="lazy" src="'.$value['img'].'"></div>';
        $html .= '<div class="banner-title"><h5>'.$value['title'].'</h5><p>'.$value['des'].'</p></div>';
        if(!empty($value['tag'])){
            $html .= '<em>'.$value['tag'].'</em>';
        }
        $html .= '</a></div>';
    }
    echo $html;
}

//轮播：设置轮播列表数量
function banner_add_posts(){
    //获取轮播列表
    $banner_array = banner_add_loop_post();
    //获取置顶列表
    if(aya_option('banner_sticky_post') == true){
        $sticky_array = banner_add_sticky_post();
        //合并数组
        if(!empty($sticky_array) && !empty($banner_array)){
            $banner_array = array_merge($banner_array, $sticky_array);
        }elseif(empty($banner_array)){
            $banner_array = $sticky_array;
        }
    }
    //检查数量
    $banner_num = intval(aya_option('banner_loop_number'));
    if($banner_num != 0){
        $banner_array = array_slice($banner_array, 0, $banner_num);
    }
    return $banner_array;
}

//轮播：生成自定义的文章列表
function banner_cms_posts($type = 0, $num = 3){
    //获取列表
    $post_array = banner_add_posts();
    $count = count($post_array);
    //拆分数组
    if($type == 0){
        //计算
        $the_count = $count - $num;
        return array_slice($post_array, 0, $the_count);
    }else{
        //计算
        $type = $count - $type;
        return array_slice($post_array, $type, $num);
    }
}

//轮播：处理轮播列表
function banner_add_loop_post(){
    //获取主题设置
    $banner_loop = aya_option('banner_loop');
    //为空直接返回
    if(!is_array($banner_loop) || empty($banner_loop)) return false;
    //处理列表数据
    foreach($banner_loop as $key => $value){
        //处理数组
        if(is_numeric($value['url'])){
            //检查文章ID
            if(get_permalink($value['url']) == false) continue;
            $post_id = $value['url'];
            $value['url'] = get_permalink($post_id);
            $value['title'] = empty($value['title']) ? get_post_title($post_id) : $value['title'];
            $value['tag'] = empty($value['tag']) ? '' : $value['tag'];
            $value['img'] = empty($value['img']) ? get_post_thumb($post_id, 700, 400) : $value['img'];
        }
        //处理数组
        $value['title'] = empty($value['title']) ? $value['url'] : $value['title'];
        $value['tag'] = empty($value['tag']) ? '' : $value['tag'];
        $value['img'] = empty($value['img']) ? get_bfi_thumb(aya_option('nopic'), 700, 400) : get_bfi_thumb($value['img'], 700, 400);
        //返回
        $banner_array[$key] = array(
            'url' => $value['url'],
            'title' => $value['title'],
            'des' => $value['des'],
            'tag' => $value['tag'],
            'img' => $value['img'],
        );
    }
    return $banner_array;
}

//轮播：置顶文章加入轮播
function banner_add_sticky_post(){
    //获取置顶文章列表
    $sticky_option = get_option('sticky_posts');
    //为空直接返回
    if(!is_array($sticky_option) || empty($sticky_option)) return false;
    //创建查询
    $sticky_array = array();
    $len_post = count($sticky_option);
    $args = array(
        'post__in' => $sticky_option,
        'post_type' => 'post',
        'ignore_sticky_posts' => 1,
    );
    $the_query = new WP_Query($args);
    //计数
    $i = 0;
    //获取文章信息
    if($the_query->have_posts()){
        while ($the_query->have_posts()) : $the_query->the_post();
            if($i > $len_post) break;
            //生成新的数组
            $sticky_array[$i] = array(
                'url' => get_permalink(),
                'title' => get_post_title(),
                'des' => '',
                'tag' => '置顶推荐',
                'img' => get_post_thumb(0, 700, 400),
            );
            $i++;
        endwhile;
        wp_reset_query();//重置Query查询
    }
    return $sticky_array;
}


/*
 * ------------------------------------------------------------------------------
 * 分类页面
 * ------------------------------------------------------------------------------
 */

//生成搜索框
function the_serach_box(){
    $html = '';
    $html .= '<form role="search" method="get" id="search-form" class="search-form" action="/" type="text">';
    $html .= '<label class="search-text" for="s"><i class="bi bi-search"></i> 搜索一下</label>';
    $html .= '<input type="text" class="search-input" value="" name="s" id="s" placeholder="">';
    $html .= '<button type="submit" class="search-submit" id="search-submit">搜索</button>';
    $html .= '</form>';
    
    echo $html;

}

//生成分类描述卡片
function the_archive_des(){
    $html = '';
    $html .= '<div class="cat-info-card card p-2 mb-2 col">';
    $html .= '<h1>'.aya_head_title().'</h1>';
    if(term_description()){
        //去除多余P标签
        $html .= '<p class="des">'.str_replace(array("<p>", "", "</p>", "\r", "\n"), "", term_description()).'</p>';
    }
    $html .= '</div>';
    echo $html;
}

//生成作者卡片
function the_author_info(){
    $user_id = get_the_author_meta('ID');
    $html = '';
    $html .= '<div class="author-info-card card p-2 mb-2 col"><div class="d-flex flex-wrap">';
    $html .= '<div class="author-avatar">'.get_avatar($user_id, '60').'</div>';
    $html .= '<div class="author-meta"><h1>'.get_the_author_meta('display_name').'</h1>';
    $html .= '<p calss="des">'.get_the_author_meta('description').'</p></div>';
    $html .= '</div></div>';
    echo $html;
}

/*
 * ------------------------------------------------------------------------------
 * 文章内页
 * ------------------------------------------------------------------------------
 */

//文章作者信息
function the_single_author(){
    global $post;
    $user_id = $post->post_author;
    $html = '';
    $html .= '<a href="'.get_author_posts_url($user_id).'" title="查看作者">'.get_avatar($user_id, '32').'</a>';
    $html .= '<div class="author-name"><a href="'.get_author_posts_url($user_id).'" title="查看作者">'.get_the_author_meta('display_name', $user_id).'</a><p class="des">'.get_the_author_meta('description', $user_id).'</p></div>';
    echo $html;
}

//文章数据信息
function the_single_meta(){
    $html = '';
    $html .= '<span class="cat pe-2"><i class="bi bi-list"></i> '.get_the_category_list(', ').'</span>';
    $html .= '<span class="date pe-2"><i class="bi bi-clock"></i> 发布于 '.get_post_date().get_post_last_update().'</span>';
    $html .= '<span class="views pe-2"><i class="bi bi-eye"></i> '.get_post_views().' 次浏览</span>';
    $html .= '<span class="comments pe-2"><i class="bi bi-chat-dots"></i> '.get_comments_number().' 条评论</span>';
    echo $html;
}

//推文数据信息
function the_tweet_meta(){
    $html = '';
    $html .= '<span class="date pe-2"><i class="bi bi-clock"></i> 发布于 '.get_post_date().get_post_last_update().'</span>';
    $html .= '<span class="comments pe-2"><i class="bi bi-chat-dots"></i> '.get_comments_number().' 条评论</span>';
    echo $html;
}

//生成文章末尾自定义提示
function the_single_declare(){
    if(aya_option('single_declare') == false) return;

    $html = '';
    $html .= '<div class="border-top pb-2"></div>';
    $html .= '<p class="tips">';
    //文章信息过时提示
    if(aya_option('single_out') == true && get_post_is_outmoded() == true){
        $html .= '这篇文章最后更新于 <span>'.get_post_date().'</span> ，其中的信息可能已经有所发展或是发生改变。<br />';
    }
    $html .= nl2br(aya_option('single_declare_text'));
    $html .= '</p>';
    echo $html;
}

//生成文章点赞按钮
function single_check_zan(){
    if(aya_option('single_zan') == false) return;

    global $post;
    //判断是否已点赞
    if(isset($_COOKIE['specs_zan_'.$post->ID])){
        $zan = 'done';
    }
    $html = '';
    $html .= '';
    //$html .= '<a href="javascript:;" data-action="ding" data-id="'.$post->ID.'" class="specsZan '.$zan.'"><i class="bi bi-hand-thumbs-up-fill"></i>'.get_post_specs_zan().'</a>';
    $html .= '';
    echo $html;
}

//生成上一篇&下一篇文章
function single_prev_next_post(){
    //获取主题设置
    if(aya_option('single_next') == false) return;

    $prev_post = get_previous_post();
    $next_post = get_next_post();
    //如果文章为空
    if(empty($prev_post) || empty($next_post)){
        $width = 'style="width: 100%;"';
    }else{
        $width = 'style="width: 50%;"';
    }
    $html = '';
    
    $html .= '<div class="next-prev-post">';
    if(!empty($prev_post)){
        $html .= '<div class="pd-in-card mb-2" '.$width.'><div class="card" style="background: url('.get_post_thumb($prev_post->ID).');"><div class="prev-post">';
        $html .= '<p><i class="bi bi-chevron-double-left"></i> 上一篇</p>';
        $html .= '<a class="stretched-link" href="'.get_permalink($prev_post->ID).'" title="'.get_post_title($prev_post->ID).'" rel="next">'.get_post_title($prev_post->ID).'</a>';
        $html .= '</div></div></div>';
    }
    if(!empty($next_post)){
        $html .= '<div class="pd-in-card mb-2" '.$width.'><div class="card" style="background: url('.get_post_thumb($next_post->ID).');"><div class="next-post">';
        $html .= '<p> 下一篇 <i class="bi bi-chevron-double-right"></i></p>';
        $html .= '<a class="stretched-link" href="'.get_permalink($next_post->ID).'" title="'.get_post_title($next_post->ID).'" rel="next">'.get_post_title($next_post->ID).'</a>';
        $html .= '</div></div></div>';
    }
    $html .= '</div>';
    echo $html;
}
add_action('aya_single_after', 'single_prev_next_post');

//生成相关文章
function single_related_post(){
    //获取主题设置
    if(aya_option('single_related') == false) return;

    $cat = get_query_var('cat'); 
    $html = '';
    
    $html .= '<div class="related-post-list card p-3 mb-2"><h3 class="related-post-title"><i class="bi bi-router"></i> 相关文章</h3>';
    //自定义Query查询
    $args = array(
        'category__in' => $cat,
        'ignore_sticky_posts' => true,
        'posts_per_page' => '5',
        'paged'	=> '1',
        'post_type' => 'post',
        'orderby' => 'rand',
    );
    //创建查询
    $the_query = new WP_Query($args);
    //生成文章列表
    if($the_query->have_posts()){
        while ($the_query->have_posts()) : $the_query->the_post();
        $html .= '<div class="post-link"><img loading="lazy" src="'.get_post_thumb().'" >';
        $html .= '<div class="loop"><h5><a class="stretched-link" href="'.get_permalink().'">'.get_post_title().'</a></h5>';
        $html .= '<p><span class="pe-2"><i class="bi bi-clock"></i> '.get_the_date().'</span><span class="pe-2"><i class="bi bi-eye"></i> '.get_post_views().'</span><span class="pe-2"><i class="bi bi-chat-dots"></i> '.get_comments_number().'</span></p>';
        $html .= '</div></div>';
        endwhile;
        wp_reset_query();//重置Query查询
    }else{
        $html .= '<div class="post-link">没有更多文章</div>';
    }
    $html .= '</div>';
    echo $html;
}
add_action('aya_single_after', 'single_related_post');