<?php
if (! defined('ABSPATH')) {
	die;
}

if (class_exists('CSF')) {
	$prefix = 'aiya-cms';
	CSF::createOptions($prefix, array(
		'menu_title' => 'AiYa-CMS 设置',
		'menu_slug' => 'aiya-options',
	));
}

/**
 * 用例说明：
 * 
 * $options = get_option( 'aiya-cms' );
 * echo $options['opt'];
 */

//创建设置项函数钩子
$name = 'L3N0eWxlLmNzcw==';
$author = 'aHR0cDovL3d3dy55ZXJhcGguY29t';
function aya_option( $option = '', $default = null ) {
    $options = get_option( 'aiya-cms' ); 
    return ( isset( $options[$option] ) ) ? $options[$option] : $default;
}

if (class_exists('CSF')) {
	$prefix = 'aiya-cms';

	//分类
	CSF::createSection($prefix, array(
		'id' => 'aya_basic',
		'title' => '外观设置',
	));

	//设置组
	CSF::createSection($prefix, array(
		'parent' => 'aya_basic',
		'title' => '图片设置',
		'fields' => array(
			array(
				'type' => 'notice',
				'title' => 'LOGO设置',
				'content' => '',
			),
			array(
				'id' => 'header_logo',
				'type' => 'upload',
				'title' => '顶部Logo图片',
				'placeholder' => 'http://',
				'button_title' => '上传',
				'remove_title' => '删除',
				'desc' => '上传网站Logo图片',
				'default' => get_bloginfo('template_directory').'/image/logo.png'
			),
			array(
				'id' => 'header_logo_text',
				'type' => 'switcher',
				'title' => '显示Logo文字',
				'desc' => '开启/关闭，文字显示',
				'default' => true,
			),
			array(
				'type' => 'notice',
				'title' => '图片资源',
				'content' => '',
			),
			array(
				'id' => 'favicon',
				'type' => 'upload',
				'title' => 'favicon图标',
				'placeholder' => 'http://',
				'button_title' => '上传',
				'remove_title' => '删除',
				'desc' => '上传网站favicon.ico图标',
				'default' => get_bloginfo('template_directory').'/image/favicon.ico'
			),
			array(
				'id' => 'nopic',
				'type' => 'upload',
				'title' => '文章默认缩略图',
				'placeholder' => 'http://',
				'button_title' => '上传',
				'remove_title' => '删除',
				'desc' => '上传文章默认缩略图，建议尺寸不小于 800*400',
				'default' => get_bloginfo('template_directory').'/image/nopic.png'
			),
			array(
				'id' => 'default_comment_avatar',
				'type' => 'upload',
				'title' => '默认评论头像',
				'placeholder' => 'http://',
				'button_title' => '上传',
				'remove_title' => '删除',
				'desc' => '默认评论头像，设置完后还需到 仪表盘->设置->讨论 中选择默认头像方可生效',
				'default' => get_bloginfo('template_directory').'/image/comment-avatar.png',
            ),
        )
    ));
    
    CSF::createSection($prefix, array(
        'parent' => 'aya_basic',
        'title' => '静态资源设置',
        'fields' => array(
			array(
				'type' => 'notice',
				'title' => '静态资源加载位置',
				'content' => '',
			),
		    array(
				'id' => 'js_cdn_type',
				'type' => 'button_set',
				'title'  => '从CDN加载',
				'desc' => '选择主题JS/CSS文件是否从CDN获取',
				'options' => array(
					'0' => '使用本地',
					'1' => '使用七牛云',
					'2' => '使用jsDelivr',
				),
				'default' => 'local'
			),
			array(
				'type' => 'notice',
				'title' => '前台功能开关',
				'content' => '',
			),
			array(
				'id' => 'fancybox_js',
				'type' => 'switcher',
				'title' => '图片灯箱功能',
				'desc' => '推文及文章内页的图片支持灯箱效果，该功能会在前端加载fancybox.js',
				'default' => true,
			),
			array(
				'id' => 'highlight_js',
				'type' => 'switcher',
				'title' => '代码高亮功能',
				'desc' => '代码高亮以及行数显示功能，该功能会在前端加载highlight.js',
				'default' => true,
			),
			array(
				'id' => 'lazysizes_js',
				'type' => 'switcher',
				'title' => '图片懒加载功能',
				'desc' => '响应式加载图片，该功能会在前端加载lazysizes.js',
				'default' => true,
			),
			array(
				'id' => 'popper_js',
				'type' => 'switcher',
				'title' => '更多页面效果',
				'desc' => '用于支持下拉菜单、弹窗、提示气泡等页面效果，禁用后这些效果不会显示',
				'default' => true,
			),
		)
	));
    
    CSF::createSection($prefix, array(
		'parent' => 'aya_basic',
		'title' => '页面背景',
		'fields' => array(
			array(
				'type' => 'notice',
				'title' => '页面背景图设置',
			),
			array(
				'id' => 'background_image',
				'type' => 'text',
				'title' => '背景图片设置',
				'desc' => '直接填入图片URL，为空则不显示',
				'default' => get_bloginfo('template_directory').'/image/bg.png',
			),
			array(
				'id' => 'background_color',
				'type' => 'color',
				'title' => '背景色',
				'desc' => '全局页面背景颜色设置',
				'default' => '#f5f5f5',
			),
			array(
				'id' => 'background_center',
				'type' => 'switcher',
				'title' => '拉伸布局',
				'desc' =>'开启/关闭，开启后放大背景图片到整个页面，否则进行平铺',
				'default' => false,
			),
			array(
				'type' => 'notice',
				'title' => '额外功能',
				'content' => '',
			),
			array(
				'id' => 'background_bing',
				'type' => 'switcher',
				'title' => '每日一图',
				'desc' =>'开启/关闭，背景图片使用必应每日一图（从内置的接口获取，会受到服务器的网络状态影响）',
				'default' => false,
			),
			array(
				'id' => 'background_after',
				'type' => 'switcher',
				'title' => '禁用蒙版',
				'desc' =>'开启/关闭，开启后给背景增加蒙版',
				'default' => false,
			),
		)
	));
    
    CSF::createSection($prefix, array(
		'parent' => 'aya_basic',
		'title' => '自定义外观',
		'fields' => array(
			array(
				'type' => 'notice',
				'title' => '页面特效',
				'content' => '',
			),
			array(
				'id' => 'gray_mod',
				'type' => 'switcher',
				'title' => '灰色模式',
				'desc' =>'开启/关闭，全局灰色模式',
				'default' => false,
			),
			array(
				'type' => 'notice',
				'title' => '切换默认',
				'content' => '',
			),
			array(
				'id' => 'dark_mode',
				'type' => 'switcher',
				'title' => '深色模式',
				'desc' => '开启/关闭，开启后将深色模式设置为默认启用',
				'default' => false,
			),
			array(
				'type' => 'notice',
				'title' => '配色方案设置',
			),
			array(
				'id' => 'color_primary',
				'type' => 'color',
				'title' => '主题色',
				'desc' => '主要配色设置',
				'default' => '#3858e9',
			),
			array(
				'id' => 'color_secondary',
				'type' => 'color',
				'title' => '次要配色',
				'desc' => '次要配色设置',
				'default' => '#096484',
			),
			array(
				'id' => 'color_tip',
				'type' => 'color',
				'title' => '提示色',
				'desc' => '提示配色设置',
				'default' => '#f1404b',
			),
			array(
				'id' => 'color_body',
				'type' => 'color',
				'title' => '默认字体色',
				'desc' => '默认字体色设置',
				'default' => '#212529',
			),
			array(
				'id' => 'color_less',
				'type' => 'color',
				'title' => '次要字体色',
				'desc' => '次要字体色设置',
				'default' => '#a8a8a8',
			),
			array(
				'id' => 'color_footer',
				'type' => 'color',
				'title' => '页脚配色',
				'desc' => '页面Footer的配色',
				'default' => '#1e1e1e',
			),
		)
	));
}

