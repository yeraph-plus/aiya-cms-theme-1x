<?php
if (!defined('ABSPATH')){
    exit;
}

/*
 * ------------------------------------------------------------------------------
 * HTML加载模板:
 * 
 * do_action('aya_index_custom');
 * ------------------------------------------------------------------------------
 */

/*
 * ------------------------------------------------------------------------------
 * 布局功能
 * ------------------------------------------------------------------------------
 */

/*
 * class="row-cols-sm-2 row-cols-md-4" data-masonry='{"percentPosition": true }'
 */

//输出列表
function loop_layout(){
    //获取模板设置
    if(aya_option('loop_mode') == '0'){
        $part = 'loop';
        $grid = '';
        $margin = ' mt-0';
    }else{
        $part = 'loop-card';
        $row = aya_option('loop_card');
        $grid = ' row row-cols-lg-'.$row.' row-cols-md-2 row-cols-2 g-2';
        $margin = ' mt-2';
    }
    //分页设置
    if (aya_option('paged_mode') == 0){
        $paged = '<div class="page-nav">'.aya_page_nav().'</div>';
    }else{
        $paged = '<div class="read-more">'.aya_page_ajax().'</div>';
    }

    $before_html = '<div class="post-list'.$grid.'">';
    $after_html = '</div><div class="post-page card'.$margin.'">'.$paged.'</div>';

    //开始输出列表
    echo $before_html;
    //LOOP
    if (have_posts()){
        while (have_posts()){
            the_post();
            get_template_part('template-parts/'.$part, loop_type());
        }
    }
    //如果没有文章
    else{
        get_template_part('template-parts/loop', 'none');
    }
    //结束输出列表
    echo $after_html;
}

//文章类型
function loop_type(){
    //返回文章
    if(get_post_type() == 'post'){
        //返回加载类型
        $format = get_post_format();
        switch($format){
            case 'gallery':
                $format = 'gallery';
                break;
            case 'image':
                $format = 'image';
                break;
            default:
                $format = 'default';
                break;
        }
        return $format;
    }
    //返回推文
    else{
        return get_post_type();
    }
}

//AJAX分页
function aya_page_ajax(){
    global $paged,$wp_query;
    $max_page = $wp_query->max_num_pages;
    //检查页数
    if($max_page > $paged){
        $html = '<a class="page-link" href="'.get_next_posts_page_link().'">加载更多</a>';
        return $html;
    }else{
        return ;
    }
}

/*
 * ------------------------------------------------------------------------------
 * LOOP组件功能
 * ------------------------------------------------------------------------------
 */

//生成文章预览图片
function the_post_thumb($post_id = 0, $w = 300, $h = 210){
    //默认输出300*210图片
    echo get_post_thumb($post_id, $w, $h);
}

//生成文章摘要
function the_post_preview($post_id = 0, $size = 225){
    //默认输出225字符
    echo get_post_preview($post_id, $size);
}

//生成文章标题
function the_post_title($post_id = 0){
    //获取标题
    $title = get_post_title($post_id);
    $status = '';
    $option = '';
    //获取文章状态
    if(get_post_status($post_id) != 'publish'){
        $status = ' - '.aya_test_post_status($post_id);
    }
    //检查置顶
    if(is_sticky($post_id)){
        $option = '<span class="badge badge-sticky"><i class="bi bi-pin-angle"></i> 置顶</span>';
    }
    //检查私密文章
    if(get_post_status($post_id) == 'private'){
        $option = '<span class="badge badge-private"><i class="bi bi-eye-slash"></i> 私密</span>';
    }
    //检查密码保护
    if(post_password_required($post_id)){
        $option = '<span class="badge badge-password"><i class="bi bi-lock"></i> 密码保护</span>';
    }
    //输出
    echo $option.$title.$status;
}

//生成图集文章的图片集
function the_post_gallery(){
    global $post;
    $post_id = $post->ID;

    $post_imgs = aya_get_first_img($post_id, 'all');
    $html = '';

    //生成循环
    if(is_array($post_imgs)){
        $max_num = 4;
        $i = 1;
        $html .= '<div class="row g-3">';
        foreach($post_imgs as $img){
            if($i <= $max_num){
                $html .= '<div class="col-6 col-lg-3"><a href="'.get_permalink().'"><img loading="auto" src="'.get_bfi_thumb($img, 300, 210).'"><span>阅读全文</span></a></div>';
            }
            if($i > $max_num){
                break;
            }
            $i++;
        }
        $html .= '</div>';
    }
    echo $html;
}

