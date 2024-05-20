<?php

//小工具：文章列表

class Aya_List_Posts extends WP_Widget {
    function __construct() {
        $widget_options = array (
            'classname' => 'widget-post-box',
            'description' => '展示文章列表'
        );
        parent::__construct( 'list_posts', 'AiYa-CMS-文章列表' , $widget_options);
    }

    //选项表单
    function form( $instance ) {
        $default = array(
            'title' => '文章列表',
            'vars' => 'post_type=post&&orderby=rand&&posts_per_page=5&&order=DESC',
            'mobile_hide' => '0'
        );
        $title = isset($instance['title']) ? $instance['title'] : $default['title'];
        $vars = isset($instance['vars']) ? $instance['vars'] : $default['vars'];
        $mobile_hide = isset($instance['mobile_hide']) ? $instance['mobile_hide'] : $default['mobile_hide'];
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>">标题:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('vars'); ?>">查询参数（关联数组）:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('vars'); ?>" name="<?php echo $this->get_field_name('vars'); ?>"  type="text" value="<?php echo $vars; ?>" />
                <label>不填写参数默认输出5篇随机文章。</label>
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
        $instance['vars'] = strip_tags($new_instance['vars']);
        $instance['mobile_hide'] = strip_tags($new_instance['mobile_hide']);

        return $instance;
    }

    //主函数
    function widget($args, $instance) {
        //提取参数
        extract($args);
		$title = isset($instance['title']) ? $instance['title'] : '';
		$vars = isset($instance['vars']) ? $instance['vars'] : 'post_type=post&&orderby=rand&&posts_per_page=5&&order=DESC';
		$mobile_hide = isset($instance['mobile_hide']) && $instance['mobile_hide'] == 1 ? 'mobile-hide' : '';
        //判断是否为移动端
        if(wp_is_mobile() && $instance['mobile_hide'] == '0'){
            return '';
        }
        //开始生成HTML
        echo $before_widget;
        echo '<div class="widget-card card p-2 mb-2 '.$mobile_hide.'">';
        if($title){
            echo $before_title.$title.$after_title;
        }
        //自定义Query查询
        $the_query = new WP_Query($vars);
        //生成文章列表
        if($the_query->have_posts()){
            echo '<ul class="widget-list p-0 m-0">';
            while ($the_query->have_posts()) : $the_query->the_post();
                echo '<li><h5><a href="'.get_permalink().'">'.get_post_title().'</a></h5></li>';
            endwhile;
            wp_reset_query();//重置Query查询
            echo '</ul>';
        }else{
            echo '<ul><li class="post-link">没有相关文章</li></ul>';
        }
        echo '</div>';
        echo $after_widget;
    }
}
