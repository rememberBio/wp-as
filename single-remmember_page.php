<?php 
/* Template Name: Remmember Item Main */

$current_tab = "";
if(isset($_GET["tab"])) { 
    $current_tab = $_GET["tab"];
}

get_header('remmember-item'); ?>

<div class="wrap-remmber-item-main">
    <?php if ($current_tab != "") {
        get_template_part( 'template-parts/remember-page/content', $current_tab );
    }  else {
        get_template_part( 'template-parts/remember-page/content', 'main' );
    }
    ?>
</div>

<?php get_footer('remmember-item'); ?>