//生成推文作者
function home_tweet_author(){
    $html = '';
    
    $user_id = get_the_author_meta('ID');
    $html .= get_avatar($user_id, '32');
    $html .= '<div class="author"><a href="'.get_author_posts_url($user_id).'" title="查看作者">'.get_the_author().'</a><p class="date">发表于 '.get_post_date().'</p></div>';
    echo $html;
}

//生成推文正文内容
function home_tweet_concent(){
    $html = '';
    //如果有标题
    if(get_the_title() != ''){
        $the_title = '<a href="'.get_the_permalink().'" title="'.get_the_title().'">'.get_post_title().'</a>';
    }
    else{
        $the_title = '无标题';
    }
    $html .= '<p class="title">'.$the_title.'</p>';
    //输出正文内容
    $the_content = apply_filters('the_content', get_the_content());
    $html .= aya_clear_text(apply_shortcodes($the_content), 1);
    echo $html;
}

//生成推文文章的图片集
function home_tweet_fancybox_loop(){
    global $post;
    $post_id = $post->ID;

    //获取图片
    $post_imgs = aya_get_first_img($post_id, 'all');
    //检查数组
    if(!is_array($post_imgs) || count($post_imgs) == 0){
        return ;
    }else{
        $img_num = count($post_imgs);
    }
    //生成循环
    $html = '';
    if($img_num == 1){
        $html .= '<div class="fancy-box">';
        foreach($post_imgs as $img){
            $html .= '<a data-fancybox="pid-'.$post_id.'" href="'.$img.'"><img loading="auto" src="'.get_bfi_thumb($img, 300, 200).'"></a>';
        }
    }
    else{
        $max_num = 9;
        $i = 1;
        $html .= '<div class="fancy-box row row-cols-3 g-3">';
        foreach($post_imgs as $img){
            if($i < $max_num){
                $html .= '<div class="col"><a data-fancybox="pid-'.$post_id.'" href="'.$img.'"><img loading="auto" src="'.get_bfi_thumb($img, 200, 200).'"></a></div>';
            }
            if($i > $max_num){
                $html .= '<div class="col pic-hiden"><a data-fancybox="pid-'.$post_id.'" href="'.$img.'"><img loading="auto" src="'.get_bfi_thumb($img, 200, 200).'"></a></div>';
            }
            if($i == $max_num){
                $more_num = ($img_num - $max_num) < 0 ? '' : '<span> + '.($img_num - $max_num).'</span>';
                $html .= '<div class="col"><a data-fancybox="pid-'.$post_id.'" href="'.$img.'"><img loading="auto" src="'.get_bfi_thumb($img, 200, 200).'">'.$more_num.'</a></div>';
            }
            $i++;
        }
    }
    $html .= '</div>';
    
    echo $html;
}

//生成推文统计数据
function home_tweet_meta(){
    global $post;
    $html = '';
    $html .= '<span class="pe-2"><a href="'.get_the_permalink().'" title="阅读全文"><i class="bi bi-arrow-return-right"></i> 阅读全文</a></span>';
    $html .= '<span class="pe-2"><a href="'.get_the_permalink().'" title="查看评论"><i class="bi bi-chat-dots"></i> '.get_comments_number().'</a></span>';
    $html .= '<span class="pe-2"><a href="javascript:;" data-action="ding" data-id="'.$post->ID.'" class="specsZan"><i class="bi bi-heart"></i> '.get_post_specs_zan().'</a></span>';
    echo $html;
}

//生成文章统计数据
function the_post_meta(){
    $html = '';
    $html .= '<span class="author pe-2"><i class="bi bi-person"></i> '.get_the_author().'</span>';
    $html .= '<span class="date pe-2"><i class="bi bi-clock"></i> '.get_post_date().'</span>';
    $html .= '<span class="views pe-2"><i class="bi bi-eye"></i> '.get_post_views().'</span>';
    $html .= '<span class="comments pe-2"><i class="bi bi-chat-dots"></i> '.get_comments_number().'</span>';
    if (aya_option('single_zan') == true){
        $html .= '<span class="zan pe-2"><i class="bi bi-heart"></i> '.get_post_specs_zan().'</span>';
    }
    echo $html;
}

//生成文章卡片统计数据
function the_post_card_meta(){
    $html = '';
    //获取主题设置
    $html .= '<span class="date pe-2"><i class="bi bi-clock"></i> '.get_post_date().'</span>';
    $html .= '<span class="views pe-2"><i class="bi bi-eye"></i> '.get_post_views().'</span>';
    $html .= '<span class="comments pe-2"><i class="bi bi-chat-dots"></i> '.get_comments_number().'</span>';
    if (aya_option('single_zan') == true){
        $html .= '<span class="zan pe-2"><i class="bi bi-heart"></i> '.get_post_specs_zan().'</span>';
    }
    echo $html;
}