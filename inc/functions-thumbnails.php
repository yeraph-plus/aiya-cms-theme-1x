<?php

//加载预定义类
require get_template_directory(). '/inc/class/thumbnails.class.php';

//禁止WordPress自动生成缩略图
function try_remove_image_size($sizes) {
    unset( $sizes['thumbnail'] );     //特色图像作用，后台设置120x90
    unset( $sizes['medium'] );        //媒体库缩略图，后台设置120x90
    unset( $sizes['medium_large'] );    //768x0 禁用
    unset( $sizes['large'] );           //后台大尺寸设定，设置为0
    unset( $sizes['1024x1024'] );       //禁止生成
    return $sizes;
}
add_filter('image_size_names_choose', 'try_remove_image_size');

//当图像超大生成  -scaled 缩略图
add_filter('big_image_size_threshold', '__return_false');

//添加特色缩略图支持
if ( function_exists('add_theme_support') )add_theme_support('post-thumbnails');

//添加SVG格式图片上传支持
function add_file_types_to_uploads($file_types){
    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg+xml';
    $file_types = array_merge($file_types, $new_filetypes );
    return $file_types;
}
add_action('upload_mimes', 'add_file_types_to_uploads');
    
//删除文章时删除图片附件
function delete_post_and_attachments($post_ID) {
    global $wpdb;
    //删除特色图片
    $thumbnails = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE meta_key = '_thumbnail_id' AND post_id = $post_ID" );
    foreach ( $thumbnails as $thumbnail ) {
        wp_delete_attachment( $thumbnail->meta_value, true );
    }
    //删除图片附件
    $attachments = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE post_parent = $post_ID AND post_type = 'attachment'" );
    foreach ( $attachments as $attachment ) {
        wp_delete_attachment( $attachment->ID, true );
    }
    $wpdb->query( "DELETE FROM $wpdb->postmeta WHERE meta_key = '_thumbnail_id' AND post_id = $post_ID" );
}
add_action('before_delete_post', 'delete_post_and_attachments');

//禁止响应式图片
function disable_srcset( $sources ) {
return false;
}
add_filter( 'wp_calculate_image_srcset', 'disable_srcset' );

//自动添加特色图像
function auto_set_featured_image() {
    global $post;
    $featured_image_exists = has_post_thumbnail($post->ID);
        if (!$featured_image_exists)  {
            $attached_image = get_children( "post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=1" );
            if ($attached_image) {
            foreach ($attached_image as $attachment_id => $attachment) {set_post_thumbnail($post->ID, $attachment_id);}
            }
        }
}

add_action('the_post', 'auto_set_featured_image');

