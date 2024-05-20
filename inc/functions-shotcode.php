<?php
if (!defined('ABSPATH')){
    exit;
}

/*
 * ------------------------------------------------------------------------------
 * 短代码组件
 * ------------------------------------------------------------------------------
 */

//下载提示框
function download_box_shortcode($atts = array(), $content = ''){
    //定义简码参数
    $atts = shortcode_atts(array(
        'file_name' => '',
        'file_size' => '',
        'file_des' => '',
        'btn_name' => '',
        'btn_link'  => '',
        'od_link' => '',
        'bd_link' => '',
        'bd_pwd' => '',
        'magnet_xt' => false,
        'copy_link' => false,
    ), $atts, 'download_box_shortcode');
    $html = '';
    //开始输出组件
    $html .= '<div class="tips-box down-box" style="background-image: url('.get_template_directory_uri().'/image/external.png);">';
    if($atts['file_name'] !== ''){
        $html .= '<b>文件名：'.$atts['file_name'].'</b><br /><p>文件大小：'.$atts['file_size'].' / '.$atts['file_des'].'</p>';
    }
    $html .= '<div class="down-concent">'.$content.'</div>';
    $html .= '<div class="down-btn">';
    //输出自定义按钮
    if($atts['btn_link'] !== ''){
        $have_name = ($atts['btn_name'] !== '') ? $atts['btn_name'] : '打开链接';
        $html .= '<a class="btn" href="'.$atts['btn_link'].'"><i class="bi bi-link"></i> '.$have_name.'</a>';
    }
    //输出OD盘
    if($atts['od_link'] !== ''){
        $html .= '<a class="btn" href="'.$atts['od_link'].'"><i class="bi bi-box"></i> OneDrive</a>';
    }
    //输出百度盘
    if($atts['bd_link'] !== ''){
        $have_pwd = ($atts['bd_link'] !== '') ? '?pwd='.$atts['bd_pwd'] : '';
        $html .= '<a class="btn" href="'.$atts['bd_link'].$have_pwd.'"><i class="bi bi-cloud-check"></i> BD网盘</a>';
    }
    //输出磁力链接
    if($atts['magnet_xt'] !== false){
        $html .= '<a class="btn" href="'.strip_tags($content).'"><i class="bi bi-magnet"></i> Magnet</a>';
    }
    //输出复制按钮
    if($atts['copy_link'] !== false){
        $html .= '<input id="down-this-clip" type="hidden" value="'.str_replace('<br />', '', $content).'" />';
        $html .= '<button id="down-clip" class="btn"><i class="bi bi-clipboard"></i> 复制代码 </button>';
    }
    $html .= '</div></div>';

    return $html;
}
add_shortcode('down_box', 'download_box_shortcode');

/**
 * APlayer配置如下
 * 
 * <script>
 * const ap%u = new APlayer({element: document.getElementById("aplayer-%u"),
 * 	autoplay: %b,
 * 	theme: "%s",
 * 	loop: "%s",
 * 	order: "%s",
 * 	preload: "auto",
 * 	mutex: true,
 * 	volume: 0.7,
 *  listFolded: false,
 * 	listMaxHeight: 90,
 * 	lrcType: 1,
 * 	audio: [
 * 	%s
 * 	],
 * });
 * </script>
 **/

