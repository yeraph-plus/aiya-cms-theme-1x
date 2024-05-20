<?php

//小工具：搜索

class Aya_Text_Html extends WP_Widget {

    //小工具信息
    function __construct() {
        $widget_options = array (
            'classname' => 'widget-post-box',
            'description' => '展示文本，可输出HTML'
        );
        parent::__construct( 'text-box', 'AiYa-CMS-文本' , $widget_options);
    }

    //选项表单
    function form( $instance ) {
        $default = array(
            'title' => '',
            'text' => '',
            'type_html' => '0',
            'mobile_hide' => '0'
        );
        $title = isset($instance['title']) ? $instance['title'] : $default['title'];
        $text = isset($instance['text']) ? $instance['text'] : $default['text'];
        $type_html = isset($instance['type_html']) ? $instance['type_html'] : $default['type_html'];
        $mobile_hide = isset($instance['mobile_hide']) ? $instance['mobile_hide'] : $default['mobile_hide'];
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">标题:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('text'); ?>">内容:</label>
                <textarea class="widefat code content" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" rows="16" cols="16"><?php echo $text; ?></textarea>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('type_html'); ?>">输出格式设置：</label>
                <select class="widefat" id="<?php echo $this->get_field_id('type_html'); ?>" name="<?php echo $this->get_field_name('type_html'); ?>" >
                    <option value="1" <?php echo ($type_html == 1 ? 'selected="selected"' : ''); ?>>纯HTML</option>
                    <option value="0" <?php echo ($type_html == 0 ? 'selected="selected"' : ''); ?>>输出为文本</option>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('mobile_hide'); ?>">移动端显示设置：</label>
                <select class="widefat" id="<?php echo $this->get_field_id('mobile_hide'); ?>" name="<?php echo $this->get_field_name('mobile_hide'); ?>" >
                    <option value="1" <?php echo ($mobile_hide == 1 ? 'selected="selected"' : ''); ?>>显示</option>
                    <option value="0" <?php echo ($mobile_hide == 0 ? 'selected="selected"' : ''); ?>>不显示</option>
                </select>
            </p>
        <?php
    }

    //保存设置
    function update($new_instance, $old_instance){
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);
        $instance['text'] = strip_tags($new_instance['text']);
        $instance['type_html'] = strip_tags($new_instance['type_html']);
        $instance['mobile_hide'] = strip_tags($new_instance['mobile_hide']);

        return $instance;
    }

    //主函数
    function widget($args, $instance) {
        //提取参数
        extract($args);
		$title = isset($instance['title']) ? $instance['title'] : '';
		$text = isset($instance['text']) ? $instance['text'] : '';
		$type_html = isset($instance['type_html']) ? $instance['type_html'] : '';
		$mobile_hide = isset($instance['mobile_hide']) && $instance['mobile_hide'] == 1 ? 'mobile-hide' : '';
        //判断是否为移动端
        if(wp_is_mobile() && $instance['mobile_hide'] == '0'){
            return '';
        }
        echo $before_widget;
        //开始生成HTML
        if($type_html == '0'){
            echo '<div class="widget-card card p-2 mb-2 '.$mobile_hide.'">';
            if($title){
                echo $before_title.$title.$after_title;
            }
            echo strip_tags($text);
            echo '</div>';
        }else{
            echo $text;
        }
        echo $after_widget;
    }
}