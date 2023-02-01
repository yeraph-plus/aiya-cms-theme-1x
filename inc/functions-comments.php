<?php
/*
 * ------------------------------------------------------------------------------
 * 评论功能函数
 * ------------------------------------------------------------------------------
 */

//ajax评论
if(!function_exists('fa_ajax_comment_scripts')) :

    function fa_ajax_comment_scripts(){
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }

        if ( is_single() || is_page() ){

        }

        wp_localize_script( 'ajax-comment', 'ajaxcomment', array(
            'ajax_url'   => admin_url('admin-ajax.php'),
            'order' => get_option('comment_order'),
            'formpostion' => 'bottom', //默认为bottom，如果你的表单在顶部则设置为top。
        ) );
    }

endif;

if(!function_exists('fa_ajax_comment_err')) :

    function fa_ajax_comment_err($a) {
        header('HTTP/1.0 500 Internal Server Error');
        header('Content-Type: text/plain;charset=UTF-8');
        echo $a;
        exit;
    }

endif;

if(!function_exists('fa_ajax_comment_callback')) :

    function fa_ajax_comment_callback(){
        $comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
        if ( is_wp_error( $comment ) ) {
            $data = $comment->get_error_data();
            if ( ! empty( $data ) ) {
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
                        <?php echo get_avatar( $comment, $size = '40')?>
                        <b class="fn">
                            <?php echo get_comment_author_link();?>
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

add_action( 'wp_enqueue_scripts', 'fa_ajax_comment_scripts' );
add_action('wp_ajax_nopriv_ajax_comment', 'fa_ajax_comment_callback');
add_action('wp_ajax_ajax_comment', 'fa_ajax_comment_callback');

//赞
function specs_zan(){
    global $wpdb,$post;
    $id = $_POST["um_id"];
    $action = $_POST["um_action"];
    if ( $action == 'ding'){
        $specs_raters = get_post_meta($id,'specs_zan',true);
        $expire = time() + 99999999;
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; // make cookies work with localhost
        setcookie('specs_zan_'.$id,$id,$expire,'/',$domain,false);
        if (!$specs_raters || !is_numeric($specs_raters)) {
            update_post_meta($id, 'specs_zan', 1);
        }
        else {
            update_post_meta($id, 'specs_zan', ($specs_raters + 1));
        }
        echo get_post_meta($id,'specs_zan',true);
    }
    die;
}
add_action('wp_ajax_nopriv_specs_zan', 'specs_zan');
add_action('wp_ajax_specs_zan', 'specs_zan');

// 评论添加@
function comment_add_at( $comment_text, $comment = '') {
  if( $comment->comment_parent > 0) {
    $comment_text = '@<a href="#comment-' . $comment->comment_parent . '">'.get_comment_author( $comment->comment_parent ) . '</a> ' . $comment_text;
  }

  return $comment_text;
}
add_filter( 'comment_text' , 'comment_add_at', 20, 2);

//直接去掉函数 comment_class() 和 body_class() 中输出的 "comment-author-" 和 "author-"避免 WordPress 登录用户名被暴露
function comment_body_class($content){
    $pattern = "/(.*?)([^>]*)author-([^>]*)(.*?)/i";
    $replacement = '$1$4';
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
}
add_filter('comment_class', 'comment_body_class');
add_filter('body_class', 'comment_body_class');

//过滤外文评论
function refused_spam_comments($commentdata){
    if (is_user_logged_in()) {
        return $commentdata;
    }
    $pattern = '/[一-龥]/u';
    $jpattern = '/[ぁ-ん]+|[ァ-ヴ]+/u';
    if (!preg_match($pattern, $commentdata['comment_content'])) {
        git_err('You should type some Chinese word!');
    }
    if (preg_match($jpattern, $commentdata['comment_content'])) {
        git_err('You should type some Chinese word!');
    }
    return $commentdata;
}
add_filter('preprocess_comment', 'refused_spam_comments');

//屏蔽关键词，email，url，ip
function keyword_spam_comments($commentdata){
    if (is_user_logged_in()) {
        return $commentdata;
    }
    if (wp_blacklist_check($commentdata['comment_author'], $commentdata['comment_author_email'], $commentdata['comment_author_url'], $commentdata['comment_content'], $commentdata['comment_author_IP'], $commentdata['comment_agent'])) {
        header("Content-type: text/html; charset=utf-8");
        git_err('不好意思，您的评论违反本站评论规则');
    } else {
        return $commentdata;
    }
}
add_filter('preprocess_comment', 'keyword_spam_comments');

//屏蔽昵称，评论内容带链接的评论
function url_spam_comments($commentdata){
    if (is_user_logged_in()) {
        return $commentdata;
    }
    $links = '/http:\\/\\/|https:\\/\\/|www\\./u';
    if (preg_match($links, $commentdata['comment_author']) || preg_match($links, $commentdata['comment_content'])) {
        git_err('在昵称和评论里面是不准发链接滴.');
    }
    return $commentdata;
}
add_filter('preprocess_comment', 'Googlolink');

//评论地址更换
function git_comment_author( $query_vars ) {
	if ( array_key_exists( 'author_name', $query_vars ) ) {
		global $wpdb;
		$author_id = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key='first_name' AND meta_value = %s", $query_vars['author_name'] ) );
		if ( $author_id ) {
			$query_vars['author'] = $author_id;
			unset( $query_vars['author_name'] );
		}
	}
	return $query_vars;
}
add_filter( 'request', 'git_comment_author' );

//评论作者链接新窗口打开
function specs_comment_author_link(){
    $url    = get_comment_author_url();
    $author = get_comment_author();
    if ( empty( $url ) || 'http://' == $url )
    return $author;
    else
    return "<a target='_blank' href='$url' rel='external nofollow' class='url'>$author</a>";
}
add_filter('get_comment_author_link', 'specs_comment_author_link');