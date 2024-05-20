<?php

//小工具：作者信息

class Aya_Author_Box extends WP_Widget {

    //小工具信息
    function __construct() {
        $widget_options = array (
            'classname' => 'widget-post-box',
            'description' => '该工具只在文章页面侧栏有效'
        );
        parent::__construct( 'author_box', 'AiYa-CMS-作者信息' , $widget_options);
    }

    //选项表单
    function form( $instance ) {
        $default = array(
            'limit' => '3',
            'mobile_hide' => '0'
        );
        $limit = isset($instance['limit']) ? $instance['limit'] : $default['limit'];
        $mobile_hide = isset($instance['mobile_hide']) ? $instance['mobile_hide'] : $default['mobile_hide'];
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('limit'); ?>">显示作者文章数量:</label>
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
        $instance['mobile_hide'] = strip_tags($new_instance['mobile_hide']);

        return $instance;
    }

    //主函数
    function widget($args, $instance) {
        extract($args);
		$limit = isset($instance['limit']) ? $instance['limit'] : 3;
        $mobile_hide = isset($instance['mobile_hide']) && $instance['mobile_hide'] == 1 ? 'mobile-hide' : '';
        //判断是否为移动端
        if(wp_is_mobile() && $instance['mobile_hide'] == '0'){
            return '';
        }
        //开始生成HTML
        echo $before_widget;
        echo '<div class="widget-card card p-2 mb-2 '.$mobile_hide.'">';
        $author = get_the_author_meta( 'ID' );
        echo '<div class="author-info">
                '.get_avatar( $author, 80).'
                <h5 class="name">'.get_the_author_meta('display_name').'</h5>
                <p class="des">'.get_the_author_meta('description').'</p>
                <p class="mate">
                    <span><i class="bi bi-book"></i>&nbsp;文章&nbsp;'.count_user_posts($author).'</span>
                    <span><i class="bi bi-chat-dots"></i>&nbsp;评论&nbsp;'.get_comments('count=true&user_id='.$author).'</span>
                </p>
            </div>';
        $args = array(
            'author__in' => $author,
            'ignore_sticky_posts' => true,
            'posts_per_page' => $limit,
            'paged'	=> '1',
            'post_type' => 'post'
        );
        $the_query = new WP_Query($args);
        //生成文章列表
        if($the_query->have_posts()){
            echo '<ul class="widget-loop-li p-0 m-0">';
            while ($the_query->have_posts()) : $the_query->the_post();
            echo '<li>
                    <img loading="lazy" src="'.get_post_thumb().'" >
                    <div class="loop">
                        <h5><a class="stretched-link" href="'.get_permalink().'">'.get_post_title().'</a></h5>
                        <p><i class="bi bi-clock"></i>&nbsp;'.get_the_date().'</p>
                    </div>
                </li>';
            endwhile;
            wp_reset_query();//重置Query查询
            echo '</ul>';

        echo '</div>';
        echo $after_widget;
        }
    }
}