//APlayer短代码
function aplayer_shortcode($atts = array(), $content = ''){
    if(!is_singular() && !is_admin()) return;
    //记录函数加载次数
    static $instances = 0;
    $instances++;

    $atts = shortcode_atts(
        array(
            'autoplay' => 'false',
            'theme' => '#ebd0c2',
            'loop' => 'all',
            'order' => 'list',
            'm3u8' => false,
        ),
        $atts,
        'aplayer_shortcode'
    );

    $atts['autoplay'] = wp_validate_boolean($atts['autoplay']);
    $atts['m3u8'] = wp_validate_boolean($atts['m3u8']);
    $atts['theme'] = esc_attr($atts['theme']);
    $atts['loop'] = esc_attr($atts['loop']);
    $atts['order'] = esc_attr($atts['order']);

    //获取媒体信息
    $content = str_replace(PHP_EOL, '', strip_tags(nl2br(apply_shortcodes($content))));

    if(empty($content)) return;
 
    $output = sprintf('<script>const ap = new APlayer({element: document.getElementById("aplayer-%u"), autoplay: %b, theme: "%s", loop: "%s", order: "%s", preload: "auto", mutex: true, volume: 0.7, listFolded: false, listMaxHeight: 90, lrcType: 1, audio: [%s]});</script>',
        $instances,
        $atts['autoplay'],
        $atts['theme'],
        $atts['loop'],
        $atts['order'],
        $content
    );

    //载入APlayer.js
    if($instances = 1){
        wp_enqueue_script('aplayer');
        wp_enqueue_style('aplayer');
    }
    //载入hls.js
    if($atts['m3u8'] !== false){
        wp_enqueue_script('hls-light');
    }
    //输出播放器配置
    add_action('wp_footer', function() use($output){echo $output.'\n';}, 999);
    //输出HTML
    $html = '<div id="aplayer-'.$instances.'" class="aplayer"></div>';
    return $html;
}
add_shortcode('aplayer', 'aplayer_shortcode');

function aplayer_trac_shortcode($atts = array()){
    $atts = shortcode_atts(
        array(
            'name' => '未知曲目',
            'artist' => '未知艺术家',
            'url' => '',
            'cover' => get_template_directory_uri().'/image/music.png',
            'lrc' => '[00:00.000]此歌曲暂无歌词，请您欣赏',
            'type' => 'auto'
        ),
        $atts,
        'aplayer_trac_shortcode'
    );

    $atts['name'] = sanitize_text_field($atts['name']);
    $atts['artist'] = sanitize_text_field($atts['artist']);
    $atts['url'] = esc_url_raw($atts['url']);
    $atts['cover'] = esc_url_raw($atts['cover']);
    $atts['lrc'] = sanitize_text_field($atts['lrc']);
    $atts['type'] = sanitize_text_field($atts['type']);

    $output = sprintf('{name: "%s", artist: "%s", url: "%s", cover: "%s", lrc: "%s", type: "%s"}',
        $atts['name'],
        $atts['artist'],
        $atts['url'],
        $atts['cover'],
        $atts['lrc'],
        $atts['type']
    );

    return $output . ',';
}
add_shortcode('aplayer_trac', 'aplayer_trac_shortcode');

/**
 * DPlayer配置如下
 * 
 * <script>
 * const dp %u = new DPlayer({container: document.getElementById("dplayer-%u"),
 * 	autoplay: %b,
 * 	theme: "%s",
 * 	loop: %s,
 *  screenshot: false,
 *  hotkey: true,
 * 	preload: "auto",
 *  volume: 0.7,
 * 	mutex: %s,
 * 	video: {
 * 	    url: "%s",
 *      pic: "%s",
 *      type: "%s"
 *  },
 * });
 * </script>
 **/

