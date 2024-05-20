<?php if (! defined('ABSPATH')) {
	die;
}

if (class_exists('CSF')) {
    $prefix = 'aiya-cms';

    //分类
    CSF::createSection($prefix, array(
        'id' => 'aya_develop',
        'title' => '开发工具',
    ));

    CSF::createSection($prefix, array(
        'parent' => 'aya_stop_site',
        'type'   => 'group',
        'title'  => '关闭网站',
        'fields' => array(
			array(
				'id' => 'friend_link',
				'type' => 'switcher',
				'title' => '关闭站点',
				'desc' =>'开启/关闭，仅在首页底部显示，启用WordPress原生链接功能',
				'default' => false,
			),
        )
    ));

    CSF::createSection($prefix, array(
        'parent' => 'aya_debug',
        'type'   => 'group',
        'title'  => 'DEBUG模式',
        'fields' => array(
			array(
				'id' => 'friend_link',
				'type' => 'switcher',
				'title' => '关闭站点',
				'desc' =>'开启/关闭，仅在首页底部显示，启用WordPress原生链接功能',
				'default' => false,
			),
        )
    ));

    CSF::createSection($prefix, array(
        'parent' => 'aya_check_shotcode',
        'type'   => 'group',
        'title'  => '短代码',
        'fields' => array(
			array(
				'id' => 'friend_link',
				'type' => 'switcher',
				'title' => '关闭站点',
				'desc' =>'开启/关闭，仅在首页底部显示，启用WordPress原生链接功能',
				'default' => false,
			),
        )
    ));

    CSF::createSection($prefix, array(
        'parent' => 'aya_check_rewrite',
        'type'   => 'group',
        'title'  => '内置路由',
        'fields' => array(
			array(
				'id' => 'friend_link',
				'type' => 'switcher',
				'title' => '关闭站点',
				'desc' =>'开启/关闭，仅在首页底部显示，启用WordPress原生链接功能',
				'default' => false,
			),
        )
    ));

    CSF::createSection($prefix, array(
        'parent' => 'aya_memes_list',
        'type'   => 'group',
        'title'  => '内置路由',
        'fields' => array(
			array(
				'id' => 'friend_link',
				'type' => 'switcher',
				'title' => '关闭站点',
				'desc' =>'开启/关闭，仅在首页底部显示，启用WordPress原生链接功能',
				'default' => false,
			),
        )
    ));

    CSF::createSection($prefix, array(
        'parent' => 'aya_wp_icons',
        'type'   => 'group',
        'title'  => 'WP图标库',
        'fields' => array(
			array(
				'id' => 'friend_link',
				'type' => 'switcher',
				'title' => '关闭站点',
				'desc' =>'开启/关闭，仅在首页底部显示，启用WordPress原生链接功能',
				'default' => false,
			),
        )
    ));

    CSF::createSection($prefix, array(
        'parent' => 'aya_bootstrap_icons',
        'type'   => 'group',
        'title'  => 'Bootstrap图标库',
        'fields' => array(
			array(
				'id' => 'friend_link',
				'type' => 'switcher',
				'title' => '关闭站点',
				'desc' =>'开启/关闭，仅在首页底部显示，启用WordPress原生链接功能',
				'default' => false,
			),
        )
    ));
}