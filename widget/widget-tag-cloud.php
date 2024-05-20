<?php

//小工具：标签云

class Aya_Tag_Cloud extends WP_Widget {

    //小工具信息
    function __construct() {
        $widget_options = array (
            'classname' => 'widget-post-box',
            'description' => '展示标签云'
        );
        parent::__construct( 'tags_cloud', 'AiYa-CMS-标签云' , $widget_options);
    }

    //选项表单
    function form( $instance ) {
        $default = array(
            'title' => '标签云',
            'limit' => '10',
            'author' => '',
            'mobile_hide' => '0'
        );
        $title = isset($instance['title']) ? $instance['title'] : $default['title'];
        $limit = isset($instance['limit']) ? $instance['limit'] : $default['limit'];
        $mobile_hide = isset($instance['mobile_hide']) ? $instance['mobile_hide'] : $default['mobile_hide'];
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">标题:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('limit'); ?>">标签数量:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>"  type="text" value="<?php echo $limit; ?>" />
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

        $instance['limit'] = strip_tags($new_instance['limit']);
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['mobile_hide'] = strip_tags($new_instance['mobile_hide']);

        return $instance;
    }

    //主函数
    function widget($args, $instance) {
        //提取参数
        extract($args);
		$title = isset($instance['title']) ? $instance['title'] : '';
		$limit = isset($instance['limit']) ? $instance['limit'] : 5;
		$mobile_hide = isset($instance['mobile_hide']) && $instance['mobile_hide'] == 1 ? 'mobile-hide' : '';
        //判断是否为移动端
        if(wp_is_mobile() && $instance['mobile_hide'] == '0'){
            return '';
        }
        //开始生成HTML
        echo $before_widget;
        echo '<div class="widget-card card p-2 mb-2 '.$mobile_hide.'"><div class="widget-tagcloud p-0 m-0">';
        if($title){
            echo $before_title.$title.$after_title;
        }
        $tags = get_tags(array(
            "number" => $limit,
            "order" => "DESC"
        ));
        foreach($tags as $tag){
            $name = apply_filters('the_title', $tag->name);
            $url = esc_attr(get_tag_link($tag->term_id));
            echo '<a href="'.$url.'" class="tag-item" title="浏览和'.$name.'有关的文章">'.$name.'</a>';
        }
        echo '</div></div>';
        echo $after_widget;
    }
}