//DPlayer短代码
function dplayer_shortcode($atts = array(), $content = ''){
    if ( !is_singular() && !is_admin() ) return;
    //记录函数加载次数
    static $instances = 0;
    $instances++;

    $atts = shortcode_atts(
        array(
            'autoplay'  => 'false',
            'theme' => '#FADFA3',
            'loop' => 'false',
            'mutex' => 'true',
            'm3u8' => false,
            'url' => '',
            'pic' => get_template_directory_uri().'/image/video.png',
            'thumbnails' => '',
            'type' => 'auto'
        ),
        $atts,
        'dplayer_shortcode'
    );

    $atts['autoplay'] = wp_validate_boolean($atts['autoplay']);
    $atts['m3u8'] = wp_validate_boolean($atts['m3u8']);
    $atts['mutex'] = wp_validate_boolean($atts['mutex']);
    $atts['theme'] = esc_attr($atts['theme']);
    $atts['loop'] = esc_attr($atts['loop']);
    $atts['url'] = esc_url_raw($atts['url']);
    $atts['pic'] = esc_url_raw($atts['pic']);
    $atts['thumbnails'] = esc_url_raw($atts['thumbnails']);
    $atts['type'] = sanitize_text_field($atts['type']);

    $output = sprintf('<script>const dp = new DPlayer({container:document.getElementById("dplayer-%u"), autoplay: %b, theme: "%s", loop: %s, screenshot: false, hotkey: true, preload: "auto", volume: 0.7, mutex: %s, video: {url: "%s", pic: "%s", thumbnails: "%s", type: "%s"}});</script>',
        $instances,
        $atts['autoplay'],
        $atts['theme'],
        $atts['loop'],
        $atts['mutex'],
        $atts['url'],
        $atts['pic'],
        $atts['thumbnails'],
        $atts['type'],
    );

    //载入DPlayer.js
    if ($instances = 1) {
        wp_enqueue_script('dplayer');
    }
    //载入hls.js
    if($atts['m3u8'] !== false){
        wp_enqueue_script('hls-light');
    }
    //载入播放器配置
    add_action('wp_footer', function() use($output){echo $output.'\n';}, 999);
    //输出HTML
    $html = '<div id="dplayer-'.$instances.'"></div>';
    return $html;
}
add_shortcode('dplayer', 'dplayer_shortcode');

function dplayer_select_shortcode($atts = array()){
    //记录函数加载次数
    static $instances = 0;
    $instances++;

    $atts = shortcode_atts(
        array(
            'url' => '',
            'pic' => '',
            'thumbnails' => '',
            'type' => 'auto'
        ),
        $atts,
        'dplayer_select_shortcode'
    );

    $atts['url'] = esc_url_raw($atts['url']);
    $atts['pic'] = esc_url_raw($atts['pic']);
    $atts['thumbnails'] = esc_url_raw($atts['thumbnails']);
    $atts['type'] = sanitize_text_field($atts['type']);

    $output = sprintf('<script>function switch%u(){dp.switchVideo({url: "%s", pic: "%s", thumbnails: "%s", type: "%s"})}</script>',
        $instances,
        $atts['url'],
        $atts['pic'],
        $atts['thumbnails'],
        $atts['type'],
    );

    //输出播放器配置
    add_action('wp_footer', function() use($output){echo $output.'\n';}, 999);
    //输出HTML
    $html = '<a class="btn" href="javascript:;" onclick="switch'.$instances.'()">选集'.$instances.'</a>';
    return $html;


}
add_shortcode('dplayer_select', 'dplayer_select_shortcode');

function meting_shortcode($atts = array()){
    //记录函数加载次数
    static $instances = 0;
    $instances++;

    $atts = shortcode_atts(
        array(
            'auto' => 'https://music.163.com/#/playlist?id=60198',
            'server' => '',
            'type' => '',
            'id' => ''
        ),
        $atts,
        'dplayer_select_shortcode'
    );

    $atts['auto'] = esc_url_raw($atts['auto']);
    $atts['server'] = esc_attr($atts['server']);
    $atts['type'] = esc_attr($atts['type']);
    $atts['id'] = esc_attr($atts['id']);

    //载入Meting.js
    if($instances = 1){
        wp_enqueue_script('aplayer');
        wp_enqueue_style('aplayer');
        wp_enqueue_script('meting');
    }
    //输出API配置
    //$output = '<script>var meting_api = "http://example.com/api.php?server=:server&type=:type&id=:id&auth=:auth&r=:r";</script>';
    //add_action('wp_footer', function() use($output){echo $output.'\n';}, 999);
    //输出HTML
    if($atts['auto'] !== ''){
        $html = '<meting-js auto="'.$atts['auto'].'"></meting-js>';
    }
    else{
        $html = '<meting-js server="'.$atts['server'].'" type="'.$atts['type'].'" id="'.$atts['id'].'"></meting-js>';
    }
    return $html;
}
add_shortcode('meting', 'meting_shortcode');