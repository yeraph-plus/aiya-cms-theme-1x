<?php

//小工具：推文

class Aya_Tweet_Posts extends WP_Widget {

    //小工具信息
    function __construct() {
        $widget_options = array (
            'classname' => 'widget-post-box',
            'description' => '展示推文列表'
        );
        parent::__construct( 'tweet_posts', 'AiYa-CMS-推文列表' , $widget_options);
    }

    //选项表单
    function form( $instance ) {
        $default = array(
            'title' => '推文列表',
            'limit' => '5',
            'author' => '',
            'mobile_hide' => '0'
        );
        $title = isset($instance['title']) ? $instance['title'] : $default['title'];
        $limit = isset($instance['limit']) ? $instance['limit'] : $default['limit'];
        $author = isset($instance['author']) ? $instance['author'] : $default['author'];
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
                <label for="<?php echo $this->get_field_id('author'); ?>">仅展示指定作者ID：</label>
                <input class="widefat" id="<?php echo $this->get_field_id('author'); ?>" name="<?php echo $this->get_field_name('author'); ?>"  type="text" value="<?php echo $author; ?>" />
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
        $instance['author'] = strip_tags($new_instance['author']);
        $instance['mobile_hide'] = strip_tags($new_instance['mobile_hide']);

        return $instance;
    }

    //主函数
    function widget($args, $instance) {
        //提取参数
        extract($args);
		$title = isset($instance['title']) ? $instance['title'] : '';
		$limit = isset($instance['limit']) ? $instance['limit'] : 5;
		$author = isset($instance['author']) ? $instance['author'] : '';
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
            'author__in' => $author,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_status'=>'publish',
            'posts_per_page' => $limit,
            'paged'	=> '1',
            'post_type' => 'tweet'
        );
        $the_query = new WP_Query($args);
        //生成文章列表
        if($the_query->have_posts()){
            echo '<ul class="widget-tweet p-0 m-0">';
            while ($the_query->have_posts()) : $the_query->the_post();
            echo '<li>
                    <div class="avatar">'.get_avatar(get_the_author_meta('ID'), '32').get_the_author().'</div>
                    <div class="concent">
                        <p>'.mb_strimwidth(aya_clear_text(apply_shortcodes(get_the_content()), 1), 0, 300,'...').'</p>
                        <span><i class="bi bi-clock"></i>&nbsp;发表于'.get_the_date().'&nbsp;<a href="'.get_permalink().'"><i class="bi bi-arrow-return-right"></i>&nbsp;查看全文</a></span>
                    </div>
                </li>';
            endwhile;
            wp_reset_query();//重置Query查询
            echo '</ul>';
        }else{
            echo '<ul><li class="post-link">没有内容</li></ul>';
        }
        echo '</div>';
        echo $after_widget;
    }
}