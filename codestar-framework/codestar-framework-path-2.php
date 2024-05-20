<?php if (! defined('ABSPATH')) {
	die;
}

if (class_exists('CSF')) {
	$prefix = 'aiya-cms';

	//分类
	CSF::createSection($prefix, array(
		'id' => 'aya_optimize',
		'title' => '站点设置',
	));

	CSF::createSection($prefix, array(
		'parent' => 'aya_optimize',
		'title' => '功能开关',
		'fields' => array(
			array(
				'type' => 'notice',
				'title' => '链接',
				'content' => '',
			),
			array(
				'id' => 'friend_link',
				'type' => 'switcher',
				'title' => '链接功能',
				'desc' => '开启/关闭，启用WordPress原生链接功能',
				'default' => true,
			),
			array(
				'id' => 'friend_link_cat',
				'type' => 'text',
				'title' => '友情链接分类',
				'desc' => '友情链接仅在首页底部显示<br />设置要显示的友情链接的分类，为空则显示全部',
				'default' => '',
			),
			array(
				'type' => 'notice',
				'title' => '头像服务',
				'content' => '',
			),
			array(
				'id' => 'get_cravatar',
				'type' => 'switcher',
				'title' => 'Cravatar头像',
				'desc' => '开启/关闭，启用国内版Cravatar头像服务',
				'default' => false,
			),
			array(
				'id' => 'get_gravatar_cdn',
				'type' => 'switcher',
				'title' => 'Gravatar头像加速',
				'desc' => '开启/关闭，使用七牛CDN源获取Gravatar头像',
				'default' => false,
			),
			array(
				'type' => 'notice',
				'title' => '后台功能设置',
				'content' => '',
			),
			array(
				'id' => 'add_ssid_column',
				'type' => 'switcher',
				'title' => '显示SSID',
				'desc' => '开启/关闭，在文章、页面、分类的列表中显示 ID',
				'default' => false,
			),
			array(
				'id' => 'add_hitokoto',
				'type' => 'switcher',
				'title' => '显示一言',
				'desc' => '开启/关闭，后台右上角添加一言显示',
				'default' => false,
			),
			array(
				'type' => 'notice',
				'title' => 'RSS功能设置',
				'content' => '',
			),
			array(
				'id' => 'feed_close',
				'type' => 'switcher',
				'title' => '关闭RSS',
				'desc' => '开启/关闭，开启后整站禁用Feed生成',
				'default' => false,
			),
			array(
				'id' => 'feed_tweet',
				'type' => 'switcher',
				'title' => 'RSS显示推文',
				'desc' => '开启/关闭，将推文添加进Feed中',
				'default' => true,
			),
			array(
				'id' => 'feed_permalink',
				'type' => 'switcher',
				'title' => 'RSS查看全文',
				'desc' => '开启/关闭，Feed中将摘要替换为查看全文链接防采集',
				'default' => true,
			),
		)
	));

    /*
    CSF::createSection($prefix, array(
        'parent' => 'aya_optimize',
        'title' => '用户组设置',
        'fields' => array(
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
    */

	CSF::createSection($prefix, array(
		'parent' => 'aya_optimize',
		'title' => '优化设置',
		'fields' => array(
			array(
				'type' => 'notice',
				'title' => '全局功能设置',
				'content' => '',
			),
			array(
				'id' => 'diable_autosave',
				'type' => 'switcher',
				'title' => '禁用自动保存',
				'desc' => '开启/关闭，禁用文章自动保存功能',
				'default' => false,
			),
			array(
				'id' => 'diable_revision',
				'type' => 'switcher',
				'title' => '禁用修订版本',
				'desc' => '开启/关闭，禁用文章修订版本功能',
				'default' => true,
			),
			array(
				'id' => 'diable_texturize',
				'type' => 'switcher',
				'title' => '禁用标点转换',
				'desc' => '开启/关闭，禁用原生标点转换功能',
				'default' => true,
			),
			array(
				'id' => 'diable_autoembed',
				'type' => 'switcher',
				'title' => '禁用链接解析',
				'desc' => '开启/关闭，禁用原生文内链接解析功能',
				'default' => true,
			),
			array(
				'id' => 'diable_emoji',
				'type' => 'switcher',
				'title' => '禁用emoji',
				'desc' => '开启/关闭，禁用原生Emoji组件加载',
				'default' => true,
			),
			array(
				'id' => 'remove_wp_unused_css',
				'type' => 'switcher',
				'title' => '禁用无用样式',
				'desc' => '开启/关闭，禁用谷歌字体等一些不需要的样式文件加载',
				'default' => true,
			),
			array(
				'id' => 'remove_css_ver',
				'type' => 'switcher',
				'title' => '禁用静态文件版本',
				'desc' => '开启/关闭，禁用JS和CSS加载时生成版本号，可以解决一些浏览器的兼容问题',
				'default' => false,
			),
			array(
				'id' => 'remove_dns_prefetch',
				'type' => 'switcher',
				'title' => '禁用DNS预读取',
				'desc' => '如果无需经常跨域加载，建议禁止站点尝试请求资源之前预解析域名',
				'default' => true,
			),
			array(
				'id' => 'remove_wp_dashboard',
				'type' => 'switcher',
				'title' => '禁用仪表盘模块',
				'desc' => '开启/关闭，禁用后台仪表盘新闻模块',
				'default' => true,
			),
			array(
				'id' => 'remove_wp_widget',
				'type' => 'switcher',
				'title' => '禁用原生小工具',
				'desc' => '开启/关闭，禁用原生小工具组件',
				'default' => true,
			),
		)
	));

	CSF::createSection($prefix, array(
		'parent' => 'aya_optimize',
		'title' => '邮件SMTP',
		'fields' => array(
			array(
				'type' => 'notice',
				'title' => 'SMTP送信功能',
				'content' => '',
			),
			array(
				'id' => 'mail_smtp',
				'type' => 'switcher',
				'title' => '启用',
				'desc' =>'开启/关闭，开启后站点会通过SMTP发送通知邮件',
				'default' => false,
			),
			array(
				'type' => 'notice',
				'title' => 'SMTP配置',
				'content' => '',
			),
			array(
				'id' => 'mail_Host',
				'type' => 'text',
				'title' => '服务器地址',
				'default' => ''
			),
			array(
				'id' => 'mail_Port',
				'type' => 'text',
				'title' => '端口',
				'default' => ''
			),
			array(
				'id' => 'mail_FromName',
				'type' => 'text',
				'title' => '发信人昵称',
				'default' => ''
			),
			array(
				'id' => 'mail_From',
				'type' => 'text',
				'title' => '显示的发信邮箱',
				'default' => ''
			),
			array(
				'id' => 'mail_SMTPAuth',
				'type' => 'switcher',
				'title' => '认证',
				'default' => false,
			),
		    array(
				'id' => 'mail_SMTPSecure',
				'type' => 'select',
				'title'  => '加密方式',
				'options' => array(
					'' => '无',
					'tls' => 'TLS',
					'ssl' => 'SSL',
				),
				'default' => '',
			),
			array(
				'id' => 'mail_Username',
				'type' => 'text',
				'title' => '邮箱地址',
				'default' => '',
			),
			array(
				'id' => 'mail_Password',
				'type' => 'text',
				'title' => '登录密码',
				'default' => '',
			),
		)
	));

	CSF::createSection($prefix, array(
		'parent' => 'aya_optimize',
		'title' => '安全性',
		'fields' => array(
			array(
				'type' => 'notice',
				'title' => '全局安全性设置',
				'content' => '',
			),
			array(
				'id' => 'remove_xmlrpc',
				'type' => 'switcher',
				'title' => '禁用XML-RPC',
				'desc' => '彻底禁用默认的离线发布接口（返回空数组）',
				'default' => true,
			),
			array(
				'id' => 'remove_pingback',
				'type' => 'switcher',
				'title' => '禁用PingBack',
				'desc' => '如果你仍需要使用第三方编辑器发布，则建议禁止PingBack防止恶意利用',
				'default' => true,
			),
			array(
				'id' => 'current_user_check',
				'type' => 'switcher',
				'title' => '后台登录验证码',
				'desc' => '登录页面增加一个范围0~9的加减法验证码，这样做是为了阻止一些机器人账号',
				'default' => true,
			),
			array(
				'id' => 'current_user_admin',
				'type' => 'switcher',
				'title' => '后台权限验证',
				'desc' => '阻止投稿者以下权限的用户进入后台，这样做是为了阻止一些机器人账号',
				'default' => true,
			),
			array(
				'id' => 'current_user_restapi',
				'type' => 'switcher',
				'title' => 'REST API验证',
				'desc' => '禁止未登录用户读取使用REST API，如果你需要使用REST API，则建议使用例如JWT等工具进行鉴权',
				'default' => true,
			),
			array(
				'type' => 'notice',
				'title' => '用户信息防泄露',
				'content' => '',
			),
			array(
				'id' => 'remove_sitemaps_provider',
				'type' => 'switcher',
				'title' => '移除Sitemap中的用户列表',
				'desc' => '禁用原生 Sitemap.xml 默认生成的users列表，防止用户名泄露',
				'default' => true,
			),
			array(
				'id' => 'remove_comment_author',
				'type' => 'switcher',
				'title' => '移除评论中的用户参数',
				'desc' => '去掉函数 comment_class() 和 body_class() 中输出的 "comment-author-" 和 "author-"，防止用户名泄露',
				'default' => true,
			),
			array(
				'id' => 'remove_password_reset',
				'type' => 'switcher',
				'title' => '禁止找回密码',
				'desc' => '<b>警告！启用这项功能后，管理员忘记密码将无法找回！只能从数据库修改。</b><br />一些时候，找回密码功能会被用于暴力破解，直接禁用找回密码功能',
				'default' => false,
			),
			array(
				'id' => 'admin_email_check',
				'type' => 'switcher',
				'title' => '禁止管理员邮箱确认邮件',
				'desc' => '<b>警告！启用这项功能后，管理员将无法更换邮箱！只能从数据库修改。</b><br />禁止发送管理员邮箱确认邮件，避免账号被通过这种验证方式盗取',
				'default' => false,
			),
			array(
				'id' => 'admin_user_check',
				'type' => 'switcher',
				'title' => '禁止超级管理员账号登录',
				'desc' => '<b>警告！启用这项功能前，应先创建一个其他账号并给予管理员权限！</b><br />由于用户 id = 1 的超级管理员账号具备一些日常用不到的权限，可以禁止这个账号登录。',
				'default' => false,
			),
		)
	));
}