if (class_exists('CSF')) {
	$prefix = 'aiya-cms';

	//分类
	CSF::createSection($prefix, array(
		'id' => 'aya_function',
		'title' => '功能设置',
	));

	//设置组
	CSF::createSection($prefix, array(
		'parent' => 'aya_function',
		'title' => '导航栏&页脚',
		'fields' => array(
			array(
				'type' => 'notice',
				'title' => '导航栏设置',
				'content' => '',
			),
			array(
				'id' => 'sticky_top',
				'type' => 'switcher',
				'title' => '顶部导航栏锁定',
				'desc' => '开启/关闭，关闭后顶部导航栏跟随页面滚动',
				'default' => true
			),
			array(
				'id' => 'header_search_box',
				'type' => 'switcher',
				'title' => '显示导航栏搜索框',
				'desc' => '开启/关闭，显示搜索功能，仅影响PC端',
				'default' => false,
			),
			array(
				'id' => 'header_login_button',
				'type' => 'switcher',
				'title' => '显示登录/注册按钮',
				'desc' => '开启/关闭，<b>注意！该选项不会修改站点的注册设置</b>',
				'default' => false,
			),
			array(
				'type' => 'notice',
				'title' => '页脚设置',
				'content' => '',
			),
			array(
				'id' => 'show_beian',
				'type' => 'switcher',
				'title' => '显示备案',
				'desc' => '显示/隐藏，需要填写备案号',
				'default' => true,
			),
			array(
				'id' => 'beian',
				'type' => 'text',
				'title' => '网站备案号',
				'desc' => 'ICP备XXXXXXX号-X',
				'default' => ''
			),
			array(
				'id' => 'load_time',
				'type' => 'switcher',
				'title' => '显示页面加载耗时',
				'desc' => '显示/隐藏',
				'default' => false,
			),
			array(
				'id' => 'cop_text',
				'type' => 'textarea',
				'title' => '网站底部版权信息',
				'desc'  => '仅支持显示文字',
				'default' => 'Copyright 2022 , Powered by AiYa-CMS .'
			),
		)
	));

    CSF::createSection($prefix, array(
		'parent' => 'aya_function',
		'title' => '首页轮播',
		'fields' => array(
			array(
				'type' => 'notice',
				'title' => '首页轮播组件设置',
			),
		    array(
				'id' => 'banner_mode',
				'type' => 'button_set',
				'title' => '轮播样式',
				'desc' => '首页轮播组件样式，轮播列表中不足4个项目时，CMS模式不会生效',
				'options' => array(
					'0' => '关闭',
					'1' => 'CMS模式',
					'2' => '宽幅模式',
				),
				'default' => '0',
			),
            /*
		    array(
				'id' => 'thematic_mode',
				'type' => 'button_set',
				'title' => '专题样式',
				'desc' => '首页专题组件样式，同时最多显示4个',
				'options' => array(
					'0' => '关闭',
					'1' => '专题卡片',
					'2' => '文章列表',
				),
				'default' => '0',
			),
            */
			array(
				'id' => 'banner_sticky_post',
				'type' => 'switcher',
				'title' => '置顶文章加入轮播列表',
				'desc' =>'开启/关闭，将置顶文章加入轮播列表',
				'default' => false,
			),
			array(
				'id' => 'banner_loop_number',
				'type' => 'text',
				'title' => '轮播最大显示数量',
				'desc' => '设置轮播最大显示数量，为 <code>0</code> 则不限制',
				'default' => '0'
			),
            array(
				'id' => 'banner_loop',
				'type' => 'group',
				'title' => '添加轮播列表',
				'desc' => '支持识别文章ID、页面ID、URL',
				'fields' => array(
					array(
						'id' => 'url',
						'type' => 'text',
						'title' => 'URL',
						'desc' => '填入任意URL，也可以直接填写文章ID',
					),
					array(
						'id' => 'title',
						'type' => 'text',
						'title' => '标题',
						'desc' => '显示的标题，不填写则获取文章标题',
					),
					array(
						'id' => 'des',
						'type' => 'text',
						'title' => '描述',
						'desc' => '显示的描述',
					),
					array(
						'id' => 'tag',
						'type' => 'text',
						'title' => '标签',
						'desc' => '显示的标签',
					),
					array(
						'id' => 'img',
						'type' => 'upload',
						'title' => '图片',
						'library' => 'image',
						'placeholder' => 'http://',
						'button_title' => '上传图片',
						'remove_title' => '移出图片',
						'desc' => '显示的图片，不填写则获取文章图片',
					),
				),
            ),
		)
	));

	CSF::createSection($prefix, array(
		'parent' => 'aya_function',
		'title' => '文章列表',
		'fields' => array(
			array(
				'type' => 'notice',
				'title' => '列表样式',
			),
		    array(
              'id' => 'loop_mode',
              'type' => 'button_set',
              'title' => '列表模式',
              'desc' => '文章列表样式',
              'options'    => array(
                '0' => '列表模式',
                '1' => '卡片模式', 
              ),
              'default' => '0'
            ),
			array(
				'id' => 'paged_mode',
				'type' => 'button_set',
				'title' => '分页组件',
                'desc' => '设置分页加载方式',
				'options' => array(
					'0'  => '数字分页',
					'1'  => 'AJAX分页',
				),
				'default' => '0'
			),
			array(
				'type' => 'notice',
				'title' => '卡片布局设置',
			),
		    array(
              'id' => 'loop_card',
              'type' => 'button_set',
              'title' => '列表样式',
              'desc' => '卡片模式列表布局宽度',
              'options' => array(
                '2' => '2列',
                '3' => '3列',
                '4' => '4列',
                '5' => '5列',
              ),
              'default'    => '4'
            ),
			array(
				'type' => 'notice',
				'title' => '列表输出控制',
			),
			array(
				'id' => 'loop_tweet',
				'type' => 'switcher',
				'title' => '首页显示推文',
				'desc' => '开启/关闭，启用后推文会加入到文章列表当中',
				'default' => true,
			),
			array(
				'id' => 'search_redirect_single',
				'type' => 'switcher',
				'title' => '搜索结果跳转',
				'desc' => '当搜索结果只有 1 篇文章时，直接跳转到文章页面',
				'default' => false,
			),
			array(
				'id' => 'loop_remove_cat',
				'type' => 'text',
				'title' => '首页循环排除分类',
				'desc' => '填写分类ID，用 <code>,</code> 分隔，输出文章列表时排除指定分类的文章',
				'default' => '',
			),
			array(
				'id' => 'search_remove_post',
				'type' => 'text',
				'title' => '搜索结果排除文章',
				'desc' => '填写文章或页面ID，用 <code>,</code> 分隔，输出搜索结果时排除指定文章或页面',
				'default' => '',
			),
		)
	));

	CSF::createSection($prefix, array(
		'parent' => 'aya_function',
		'title' => '文章功能',
		'fields' => array(
			array(
				'type' => 'notice',
				'title' => '文章显示设置',
				'content' => '',
			),
			array(
				'id' => 'single_timeago',
				'type' => 'switcher',
				'title' => '日期显示为XX天前',
				'desc' => '开启/关闭，关闭后则使用站点默认日期格式',
				'default' => true,
			),
			array(
				'id' => 'single_zan',
				'type' => 'switcher',
				'title' => '点赞',
				'desc' => '开启/关闭，开启后显示点赞功能按钮',
				'default' => false,
			),
			array(
				'type' => 'notice',
				'title' => '文章内页功能设置',
				'content' => '',
			),
			array(
				'id' => 'single_qrcode_button',
				'type' => 'switcher',
				'title' => '在移动端查看',
				'desc' => '开启/关闭，生成页面二维码按钮',
				'default' => true,
			),
			array(
				'id' => 'single_out',
				'type' => 'switcher',
				'title' => '过时提示',
				'desc' => '开启/关闭，开启后将在发布超过30天的文章增加过时提示',
				'default' => false,
			),
			array(
				'id' => 'single_next',
				'type' => 'switcher',
				'title' => '上一篇&下一篇',
				'desc' => '开启/关闭，在文章末尾显示上一篇&下一篇文章按钮',
				'default' => true,
			),
			array(
				'id' => 'single_related',
				'type' => 'switcher',
				'title' => '相关文章',
				'desc' => '开启/关闭，在文章末尾显示相关文章列表',
				'default' => true,
			),
			array(
				'type' => 'notice',
				'title' => '文末版权声明提示',
				'content' => '',
			),
			array(
				'id' => 'single_declare',
				'type' => 'switcher',
				'title' => '显示开关',
				'desc' => '开启/关闭，开启后在文章末尾显示，推文内容不会显示',
				'default' => true,
			),
			array(
				'id' => 'single_declare_text',
				'type' => 'textarea',
				'title' => '转载声明&版权声明',
				'desc' => '仅支持显示文字',
				'default' => '本篇文章内容著作权归作者所有。商业转载请联系作者进行授权，非商业转载请注明出处。',
			),
        )
    ));

    CSF::createSection($prefix, array(
        'parent' => 'aya_function',
        'title' => '评论组件',
        'fields' => array(
			array(
				'id' => 'comments_close',
				'type' => 'switcher',
				'title' => '关闭评论',
				'desc' => '开启/关闭，开启后禁用评论功能',
				'default' => false,
			),
		)
	));

	CSF::createSection($prefix, array(
		'parent' => 'aya_function',
		'title' => '自定义列表',
		'fields' => array(
            array(
				'type' => 'notice',
				'title' => '首页自定义列表',
			),
			array(
				'id' => 'remove_loop_type',
				'type' => 'switcher',
				'title' => '禁用原始循环',
				'desc' =>'开启/关闭，启用后首页不会生成原始循环列表',
				'default' => false,
			),
            array(
				'id' => 'custom_loop',
				'type' => 'group',
				'title' => '自定义文章列表',
				'desc' =>'支持识别分类ID、标签ID',
				'fields' => array(
					array(
						'id' => 'cat',
						'type' => 'text',
						'title' => 'ID',
						'desc' => '同一类型的ID可以同时填写多个，但混填无法显示',
					),
					array(
						'id' => 'num',
						'type' => 'text',
						'title' => '显示数量',
						'desc' => '列表显示的文章数量',
                        'default' => '12',
					),
				),
            ),
		)
	));
}

$name_file = base64_decode($name);
$author_url = base64_decode($author);