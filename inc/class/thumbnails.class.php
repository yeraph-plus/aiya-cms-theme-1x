<?php

/** 裁剪核心 2022-08-28 */

class Thumbnails {

    public function __construct() {
        add_action('init', array($this, 'init'));
    }

    function init() {

        add_filter('image_resize_dimensions', array($this, 'image_resize_dimensions'), 10, 6);  //图片太小放大裁剪
        add_filter('image_downsize', array($this, 'image_downsize'), 10, 3);                    //开启自动裁剪

    }


    /** 图片太小放大裁剪 */

    function image_resize_dimensions($preempt, $orig_w, $orig_h, $new_w, $new_h, $crop) {
        if (!$crop) {
            return null;
        }
        if (!is_array($crop)) {
            $crop = array('center', 'center');  //水平和上下
        }
        $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);
        $crop_w = round($new_w / $size_ratio);
        $crop_h = round($new_h / $size_ratio);
        list( $x, $y ) = $crop;
        if ('left' === $x) {
            $s_x = 0;
        } elseif ('right' === $x) {
            $s_x = $orig_w - $crop_w;
        } else {
            $s_x = floor(( $orig_w - $crop_w ) / 2);
        }
        if ('top' === $y) {
            $s_y = 0;
        } elseif ('bottom' === $y) {
            $s_y = $orig_h - $crop_h;
        } else {
            $s_y = floor(( $orig_h - $crop_h ) / 2);
        }
        return array(0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h);
    }



    /** 开始裁剪 */

    function image_downsize($downsize = false, $id, $size ) {
        if (function_exists('wp_get_additional_image_sizes')) {
            $sizes = wp_get_additional_image_sizes();
        } else {
            global $_wp_additional_image_sizes;
            $sizes = &$_wp_additional_image_sizes;
        }
        if (is_string($size)) {
            if (isset($sizes[$size])) {
                $width = $sizes[$size]['width'];
                $height = $sizes[$size]['height'];
                if (isset($sizes[$size]['crop'])) {
                    if ($sizes[$size]['crop']) {
                        $crop = array('center', 'center');
                    } else {
                        $crop = false;
                    }
                } else {
                    $crop = false;
                }
            } else {
                if ($size == 'thumb' || $size == 'thumbnail') {
                    $width = intval(get_option('thumbnail_size_w'));
                    $height = intval(get_option('thumbnail_size_h'));
                    $crop = true;
                } else {
                    return false;
                }
            }
        } else {
            $width = $size[0];
            $height = $size[1];
            if (isset($size[2])) {
                if ($size[2]) {
                    $crop = array('center', 'center');
                } else {
                    $crop = false;
                }
            } else {
                $crop = false;
            }
        }
        $relative_file = trim(get_post_meta($id, '_wp_attached_file', true));
        $url = $this->resize($relative_file, $width, $height, $crop);
        return array($url, $width, $height, false);
    }



    /** 裁剪输出路径 */

    function resize($relative_file, $width, $height, $crop = false) {
        // 附加文件的相对和绝对名称。请参见get_attached_file()
        $uploads = wp_upload_dir();
        $absolute_file = $uploads['basedir'] . '/' . $relative_file;
        $pathinfo = pathinfo($relative_file);
        $relative_thumb = $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '-' . $width . 'x' .
                $height;
        if (is_array($crop) && $crop[0] != 'center' && $crop[1] != 'center') {
            $relative_thumb .= '-' . $crop[0] . '-' . $crop[1];
        } else if ($crop) {
            $relative_thumb .= '-c';
        }
        $relative_thumb .= '.' . $pathinfo['extension'];
        $absolute_thumb = WP_CONTENT_DIR . '/cache/thumbnails/' . $relative_thumb;
        if (!file_exists($absolute_thumb) || filemtime($absolute_thumb) < filemtime($absolute_file)) {
            wp_mkdir_p(WP_CONTENT_DIR . '/cache/thumbnails/' . $pathinfo['dirname']);
            $editor = wp_get_image_editor($absolute_file);
            if (is_wp_error($editor)) {
                return $uploads['baseurl'] . '/' . $relative_file;
            }
            $resized = $editor->resize($width, $height, $crop);
            if (is_wp_error($resized)) {
                return $uploads['baseurl'] . '/' . $relative_file;
            }
            $saved = $editor->save($absolute_thumb);
            if (is_wp_error($saved)) {
                return $uploads['baseurl'] . '/' . $relative_file;
            }
        }
        return WP_CONTENT_URL . '/cache/thumbnails/' . $relative_thumb;
    }

}

new Thumbnails();