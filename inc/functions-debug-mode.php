<?php
if (!defined('ABSPATH')){
    exit;
}

/*
 * ------------------------------------------------------------------------------
 * AiYa-cms debug组件
 * ------------------------------------------------------------------------------
 */

 
if(isset($_GET['debug'])){
    define('SAVEQUERIES', true);
    define('WP_DEBUG', true); // false
    //if (WP_DEBUG) {
    //  define('WP_DEBUG_LOG', true);
    //  define('WP_DEBUG_DISPLAY', false);
    //  @ini_set('display_errors',0);
    //}
    add_action('wp_footer','aya_debug_mod');

}
function aya_debug_mod(){

    global $wpdb;

    echo 'queries '.get_num_queries().' num ,in '.timer_stop(1).'s .</br>';
    echo 'list by load:</br>';
    echo '<pre>';
    var_dump($wpdb->queries);
    echo '</pre>';

    echo 'list by time:</br>';
    echo '<pre>';
    $qs = array();
    foreach($wpdb->queries as $q){
        $qs[''.$q[1].''] = $q;
    }
    krsort($qs);
    print_r($qs);
    echo '</pre>';
}

function dashicons_page(){
    $file	= fopen(ABSPATH.'/'.WPINC.'/css/dashicons.css','r') or die("Unable to open file!");
    $html	= '';

    while(!feof($file)) {
        if($line = fgets($file)){
            if(preg_match_all('/.dashicons-(.*?):before/i', $line, $matches) && $matches[1][0] != 'before'){
                $html .= '<p data-dashicon="dashicons-'.$matches[1][0].'"><span class="dashicons-before dashicons-'.$matches[1][0].'"></span> <br />'.$matches[1][0].'</p>'."\n";
            }
        }
    }

    fclose($file);

    echo '<div class="wpjam-dashicons">'.$html.'</div>'.'<div class="clear"></div>';
    ?>
    <style type="text/css">
    div.wpjam-dashicons{max-width: 800px; float: left;}
    div.wpjam-dashicons p{float: left; margin:0px 10px 10px 0; padding: 10px; width:70px; height:70px; text-align: center; cursor: pointer;}
    div.wpjam-dashicons .dashicons-before:before{font-size:32px; width: 32px; height: 32px;}
    div#TB_ajaxContent p{font-size:20px; float: left;}
    div#TB_ajaxContent .dashicons{font-size:100px; width: 100px; height: 100px;}
    </style>
    <script type="text/javascript">
    jQuery(function($){
        $('body').on('click', 'div.wpjam-dashicons p', function(){
            let dashicon	= $(this).data('dashicon');
            let html 		= '<p><span class="dashicons '+dashicon+'"></span></p><p style="margin-left:20px;">'+dashicon+'<br /><br />HTML：<br /><code>&lt;span class="dashicons '+dashicon+'"&gt;&lt;/span&gt;</code></p>';
            
            $.wpjam_show_modal('tb_modal', html, dashicon, 680);
        });
    });
    </script>
    <?php
}
