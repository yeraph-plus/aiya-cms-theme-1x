<?php if (! defined('ABSPATH')) {
	die;
}

if (class_exists('CSF')) {
    $prefix = 'aiya-cms';

    //分类
    CSF::createSection($prefix, array(
        'id' => 'aya_ads',
        'title' => '广告模块',
    ));

    CSF::createSection($prefix, array(
        'parent' => 'aya_ads',
        'type'   => 'group',
        'title'  => '广告内容',
        'fields' => array(
            array(
                'id' => 'sidebar_ad_html',
                'type' => 'code_editor',
                'title' => '添加JavaScript/HTML',
                'subtitle' => '卡片广告位，加载位置为侧边栏',
                'desc' => '使用html编辑',
                'sanitize' => false,
                'settings' => array(
                    'theme' => 'monokai',
                    'mode' => 'javascript',
                ),
                'default' => '',
            ),
            array(
                'id' => 'single_ad_html',
                'type' => 'code_editor',
                'title' => '添加JavaScript/HTML',
                'subtitle' => '横幅广告位，加载位置为文章末尾',
                'desc' => '使用html编辑',
                'sanitize' => false,
                'settings' => array(
                    'theme' => 'monokai',
                    'mode' => 'javascript',
                ),
                'default' => '',
            ),
            array(
                'id' => 'header_ad_html',
                'type' => 'code_editor',
                'title' => '添加JavaScript/HTML',
                'subtitle' => '横幅广告位，全局页面顶部加载',
                'desc' => '使用html编辑',
                'sanitize' => false,
                'settings' => array(
                    'theme' => 'monokai',
                    'mode' => 'javascript',
                ),
                'default' => '',
            ),
            array(
                'id' => 'footer_ad_html',
                'type' => 'code_editor',
                'title' => '添加JavaScript/HTML',
                'subtitle' => '横幅广告位，全局页面底部加载',
                'desc' => '使用html编辑',
                'sanitize' => false,
                'settings' => array(
                    'theme' => 'monokai',
                    'mode' => 'javascript',
                ),
                'default' => '',
            ),
        )
    ));
}

if (class_exists('CSF')) {
    $prefix = 'aiya-cms';

    //分类
    CSF::createSection($prefix, array(
        'id' => 'aya_add_html',
        'title' => '额外JS/HTML',
    ));

	CSF::createSection($prefix, array(
		'parent' => 'aya_add_html',
		'title' => '额外JS/HTML',
		'fields' => array(
            array(
				'id' => 'header_js',
				'type' => 'code_editor',
				'title' => '添加JavaScript',
				'subtitle' => '加载位置为hard内部，全局生效</br>通常用于加载站长统计代码',
				'desc' => '使用html编辑',
                'sanitize' => false,
				'settings' => array(
					'theme' => 'monokai',
					'mode' => 'javascript',
				),
				'default' => '',
            ),
            array(
				'id' => 'footer_html',
				'type' => 'code_editor',
				'title' => '添加JavaScript/HTML',
				'subtitle' => '加载位置为body底部，全局生效',
				'desc' => '使用html编辑',
                'sanitize' => false,
				'settings' => array(
					'theme' => 'monokai',
					'mode' => 'javascript',
				),
				'default' => '',
			),
		)
	));

    CSF::createSection($prefix, array(
        'parent' => 'aya_add_html',
        'type'   => 'group',
        'title'  => '额外CSS',
        'fields' => array(
			array(
				'type' => 'notice',
				'title' => '高级自定义模式',
				'content' => '',
			),
			array(
				'id' => 'custom_mod',
				'type' => 'switcher',
				'title' => '模式开关',
				'desc' =>'开启后，将禁用内置的外观设置，您需要手动定义参数，相关功能请参考主题文档',
				'default' => false
			),
			array(
				'type' => 'notice',
				'title' => '额外CSS样式',
				'content' => '',
			),
			array(
				'id' => 'more_css',
				'type' => 'code_editor',
				'title' => '额外CSS',
				'subtitle' => '页面顶部加载，全局生效',
				'desc' => '无需添加<code>style</code>标签',
                'sanitize' => false,
				'settings' => array(
					'theme'  => 'monokai',
					'mode'   => 'css',
				),
				'default'  => '',
			),
        )
    ));
}