if (class_exists('CSF')) {
    $prefix = 'aiya-cms';

    //分类
    CSF::createSection($prefix, array(
        'id' => 'aya_seo',
        'title' => 'SEO设置',
    ));

    CSF::createSection($prefix, array(
        'parent' => 'aya_seo',
        'title' => 'SEO设置',
        'fields' => array(
            array(
                'type' => 'notice',
                'title' => '全局SEO设置',
                'content' => '',
            ),
            array(
                'id' => 'title_fgf',
                'type' => 'text',
                'title' => '页面标题分隔符',
                'desc' => '设置页面标题的分隔符',
                'default' => '-'
            ),
            array(
                'id' => 'seo_keywords',
                'type' => 'textarea',
                'title' => '网站关键词',
                'desc'  => '添加额外的SEO关键词，全局生效',
                'default' => ''
            ),
            array(
                'id' => 'seo_description',
                'type' => 'textarea',
                'title' => '关键词描述',
                'desc'  => '添加额外的SEO关键词描述，全局生效',
                'default' => ''
            ),
            array(
                'type' => 'notice',
                'title' => 'Roboots.txt设置',
                'content' => '',
            ),
            array(
                'id' => 'seo_Roboots_txt',
                'type' => 'textarea',
                'title' => 'Roboots.txt',
                'desc'  => '配置<code>Roboots.txt</code>的内容，为空则使用默认配置',
                'default' => '',
            ),
        )
    ));

    CSF::createSection($prefix, array(
        'parent' => 'aya_seo',
        'title' => '内链优化',
        'fields' => array(
            array(
                'type' => 'notice',
                'title' => '内链功能设置',
                'content' => '',
            ),
            array(
                'id' => 'seo_page_link',
                'type' => 'switcher',
                'title' => '伪静态页面链接',
                'desc' => '给页面的链接添加<code>.html</code></br><b>注意！修改此设置后需重新保存固定链接</b>',
                'default' => true,
            ),
            array(
                'id' => 'seo_img_alt',
                'type' => 'switcher',
                'title' => '图片自动添加Title',
                'desc' =>'开启/关闭，自动给文章中的图片添加<code>title</code>和<code>alt</code>属性',
                'default' => true,
            ),
            array(
                'id' => 'seo_link_jump',
                'type' => 'switcher',
                'title' => '外链跳转',
                'desc' =>'开启/关闭，自动给文章中的外部链接转换为内链',
                'default' => true,
            ),
            array(
                'id' => 'seo_link_nofollow',
                'type' => 'switcher',
                'title' => '外链自动Nofollow',
                'desc' =>'开启/关闭，自动给文章中的外部链接添加<code>nofollow</code>属性',
                'default' => true,
            ),
        )
    ));

    CSF::createSection($prefix, array(
        'parent' => 'aya_seo',
        'title' => '多站点设置',
        'fields' => array(
            array(
                'type' => 'notice',
                'title' => '多站点标题替换',
                'content' => '',
            ),
            array(
                'id' => 'seo_site_name',
                'type' => 'text',
                'title' => '站点标题',
                'desc'  => '为空则调用站点设置，默认无需填写</br>如果需要显示标题和后台不同，可修改此项',
                'default' => '',
            ),
            array(
                'id' => 'seo_site_description',
                'type' => 'text',
                'title' => '站点副标题',
                'desc'  => '为空则调用站点设置，默认无需填写</br>如果需要显示标题和后台不同，可修改此项',
                'default' => '',
            ),
        )
    ));
}

