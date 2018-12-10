<?php $themePath = get_template_directory_uri(); ?>
<?php $themelogo = $themePath .'/images/logo.png'; ?>
<?php $logo = get_option_tree ('logo', $themelogo); ?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" <?php language_attributes() ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" <?php language_attributes() ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" <?php language_attributes() ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes() ?>> <!--<![endif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<title><?php if (is_front_page()) { ?><?php bloginfo('name'); ?> - <?php bloginfo('description'); ?><?php } else { ?><?php wp_title($sep = ''); ?> - <?php bloginfo('name'); ?><?php } ?></title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="<?php echo $themePath; ?>/style.css">
<link rel="stylesheet" href="<?php echo $themePath; ?>/css/print.css" media="print">
<link rel="stylesheet" href="<?php echo $themePath; ?>/css/selection.php">
<?php if(get_option_tree ('customcss', '')){ ?>
<style type="text/css">
<?php echo  get_option_tree ('customcss', ''); ?>
</style>
<?php } ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="container">
  <div class="row">
    <header class="span3 mainmenu">
        <div id="logo"><a href="<?php echo site_url(); ?>" title="<?php bloginfo('name'); ?>"><img src="<?php echo $logo; ?>" /></a></div>       
        <nav>
            <?php wp_nav_menu( array( 'theme_location' => 'nav-menu', 'depth' => 2, 'container' => false ) ); ?>
        </nav>
        <form method="get" id="searchform" action="<?php echo home_url(); ?>/">
        	<input name="s" type="text" id="s" class="medium" placeholder="<?php _e("Click here to search", "instyle"); ?>">
        </form>
        <div class="scrolltotop"><a href="#">Scroll To Top</a></div>
    </header>
