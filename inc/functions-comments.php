<?php
if (!defined('ABSPATH')){
    exit;
}

/*
 * ------------------------------------------------------------------------------
 * 评论组件及相关函数
 * ------------------------------------------------------------------------------
 */

//ajax评论
if (!function_exists('fa_ajax_comment_scripts')) :
    function fa_ajax_comment_scripts(){
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }

        if (is_single() || is_page()) {
            
        }
        wp_localize_script('ajax-comment', 'ajaxcomment', array(
            'ajax_url'   => admin_url('admin-ajax.php'),
            'order' => get_option('comment_order'),
            'formpostion' => 'bottom', //默认为bottom，如果你的表单在顶部则设置为top。
        ));
    }
endif;

if (!function_exists('fa_ajax_comment_err')) :
    function fa_ajax_comment_err($a){
        header('HTTP/1.0 500 Internal Server Error');
        header('Content-Type: text/plain;charset=UTF-8');
        echo $a;
        exit;
    }
endif;

if (!function_exists('fa_ajax_comment_callback')) :
    function fa_ajax_comment_callback(){
        $comment = wp_handle_comment_submission(wp_unslash($_POST));
        if (is_wp_error($comment)) {
            $data = $comment->get_error_data();
            if (!empty($data)) {
                fa_ajax_comment_err($comment->get_error_message());
            } else {
                exit;
            }
        }
        $user = wp_get_current_user();
        do_action('set_comment_cookies', $comment, $user);
        $GLOBALS['comment'] = $comment; //根据你的评论结构自行修改，如使用默认主题则无需修改
?>
        <li <?php comment_class(); ?>>
            <article class="comment-body">
                <footer class="comment-meta">
                    <div class="comment-author vcard">
                        <?php echo get_avatar($comment, $size = '40') ?>
                        <b class="fn">
                            <?php echo get_comment_author_link(); ?>
                        </b>
                    </div>
                    <div class="comment-metadata">
                        <?php echo get_comment_date(); ?>
                    </div>
                </footer>
                <div class="comment-content">
                    <?php comment_text(); ?>
                </div>
            </article>
        </li>
<?php die();
    }
endif;

add_action('wp_enqueue_scripts', 'fa_ajax_comment_scripts');
add_action('wp_ajax_nopriv_ajax_comment', 'fa_ajax_comment_callback');
add_action('wp_ajax_ajax_comment', 'fa_ajax_comment_callback');

// 评论添加@
function comment_add_at($comment_text, $comment = ''){
    if ($comment->comment_parent > 0) {
        $comment_text = '<span class="aaa">回复@' . get_comment_author($comment->comment_parent) . '</span> ' . $comment_text;
    }

    return $comment_text;
}
add_filter('comment_text', 'comment_add_at', 20, 2);

//屏蔽纯英文评论和纯日文
function refused_english_comments($incoming_comment) {
    $pattern = '/[一-龥]/u';
    // 禁止全英文评论
    if(!preg_match($pattern, $incoming_comment['comment_content'])) {
        fa_ajax_comment_err( "您的评论中必须包含汉字!" );
    }
    $pattern = '/[あ-んア-ン]/u';
    // 禁止日文评论
    if(preg_match($pattern, $incoming_comment['comment_content'])) {
        fa_ajax_comment_err( "评论禁止包含日文!" );
    }
    return( $incoming_comment );
}
add_filter('preprocess_comment', 'refused_english_comments');

//新窗口打开评论者网站链接
function modifiy_comment_author_url($author_link){
    return str_replace("<a", "<a target='_blank'", $author_link);
}
add_filter('get_comment_author_link', 'modifiy_comment_author_url');
