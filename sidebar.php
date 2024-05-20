<?php 
/**
 * The template for the sidebar containing the main widget area
 *
 */
?>
<div class="sidebar sidebar-sticky">
    <?php
    switch (aya_page_type()){
        case 'page':
            dynamic_sidebar('page_widgets');
            break;
        case 'single':
            dynamic_sidebar('single_widgets');
            break;
        default:
            dynamic_sidebar('index_widgets');
            break;
    }
    ?>
</div>