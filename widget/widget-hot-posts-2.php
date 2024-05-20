<?php

//小工具：热评文章

class Aya_Connent_Posts extends WP_Widget {

    //小工具信息
    function __construct() {
        $widget_options = array (
            'classname' => 'widget-post-box',
            'description' => '展示全站评论数量最高的文章'
        );
        parent::__construct( 'comment_posts', 'AiYa-CMS-热评文章' , $widget_options);
    }

    //选项表单
    function form( $instance ) {
        $default = array(
            'title' => '热评文章',
            'limit' => '5',
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
                <label for="<?php echo $this->get_field_id('limit'); ?>">数量:</label>
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
        echo '<div class="widget-card card p-2 mb-2 '.$mobile_hide.'">';
        if($title){
            echo $before_title.$title.$after_title;
        }
        //自定义Query查询
        $args = array(
            'orderby' => 'comment_count',
            'order' => 'DESC',
            'ignore_sticky_posts' => true,
            'posts_per_page' => $limit,
            'paged'	=> '1',
            'post_type' => 'post'
        );
        $the_query = new WP_Query($args);
        //生成文章列表
        if($the_query->have_posts()){
            echo '<ul class="widget-loop p-0 m-0">';
            while ($the_query->have_posts()) : $the_query->the_post();
            echo '<li>
                    <img loading="lazy" src="'.get_post_thumb().'" >
                    <div class="loop">
                        <h5><a class="stretched-link" href="'.get_permalink().'">'.get_post_title().'</a></h5>
                        <p><i class="bi bi-chat-dots"></i>&nbsp;'.get_comments_number().'条留言</p>
                    </div>
                </li>';
            endwhile;
            wp_reset_query();//重置Query查询
            echo '</ul>';
        }else{
            echo '<ul><li class="post-link">没有文章</li></ul>';
        }
        echo '</div>';
        echo $after_widget;
    }
}