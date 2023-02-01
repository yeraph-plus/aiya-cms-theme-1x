<?php
/*
//WordPress 评论回复邮件通知
function fanly_comment_mail_notify($comment_id) {
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    $comment = get_comment($comment_id);
    $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
    $spam_confirmed = $comment->comment_approved;
    if (($parent_id != '') && ($spam_confirmed != 'spam')) {
        $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
        $to = trim(get_comment($parent_id)->comment_author_email);
        $subject = trim(get_comment($parent_id)->comment_author) . '，您在【' . $blogname . '】中的留言有新的回复啦！';
        $message = '
        <p>您好, ' . trim(get_comment($parent_id)->comment_author) . '，您在《' . get_the_title($comment->comment_post_ID) . '》发表的评论有了回应。</p>
        <p style="margin:20px 0;color:#757575;">' . nl2br(strip_tags(get_comment($parent_id)->comment_content)) . '</p>
        <p>' . trim($comment->comment_author) . ' 给您的回复如下:</p>
        <p style="margin:20px 0;color:#757575;">' . nl2br(strip_tags($comment->comment_content)) . '</p>
        <p>您可以查看<a style="color:#5692BC" href="' . htmlspecialchars(get_comment_link($parent_id)) . '">完整的回复內容</a>，也欢迎再次光临【<a style="color:#5692BC" href="' . home_url() . '">' . $blogname . '</a>】。</p>
        <p style="padding-bottom:15px;">(此邮件由系统自动发出,请勿直接回复!)</p>';
        $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
        $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
        wp_mail( $to, $subject, $message, $headers );
    }
}
add_action('comment_post', 'fanly_comment_mail_notify');
*/