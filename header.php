<?php
/**
 * @package WordPress
 * @subpackage Theme_Compat
 * @deprecated 3.0.0
 *
 * This file is here for backward compatibility with old themes and will be removed in a future version.
 */
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo wp_get_document_title(); ?></title>

    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page">

    <div id="header" role="banner">
        <a class="js-back" href="#"><i class="fa fa-chevron-left"></i></a>
        <h1 class="site-title"><a href="<?php echo home_url(); ?>/"><?php bloginfo('name'); ?></a></h1>
        <a href="#"><i class="fa fa-futbol-o"></i></a>
    </div>
