<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximun-scale=1,user-scalable=no" />
<meta name="renderer" content="webkit" />
<meta name="format-detection" content="telephone=no" />
<meta name="format-detection" content="email=no" />
<meta name="format-detection" content="address=no" />
<meta name="format-detection" content="date=no" />
<?php do_action('aya_head'); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<!--header-->
<header class="header <?php navbar_sticky_top(); ?>">
    <div class="container">
        <div class="navbar navbar-expand-lg py-3" aria-label="offcanvas navbar large">
            <div class="navbar-brand">
                <?php header_logo(); ?>
            </div>
            <button class="navbar-toggler" type="button" title="菜单" data-bs-toggle="offcanvas" data-bs-target="#mobile-navbar" aria-controls="navbar">
                <i class="bi bi-list"></i>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="mobile-navbar" aria-labelledby="navbar-label">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="navbar-label">菜单</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div class="menu navbar-collapse">
                        <?php aya_menu_nav('header-menu', 0); ?>
                    </div>
                    <?php do_action('aya_header_canvas_box'); ?>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="background"></div>
<?php do_action('aya_header'); ?>
