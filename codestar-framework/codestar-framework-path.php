<?php if (! defined('ABSPATH')) {
	die;
}
/**
 * 用例说明：
 * 
 * $options = get_option( 'aiya-cms' );// Attention: Set your unique id of the framework
 * echo $options['opt-textarea']; // id of the field
 */

//创建设置项函数钩子
function aya_option( $option = '', $default = null ) {
    $options = get_option( 'aiya-cms' ); 
    return ( isset( $options[$option] ) ) ? $options[$option] : $default;
}

//设置项
if (class_exists('CSF')) {
	$prefix = 'aiya-cms';
	CSF::createOptions($prefix, array(
		'menu_title' => '主题设置',
		'menu_slug' => 'aiya-options',
	));

	//基本设置

	CSF::createSection($prefix, array(
		'id' => 'aya_basic',
		'title' => '基本设置',
	));
	CSF::createSection($prefix, array(
		'parent' => 'aya_basic',
		'title' => '顶部设置',
		'fields' => array(
			array(
				'id' => 'head_logo',
				'type' => 'upload',
				'title' => '顶部Logo图片',
				'placeholder' => 'https://',
				'button_title' => '上传',
				'remove_title' => '删除',
				'desc' => '上传网站Logo图片',
				'default' => get_bloginfo('url').'/wp-content/themes/AiYa-CMS/assets/logo.png'
			),
			array(
				'id' => 'favicon',
				'type' => 'upload',
				'title' => '网站favicon.ico图标',
				'placeholder' => 'https://',
				'button_title' => '上传',
				'remove_title' => '删除',
				'desc' => '上传网站favicon.ico图标',
				'default' => get_bloginfo('url').'/wp-content/themes/AiYa-CMS/assets/favicon.ico'
			),
			array(
				'id' => 'head_logo_text',
				'type' => 'switcher',
				'title' => '开启/关闭顶部Logo文字',
				'default' => true
			),
			array(
				'id' => 'sticky_top',
				'type' => 'switcher',
				'title' => '开启/关闭顶部导航悬浮',
				'default' => true
			),
            array(
              'id'       => 'header_js',
              'type'     => 'code_editor',
              'title'    => '顶部Javascript代码',
              'settings' => array(
                'theme'  => 'monokai',
                'mode'   => 'javascript',
              ),
              'default'  => '',
            ),
            array(
              'id'       => 'header_css',
              'type'     => 'code_editor',
              'title'    => '顶部Css代码',
              'settings' => array(
                'theme'  => 'monokai',
                'mode'   => 'javascript',
              ),
              'default'  => '',
            ),
		)
	));
	CSF::createSection($prefix, array(
		'parent' => 'aya_basic',
		'title' => '底部设置',
		'fields' => array(
			array(
				'id' => 'show_beian',
				'type' => 'switcher',
				'title' => '隐藏/显示网站备案号',
				'default' => false
			),
			array(
				'id' => 'beian',
				'type' => 'text',
				'title' => '网站备案号',
				'default' => 'ICP没备XXXXXXX号-X'
			),
			array(
				'id' => 'cop_text',
				'type' => 'textarea',
				'title' => '网站底部版权信息',
				'desc'  => '可以使用html编辑',
				'default' => 'Copyright 2022 , Powered by WordPress .'
			),
			array(
				'id' => 'load_time',
				'type' => 'switcher',
				'title' => '隐藏/显示网站加载时间',
				'default' => true
			),
            array(
				'id'       => 'footer_js',
				'type'     => 'code_editor',
				'title'    => '底部Javascript代码',
				'settings' => array(
				  'theme'  => 'monokai',
				  'mode'   => 'javascript',
				),
				'default'  => '',
			  ),
		)
	));
	CSF::createSection($prefix, array(
		'parent' => 'aya_basic',
		'title' => 'SEO设置',
		'fields' => array(
			array(
				'id' => 'site_name',
				'type' => 'text',
				'title' => '网站标题',
				'desc'  => '默认则调用WP设置',
				'default' => get_bloginfo('name')
			),
			array(
				'id' => 'site_description',
				'type' => 'text',
				'title' => '网站副标题',
				'desc'  => '默认调用WP设置',
				'default' => get_bloginfo('description')
			),
			array(
				'id' => 'title_fgf',
				'type' => 'text',
				'title' => '标题分隔符',
				'default' => '-'
			),
			array(
				'id' => 'seo_keywords',
				'type' => 'text',
				'title' => '网站关键词',
				'desc'  => '首页SEO关键词',
				'default' => ''
			),
			array(
				'id' => 'seo_description',
				'type' => 'text',
				'title' => '网站描述',
				'desc'  => '首页SEO关键词描述',
				'default' => ''
			),
		)
	));

	//首页设置

	CSF::createSection($prefix, array(
		'id' => 'aya_home',
		'title' => '首页设置',
	));
	CSF::createSection($prefix, array(
		'parent' => 'aya_home',
		'title' => '首页轮播',
		'fields' => array(
		    array(
				'id'         => 'banner_show',
				'type'       => 'button_set',
				'title'      => '轮播模式',
				'desc' => '首页轮播样式',
				'options'    => array(
				  '1' => '大图模式',
				  '2' => '多图模式',
				),
				'default'    => '1'
			  ),
            array(
              'id'        => 'slide',
              'type'      => 'group',
              'title'     => '文章ID',
              'fields'    => array(
                array(
                  'id'    => 'link',
                  'type'  => 'text',
                  'title' => '文章ID',
                ),
                array(
                  'id'           => 'img',
                  'type'         => 'upload',
                  'title'        => '上传图片',
                  'library'      => 'image',
                  'placeholder'  => 'http://',
                  'button_title' => '上传图片',
                  'remove_title' => '移出图片',
                ),
              ),
              'default'   => array(
                array(
                  'link'     => '#',
                  'img'    => '#',
                ),
              ),
            ),
			
		)
	));
	CSF::createSection($prefix, array(
		'parent' => 'aya_home',
		'title' => '文章展示',
		'fields' => array(
		    array(
              'id'         => 'home_show',
              'type'       => 'button_set',
              'title'      => '列表模式',
              'desc' => '首页文章列表展示样式',
              'options'    => array(
                '1' => '列表模式',
                '2' => '卡片模式',
              ),
              'default'    => '1'
            ),
			array(
				'id'          => 'home_load',
				'type'        => 'select',
				'title'       => '分页加载方式',
				'options'     => array(
					'1'  => '上一页/下一页数字加载',
					'2'  => 'AJAX无刷新加载',
				),
				'default'     => '1'
			),
		)
	));

	//文章页设置

	CSF::createSection($prefix, array(
		'id' => 'aya_single',
		'title' => '文章设置',
	));
	CSF::createSection($prefix, array(
		'parent' => 'aya_single',
		'title' => '文章页功能',
		'fields' => array(

			array(
				'id' => 'single_cop',
				'type' => 'switcher',
				'title' => '隐藏/显示文章版权',
				'default' => true,
			),
			array(
				'id' => 'single_time',
				'type' => 'switcher',
				'title' => '隐藏/显示下一篇文章',
				'default' => true,
			),
			array(
				'id' => 'single_view',
				'type' => 'switcher',
				'title' => '隐藏/显示相关文章',
				'default' => true,
			),

		)
	));

	//站点优化设置

	CSF::createSection($prefix, array(
		'id' => 'aya_optimization',
		'title' => '优化设置',
	));
	CSF::createSection($prefix, array(
		'parent' => 'aya_optimization',
		'title' => '优化加速',
		'fields' => array(
			array(
				'id' => 'gtb_editor',
				'type' => 'switcher',
				'title' => '禁用古腾堡编辑器',
				'default' => true,
				'subtitle' => '古腾堡用不习惯吗？那就关闭吧！(默认关闭)',
			),
			array(
				'id' => 'diable_revision',
				'type' => 'switcher',
				'title' => '屏蔽文章修订',
				'default' => true,
				'subtitle' => '屏蔽文章修订',
			),
			array(
				'id' => 'googleapis',
				'type' => 'switcher',
				'title' => '后台禁止加载谷歌字体',
				'default' => true,
				'subtitle' => '后台禁止加载谷歌字体，加快后台访问速度',
			),
			array(
				'id' => 'category',
				'type' => 'switcher',
				'title' => '去掉分类目录中的category',
				'default' => true,
				'subtitle' => '去掉分类目录中的category，精简URL，有利于SEO，推荐去掉',
			),
			array(
				'id' => 'emoji',
				'type' => 'switcher',
				'title' => '禁用emoji表情',
				'default' => true,
				'subtitle' => '禁用WordPress的Emoji功能和禁止head区域Emoji css加载',
			),
			array(
				'id' => 'article_revision',
				'type' => 'switcher',
				'title' => '屏蔽文章修订功能',
				'default' => true,
				'subtitle' => '文章多，修订次数的用户建议关闭此功能',
			),
		)
	));

	//广告设置

	CSF::createSection($prefix, array(
		'id' => 'aya_ads',
		'title' => '广告设置',
	));

	CSF::createSection($prefix, array(
		'parent' => 'aya_ads',
		'type'   => 'group',
		'title'  => '广告内容',
		'fields' => array(
			array(
				'id' => 'side_ad',
				'type' => 'textarea',
				'title' => '侧边栏广告',
				'desc'  => '可以使用html编辑',
				'default' => ''
			),
			array(
				'id' => 'single_ad',
				'type' => 'textarea',
				'title' => '文章页广告',
				'desc'  => '可以使用html编辑',
				'default' => ''
			),
		)
	));

	//其他设置

	CSF::createSection($prefix, array(
		'id' => 'aya_other',
		'title' => '其他设置',
	));
	CSF::createSection($prefix, array(
		'parent' => 'aya_other',
		'title' => '其他设置',
		'fields' => array(
			array(
				'id' => 'dark_mod',
				'type' => 'switcher',
				'title' => '暗黑模式',
				'desc' =>'开启/关闭黑夜模式',
				'default' => true
			),
			array(
				'id' => 'gray_mod',
				'type' => 'switcher',
				'title' => '灰色模式',
				'desc' =>'开启/关闭灰色模式',
				'default' => false
			),
			array(
				'id' => 'snow_mod',
				'type' => 'switcher',
				'title' => '下雪模式',
				'desc' =>'开启/关闭下雪模式',
				'default' => false
			),
			array(
				'id' => 'link_show',
				'type' => 'switcher',
				'title' => '友情链接',
				'desc' =>'开启/关闭首页底部友情链接',
				'default' => false
			),
			array(
				'id' => 'comments_close',
				'type' => 'switcher',
				'title' => '整站评论',
				'desc' =>'开启后关闭整站评论',
				'default' => false
			),
			array(
				'id' => 'website_close',
				'type' => 'switcher',
				'title' => '维护模式',
				'desc' =>'开启后关闭网站',
				'default' => false
			),
			array(
			'id'     => 'user_group_name',
			'type'   => 'fieldset',
			'title'  => '用户组名称',
			'fields' => array(
				array(
				'id'    => 'user_admin',
				'type'  => 'text',
				'title' => '管理员',
				'desc'  => '管理员 用户组名称修改'
				),
				array(
				'id'    => 'user_edit',
				'type'  => 'text',
				'title' => '编辑',
				'desc'  => '编辑 用户组名称修改'
				),
				array(
				'id'    => 'user_author',
				'type'  => 'text',
				'title' => '作者',
				'desc'  => '作者 用户组名称修改'
				),
				array(
				'id'    => 'user_contributor',
				'type'  => 'text',
				'title' => '贡献者',
				'desc'  => '贡献者 用户组名称修改'
				),
				array(
				'id'    => 'user_subscriber',
				'type'  => 'text',
				'title' => '订阅者',
				'desc'  => '订阅者 用户组名称修改'
				),
			),
			),
		)
	));

}
