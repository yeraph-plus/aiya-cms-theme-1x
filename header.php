<?php 
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 */

$site_url = get_bloginfo('url');
$site_name = get_bloginfo('name');

$site_favicon = aya_option('favicon');
$site_logo = aya_option('head_logo');

$site_title = aya_site_title_type();
$seo_key = aya_add_seo_keywords();
$seo_des = aya_add_seo_description();

$site_add_css = aya_add_css_mod();

$header_js = aya_option('header_js');
$header_css = aya_option('header_css');
?>
<!doctype html>

<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="email=no">
<meta name="format-detection" content="address=no">
<meta name="format-detection" content="date=no">
<link rel="shortcut icon" href="<?php echo $site_favicon; ?>" type="image/x-icon">
<title><?php echo $site_title; ?></title>
<meta name="keywords" content="<?php echo $seo_key; ?>" />
<meta name="description" content="<?php echo $seo_des; ?>" />
<?php wp_head();?>

</head>
<body <?php body_class(); ?> >
<?php echo $header_js; ?>
<?php echo $header_css; ?>
<?php echo $site_add_css; ?>

<header class="header <?php if( aya_option('sticky_top') == true): echo 'sticky-top'; endif;?>">
    <div class="container">
		<div class="top">
			<button class="mobile_an" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobile_right_nav"><i class="bi bi-list"></i></button>
			<div class="top_l">
            	<h1 class="logo">
					<a href="<?php echo $site_url; ?>" title="<?php echo $site_name; ?>">
                    <img src="<?php echo $site_logo; ?>">
                    <b><?php if( aya_option('head_logo_text') == true): echo $site_name; endif;?></b>
					</a>
	        	</h1>
        		<?php wp_nav_menu( 
                    array(
                    'theme_location'  => 'main',
                    'container' => 'nav',
                    'container_class' => 'header-menu',
                    'container_id'  => '',
                    'menu_class'  => 'header-menu-ul',
                    'menu_id'         => '', 
                    ) 
                );?>
        	</div>
        	<div class="top_r">
                <!--
        		<div class="top_r_an theme-switch me-4" onclick="switchDarkMode()">
                    <i class="bi bi-lightbulb-fill"></i>
                </div>
                -->
				<button class="top_r_an" type="button" data-bs-toggle="offcanvas" data-bs-target="#c_sousuo">
                    <i class="bi bi-search"></i>
                </button>
        	</div>
        </div>
    </div>
</header>

<div class="offcanvas offcanvas-top" id="c_sousuo">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-10 col-lg-6 search_box">
                <form action="/" class="ss_a clearfix" method="get">
                    <input name="s" aria-label="Search" type="text" onblur="if(this.value=='')this.value='搜索'" onfocus="if(this.value=='搜索')this.value=''" value="搜索">
                    <button type="submit" title="Search"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-start" id="mobile_right_nav">
	<div class="mobile_head">
		<div class="mobile_head_logo">
            <img src="<?php echo $site_logo; ?>">
			<b><?php //echo $site_name; ?></b>
		</div>
                <!--
		<div class="theme-switch" onclick="switchDarkMode()">
            <i class="bi bi-lightbulb-fill"></i>
        </div>
                -->
	</div>
	<?php wp_nav_menu( 
        array( 
            'theme_location'=>'main', 
            'fallback_cb'=>'', 
            'container_id'=>'sjcldnav', 
            'menu_class'=>'menu-zk' 
            ) 
        );
	?>
</div>